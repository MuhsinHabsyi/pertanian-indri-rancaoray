@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="/internal/orders" class="inline-flex items-center text-xs text-gray-500 hover:text-gray-800 mb-2 transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Pesanan
            </a>
            <h2 class="text-lg font-bold text-gray-900">Detail Pesanan #{{ $order->id }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ date('d M Y, H:i', strtotime($order->transaction_date)) }} WIB</p>
        </div>

        {{-- Status Badge --}}
        <div>
            @if($order->order_status == 'Pending')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                    Menunggu Pembayaran
                </span>
            @elseif($order->order_status == 'Paid')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                    Sudah Dibayar
                </span>
            @elseif($order->order_status == 'Processing')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                    Sedang Diproses
                </span>
            @elseif($order->order_status == 'Completed')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                    Selesai dan Dikirim
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $order->order_status }}
                </span>
            @endif
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Info Pembeli & Pengiriman --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">👤 Informasi Pembeli & Penerima</h3>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Nama Pemesan</span>
                    <span class="font-semibold text-gray-900">{{ $order->customer->full_name ?? 'Pelanggan #' . $order->customer_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Username</span>
                    <span class="text-gray-700">{{ $order->customer->username ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Kanal Penjualan</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                        {{ $order->sale_channel == 'Online' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                        {{ $order->sale_channel ?? 'Offline' }}
                    </span>
                </div>

                {{-- Nama & Telepon Penerima (dari form checkout peta) --}}
                @if($order->recipient_name)
                <div class="pt-2 border-t border-gray-100 flex justify-between">
                    <span class="text-gray-400 font-medium">Nama Penerima</span>
                    <span class="font-semibold text-gray-900">{{ $order->recipient_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Telepon Penerima</span>
                    <span class="font-semibold text-gray-900">{{ $order->recipient_phone ?? '-' }}</span>
                </div>
                @endif

                <div class="pt-2 border-t border-gray-100">
                    <span class="text-gray-400 font-medium block mb-1">Alamat Pengiriman</span>
                    <span class="text-gray-800 text-xs bg-amber-50 border border-amber-100 rounded px-3 py-1.5 block">
                        {{ $order->shipping_address }}
                    </span>
                </div>

                {{-- Kurir & Jarak (ditentukan sistem dari Haversine) --}}
                @if($order->delivery_courier)
                <div class="pt-2 border-t border-gray-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-medium">Kurir</span>
                        <div class="flex items-center gap-2">
                            @if($order->delivery_courier === 'Rancaoray')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    🚜 Diantar Langsung (Rancaoray)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    📦 JNT Express
                                </span>
                            @endif
                            @if($order->delivery_distance_km)
                                <span class="text-xs text-gray-400">{{ number_format($order->delivery_distance_km, 2) }} km</span>
                            @endif
                        </div>
                    </div>

                    {{-- Kontak Kurir --}}
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-medium">Kontak Kurir</span>
                        <div>
                            @if($order->delivery_courier === 'Rancaoray')
                                <a href="https://wa.me/6283116581808" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-lg transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>6283116581808 (WhatsApp)</span>
                                </a>
                            @elseif($order->delivery_courier === 'JNT')
                                @if($order->courier_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->courier_phone) }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-lg transition shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        <span>{{ $order->courier_phone }} (WhatsApp)</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded">
                                        Belum diinput
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Form Input / Ubah Kurir JNT --}}
                    @if($order->delivery_courier === 'JNT')
                        <div class="mt-2 bg-slate-50 border border-slate-200 rounded-lg p-3">
                            <details class="text-xs group" {{ !$order->courier_phone ? 'open' : '' }}>
                                <summary class="cursor-pointer font-semibold text-slate-700 hover:text-blue-700 flex items-center justify-between">
                                    <span>{{ $order->courier_phone ? '✏️ Ubah Nomor Telepon Kurir JNT' : '➕ Input Nomor Telepon Kurir JNT' }}</span>
                                    <span class="text-[10px] text-gray-400 group-open:rotate-180 transition">▼</span>
                                </summary>
                                <form action="/internal/orders/{{ $order->id }}/courier-phone" method="POST" class="mt-2.5 space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center gap-2">
                                        <input type="tel" name="courier_phone" required
                                            pattern="^(62|\+62)[0-9]{8,13}$"
                                            placeholder="Contoh: 628123456789"
                                            value="{{ old('courier_phone', $order->courier_phone) }}"
                                            title="Harus diawali 62 (contoh: 628123456789). Tidak boleh menggunakan 08."
                                            class="text-xs bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 flex-1">
                                        <button type="submit"
                                            class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 px-3.5 py-1.5 rounded-lg transition shadow-2xs shrink-0">
                                            {{ $order->courier_phone ? 'Perbarui' : 'Simpan Nomor' }}
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-gray-500">* Format nomor harus diawali 62 (contoh: 628123456789), tidak boleh menggunakan 08.</p>
                                </form>
                            </details>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Info Transaksi --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">💳 Informasi Transaksi</h3>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">ID Pesanan</span>
                    <span class="font-mono font-bold text-gray-900">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Tanggal Transaksi</span>
                    <span class="text-gray-700">{{ date('d M Y, H:i', strtotime($order->transaction_date)) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Metode Pembayaran</span>
                    <span class="font-semibold text-blue-700">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400 font-medium">Ongkos Kirim</span>
                    <span class="text-gray-700">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>


    {{-- Daftar Item Pesanan --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/60">
            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">🛒 Rincian Item Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga/Kg</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $i => $item)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-5 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-3">
                            <span class="font-semibold text-gray-900">{{ $item->product->name ?? 'Produk #' . $item->product_id }}</span>
                            @if($item->product && $item->product->category)
                            <span class="text-xs text-gray-400 ml-1">({{ $item->product->category }})</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right text-gray-700 font-medium">{{ number_format($item->quantity, 2, ',', '.') }} Kg</td>
                        <td class="px-5 py-3 text-right text-gray-700">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-gray-200 bg-gray-50/60">
                        <td colspan="4" class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal Barang</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">
                            Rp{{ number_format($order->total_payment - $order->shipping_cost, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="border-t border-gray-100 bg-gray-50/30">
                        <td colspan="4" class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ongkos Kirim</td>
                        <td class="px-5 py-3 text-right text-gray-700">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-t-2 border-gray-200 bg-emerald-50/40">
                        <td colspan="4" class="px-5 py-4 text-right font-bold text-gray-700 uppercase tracking-wider">Total Tagihan</td>
                        <td class="px-5 py-4 text-right font-bold text-emerald-700 text-base">
                            Rp{{ number_format($order->total_payment, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Aksi --}}
    <div class="flex items-center justify-between">
        <a href="/internal/orders"
            class="inline-flex items-center space-x-1.5 text-sm text-gray-600 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali</span>
        </a>

        <div class="flex items-center space-x-3">
            @if($order->order_status == 'Paid')
                <form action="/internal/orders/{{ $order->id }}/confirm" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                        Konfirmasi Pesanan
                    </button>
                </form>
            @endif

            @if($order->order_status == 'Processing')
                <form action="/internal/orders/{{ $order->id }}/complete" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-lg transition shadow-sm">
                        Cetak Nota & Selesaikan
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection
