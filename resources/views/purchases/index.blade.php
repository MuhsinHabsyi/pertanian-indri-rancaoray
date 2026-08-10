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

        <form action="/purchases" method="POST" class="space-y-4">
            @csrf
            
            <!-- Tanggal Pengajuan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Tanggal Pengajuan</label>
                <input type="date" name="submission_date" value="{{ date('Y-m-d') }}" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Sumber Pengadaan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Sumber Pengadaan</label>
                <select name="procurement_source" id="procurement_source" required onchange="toggleCostInput()"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <option value="Mandiri">Pembelian Mandiri (Modal Sendiri)</option>
                    <option value="Subsidi">Bantuan / Subsidi Pemerintah (Gratis)</option>
                </select>
            </div>

            <!-- Pilih Sarana Produksi -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Sarana Produksi</label>
                <select name="product_id" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah Kebutuhan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Kebutuhan</label>
                <input type="number" name="quantity" required placeholder="Contoh: 10"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Estimasi Biaya Total -->
            <div id="cost_container">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Estimasi Biaya Total (Rp)</label>
                <input type="number" name="total_cost" id="total_cost" required placeholder="Contoh: 150000"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
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
                                <span class="font-semibold text-gray-700">Rp{{ number_format($p->total_cost, 0, ',', '.') }}</span>
                            </div>
                            @if($p->receipt_proof)
                                <div class="col-span-2 flex flex-col space-y-1.5 mt-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[11px] font-medium text-gray-500">Bukti Nota:</span>
                                        <button type="button" onclick="openReceiptModal('{{ asset('storage/' . $p->receipt_proof) }}')"
                                            class="inline-flex items-center space-x-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Lihat Gambar Nota</span>
                                        </button>
                                    </div>
                                    <div class="mt-1">
                                        <img src="{{ asset('storage/' . $p->receipt_proof) }}" 
                                            alt="Nota Pengadaan" 
                                            onclick="openReceiptModal('{{ asset('storage/' . $p->receipt_proof) }}')"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition shadow-sm">
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
                                <form action="/purchases/{{ $p->id }}/realize" method="POST" class="flex flex-col items-end">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                        class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded px-3.5 py-1.5 shadow-sm transition flex items-center space-x-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Konfirmasi Barang Masuk</span>
                                    </button>
                                </form>
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

        if (source === 'Subsidi') {
            costContainer.style.display = 'none';
            costInput.removeAttribute('required');
            costInput.value = '';
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

    // Initialize correct state
    document.addEventListener('DOMContentLoaded', toggleCostInput);
</script>
@endsection