<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    // ==================== CUSTOMER-FACING ====================

    public function indexCustomer()
    {
        // Get all Rice products that have at least one active package
        $products = Product::where('category', 'Rice')
            ->where('is_for_sale_online', true)
            ->with(['packages' => function ($q) {
                $q->where('is_active', true)->orderBy('package_size');
            }])
            ->get()
            ->filter(fn($p) => $p->packages->isNotEmpty());

        $customerId = auth('web')->id() ?? auth()->id() ?? 3;
        $orders = Order::with('items.product')
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('orders.index', compact('products', 'orders'));
    }

    // ==================== INTERNAL STAFF ====================

    public function indexInternal()
    {
        $products = Product::where('category', 'Rice')->get();
        $orders = Order::with(['items.product', 'customer', 'operational'])->latest()->get();
        return view('orders.internal_index', compact('products', 'orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'customer', 'operational']);
        return view('orders.show', compact('order'));
    }

    public function nota(Order $order)
    {
        $order->load(['items.product', 'customer', 'operational']);
        return view('orders.nota', compact('order'));
    }

    // SKENARIO LANGKAH 1-2: Offline Checkout Form (Internal Staff)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'shipping_address' => 'required|string|min:10',
        ]);

        $product = Product::find($request->product_id);

        if ($request->quantity > $product->stock_available) {
            return redirect()->back()->with('error', 'Jumlah pesanan melebihi stok yang tersedia');
        }

        // Kalkulasi Biaya
        $subtotal = $product->price * $request->quantity;
        $shippingCost = 15000;
        $total_payment = $subtotal + $shippingCost;

        $order = Order::create([
            'customer_id'        => 3,
            'transaction_date'   => now(),
            'shipping_address'   => $request->shipping_address,
            'shipping_cost'      => $shippingCost,
            'total_payment'      => $total_payment,
            'payment_method'     => 'Midtrans',
            'order_status'       => 'Pending',
            'sale_channel'       => 'Offline',
            'payment_expires_at' => now()->addHour(), // Hangus dalam 1 jam
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'unit_price' => $product->price,
            'subtotal' => $subtotal
        ]);

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $order->id . '-' . time(),
                'gross_amount' => $total_payment,
            ],
            'customer_details' => [
                'first_name'       => 'Pelanggan',
                'shipping_address' => [
                    'address' => $request->shipping_address,
                ]
            ],
            'expiry' => [
                'unit'     => 'hour',
                'duration' => 1,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return redirect('/internal/orders')->with([
            'snap_token' => $snapToken,
            'order_id' => $order->id
        ]);
    }

    // SKENARIO LANGKAH 4: Payment Success — Potong Stok
    public function paymentSuccess($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::with('items')->find($id);

            if ($order && $order->order_status == 'Pending') {
                $order->update(['order_status' => 'Paid']);

                foreach ($order->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if ($order->sale_channel === 'Offline') {
                        // Offline: deduct from warehouse stock (Kg)
                        $product->decrement('stock_available', $item->quantity);
                    } else {
                        // Online: deduct from the specific package's online_stock (packs)
                        $packages = ProductPackage::where('product_id', $item->product_id)
                            ->where('is_active', true)
                            ->get();

                        foreach ($packages as $pkg) {
                            // Find the exact package by verifying that:
                            // 1. Total weight (Kg) is a multiple of the package size.
                            // 2. The calculated packs ordered multiplied by package price matches the subtotal.
                            if ($item->quantity % $pkg->package_size === 0) {
                                $packsOrdered = (int) ($item->quantity / $pkg->package_size);
                                if ($packsOrdered * $pkg->online_price == $item->subtotal) {
                                    $pkg->decrement('online_stock', $packsOrdered);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        });

        $order = Order::find($id);
        $redirectUrl = ($order && $order->sale_channel === 'Offline') ? '/internal/orders' : '/orders';
        return redirect($redirectUrl)->with('success', 'Pembayaran Midtrans berhasil divalidasi. Stok otomatis terpotong.');
    }

    // SKENARIO LANGKAH 5: Konfirmasi Pesanan
    public function confirm(Request $request, Order $order)
    {
        $order->update(['operational_id' => auth('internal')->id() ?? 1, 'order_status' => 'Processing']);
        return redirect('/internal/orders')->with('success', 'Pesanan berhasil dikonfirmasi. Status: Diproses.');
    }

    // SKENARIO FINAL: Selesaikan Transaksi & Cetak Nota
    public function complete(Order $order)
    {
        // Generate nomor nota: NOTA-{YYYYMMDD}-{ID}
        $notaNumber = 'NOTA-' . now()->format('Ymd') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
        $order->update([
            'order_status' => 'Completed',
            'nota_number'  => $notaNumber,
        ]);
        // Redirect ke halaman nota cetak
        return redirect('/internal/orders/' . $order->id . '/nota');
    }

    // Input Nomor Telepon Kurir JNT secara Mandiri (Owner / Internal)
    public function updateCourierPhone(Request $request, Order $order)
    {
        $request->validate([
            'courier_phone' => ['required', 'regex:/^(62|\+62)[0-9]{8,13}$/'],
        ], [
            'courier_phone.required' => 'Nomor telepon kurir JNT wajib diisi.',
            'courier_phone.regex'    => 'Nomor telepon kurir harus diawali dengan 62 (contoh: 628123456789), tidak boleh menggunakan 08.',
        ]);

        $order->update([
            'courier_phone' => $request->courier_phone,
        ]);

        return redirect()->back()->with('success', 'Nomor telepon kurir JNT berhasil diperbarui.');
    }

    // ==================== FITUR E-COMMERCE CART ====================

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'package_size' => 'required|in:5,10,20',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $packageSize = (int)$request->package_size;
        $qty = (int)$request->quantity;

        // Find the specific package row for price and stock
        $package = ProductPackage::where('product_id', $product->id)
            ->where('package_size', $packageSize)
            ->where('is_active', true)
            ->first();

        if (!$package || $package->online_stock < $qty) {
            return redirect()->back()->with('error', 'Stok paket ini tidak mencukupi.');
        }

        $cart = session()->get('cart', []);
        $cartId = $product->id . '-' . $packageSize;

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $qty;
        } else {
            $cart[$cartId] = [
                'product_id' => $product->id,
                'package_id' => $package->id,
                'name' => $package->online_name ?? ($product->name . ' (' . $packageSize . ' Kg)'),
                'price' => $package->online_price, // Price per PACK
                'package_size' => $packageSize,
                'quantity' => $qty,
            ];
        }

        session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Beras berhasil ditambahkan ke keranjang.');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('orders.cart', compact('cart'));
    }

    public function removeFromCart($cartId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
            session()->put('cart', $cart);
        }
        return redirect('/cart')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        // ── Validasi form checkout ──────────────────────────────────────────
        // Catatan: regex nama & telepon sinkron dengan pattern HTML di cart.blade.php
        $request->validate([
            'recipient_name'       => ['required', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'recipient_phone'      => ['required', 'regex:/^(62|\+62)[0-9]{8,13}$/'],
            'recipient_lat'        => 'required|numeric|between:-90,90',
            'recipient_lng'        => 'required|numeric|between:-180,180',
            'delivery_courier'     => 'required|in:Rancaoray,JNT',
            'delivery_distance_km' => 'required|numeric|min:0',
            'shipping_address'     => 'required|string|min:10',
        ], [
            'recipient_name.required'  => 'Nama penerima wajib diisi.',
            'recipient_name.regex'     => 'Nama penerima hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',
            'recipient_phone.required' => 'Nomor telepon penerima wajib diisi.',
            'recipient_phone.regex'    => 'Nomor telepon harus diawali dengan 62 (contoh: 628123456789), tidak boleh menggunakan 08.',
            'recipient_lat.required'   => 'Lokasi pada peta belum dipilih. Klik pada peta untuk menentukan lokasi.',
            'recipient_lng.required'   => 'Lokasi pada peta belum dipilih. Klik pada peta untuk menentukan lokasi.',
            'delivery_courier.required'=> 'Kurir pengiriman belum terdeteksi. Pastikan lokasi peta sudah dipilih.',
            'shipping_address.required'=> 'Alamat lengkap wajib diisi.',
            'shipping_address.min'     => 'Alamat terlalu singkat (minimal 10 karakter).',
        ]);

        // ── Validasi stok keranjang ─────────────────────────────────────────
        $subtotal = 0;
        foreach ($cart as $item) {
            $package = ProductPackage::find($item['package_id'] ?? 0);
            if (!$package || $package->online_stock < $item['quantity']) {
                return redirect()->back()->with('error', "Stok '{$item['name']}' tidak mencukupi.");
            }
            $subtotal += $item['price'] * $item['quantity'];
        }

        // ── Logika ongkos kirim ─────────────────────────────────────────────
        // <= 5 km (Rancaoray): Rp 15.000 (diantar langsung oleh armada Pertanian Rancaoray)
        // > 5 km (JNT): Rp 0 (biaya pengiriman JNT dibayarkan langsung oleh penerima saat paket tiba / COD)
        $shippingCost = ($request->delivery_courier === 'Rancaoray') ? 15000 : 0;
        $total_payment = $subtotal + $shippingCost;

        // ── Buat Order ──────────────────────────────────────────────────────
        $order = Order::create([
            'customer_id'          => auth('web')->id() ?? auth()->id() ?? 3,
            'transaction_date'     => now(),
            'shipping_address'     => $request->shipping_address,
            // Informasi penerima (dikumpulkan dari form + peta Leaflet)
            'recipient_name'       => $request->recipient_name,
            'recipient_phone'      => $request->recipient_phone,
            'recipient_lat'        => $request->recipient_lat,
            'recipient_lng'        => $request->recipient_lng,
            // Kurir ditentukan oleh jarak Haversine (dihitung di sisi klien & diverifikasi oleh form)
            // ≤ 5 km → 'Rancaoray', > 5 km → 'JNT'
            'delivery_courier'     => $request->delivery_courier,
            'delivery_distance_km' => $request->delivery_distance_km,
            'shipping_cost'        => $shippingCost,
            'total_payment'        => $total_payment,
            'payment_method'       => 'Midtrans',
            'order_status'         => 'Pending',
            'sale_channel'         => 'Online',
            'payment_expires_at'   => now()->addHour(),
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['package_size'] * $item['quantity'], // Total Kg
                'unit_price' => $item['price'] / $item['package_size'],    // Per-Kg price
                'subtotal'   => $item['price'] * $item['quantity']
            ]);
        }

        // ── Midtrans Snap ───────────────────────────────────────────────────
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $order->id . '-' . time(),
                'gross_amount' => $total_payment,
            ],
            'customer_details' => [
                'first_name' => $request->recipient_name,
                'phone'      => $request->recipient_phone,
                'shipping_address' => [
                    'address' => $request->shipping_address,
                ]
            ],
            'expiry' => [
                'unit'     => 'hour',
                'duration' => 1,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Hapus keranjang
        session()->forget('cart');

        return redirect('/cart')->with([
            'snap_token' => $snapToken,
            'order_id'   => $order->id
        ]);
    }

    // ==================== KELOLA PENJUALAN ONLINE (OWNER) ====================

    public function indexOnlineProducts()
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses khusus Pemilik.');
        }

        $products = Product::where('category', 'Rice')->with('packages')->get();
        return view('orders.online_products', compact('products'));
    }

    public function updatePackageSettings(Request $request, Product $product, $size)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses khusus Pemilik.');
        }

        $size = (int) $size;
        if (!in_array($size, [5, 10, 20])) {
            abort(422, 'Ukuran paket tidak valid.');
        }

        $request->validate([
            'online_name' => 'required|string|max:255',
            'online_price' => 'required|numeric|min:0',
            'online_stock' => 'required|integer|min:0',
        ]);

        $newPacks = (int) $request->online_stock;
        $isActive = $request->has('is_active');

        // Find or create the package row
        $package = ProductPackage::firstOrNew([
            'product_id' => $product->id,
            'package_size' => $size,
        ]);

        $currentPacks = $package->exists ? $package->online_stock : 0;
        $diffPacks = $newPacks - $currentPacks;
        $diffKg = $diffPacks * $size; // Convert packs to Kg

        // If allocating MORE packs to online, check warehouse has enough Kg
        if ($diffKg > 0 && $product->stock_available < $diffKg) {
            return redirect()->back()->with('error',
                "Stok gudang tidak mencukupi. Dibutuhkan {$diffKg} Kg tapi hanya tersedia {$product->stock_available} Kg.");
        }

        DB::transaction(function () use ($product, $package, $request, $newPacks, $diffKg, $isActive) {
            // Transfer stock: warehouse ↔ online (in Kg)
            $product->stock_available -= $diffKg;
            $product->save();

            // Update package
            $package->online_name = $request->online_name;
            $package->online_price = $request->online_price;
            $package->online_stock = $newPacks;
            $package->is_active = $isActive;

            // Handle image upload per-package
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_pkg' . $package->package_size . '_' . str_replace(' ', '_', $file->getClientOriginalName());

                if (!file_exists(public_path('uploads/products'))) {
                    mkdir(public_path('uploads/products'), 0755, true);
                }

                $file->move(public_path('uploads/products'), $filename);
                $package->image_path = 'uploads/products/' . $filename;
            }

            $package->save();

            // Update parent product's is_for_sale_online flag
            $hasActivePackage = ProductPackage::where('product_id', $product->id)
                ->where('is_active', true)
                ->exists();
            $product->update(['is_for_sale_online' => $hasActivePackage]);
        });

        return redirect()->back()->with('success', "Paket {$size} Kg berhasil diperbarui.");
    }
}