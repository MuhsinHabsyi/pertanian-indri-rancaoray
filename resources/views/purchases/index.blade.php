@extends('layouts.app')

@section('title', 'Pembiayaan Produksi')

@section('content')
<div class="space-y-6">

    <!-- Top Stock Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stok Bibit Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
            <div class="flex items-center space-x-3">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Bibit</h4>
                    <span class="text-lg font-bold text-gray-900">
                        {{ number_format($seedProducts->sum('stock_available')) }} <span class="text-xs font-normal text-gray-500">Unit/Kg</span>
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-2.5 space-y-1.5 text-xs text-gray-600">
                @forelse($seedProducts as $sp)
                    <div class="flex justify-between items-center">
                        <span class="truncate font-medium text-gray-700">{{ $sp->name }}</span>
                        <span class="font-bold text-emerald-700 ml-2 shrink-0">{{ number_format($sp->stock_available) }} {{ $sp->unit }}</span>
                    </div>
                @empty
                    <span class="text-gray-400 italic">Belum ada data bibit</span>
                @endforelse
            </div>
        </div>

        <!-- Stok Pupuk Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
            <div class="flex items-center space-x-3">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Pupuk</h4>
                    <span class="text-lg font-bold text-gray-900">
                        {{ number_format($fertilizerProducts->sum('stock_available')) }} <span class="text-xs font-normal text-gray-500">Unit/Kg</span>
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-2.5 space-y-1.5 text-xs text-gray-600">
                @forelse($fertilizerProducts as $fp)
                    <div class="flex justify-between items-center">
                        <span class="truncate font-medium text-gray-700">{{ $fp->name }}</span>
                        <span class="font-bold text-blue-700 ml-2 shrink-0">{{ number_format($fp->stock_available) }} {{ $fp->unit }}</span>
                    </div>
                @empty
                    <span class="text-gray-400 italic">Belum ada data pupuk</span>
                @endforelse
            </div>
        </div>

        <!-- Stok Beras Hasil Panen Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
            <div class="flex items-center space-x-3">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Beras Hasil Panen</h4>
                    <span class="text-lg font-bold text-gray-900">
                        {{ number_format($riceProducts->sum('stock_available')) }} <span class="text-xs font-normal text-gray-500">Kg</span>
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-2.5 space-y-1.5 text-xs text-gray-600">
                @forelse($riceProducts as $rp)
                    <div class="flex justify-between items-center">
                        <span class="truncate font-medium text-gray-700">{{ $rp->name }}</span>
                        <span class="font-bold text-amber-700 ml-2 shrink-0">{{ number_format($rp->stock_available) }} {{ $rp->unit }}</span>
                    </div>
                @empty
                    <span class="text-gray-400 italic">Belum ada data beras</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <!-- Left Column: Form Pengajuan Pengadaan -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">Form Pengajuan Pengadaan</h3>
            <p class="text-xs text-gray-500 mt-1">Aktor: Petugas Operasional</p>
        </div>

        <!-- Error / Validation Alert -->
        @if($errors->any())
            <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-red-700">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Peringatan Input Data:</span>
                </div>
                <ul class="list-disc list-inside pl-1 text-red-600 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/purchases" method="POST" id="purchase_form" class="space-y-4">
            @csrf
            
            <!-- Tanggal Pengajuan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Tanggal Pengajuan</label>
                <input type="date" name="submission_date" value="{{ old('submission_date', date('Y-m-d')) }}" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Sumber Pengadaan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Sumber Pengadaan</label>
                <select name="procurement_source" id="procurement_source" required onchange="toggleCostInput()"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <option value="Mandiri" {{ old('procurement_source') == 'Mandiri' ? 'selected' : '' }}>Pembelian Mandiri (Modal Sendiri)</option>
                    <option value="Subsidi" {{ old('procurement_source') == 'Subsidi' ? 'selected' : '' }}>Bantuan / Subsidi Pemerintah (Gratis)</option>
                </select>
            </div>

            <!-- Pilih Sarana Produksi -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Sarana Produksi</label>
                <select name="product_id" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ old('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }} ({{ $prod->category }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah Kebutuhan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Kebutuhan</label>
                <input type="number" name="quantity" id="purchase_quantity" min="1" step="any" required placeholder="Contoh: 10" value="{{ old('quantity') }}"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <p id="qty_warning" class="text-[11px] text-red-600 mt-1 hidden">⚠️ Jumlah kebutuhan harus lebih dari 0 (tidak boleh 0 atau minus).</p>
            </div>

            <!-- Estimasi Biaya Total -->
            <div id="cost_container">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Estimasi Biaya Total (Rp)</label>
                <input type="number" name="total_cost" id="total_cost" min="1" step="any" required placeholder="Contoh: 150000" value="{{ old('total_cost') }}"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <p id="cost_warning" class="text-[11px] text-red-600 mt-1 hidden">⚠️ Estimasi biaya harus lebih dari 0 (tidak boleh 0 atau minus).</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg py-2.5 transition shadow-sm mt-2">
                Ajukan Pengadaan
            </button>
        </form>
    </div>

    <!-- Right Column: Daftar Riwayat Pengadaan -->
    <div class="bg-white border border-gray-200 rounded-lg lg:col-span-2 shadow-sm flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50 rounded-t-lg">
            <h3 class="text-sm font-semibold text-gray-900">Daftar Riwayat Pengadaan</h3>
            <span class="text-xs text-gray-500 font-medium">Log Pengadaan Sarana</span>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($purchases as $p)
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $p->items[0]->product->name ?? '-' }}
                            </span>
                            <span class="text-xs text-gray-500">
                                ({{ $p->items[0]->quantity ?? 0 }} unit)
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-500">
                            <div>
                                <span class="font-medium text-gray-400">Tanggal:</span> 
                                {{ date('d M Y', strtotime($p->submission_date)) }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-400">Sumber:</span> 
                                @if($p->procurement_source == 'Subsidi')
                                    <span class="text-blue-600 font-medium">Subsidi Pemerintah</span>
                                @else
                                    <span class="text-gray-700 font-medium">Mandiri</span>
                                @endif
                            </div>
                            <div>
                                <span class="font-medium text-gray-400">Total Biaya:</span> 
                                @if($p->is_realized)
                                    <span class="font-semibold text-emerald-700">Rp{{ number_format($p->total_cost, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 font-medium ml-1">Realisasi</span>
                                @elseif($p->receipt_proof)
                                    <span class="font-semibold text-gray-800">Rp{{ number_format($p->total_cost, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100 font-medium ml-1">Nota Terbaru</span>
                                @else
                                    <span class="font-semibold text-gray-700">Rp{{ number_format($p->total_cost, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded font-medium ml-1">Estimasi Form</span>
                                @endif
                            </div>
                            @if($p->receipt_proof)
                                <div class="col-span-2 flex flex-col space-y-1.5 mt-2 pt-2 border-t border-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[11px] font-medium text-gray-600">Foto Nota Pembelian:</span>
                                        <button type="button" onclick="openReceiptModal('{{ url('/purchases/' . $p->id . '/receipt') }}')"
                                            class="inline-flex items-center space-x-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Lihat Gambar Nota</span>
                                        </button>
                                    </div>
                                    <div class="mt-1 flex items-center space-x-3">
                                        <img src="{{ url('/purchases/' . $p->id . '/receipt') }}" 
                                            alt="Nota Pengadaan" 
                                            onclick="openReceiptModal('{{ url('/purchases/' . $p->id . '/receipt') }}')"
                                            class="w-20 h-20 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition shadow-sm hover:ring-2 hover:ring-emerald-500">
                                        <span class="text-[10px] text-gray-400 italic">Klik gambar untuk memperbesar</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-end justify-between space-y-3">
                        <!-- Status Badge -->
                        <div class="flex flex-col items-end space-y-1">
                            @if($p->validation_status == 'Pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    Menunggu Validasi
                                </span>
                            @elseif($p->validation_status == 'Approved')
                                @if($p->is_realized)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Barang Masuk Dikonfirmasi
                                    </span>
                                @elseif($p->receipt_proof)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        Nota Terunggah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Disetujui
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                    Ditolak
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @if($p->validation_status == 'Pending' && auth('internal')->user()->role === 'Owner')
                            <div class="flex items-center space-x-2">
                                <form action="/purchases/{{ $p->id }}/validate" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="Approved">
                                    <button type="submit" 
                                        class="text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-lg transition">
                                        Setujui
                                    </button>
                                </form>
                                <form action="/purchases/{{ $p->id }}/validate" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="Rejected">
                                    <button type="submit" 
                                        class="text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 border border-red-200 px-3 py-1.5 rounded-lg transition">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($p->validation_status == 'Approved' && !$p->receipt_proof)
                            <form action="/purchases/{{ $p->id }}/upload-receipt" method="POST" enctype="multipart/form-data" class="flex flex-col items-end space-y-1.5">
                                @csrf
                                <label class="text-[11px] font-semibold text-gray-600">Unggah Foto Nota:</label>
                                <input type="file" name="receipt_proof" accept="image/*" required
                                    class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 text-gray-800 w-52 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                                <button type="submit" 
                                    class="text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded px-3 py-1.5 shadow-sm transition">
                                    Unggah Nota
                                </button>
                            </form>
                        @endif

                        @if($p->validation_status == 'Approved' && $p->receipt_proof && !$p->is_realized)
                            @if(auth('internal')->user()->role === 'Owner')
                                <div class="flex flex-col items-end space-y-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                    <div class="text-right">
                                        <label class="text-[11px] font-semibold text-slate-800 block">Harga Sebenarnya (Nota):</label>
                                        <p class="text-[10px] text-slate-500">Sesuaikan total biaya dari nota sebelum konfirmasi</p>
                                    </div>
                                    <form action="/purchases/{{ $p->id }}/realize" method="POST" class="flex flex-col items-end space-y-2">
                                        @csrf @method('PATCH')
                                        <div class="flex items-center space-x-1">
                                            <span class="text-xs font-bold text-gray-600">Rp</span>
                                            <input type="number" name="actual_cost" value="{{ (int)$p->total_cost }}" min="0" step="1" required
                                                class="w-36 text-xs bg-white border border-gray-300 rounded px-2.5 py-1 text-gray-900 font-semibold focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="submit" formaction="/purchases/{{ $p->id }}/update-cost"
                                                class="text-[11px] font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 px-2.5 py-1.5 rounded-lg transition shadow-2xs">
                                                Update Biaya
                                            </button>
                                            <button type="submit" 
                                                class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-3.5 py-1.5 shadow-sm transition flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Konfirmasi Barang Masuk</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <span class="text-[11px] italic text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100">
                                    Menunggu Konfirmasi Pemilik
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeReceiptModal()">
    <div class="bg-white rounded-xl max-w-2xl w-full p-4 relative shadow-2xl space-y-3" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-gray-100 pb-2">
            <h4 class="text-sm font-bold text-gray-900">Bukti Nota Pengadaan</h4>
            <button type="button" onclick="closeReceiptModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="max-h-[75vh] overflow-auto flex items-center justify-center bg-gray-50 rounded-lg p-2 border border-gray-100">
            <img id="receiptModalImg" src="" alt="Nota Full" class="max-w-full h-auto max-h-[65vh] rounded object-contain shadow-sm">
        </div>
        <div class="flex justify-end pt-1">
            <button type="button" onclick="closeReceiptModal()" class="text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-1.5 rounded-lg transition">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleCostInput() {
        const source = document.getElementById('procurement_source').value;
        const costContainer = document.getElementById('cost_container');
        const costInput = document.getElementById('total_cost');
        const costWarning = document.getElementById('cost_warning');

        if (source === 'Subsidi') {
            costContainer.style.display = 'none';
            costInput.removeAttribute('required');
            costInput.value = '';
            if (costWarning) costWarning.classList.add('hidden');
            costInput.classList.remove('border-red-500', 'bg-red-50');
        } else {
            costContainer.style.display = 'block';
            costInput.setAttribute('required', 'true');
        }
    }

    function openReceiptModal(src) {
        document.getElementById('receiptModalImg').src = src;
        document.getElementById('receiptModal').classList.remove('hidden');
    }

    function closeReceiptModal() {
        document.getElementById('receiptModal').classList.add('hidden');
        document.getElementById('receiptModalImg').src = '';
    }

    // Client-side validation: cegah nilai 0 dan minus
    document.addEventListener('DOMContentLoaded', function() {
        toggleCostInput();

        const form = document.getElementById('purchase_form');
        const qtyInput = document.getElementById('purchase_quantity');
        const qtyWarning = document.getElementById('qty_warning');
        const costInput = document.getElementById('total_cost');
        const costWarning = document.getElementById('cost_warning');
        const sourceSelect = document.getElementById('procurement_source');

        function validateQuantity() {
            const val = parseFloat(qtyInput.value);
            if (isNaN(val) || val <= 0) {
                qtyWarning.classList.remove('hidden');
                qtyInput.classList.add('border-red-500', 'bg-red-50');
                return false;
            } else {
                qtyWarning.classList.add('hidden');
                qtyInput.classList.remove('border-red-500', 'bg-red-50');
                return true;
            }
        }

        function validateCost() {
            if (sourceSelect.value === 'Subsidi') {
                costWarning.classList.add('hidden');
                costInput.classList.remove('border-red-500', 'bg-red-50');
                return true;
            }
            const val = parseFloat(costInput.value);
            if (isNaN(val) || val <= 0) {
                costWarning.classList.remove('hidden');
                costInput.classList.add('border-red-500', 'bg-red-50');
                return false;
            } else {
                costWarning.classList.add('hidden');
                costInput.classList.remove('border-red-500', 'bg-red-50');
                return true;
            }
        }

        if (qtyInput) {
            qtyInput.addEventListener('input', validateQuantity);
        }

        if (costInput) {
            costInput.addEventListener('input', validateCost);
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                const isQtyValid = validateQuantity();
                const isCostValid = validateCost();

                if (!isQtyValid || !isCostValid) {
                    e.preventDefault();
                    if (!isQtyValid) {
                        qtyInput.focus();
                    } else if (!isCostValid) {
                        costInput.focus();
                    }
                }
            });
        }
    });
</script>
@endsection
