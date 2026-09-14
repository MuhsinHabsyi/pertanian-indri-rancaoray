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

                            {{-- Nama & Telepon Penerima --}}
                            @if($o->recipient_name)
                            <div>
                                <span class="font-medium text-gray-400">Nama Penerima:</span>
                                <span class="font-semibold text-gray-800">{{ $o->recipient_name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-400">Telepon Penerima:</span>
                                <span class="font-semibold text-gray-800">{{ $o->recipient_phone ?? '-' }}</span>
                            </div>
                            @endif

                            <div class="sm:col-span-2">
                                <span class="font-medium text-gray-400">Alamat Kirim:</span> 
                                <span class="text-gray-700">{{ $o->shipping_address }}</span>
                            </div>

                            {{-- Kurir & Kontak Kurir WhatsApp --}}
                            @if($o->delivery_courier)
                            <div class="sm:col-span-2 flex flex-wrap items-center gap-2 pt-1 border-t border-gray-100">
                                <span class="font-medium text-gray-400">Kurir Pengiriman:</span>
                                @if($o->delivery_courier === 'Rancaoray')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🚜 Diantar Langsung (Rancaoray)
                                    </span>
                                    <span class="text-gray-400 text-[11px]">• No. Kurir:</span>
                                    <a href="https://wa.me/6283116581808" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded transition shadow-2xs">
                                        <svg class="w-3 h-3 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        <span>6283116581808 (WhatsApp)</span>
                                    </a>
                                @elseif($o->delivery_courier === 'JNT')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        📦 JNT Express
                                    </span>
                                    @if($o->courier_phone)
                                        <span class="text-gray-400 text-[11px]">• No. Kurir:</span>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $o->courier_phone) }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded transition shadow-2xs">
                                            <svg class="w-3 h-3 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                            <span>{{ $o->courier_phone }} (WhatsApp)</span>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded">
                                            ⏳ Menunggu nomor kurir
                                        </span>
                                    @endif
                                @endif
                            </div>
                            @endif

                            <div class="sm:col-span-2">
                                <span class="font-medium text-gray-400">Total Tagihan:</span> 
                                <span class="font-semibold text-gray-800 text-sm">Rp{{ number_format($o->total_payment, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end space-y-2">
                        @if($o->order_status == 'Pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                Menunggu Pembayaran
                            </span>
                            {{-- Peringatan Expiry --}}
                            @if($o->payment_expires_at)
                                <div class="text-[10px] text-red-600 font-semibold bg-red-50 border border-red-100 rounded px-2 py-1 text-right">
                                    ⏳ Bayar sebelum:<br>
                                    {{ $o->payment_expires_at->format('d M Y, H:i') }} WIB
                                    @if($o->isPaymentExpired())
                                        <span class="block text-red-700 font-bold">❌ Pembayaran telah hangus</span>
                                    @endif
                                </div>
                            @endif
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
                                Selesai dan Dikirim
                            </span>
                            @if($o->nota_number)
                                <a href="/internal/orders/{{ $o->id }}/nota" target="_blank"
                                    class="inline-flex items-center space-x-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-lg transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Unduh Nota</span>
                                </a>
                            @endif
                        @elseif($o->order_status == 'Cancelled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                ❌ Dibatalkan
                            </span>
                            <span class="text-[10px] text-red-500">Pembayaran tidak dilakukan dalam 1 jam</span>
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