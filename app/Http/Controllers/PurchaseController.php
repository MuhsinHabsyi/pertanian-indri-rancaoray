<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function index()
    {
        // Menampilkan daftar barang (Bibit/Pupuk), stok per kategori, dan daftar pengajuan
        $products = Product::whereIn('category', ['Seed', 'Fertilizer'])->get();
        $seedProducts = Product::where('category', 'Seed')->get();
        $fertilizerProducts = Product::where('category', 'Fertilizer')->get();
        $riceProducts = Product::where('category', 'Rice')->get();

        $purchases = Purchase::with('items.product')->latest()->get();

        return view('purchases.index', compact('products', 'purchases', 'seedProducts', 'fertilizerProducts', 'riceProducts'));
    }

    // SKENARIO LANGKAH 1-2: Operasional Mengajukan Pembelian
    // SKENARIO PENGADAAN (MANDIRI & SUBSIDI)
    public function store(Request $request)
    {
        $rules = [
            'submission_date'    => 'required|date',
            'procurement_source' => 'required|in:Mandiri,Subsidi',
            'product_id'         => 'required|exists:products,id',
            'quantity'           => 'required|numeric|gt:0',
        ];

        if ($request->procurement_source === 'Mandiri') {
            $rules['total_cost'] = 'required|numeric|gt:0';
        } else {
            $rules['total_cost'] = 'nullable|numeric|min:0';
        }

        $validated = $request->validate($rules, [
            'quantity.required'   => 'Jumlah kebutuhan wajib diisi.',
            'quantity.numeric'    => 'Jumlah kebutuhan harus berupa angka.',
            'quantity.gt'         => 'Jumlah kebutuhan harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
            'total_cost.required' => 'Estimasi biaya wajib diisi untuk pengadaan mandiri.',
            'total_cost.numeric'  => 'Estimasi biaya harus berupa angka.',
            'total_cost.gt'       => 'Estimasi biaya harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
        ]);

        // LOGIKA SUBSIDI: Jika subsidi, biaya otomatis 0. Jika mandiri, ambil dari input form.
        $finalCost = ($validated['procurement_source'] === 'Subsidi') ? 0 : ($validated['total_cost'] ?? 0);

        DB::transaction(function () use ($validated, $finalCost) {
            // Bypass login: Anggap ID 1 adalah Operasional
            $purchase = Purchase::create([
                'user_id' => 1,
                'procurement_source' => $validated['procurement_source'],
                'submission_date' => $validated['submission_date'],
                'total_cost' => $finalCost,
                'validation_status' => 'Pending', // Tetap butuh validasi pemilik untuk pencatatan inventaris
            ]);

            // Menghindari error pembagian dengan nol (0 / quantity)
            $unitPrice = ($finalCost > 0) ? ($finalCost / $validated['quantity']) : 0;

            $purchase->items()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $finalCost
            ]);
        });

        return redirect('/purchases')->with('success', 'Pengajuan pengadaan berhasil dikirim. Status: Menunggu Persetujuan.');
    }

    // SKENARIO LANGKAH 3-6 (Normal & Alternatif): Pemilik Validasi Setuju/Tolak
    public function validatePurchase(Request $request, Purchase $purchase)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses khusus Pemilik.');
        }

        $validated = $request->validate([
            'action' => 'required|in:Approved,Rejected'
        ]);

        $purchase->update([
            'validation_status' => $validated['action'] // "Disetujui" atau "Ditolak"
        ]);

        $pesan = $validated['action'] == 'Approved' ? 'Pengajuan telah Disetujui.' : 'Pengajuan Ditolak.';
        return redirect('/purchases')->with('success', $pesan);
    }

    // Operasional / User Unggah Nota Pembelian (Gambar)
    public function uploadReceipt(Request $request, Purchase $purchase)
    {
        if ($purchase->validation_status !== 'Approved') {
            return redirect('/purchases')->with('error', 'Status pengadaan belum disetujui.');
        }

        $request->validate([
            'receipt_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        if ($request->hasFile('receipt_proof')) {
            $path = $request->file('receipt_proof')->store('receipts', 'public');
            $purchase->update([
                'receipt_proof' => $path
            ]);
        }

        return redirect('/purchases')->with('success', 'Bukti nota berhasil diunggah.');
    }

    // Menampilkan / Menyajikan Gambar Nota Pengadaan secara Langsung
    public function showReceipt(Purchase $purchase)
    {
        if (!$purchase->receipt_proof) {
            abort(404, 'Bukti nota tidak ditemukan.');
        }

        if (Storage::disk('public')->exists($purchase->receipt_proof)) {
            return response()->file(Storage::disk('public')->path($purchase->receipt_proof));
        }

        $fullPath = storage_path('app/public/' . $purchase->receipt_proof);
        if (file_exists($fullPath)) {
            return response()->file($fullPath);
        }

        $altPath = public_path('storage/' . $purchase->receipt_proof);
        if (file_exists($altPath)) {
            return response()->file($altPath);
        }

        $basePath = base_path('storage/app/public/' . $purchase->receipt_proof);
        if (file_exists($basePath)) {
            return response()->file($basePath);
        }

        abort(404, 'File gambar nota tidak ada di server.');
    }

    // Pemilik Meng-update Biaya Total berdasarkan Nota Pembelian Sebenarnya
    public function updateCost(Request $request, Purchase $purchase)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses khusus Pemilik.');
        }

        if ($purchase->is_realized) {
            return redirect('/purchases')->with('error', 'Pengadaan yang sudah direalisasikan tidak dapat diubah biayanya.');
        }

        $validated = $request->validate([
            'total_cost' => 'required|numeric|gt:0'
        ], [
            'total_cost.required' => 'Biaya total wajib diisi.',
            'total_cost.numeric'  => 'Biaya total harus berupa angka.',
            'total_cost.gt'       => 'Biaya total pengadaan harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
        ]);

        DB::transaction(function () use ($purchase, $validated) {
            $newCost = (float) $validated['total_cost'];
            $purchase->update(['total_cost' => $newCost]);

            foreach ($purchase->items as $item) {
                $unitPrice = ($item->quantity > 0) ? ($newCost / $item->quantity) : 0;
                $item->update([
                    'unit_price' => $unitPrice,
                    'subtotal' => $newCost
                ]);
            }
        });

        return redirect('/purchases')->with('success', 'Biaya total pengadaan berhasil diperbarui sesuai harga nota sebenarnya.');
    }

    // Pemilik Mengonfirmasi Barang Masuk & Updating Stok Produk
    public function realize(Request $request, Purchase $purchase)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses khusus Pemilik untuk mengonfirmasi barang masuk.');
        }

        if ($purchase->validation_status !== 'Approved' || !$purchase->receipt_proof) {
            return redirect('/purchases')->with('error', 'Bukti nota belum diunggah atau pengadaan belum disetujui.');
        }

        if ($purchase->is_realized) {
            return redirect('/purchases')->with('error', 'Barang masuk untuk pengadaan ini sudah pernah dikonfirmasi.');
        }

        $validated = $request->validate([
            'actual_cost' => 'nullable|numeric|gt:0'
        ], [
            'actual_cost.numeric' => 'Biaya aktual harus berupa angka.',
            'actual_cost.gt'      => 'Biaya aktual harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
        ]);

        DB::transaction(function () use ($purchase, $validated, $request) {
            if ($request->has('actual_cost') && $validated['actual_cost'] !== null) {
                $newCost = (float) $validated['actual_cost'];
                $purchase->update(['total_cost' => $newCost]);

                foreach ($purchase->items as $item) {
                    $unitPrice = ($item->quantity > 0) ? ($newCost / $item->quantity) : 0;
                    $item->update([
                        'unit_price' => $unitPrice,
                        'subtotal' => $newCost
                    ]);
                }
            }

            $purchase->update([
                'is_realized' => true
            ]);

            foreach ($purchase->items as $item) {
                $item->product->increment('stock_available', $item->quantity);
            }
        });

        return redirect('/purchases')->with('success', 'Konfirmasi barang masuk berhasil. Stok produk otomatis bertambah.');
    }
}
