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
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $o->items[0]->product->name ?? '-' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    ({{ $o->items[0]->quantity ?? 0 }} Kg)
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $o->sale_channel ?? 'Online' }}
                                </span>
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
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-400">Alamat Pengiriman:</span> 
                                    <span class="text-amber-800 font-semibold bg-amber-50/70 px-2 py-0.5 rounded inline-block mt-0.5">{{ $o->shipping_address }}</span>
                                </div>
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
