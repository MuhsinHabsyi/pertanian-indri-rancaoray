<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    // ==========================================
    // HALAMAN 1: PENGGUNAAN STOK (SEED & FERTILIZER)
    // ==========================================
    
    public function indexUsage()
    {
        $seeds = Product::where('category', 'Seed')->get();
        $fertilizers = Product::where('category', 'Fertilizer')->get();
        
        // Mengambil batch tanam yang masih aktif untuk keperluan pemupukan
        $activeSchedules = ProductionSchedule::where('status', 'Ongoing')->get();

        return view('production.usage', compact('seeds', 'fertilizers', 'activeSchedules'));
    }

    // Aksi A: Menanam Bibit Baru (Membuat Batch Produksi Baru)
    public function storeSeedUsage(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'usage_date'    => 'required|date',
            'quantity_used' => 'required|numeric|gt:0',
        ], [
            'quantity_used.required' => 'Jumlah bibit wajib diisi.',
            'quantity_used.numeric'  => 'Jumlah bibit harus berupa angka.',
            'quantity_used.gt'       => 'Jumlah bibit yang ditanam harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock_available < $request->quantity_used) {
            return redirect()->back()->with('error', 'Gagal, jumlah melebihi ketersediaan stok bibit (Tersedia: ' . $product->stock_available . ' ' . $product->unit . ').');
        }

        DB::transaction(function () use ($request, $product) {
            // 1. Kurangi stok bibit
            $product->decrement('stock_available', $request->quantity_used);

            // 2. Tentukan Musim Tanam otomatis berdasarkan bulan tanggal tanam
            $month = (int) date('m', strtotime($request->usage_date));
            if (in_array($month, [11, 12, 1, 2, 3])) {
                $season = 'MT 1 (Nov-Mar)';
            } elseif (in_array($month, [4, 5, 6, 7])) {
                $season = 'MT 2 (Apr-Jul)';
            } else {
                $season = 'MT 3 (Aug-Oct)';
            }

            // 3. Hitung Otomatis Estimasi Hasil dan Estimasi Tanggal Panen (+120 hari)
            $estimatedHarvestWeight = $request->quantity_used * 100; // Rasio 1kg = 100kg
            $estimatedHarvestDate = date('Y-m-d', strtotime($request->usage_date . ' + 120 days'));

            // 4. Buat baris Batch Tanam Baru
            $schedule = ProductionSchedule::create([
                'user_id' => 1,
                'planting_start_date' => $request->usage_date,
                'crop_season' => $season,
                'estimated_harvest_date' => $estimatedHarvestDate,
                'estimated_harvest' => $estimatedHarvestWeight,
                'status' => 'Ongoing'
            ]);

            // Catat log detail pemakaian material
            $schedule->materialUsages()->create([
                'product_id' => $request->product_id,
                'usage_date' => $request->usage_date,
                'quantity_used' => $request->quantity_used
            ]);
        });

        return redirect('/production/usage')->with('success', 'Batch penanaman bibit baru berhasil dibuat dan estimasi panen telah dikalkulasi.');
    }

    // Aksi B: Melakukan Pemupukan pada Batch Tanam yang Ada
    public function storeFertilizerUsage(Request $request)
    {
        $request->validate([
            'production_schedule_id' => 'required|exists:production_schedules,id',
            'product_id'             => 'required|exists:products,id',
            'usage_date'             => 'required|date',
            'quantity_used'          => 'required|numeric|gt:0',
        ], [
            'production_schedule_id.required' => 'Batch tanam wajib dipilih.',
            'quantity_used.required'          => 'Jumlah pupuk wajib diisi.',
            'quantity_used.numeric'           => 'Jumlah pupuk harus berupa angka.',
            'quantity_used.gt'                => 'Jumlah pupuk yang digunakan harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock_available < $request->quantity_used) {
            return redirect()->back()->with('error', 'Gagal, jumlah melebihi ketersediaan stok pupuk (Tersedia: ' . $product->stock_available . ' ' . $product->unit . ').');
        }

        DB::transaction(function () use ($request, $product) {
            // 1. Kurangi stok pupuk
            $product->decrement('stock_available', $request->quantity_used);

            // 2. Catat log pemakaian ke batch tanam yang dipilih
            $schedule = ProductionSchedule::find($request->production_schedule_id);
            $schedule->materialUsages()->create([
                'product_id' => $request->product_id,
                'usage_date' => $request->usage_date,
                'quantity_used' => $request->quantity_used
            ]);

            // 3. Perbarui tanggal pemupukan terakhir pada batch tersebut
            $schedule->update(['fertilizing_date' => $request->usage_date]);
        });

        return redirect('/production/usage')->with('success', 'Pencatatan pemakaian pupuk berhasil disimpan.');
    }

    // ==========================================
    // HALAMAN 2: PENCATATAN HASIL PANEN
    // ==========================================

    public function indexHarvest()
    {
        // Hanya mengambil batch tanam yang masih aktif (Ongoing) untuk dipanen satu per satu
        $activeSchedules = ProductionSchedule::where('status', 'Ongoing')->latest()->get();
        $riceProducts = Product::where('category', 'Rice')->get();

        return view('production.harvest', compact('activeSchedules', 'riceProducts'));
    }

    public function storeHarvest(Request $request, ProductionSchedule $schedule)
    {
        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'actual_harvest_date' => 'required|date',
            'total_harvest'       => 'required|numeric|gt:0',
        ], [
            'total_harvest.required' => 'Berat riil hasil panen wajib diisi.',
            'total_harvest.numeric'  => 'Berat riil harus berupa angka.',
            'total_harvest.gt'       => 'Berat riil hasil panen harus lebih dari 0 (tidak boleh bernilai 0 atau angka minus).',
        ]);

        DB::transaction(function () use ($request, $schedule) {
            // 1. Tambah stok beras hasil pertanian
            $product = Product::find($request->product_id);
            $product->increment('stock_available', $request->total_harvest);

            // 2. Selesaikan batch tanam spesifik ini
            $schedule->update([
                'actual_harvest_date' => $request->actual_harvest_date,
                'total_harvest' => $request->total_harvest,
                'status' => 'Completed'
            ]);
        });

        return redirect('/production/harvest')->with('success', 'Data hasil panen riil berhasil disimpan untuk batch ini.');
    }
}