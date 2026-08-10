@extends('layouts.app')

@section('title', 'Atur Penjualan Online')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Kelola Katalog & Penjualan Online</h2>
        <p class="text-xs text-gray-500 mt-1">Pisahkan stok hasil panen (/production) untuk dijual online. Setiap paket (5 Kg, 10 Kg, 20 Kg) bisa diatur secara independen.</p>
    </div>

    @if(session('success'))
        <div class="p-3 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-50 text-red-800 border border-red-200 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @forelse($products as $prod)
        <!-- Product Section Header -->
        <!-- <div class="border-b border-gray-200 pb-2 pt-4 flex items-center space-x-3">
            @if($prod->image_path)
                <img src="{{ asset($prod->image_path) }}" alt="{{ $prod->name }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200 shrink-0">
            @else
                <div class="w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ substr($prod->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ $prod->name }}</h3>
                <p class="text-[10px] text-gray-400">Stok Gudang (Offline): <span class="font-bold text-gray-700">{{ number_format($prod->stock_available) }} Kg</span> · Harga Dasar: <span class="font-bold text-gray-700">Rp{{ number_format($prod->price, 0, ',', '.') }}/Kg</span></p>
            </div>
        </div> -->

        <!-- Package Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([5, 10, 20] as $size)
                @php
                    $pkg = $prod->packages->firstWhere('package_size', $size);
                    $isActive = $pkg && $pkg->is_active;
                    $pkgName = $pkg->online_name ?? ($prod->name . ' (' . $size . ' Kg)');
                    $pkgPrice = $pkg->online_price ?? ($prod->price * $size);
                    $pkgStock = $pkg->online_stock ?? 0;
                    $pkgImage = $pkg->image_path ?? $prod->image_path;
                @endphp
                <div class="bg-white border {{ $isActive ? 'border-emerald-200' : 'border-gray-200' }} rounded-xl overflow-hidden shadow-sm hover:border-emerald-300 transition duration-200 flex flex-col justify-between">
                    <div>
                        <!-- Banner -->
                        <div class="h-36 bg-gray-50 border-b border-gray-100 relative flex items-center justify-center overflow-hidden">
                            @if($pkgImage)
                                <img src="{{ asset($pkgImage) }}" alt="{{ $pkgName }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center justify-center text-gray-300 space-y-1">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-[9px] uppercase font-semibold tracking-wider text-gray-400">Belum ada gambar</span>
                                </div>
                            @endif

                            <div class="absolute top-2.5 left-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-white/90 text-gray-800 border border-gray-200 shadow-sm">
                                    Paket {{ $size }} Kg
                                </span>
                            </div>

                            <div class="absolute top-2.5 right-2.5">
                                @if($isActive)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Aktif Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                        Draft / Offline
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-5 space-y-3">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $pkgName }}</h4>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 bg-gray-50 border border-gray-100 p-2.5 rounded-lg text-xs">
                                <div>
                                    <span class="text-gray-400 block mb-0.5 text-[10px]">Harga / Pak</span>
                                    <span class="font-bold text-emerald-700">Rp{{ number_format($pkgPrice, 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block mb-0.5 text-[10px]">Stok Online</span>
                                    <span class="font-black text-emerald-700">{{ $pkgStock }} pak</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Action -->
                    <div class="p-5 pt-0">
                        <button type="button"
                            onclick="openModal({{ $prod->id }}, {{ $size }}, {{ json_encode([
                                'product_name' => $prod->name,
                                'stock_available' => $prod->stock_available,
                                'unit' => $prod->unit,
                                'base_price' => $prod->price,
                                'pkg_name' => $pkgName,
                                'pkg_price' => $pkgPrice,
                                'pkg_stock' => $pkgStock,
                                'is_active' => $isActive,
                                'pkg_image' => $pkgImage ? asset($pkgImage) : null,
                            ]) }})"
                            class="w-full text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg px-4 py-2 transition shadow-sm">
                            Edit Paket {{ $size }} Kg
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="bg-amber-50 text-amber-800 border border-amber-200 rounded-xl p-8 text-center text-sm font-medium">
            Belum ada produk beras hasil produksi yang terdaftar. Silakan catat hasil panen terlebih dahulu di halaman <a href="/production/harvest" class="underline font-bold">Produksi</a>.
        </div>
    @endforelse
</div>

<!-- ============== MODAL ============== -->
<div id="pkg-modal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white border border-gray-200 rounded-xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900" id="modal-title">Edit Paket</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="pkg-form" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Active checkbox -->
            <div class="flex items-start space-x-3 bg-emerald-50/50 border border-emerald-100 p-3.5 rounded-lg">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="mt-1 h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                <div>
                    <label for="is_active" class="text-xs font-bold text-emerald-800 cursor-pointer block">Tampilkan di Katalog Online</label>
                    <p class="text-[10px] text-emerald-600 mt-0.5">Jika aktif, pelanggan dapat melihat dan memesan paket ini.</p>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Nama Paket Online</label>
                <input type="text" name="online_name" id="pkg_name" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <small class="text-[10px] text-gray-400 block mt-1">Nama yang dilihat pelanggan di katalog. Contoh: "Beras Premium Pandan Wangi 5 Kg"</small>
            </div>

            <!-- Price per pack -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Harga Jual Online (Per Pak)</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-xs font-semibold text-gray-400">Rp</span>
                    <input type="number" name="online_price" id="pkg_price" required min="1"
                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg pl-9 pr-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>
                <small class="text-[10px] text-gray-400 block mt-1" id="price-hint">Harga per pak (bukan per Kg).</small>
            </div>

            <!-- Stock allocation -->
            <div class="border border-gray-100 rounded-lg p-4 bg-gray-50/50 space-y-3">
                <div>
                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Alokasi Stok Paket</h4>
                    <p class="text-[10px] text-gray-500 mt-0.5">Pindahkan stok dari gudang ke marketplace online (dalam satuan <strong>pak</strong>).</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-500 mb-1">Stok Gudang Tersedia</label>
                        <input type="text" id="gudang_display" readonly
                            class="w-full text-xs bg-gray-100 border border-gray-200 rounded px-2.5 py-2 text-gray-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-emerald-700 mb-1">Stok Online (Pak)</label>
                        <input type="number" name="online_stock" id="pkg_stock" required min="0" oninput="calcTransfer()"
                            class="w-full text-xs bg-white border border-gray-200 rounded px-2.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    </div>
                </div>
                <div class="text-[10px] font-semibold" id="transfer-helper">
                    <!-- Dynamic helper text -->
                </div>
            </div>

            <!-- Image upload -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Gambar Paket</label>
                <div id="current-image-preview" class="mb-2 hidden">
                    <img id="preview-img" src="" alt="Current" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                    <span class="text-[10px] text-gray-400 block mt-0.5">Gambar saat ini</span>
                </div>
                <input type="file" name="image" accept="image/*"
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                <small class="text-[10px] text-gray-400 block mt-1">Format: JPG, PNG. Max 2MB. Gambar khusus untuk paket ini.</small>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeModal()"
                    class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 transition">
                    Batal
                </button>
                <button type="submit" id="submit-btn"
                    class="text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg px-4 py-2 transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let activeCtx = null; // { productId, size, data }

    function openModal(productId, size, data) {
        activeCtx = { productId, size, data };

        document.getElementById('modal-title').innerText = `Edit Paket ${size} Kg: ${data.product_name}`;
        document.getElementById('pkg-form').action = `/internal/online-products/${productId}/package/${size}`;

        document.getElementById('is_active').checked = data.is_active;
        document.getElementById('pkg_name').value = data.pkg_name;
        document.getElementById('pkg_price').value = data.pkg_price;
        document.getElementById('pkg_stock').value = data.pkg_stock;
        document.getElementById('gudang_display').value = `${data.stock_available.toLocaleString()} Kg`;
        document.getElementById('price-hint').innerText = `Harga per pak ${size} Kg. Harga dasar: Rp${(data.base_price * size).toLocaleString()}.`;

        // Show current image preview
        const previewContainer = document.getElementById('current-image-preview');
        const previewImg = document.getElementById('preview-img');
        if (data.pkg_image) {
            previewImg.src = data.pkg_image;
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }

        calcTransfer();

        document.getElementById('pkg-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('pkg-modal').classList.add('hidden');
        activeCtx = null;
    }

    function calcTransfer() {
        if (!activeCtx) return;

        const inputPacks = parseInt(document.getElementById('pkg_stock').value) || 0;
        const currentPacks = activeCtx.data.pkg_stock;
        const diffPacks = inputPacks - currentPacks;
        const diffKg = diffPacks * activeCtx.size;

        const helper = document.getElementById('transfer-helper');
        const btn = document.getElementById('submit-btn');

        if (diffKg > 0) {
            if (activeCtx.data.stock_available < diffKg) {
                helper.innerHTML = `<span class="text-red-600">⚠️ Stok gudang tidak cukup! Butuh ${diffKg.toLocaleString()} Kg, tersedia ${activeCtx.data.stock_available.toLocaleString()} Kg.</span>`;
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                helper.innerHTML = `<span class="text-amber-600">ℹ️ Akan memindahkan ${diffPacks} pak (${diffKg.toLocaleString()} Kg) dari Gudang → Online.</span>`;
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        } else if (diffKg < 0) {
            helper.innerHTML = `<span class="text-blue-600">ℹ️ Akan mengembalikan ${Math.abs(diffPacks)} pak (${Math.abs(diffKg).toLocaleString()} Kg) dari Online → Gudang.</span>`;
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            helper.innerHTML = `<span class="text-gray-500">Stok tetap sama.</span>`;
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
</script>

@endsection
