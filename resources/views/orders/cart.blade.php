@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

{{-- Leaflet.js CSS — peta interaktif untuk pemilihan lokasi pengiriman --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
<style>
    /* Pastikan container peta memiliki dimensi pasti */
    #delivery-map {
        height: 260px !important;
        min-height: 260px !important;
        width: 100% !important;
        z-index: 0;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }
    .leaflet-pane          { z-index: 2 !important; }
    .leaflet-top,
    .leaflet-bottom        { z-index: 3 !important; }
    .leaflet-control       { z-index: 4 !important; }
</style>
@endsection

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
                    <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wider">Ringkasan & Pengiriman</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Lengkapi detail penerima untuk menentukan kurir pengiriman.</p>
                </div>

                <form action="/cart/checkout" method="POST" class="space-y-5" id="checkout-form">
                    @csrf

                    {{-- ── 1. NAMA PENERIMA ──────────────────────────────── --}}
                    <div>
                        <label for="recipient_name" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                            Nama Penerima <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="recipient_name"
                            name="recipient_name"
                            required
                            pattern="^[a-zA-Z\s\.\'\-]+$"
                            title="Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung."
                            placeholder="Contoh: Budi Santoso"
                            value="{{ old('recipient_name') }}"
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
                        >
                    </div>

                    {{-- ── 2. NOMOR TELEPON PENERIMA ─────────────────────── --}}
                    <div>
                        <label for="recipient_phone" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                            Nomor Telepon Penerima <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            inputmode="numeric"
                            id="recipient_phone"
                            name="recipient_phone"
                            required
                            pattern="^(62|\+62)[0-9]{8,13}$"
                            title="Format nomor telepon wajib diawali 62 (contoh: 628123456789). Tidak boleh menggunakan 08."
                            placeholder="Contoh: 628123456789"
                            value="{{ old('recipient_phone') }}"
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
                        >
                        <p class="text-[10px] text-gray-400 mt-1">Format: Wajib diawali 62 (contoh: 628123456789). Tidak boleh 08.</p>
                    </div>

                    {{-- ── 3. PETA LOKASI (Leaflet.js) ───────────────────── --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Lokasi Pengiriman (Peta) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[10px] text-emerald-700 font-medium bg-emerald-50 px-2 py-0.5 rounded">Radius ≤ 5 km = Antar Langsung</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mb-2">Klik pada peta atau seret marker untuk menentukan titik pengiriman rumah/kantor Anda.</p>

                        {{-- Map container --}}
                        <div id="delivery-map" style="height: 260px; min-height: 260px; width: 100%;"></div>

                        {{-- Courier badge — ditampilkan setelah titik dipilih --}}
                        <div id="courier-badge" class="hidden mt-2.5 flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-xs font-semibold border" role="status" aria-live="polite">
                            <span id="courier-label" class="flex items-center gap-1.5"></span>
                            <span id="courier-dist" class="font-semibold"></span>
                        </div>
                        <p id="map-hint" class="text-[10px] text-amber-600 mt-1.5 hidden">⚠ Belum ada titik yang dipilih. Klik pada peta untuk memilih lokasi pengiriman.</p>

                        {{-- Hidden inputs: diisi otomatis oleh JavaScript saat marker dipindah --}}
                        <input type="hidden" id="recipient_lat"        name="recipient_lat"        value="{{ old('recipient_lat') }}">
                        <input type="hidden" id="recipient_lng"        name="recipient_lng"        value="{{ old('recipient_lng') }}">
                        <input type="hidden" id="delivery_courier"     name="delivery_courier"     value="{{ old('delivery_courier') }}">
                        <input type="hidden" id="delivery_distance_km" name="delivery_distance_km" value="{{ old('delivery_distance_km') }}">
                    </div>

                    {{-- ── 4. ALAMAT PENERIMA ────────────────────────────── --}}
                    <div>
                        <label for="shipping_address" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                            Alamat Lengkap Penerima <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="shipping_address"
                            name="shipping_address"
                            rows="3"
                            required
                            minlength="10"
                            placeholder="Contoh: Jl. Dipatiukur No. 112, RT 03/RW 05, Kota Bandung..."
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition resize-none"
                        >{{ old('shipping_address', auth('web')->check() ? auth('web')->user()->shipping_address : '') }}</textarea>
                    </div>

                    {{-- ── RINGKASAN BIAYA ───────────────────────────────── --}}
                    <div class="pt-4 border-t border-gray-100 text-xs space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal Belanja</span>
                            <span class="font-medium text-gray-900">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span>
                                Ongkos Kirim
                                <span id="courier-cost-label" class="block font-normal text-gray-400 text-[10px]">Pilih lokasi di peta</span>
                            </span>
                            <span id="shipping-cost-display" class="font-medium text-gray-900">
                                Rp15.000 <span class="text-[10px] text-gray-400 font-normal">(Rancaoray)</span>
                            </span>
                        </div>
                        <p id="shipping-disclaimer" class="text-[10px] text-gray-500 italic">
                            * Radius ≤ 5 km diantar langsung oleh Pertanian (Rp15.000). Jika > 5 km via JNT (ongkir dibayarkan langsung oleh penerima saat paket tiba).
                        </p>
                        <div class="flex justify-between pt-2 border-t border-gray-100 text-sm font-bold text-gray-900">
                            <span>Total Pembayaran</span>
                            <span id="total-payment-display" class="text-emerald-700">Rp{{ number_format($subtotal + 15000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- ── SUBMIT ────────────────────────────────────────── --}}
                    <button
                        type="submit"
                        id="checkout-submit-btn"
                        class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg py-2.5 transition shadow-sm"
                    >
                        Proses Pembayaran (Midtrans)
                    </button>
                </form>
            </div>

        </div>
    @endif

</div>
@endsection

@section('scripts')

{{-- Hanya render peta jika keranjang tidak kosong --}}
@if(!empty($cart ?? []))
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
<script>
(function() {
    function initDeliveryMap() {
        const mapContainer = document.getElementById('delivery-map');
        if (!mapContainer || typeof L === 'undefined') {
            return;
        }

        // Koordinat kawasan pertanian Rancaoray
        const FARM_LAT = -6.9744;
        const FARM_LNG = 107.6713;
        const THRESHOLD_KM = 5.0; // <= 5 km: Rancaoray, > 5 km: JNT

        // Formula Haversine (menghitung jarak bola bumi dalam km)
        function haversineKm(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius bumi dalam km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        // Inisialisasi Peta Leaflet
        const map = L.map('delivery-map').setView([FARM_LAT, FARM_LNG], 13);

        // Tambahkan OpenStreetMap tile layer
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Pastikan ukuran peta disesuaikan setelah render
        setTimeout(function() {
            map.invalidateSize();
        }, 250);

        // Lingkaran visual radius 5 km dari Rancaoray
        L.circle([FARM_LAT, FARM_LNG], {
            radius: 5000,
            color: '#059669',
            fillColor: '#10b981',
            fillOpacity: 0.12,
            weight: 1.5,
            dashArray: '5, 5'
        }).addTo(map).bindTooltip('Area Pengantaran Pertanian (≤ 5 km)', { sticky: true });

        // Marker Pertanian Rancaoray (Pusat)
        L.circleMarker([FARM_LAT, FARM_LNG], {
            radius: 9,
            color: '#065f46',
            fillColor: '#059669',
            fillOpacity: 1,
            weight: 3
        }).addTo(map).bindPopup('<b>🌾 Pertanian Rancaoray</b><br><span style="font-size:11px">Titik asal pengiriman</span>').openPopup();

        // Marker Pelanggan
        let customerMarker = null;
        let routeLine = null;

        const oldLat = parseFloat('{{ old("recipient_lat", "") }}');
        const oldLng = parseFloat('{{ old("recipient_lng", "") }}');
        const hasOldCoords = !isNaN(oldLat) && !isNaN(oldLng);

        function updateDeliveryInfo(lat, lng) {
            const distKm = haversineKm(FARM_LAT, FARM_LNG, lat, lng);
            const isWithinRancaoray = (distKm <= THRESHOLD_KM);
            const courier = isWithinRancaoray ? 'Rancaoray' : 'JNT';

            // Hitung ongkir dinamis:
            // <= 5 km: Rp15.000 (Rancaoray)
            // > 5 km: Rp0 (JNT - bayar langsung oleh penerima saat tiba / COD)
            const subtotal = {{ $subtotal }};
            const shippingCost = isWithinRancaoray ? 15000 : 0;
            const totalPayment = subtotal + shippingCost;

            // Update hidden inputs
            document.getElementById('recipient_lat').value = lat.toFixed(7);
            document.getElementById('recipient_lng').value = lng.toFixed(7);
            document.getElementById('delivery_courier').value = courier;
            document.getElementById('delivery_distance_km').value = distKm.toFixed(2);

            // Update visual polyline
            if (routeLine) {
                map.removeLayer(routeLine);
            }
            routeLine = L.polyline([[FARM_LAT, FARM_LNG], [lat, lng]], {
                color: isWithinRancaoray ? '#059669' : '#2563eb',
                weight: 3,
                dashArray: '6, 6',
                opacity: 0.8
            }).addTo(map);

            // Update badge UI
            const badge = document.getElementById('courier-badge');
            const label = document.getElementById('courier-label');
            const dist = document.getElementById('courier-dist');
            const costLabel = document.getElementById('courier-cost-label');
            const shippingDisplay = document.getElementById('shipping-cost-display');
            const totalDisplay = document.getElementById('total-payment-display');
            const disclaimer = document.getElementById('shipping-disclaimer');

            badge.classList.remove('hidden', 'bg-black-50', 'border-black-200', 'text-black-800',
                                   'bg-black-50', 'border-black-200', 'text-black-800');

            if (isWithinRancaoray) {
                badge.classList.add('bg-black-50', 'border-black-200', 'text-black-800');
                label.innerHTML = '<span>Diantar Langsung oleh Pertanian Rancaoray</span>';
                dist.textContent = distKm.toFixed(2) + ' km (≤ 5 km)';
                costLabel.textContent = 'Diantar Pertanian (≤ 5 km)';
                if (shippingDisplay) shippingDisplay.innerHTML = 'Rp15.000 <span class="text-[10px] text-gray-400 font-normal">(flat)</span>';
                if (disclaimer) disclaimer.textContent = '* Ongkos kirim flat Rp15.000 diantar langsung oleh armada Pertanian Rancaoray.';
            } else {
                badge.classList.add('bg-black-50', 'border-black-200', 'text-black-800');
                label.innerHTML = '<span>Pengiriman via JNT Express</span>';
                dist.textContent = distKm.toFixed(2) + ' km (> 5 km)';
                costLabel.textContent = 'Via JNT Express (> 5 km)';
                if (shippingDisplay) shippingDisplay.innerHTML = '<span class="text-emerald-700 font-bold">Rp0</span> <span class="text-[10px] text-gray-500 font-normal">(Bayar di Tempat / COD)</span>';
                if (disclaimer) disclaimer.textContent = '* Ongkos kirim JNT ditanggung & dibayarkan langsung oleh penerima saat paket tiba (COD).';
            }

            if (totalDisplay) {
                totalDisplay.textContent = 'Rp' + totalPayment.toLocaleString('id-ID');
            }

            document.getElementById('map-hint').classList.add('hidden');
        }

        function setCustomerLocation(lat, lng) {
            if (!customerMarker) {
                customerMarker = L.marker([lat, lng], {
                    draggable: true,
                    title: 'Lokasi Pengiriman'
                }).addTo(map);

                customerMarker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    updateDeliveryInfo(pos.lat, pos.lng);
                });
            } else {
                customerMarker.setLatLng([lat, lng]);
            }

            updateDeliveryInfo(lat, lng);
        }

        // Jika ada koordinat lama (repopulate dari validation error)
        if (hasOldCoords) {
            setCustomerLocation(oldLat, oldLng);
            map.setView([oldLat, oldLng], 13);
        }

        // Event klik di peta untuk memilih lokasi
        map.on('click', function(e) {
            setCustomerLocation(e.latlng.lat, e.latlng.lng);
        });

        // Validasi sebelum submit form
        const form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const lat = document.getElementById('recipient_lat').value;
                const lng = document.getElementById('recipient_lng').value;
                if (!lat || !lng) {
                    e.preventDefault();
                    document.getElementById('map-hint').classList.remove('hidden');
                    mapContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDeliveryMap);
    } else {
        initDeliveryMap();
    }
})();
</script>
@endif

@if(session('snap_token'))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    {{-- Banner peringatan batas waktu 1 jam --}}
    <div id="payment-warning" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-red-600 text-white rounded-xl shadow-xl px-6 py-4 flex items-start space-x-3 max-w-sm w-full" style="display:none !important;">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-bold">⏳ Batas Waktu Pembayaran: 1 Jam</p>
            <p class="text-xs text-red-100 mt-0.5">Selesaikan pembayaran sebelum waktu habis. Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 1 jam.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            snap.pay('{{ session("snap_token") }}', {
                onSuccess: function(result){
                    window.location.href = '/orders/{{ session("order_id") }}/success';
                },
                onPending: function(result){
                    document.getElementById('payment-warning').style.display = 'flex';
                },
                onError: function(result){
                    alert("Pembayaran gagal! Silakan coba lagi.");
                },
                onClose: function(){
                    document.getElementById('payment-warning').style.display = 'flex';
                }
            });
        });
    </script>
@endif
@endsection

