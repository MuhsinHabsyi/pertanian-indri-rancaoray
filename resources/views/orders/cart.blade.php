@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-6xl">
    
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 font-sans">Keranjang Belanja Anda</h2>
        <p class="text-xs text-gray-500 mt-1">Kelola item pilihan Anda dan selesaikan transaksi dengan aman.</p>
    </div>

    <!-- Error/Validation Messages -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center space-x-2">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if(empty($cart))
        <!-- Empty Cart State -->
        <div class="bg-white border border-gray-200 rounded-xl p-16 text-center shadow-sm space-y-4">
            <div class="mx-auto w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="space-y-1">
                <h3 class="text-sm font-semibold text-gray-800">Keranjang Belanja Kosong</h3>
                <p class="text-xs text-gray-400">Anda belum menambahkan paket beras apa pun ke keranjang belanja.</p>
            </div>
            <div class="pt-2">
                <a href="/orders" 
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                    Lihat Katalog Beras
                </a>
            </div>
        </div>
    @else
        <!-- Cart Content split screen -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Cart Items List -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden lg:col-span-2">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wider">Item Pilihan</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @php
                        $subtotal = 0;
                    @endphp
                    @foreach($cart as $cartId => $item)
                        @php
                            $itemTotal = $item['price'] * $item['quantity'];
                            $subtotal += $itemTotal;
                        @endphp
                        <div class="p-6 flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-gray-900">{{ $item['name'] }}</h4>
                                <div class="flex items-center gap-3 text-xs text-gray-400">
                                    <span>Harga: Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                                    <span>&bull;</span>
                                    <span>Ukuran: {{ $item['package_size'] }} Kg</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6">
                                <!-- Quantity & Subtotal -->
                                <div class="text-right space-y-0.5">
                                    <span class="text-xs text-gray-500 font-medium block">Qty: {{ $item['quantity'] }} pak</span>
                                    <span class="text-sm font-bold text-emerald-700 block">Rp{{ number_format($itemTotal, 0, ',', '.') }}</span>
                                </div>

                                <!-- Remove Button -->
                                <form action="/cart/remove/{{ $cartId }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Checkout Summary -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wider">Ringkasan Pesanan</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Selesaikan detail pengiriman untuk checkout.</p>
                </div>

                <form action="/cart/checkout" method="POST" class="space-y-5">
                    @csrf

                    <!-- Shipping Address -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Alamat Pengiriman Lengkap</label>
                        <textarea name="shipping_address" rows="3" required placeholder="Contoh: Jl. Dipatiukur No. 112, Kota Bandung..."
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition resize-none">{{ auth('web')->check() ? auth('web')->user()->shipping_address : '' }}</textarea>
                    </div>

                    <!-- Cost Calculations -->
                    <div class="pt-4 border-t border-gray-100 text-xs space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal Belanja</span>
                            <span class="font-medium text-gray-900">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim (Flat)</span>
                            <span class="font-medium text-gray-900">Rp15.000</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-100 text-sm font-bold text-gray-900">
                            <span>Total Pembayaran</span>
                            <span class="text-emerald-700">Rp{{ number_format($subtotal + 15000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg py-2.5 transition shadow-sm">
                        Proses Pembayaran (Midtrans)
                    </button>
                </form>
            </div>

        </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

@if(session('snap_token'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            snap.pay('{{ session("snap_token") }}', {
                onSuccess: function(result){
                    window.location.href = '/orders/{{ session("order_id") }}/success';
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert("Anda menutup jendela pembayaran tanpa menyelesaikannya");
                }
            });
        });
    </script>
@endif
@endsection
