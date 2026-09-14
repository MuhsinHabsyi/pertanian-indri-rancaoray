@extends('layouts.app')

@section('title', 'Tanam & Pupuk (Penggunaan Stok)')

@section('content')
<div class="space-y-6">

    <!-- Top Stock Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stok Bibit Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
            <div class="flex items-center space-x-3">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Bibit Tersedia</h4>
                    <span class="text-lg font-bold text-gray-900">
                        {{ number_format($seeds->sum('stock_available')) }} <span class="text-xs font-normal text-gray-500">Unit/Kg</span>
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-2.5 space-y-1.5 text-xs text-gray-600">
                @forelse($seeds as $s)
                    <div class="flex justify-between items-center">
                        <span class="truncate font-medium text-gray-700">{{ $s->name }}</span>
                        <span class="font-bold text-emerald-700 ml-2 shrink-0">{{ number_format($s->stock_available) }} {{ $s->unit }}</span>
                    </div>
                @empty
                    <span class="text-gray-400 italic">Belum ada stok bibit</span>
                @endforelse
            </div>
        </div>

        <!-- Stok Pupuk Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
            <div class="flex items-center space-x-3">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Pupuk Tersedia</h4>
                    <span class="text-lg font-bold text-gray-900">
                        {{ number_format($fertilizers->sum('stock_available')) }} <span class="text-xs font-normal text-gray-500">Unit/Kg</span>
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-2.5 space-y-1.5 text-xs text-gray-600">
                @forelse($fertilizers as $f)
                    <div class="flex justify-between items-center">
                        <span class="truncate font-medium text-gray-700">{{ $f->name }}</span>
                        <span class="font-bold text-blue-700 ml-2 shrink-0">{{ number_format($f->stock_available) }} {{ $f->unit }}</span>
                    </div>
                @empty
                    <span class="text-gray-400 italic">Belum ada stok pupuk</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Validation Error Alert -->
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

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

    <!-- Card 1: Mulai Penanaman / Tanam Bibit Baru -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-6">
        <div class="flex items-center space-x-2">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Mulai Penanaman Bibit Baru</h3>
                <p class="text-xs text-gray-500">Membuka batch produksi baru</p>
            </div>
        </div>

        <form action="/production/usage/seed" method="POST" id="seed_form" class="space-y-4">
            @csrf
            
            <!-- Pilih Jenis Bibit -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Jenis Bibit</label>
                <select name="product_id" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    @foreach($seeds as $seed)
                        <option value="{{ $seed->id }}">
                            {{ $seed->name }} (Tersedia: {{ $seed->stock_available }} {{ $seed->unit }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Tanam Aktual -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Tanggal Tanam</label>
                <input type="date" name="usage_date" value="{{ old('usage_date', date('Y-m-d')) }}" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Jumlah Bibit Ditanam -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Bibit Ditanam (Kg)</label>
                <input type="number" name="quantity_used" id="seed_quantity" required placeholder="Contoh: 50" min="1" step="any" value="{{ old('quantity_used') }}"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <p id="seed_qty_warning" class="text-[11px] text-red-600 mt-1 hidden">⚠️ Jumlah bibit yang ditanam harus lebih dari 0 (tidak boleh 0 atau minus).</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg py-2.5 transition shadow-sm mt-2">
                Konfirmasi & Mulai Tanam
            </button>
        </form>
    </div>

    <!-- Card 2: Pencatatan Pemakaian Pupuk Berkala -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-6">
        <div class="flex items-center space-x-2">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Pencatatan Pemakaian Pupuk</h3>
                <p class="text-xs text-gray-500">Log pemakaian pupuk untuk batch berjalan</p>
            </div>
        </div>

        <form action="/production/usage/fertilizer" method="POST" id="fertilizer_form" class="space-y-4">
            @csrf
            
            <!-- Pilih Alokasi Batch Tanam -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Alokasi Batch Tanam Aktif</label>
                <select name="production_schedule_id" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    <option value="">-- Pilih Batch Tanam Aktif --</option>
                    @foreach($activeSchedules as $sch)
                        <option value="{{ $sch->id }}">
                            Batch #{{ $sch->id }} | Mulai: {{ date('d M Y', strtotime($sch->planting_start_date)) }} | {{ $sch->crop_season }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Jenis Pupuk -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Jenis Pupuk</label>
                <select name="product_id" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    @foreach($fertilizers as $fert)
                        <option value="{{ $fert->id }}">
                            {{ $fert->name }} (Tersedia: {{ $fert->stock_available }} {{ $fert->unit }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Pemupukan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Tanggal Pemupukan</label>
                <input type="date" name="usage_date" value="{{ old('usage_date', date('Y-m-d')) }}" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Jumlah Pupuk Digunakan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Pupuk Digunakan (Kg)</label>
                <input type="number" name="quantity_used" id="fert_quantity" required placeholder="Contoh: 10" min="1" step="any" value="{{ old('quantity_used') }}"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                <p id="fert_qty_warning" class="text-[11px] text-red-600 mt-1 hidden">⚠️ Jumlah pupuk yang digunakan harus lebih dari 0 (tidak boleh 0 atau minus).</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-lg py-2.5 transition shadow-sm mt-2">
                Simpan Data Pemupukan
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation for Seed Form
        const seedForm = document.getElementById('seed_form');
        const seedQty = document.getElementById('seed_quantity');
        const seedWarning = document.getElementById('seed_qty_warning');

        function validateSeedQty() {
            const val = parseFloat(seedQty.value);
            if (isNaN(val) || val <= 0) {
                seedWarning.classList.remove('hidden');
                seedQty.classList.add('border-red-500', 'bg-red-50');
                return false;
            } else {
                seedWarning.classList.add('hidden');
                seedQty.classList.remove('border-red-500', 'bg-red-50');
                return true;
            }
        }

        if (seedQty) {
            seedQty.addEventListener('input', validateSeedQty);
        }

        if (seedForm) {
            seedForm.addEventListener('submit', function(e) {
                if (!validateSeedQty()) {
                    e.preventDefault();
                    seedQty.focus();
                }
            });
        }

        // Validation for Fertilizer Form
        const fertForm = document.getElementById('fertilizer_form');
        const fertQty = document.getElementById('fert_quantity');
        const fertWarning = document.getElementById('fert_qty_warning');

        function validateFertQty() {
            const val = parseFloat(fertQty.value);
            if (isNaN(val) || val <= 0) {
                fertWarning.classList.remove('hidden');
                fertQty.classList.add('border-red-500', 'bg-red-50');
                return false;
            } else {
                fertWarning.classList.add('hidden');
                fertQty.classList.remove('border-red-500', 'bg-red-50');
                return true;
            }
        }

        if (fertQty) {
            fertQty.addEventListener('input', validateFertQty);
        }

        if (fertForm) {
            fertForm.addEventListener('submit', function(e) {
                if (!validateFertQty()) {
                    e.preventDefault();
                    fertQty.focus();
                }
            });
        }
    });
</script>
@endsection