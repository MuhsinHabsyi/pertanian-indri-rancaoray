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

        <form action="/production/usage/seed" method="POST" class="space-y-4">
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
                <input type="date" name="usage_date" value="{{ date('Y-m-d') }}" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Jumlah Bibit Ditanam -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Bibit Ditanam (Kg)</label>
                <input type="number" name="quantity_used" required placeholder="Contoh: 50" min="1"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
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

        <form action="/production/usage/fertilizer" method="POST" class="space-y-4">
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
                <input type="date" name="usage_date" value="{{ date('Y-m-d') }}" required
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Jumlah Pupuk Digunakan -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Pupuk Digunakan (Kg)</label>
                <input type="number" name="quantity_used" required placeholder="Contoh: 10" min="1"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
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