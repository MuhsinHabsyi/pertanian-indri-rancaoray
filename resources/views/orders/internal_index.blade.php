@extends('layouts.app')

@section('title', 'Penjualan Hasil Pertanian')

@section('content')

    <!-- ==================== INTERNAL STAFF DASHBOARD ORDERS ==================== -->
    
        <!-- Right Column: Daftar Pesanan Masuk -->
        <div class="bg-white border border-gray-200 rounded-lg lg:col-span-2 shadow-sm flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50 rounded-t-lg">
                <h3 class="text-sm font-semibold text-gray-900">Daftar Pesanan Masuk</h3>
                <span class="text-xs text-gray-500 font-medium">Aktor: Petugas Operasional</span>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($orders as $o)
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $o->items[0]->product->name ?? '-' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    ({{ $o->items[0]->quantity ?? 0 }} Kg)
                                </span>
                            </div>
                            {{-- Nama Pemesan --}}
                            <div class="flex items-center space-x-1.5 text-xs">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-500 font-medium">Pemesan:</span>
                                <span class="font-semibold text-gray-800">{{ $o->customer->full_name ?? 'Pelanggan #' . $o->customer_id }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs text-gray-500">
                                <div>
                                    <span class="font-medium text-gray-400">Tanggal Transaksi:</span> 
                                    {{ date('d M Y H:i', strtotime($o->transaction_date)) }}
                                </div>
                                <div>
                                    <span class="font-medium text-gray-400">Metode Bayar:</span> 
                                    <span class="text-blue-600 font-medium">{{ $o->payment_method }}</span>
                                </div>

                                {{-- Nama & Telepon Penerima (kolom baru dari migration) --}}
                                @if($o->recipient_name)
                                <div>
                                    <span class="font-medium text-gray-400">Nama Penerima:</span>
                                    <span class="font-semibold text-gray-800">{{ $o->recipient_name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-400">Telepon:</span>
                                    <span class="font-semibold text-gray-800">{{ $o->recipient_phone ?? '-' }}</span>
                                </div>
                                @endif

                                <div class="col-span-2">
                                    <span class="font-medium text-gray-400">Alamat Pengiriman:</span> 
                                    <span class="text-amber-800 font-semibold bg-amber-50/70 px-2 py-0.5 rounded inline-block mt-0.5">{{ $o->shipping_address }}</span>
                                </div>

                                {{-- Kurir & Jarak (ditentukan otomatis dari peta saat checkout) --}}
                                @if($o->delivery_courier)
                                <div class="col-span-2 flex flex-col gap-1.5 mt-0.5 pt-1.5 border-t border-gray-100">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-gray-400">Kurir:</span>
                                        @if($o->delivery_courier === 'Rancaoray')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                🚜 Diantar Langsung (Rancaoray)
                                            </span>
                                            @if($o->delivery_distance_km)
                                                <span class="text-[10px] text-gray-400">({{ number_format($o->delivery_distance_km, 2) }} km)</span>
                                            @endif
                                            <span class="text-gray-400 text-[10px]">• No. Kurir:</span>
                                            <a href="https://wa.me/6283116581808" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded transition shadow-2xs">
                                                <svg class="w-3 h-3 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                </svg>
                                                <span>6283116581808 (WhatsApp)</span>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                📦 JNT Express
                                            </span>
                                            @if($o->delivery_distance_km)
                                                <span class="text-[10px] text-gray-400">({{ number_format($o->delivery_distance_km, 2) }} km)</span>
                                            @endif

                                            @if($o->courier_phone)
                                                <span class="text-gray-400 text-[10px]">• No. Kurir:</span>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $o->courier_phone) }}" target="_blank" rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded transition shadow-2xs">
                                                    <svg class="w-3 h-3 text-emerald-600 fill-current" viewBox="0 0 24 24">
                                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                    </svg>
                                                    <span>{{ $o->courier_phone }} (WhatsApp)</span>
                                                </a>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Form Input / Update Nomor Kurir JNT jika kurir JNT --}}
                                    @if($o->delivery_courier === 'JNT')
                                        <div class="mt-1 bg-slate-50 border border-slate-200 rounded-lg p-2.5">
                                            <details class="text-[11px] group" {{ !$o->courier_phone ? 'open' : '' }}>
                                                <summary class="cursor-pointer font-semibold text-slate-700 hover:text-blue-700 flex items-center justify-between">
                                                    <span>{{ $o->courier_phone ? '✏️ Ubah Nomor Telepon Kurir JNT' : '➕ Input Nomor Telepon Kurir JNT' }}</span>
                                                    <span class="text-[10px] text-gray-400 group-open:rotate-180 transition">▼</span>
                                                </summary>
                                                <form action="/internal/orders/{{ $o->id }}/courier-phone" method="POST" class="mt-2 flex flex-wrap items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="tel" name="courier_phone" required
                                                        pattern="^(62|\+62)[0-9]{8,13}$"
                                                        placeholder="Contoh: 628123456789"
                                                        value="{{ old('courier_phone', $o->courier_phone) }}"
                                                        title="Harus diawali 62 (contoh: 628123456789). Tidak boleh menggunakan 08."
                                                        class="text-xs bg-white border border-gray-300 rounded px-2.5 py-1 text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 w-48">
                                                    <button type="submit"
                                                        class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 px-3 py-1 rounded transition shadow-2xs">
                                                        {{ $o->courier_phone ? 'Perbarui' : 'Simpan Nomor' }}
                                                    </button>
                                                    <span class="text-[10px] text-gray-500 w-full">* Wajib diawali 62 (contoh: 628123456789), tidak boleh 08.</span>
                                                </form>
                                            </details>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                <div class="col-span-2">
                                    <span class="font-medium text-gray-400">Rincian Bayar:</span> 
                                    Barang (Rp{{ number_format($o->total_payment - $o->shipping_cost, 0, ',', '.') }}) + Ongkir (Rp{{ number_format($o->shipping_cost, 0, ',', '.') }})
                                </div>
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-400">Total Tagihan:</span> 
                                    <span class="font-semibold text-gray-800 text-sm">Rp{{ number_format($o->total_payment, 0, ',', '.') }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-col items-end justify-between space-y-3">
                            <!-- Status Badge -->
                            @if($o->order_status == 'Pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    Belum Dibayar
                                </span>
                            @elseif($o->order_status == 'Paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    Sudah Dibayar (Paid)
                                </span>
                            @elseif($o->order_status == 'Processing')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    Diproses (Processing)
                                </span>
                            @elseif($o->order_status == 'Completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Selesai (Completed)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-100">
                                    {{ $o->order_status }}
                                </span>
                            @endif

                            <!-- Action Buttons -->
                            @if($o->order_status == 'Paid')
                                <form action="/internal/orders/{{ $o->id }}/confirm" method="POST" class="mt-2">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                        class="text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-lg transition shadow-sm">
                                        Konfirmasi Pesanan
                                    </button>
                                </form>
                            @endif
                            
                            @if($o->order_status == 'Processing')
                                <form action="/internal/orders/{{ $o->id }}/complete" method="POST" class="mt-2">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                        class="text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg transition shadow-sm">
                                        Cetak Nota & Selesaikan
                                    </button>
                                </form>
                            @endif

                            {{-- Tombol Lihat Nota untuk Completed --}}
                            @if($o->order_status == 'Completed' && $o->nota_number)
                                <a href="/internal/orders/{{ $o->id }}/nota" target="_blank"
                                    class="mt-2 inline-flex items-center space-x-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Lihat Nota</span>
                                </a>
                            @endif

                            {{-- Tombol Lihat Detail --}}
                            <a href="/internal/orders/{{ $o->id }}" class="mt-2 inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-lg transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

@if(isset($snap_token) || session('snap_token'))
    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            snap.pay('{{ session('snap_token') ?? $snap_token }}', {
                onSuccess: function(result){
                    window.location.href = '/orders/' + '{{ session('order_id') ?? $order_id }}' + '/success';
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

@section('scripts')
<script>
    function checkStock() {
        const select = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const btnCheckout = document.getElementById('btn_checkout');
        const warning = document.getElementById('stock_warning');

        if(select.selectedIndex === 0 || !quantityInput.value) return;

        const selectedOption = select.options[select.selectedIndex];
        const maxStock = parseInt(selectedOption.getAttribute('data-stock'));
        const inputQty = parseInt(quantityInput.value);

        if (inputQty > maxStock) {
            btnCheckout.disabled = true;
            btnCheckout.classList.add('opacity-50', 'cursor-not-allowed');
            warning.style.display = 'block';
        } else {
            btnCheckout.disabled = false;
            btnCheckout.classList.remove('opacity-50', 'cursor-not-allowed');
            warning.style.display = 'none';
        }
    }
</script>
@endsection
