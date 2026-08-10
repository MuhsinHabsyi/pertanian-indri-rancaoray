@extends('layouts.customer')

@section('title', 'Katalog Beras & Checkout')

@section('content')
<div class="space-y-12">
    
    <!-- Rice Product Catalog -->
    <div>
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900">Pilihan Paket Beras Unggulan</h2>
            <p class="text-xs text-gray-500 mt-1">Pilih paket beras berkualitas tinggi langsung dari sawah Rancaoray.</p>
        </div>

        @forelse($products as $product)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8 space-y-6">
                <!-- Product Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2.5 h-5 rounded bg-emerald-600"></div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-400">Beras lokal premium organik hasil panen langsung dari sawah Rancaoray.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($product->packages as $pkg)
                        <div class="bg-gray-50/50 border border-gray-200 rounded-xl overflow-hidden flex flex-col justify-between hover:border-emerald-300 transition duration-200 relative">
                            
                            <!-- Card Image Banner -->
                            <div class="h-40 bg-gray-100 relative flex items-center justify-center overflow-hidden border-b border-gray-200">
                                @if($pkg->image_path)
                                    <img src="{{ asset($pkg->image_path) }}" alt="{{ $pkg->online_name }}" class="w-full h-full object-cover">
                                @elseif($product->image_path)
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $pkg->online_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-300 space-y-2">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-[9px] uppercase font-semibold tracking-wider text-gray-400">Belum ada gambar</span>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-xs text-[9px] font-bold px-2 py-0.5 rounded border border-gray-200 text-gray-700 shadow-xs">
                                    Paket {{ $pkg->package_size }} Kg
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $pkg->online_name }}</h4>
                                    <p class="text-xs text-gray-400">
                                        @if($pkg->package_size == 5)
                                            Pilihan praktis untuk kebutuhan keluarga kecil Anda.
                                        @elseif($pkg->package_size == 10)
                                            Paling disukai untuk konsumsi bulanan keluarga.
                                        @else
                                            Harga terbaik untuk persediaan pangan jangka panjang.
                                        @endif
                                    </p>
                                    <div class="pt-1.5">
                                        <span class="text-2xl font-black text-emerald-700">Rp{{ number_format($pkg->online_price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-400 block mt-0.5">Stok: {{ $pkg->online_stock }} pak</span>
                                    </div>
                                </div>
                                
                                <form action="/cart/add" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="package_size" value="{{ $pkg->package_size }}">
                                    
                                    <div>
                                        <label class="block text-[9px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Jumlah Pembelian (Pak)</label>
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $pkg->online_stock }}" required
                                            class="w-full text-xs bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                                    </div>

                                    <button type="submit" {{ $pkg->online_stock <= 0 ? 'disabled' : '' }}
                                        class="w-full text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg py-2.5 transition shadow-sm flex items-center justify-center space-x-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span>Masukkan Keranjang</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="p-6 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg text-sm">
                Stok beras saat ini sedang tidak tersedia di katalog online.
            </div>
        @endforelse
    </div>

    <!-- Riwayat Pesanan Saya Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50 rounded-t-xl">
            <h3 class="text-sm font-semibold text-gray-900">Riwayat Pesanan Saya</h3>
            <span class="text-xs text-gray-500 font-medium">Melacak status pesanan Anda</span>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($orders as $o)
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $o->items[0]->product->name ?? 'Beras Rancaoray' }}
                            </span>
                            <span class="text-xs text-gray-500">
                                ({{ $o->items[0]->quantity ?? 0 }} Kg)
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5 text-xs text-gray-500">
                            <div>
                                <span class="font-medium text-gray-400">Tanggal:</span> 
                                {{ date('d M Y H:i', strtotime($o->transaction_date)) }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-400">Metode:</span> 
                                <span class="text-blue-600 font-medium">{{ $o->payment_method }}</span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="font-medium text-gray-400">Alamat Kirim:</span> 
                                <span class="text-gray-700">{{ $o->shipping_address }}</span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="font-medium text-gray-400">Total Tagihan:</span> 
                                <span class="font-semibold text-gray-800 text-sm">Rp{{ number_format($o->total_payment, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end">
                        @if($o->order_status == 'Pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                Menunggu Pembayaran
                            </span>
                        @elseif($o->order_status == 'Paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                Sudah Dibayar
                            </span>
                        @elseif($o->order_status == 'Processing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                Sedang Diproses
                            </span>
                        @elseif($o->order_status == 'Completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-100">
                                {{ $o->order_status }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-xs text-gray-400">
                    Belum ada riwayat pesanan.
                </div>
            @endforelse
        </div>
    </div>

</div>

@if(session('snap_token'))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            snap.pay('{{ session('snap_token') }}', {
                onSuccess: function(result){
                    window.location.href = '/orders/' + '{{ session('order_id') }}' + '/success';
                },
                onPending: function(result){
                    alert("Menunggu pembayaran!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                }
            });
        });
    </script>
@endif

@endsection