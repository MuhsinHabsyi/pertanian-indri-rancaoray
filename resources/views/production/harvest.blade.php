@extends('layouts.app')

@section('title', 'Pencatatan Hasil Panen')

@section('content')
<div class="space-y-6">

    <!-- Top Stock Information Card: Stok Beras Hasil Panen -->
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
        <div class="flex items-center space-x-3">
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Beras Hasil Panen (Gudang)</h4>
                <span class="text-lg font-bold text-gray-900">
                    {{ number_format($riceProducts->sum('stock_available')) }} <span class="text-xs font-normal text-gray-500">Kg</span>
                </span>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-2.5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs text-gray-600">
            @forelse($riceProducts as $rp)
                <div class="flex justify-between items-center bg-gray-50/70 p-2.5 rounded-lg border border-gray-100">
                    <span class="truncate font-medium text-gray-700">{{ $rp->name }}</span>
                    <span class="font-bold text-amber-700 ml-2 shrink-0">{{ number_format($rp->stock_available) }} {{ $rp->unit }}</span>
                </div>
            @empty
                <span class="text-gray-400 italic">Belum ada stok beras hasil panen</span>
            @endforelse
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">Daftar Batch Tanam Aktif</h3>
            <p class="text-xs text-gray-500 mt-1">Batch penanaman yang sedang berjalan dan menunggu masa panen</p>
        </div>
    </div>

    @if($activeSchedules->isEmpty())
        <div class="bg-white border border-gray-200 rounded-lg p-12 text-center shadow-sm">
            <h4 class="text-sm font-semibold text-gray-900 mt-3">Tidak Ada Batch Tanam Aktif</h4>
            <p class="text-xs text-gray-500 mt-1">Semua batch telah selesai dipanen atau belum ada penanaman bibit baru.</p>
            <a href="/production/usage" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg mt-4 transition shadow-sm">
                Mulai Tanam Bibit Baru
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach($activeSchedules as $sch)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
                    
                    <!-- Left: Batch Info -->
                    <div class="p-6 space-y-4 bg-gray-50/50">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                Batch #{{ $sch->id }}
                            </span>
                            <span class="text-xs font-semibold text-blue-800 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                {{ $sch->crop_season }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="text-xs text-gray-500">
                                <span class="font-medium text-gray-400 block">Tanggal Mulai Tanam:</span>
                                <span class="font-semibold text-gray-800">{{ date('d M Y', strtotime($sch->planting_start_date)) }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                <span class="font-medium text-gray-400 block">Tanggal Pemupukan Akhir:</span>
                                <span class="font-semibold text-gray-800">{{ $sch->fertilizing_date ? date('d M Y', strtotime($sch->fertilizing_date)) : 'Belum dipupuk' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Middle: System Estimates -->
                    <div class="p-6 space-y-3 flex flex-col justify-center">
                        <div class="text-xs">
                            <span class="font-semibold text-gray-400 uppercase tracking-wider block text-[10px]">Estimasi Tanggal Panen</span>
                            <span class="text-sm font-bold text-amber-700 mt-1 block">
                                {{ date('d M Y', strtotime($sch->estimated_harvest_date)) }}
                            </span>
                        </div>
                        <div class="text-xs">
                            <span class="font-semibold text-gray-400 uppercase tracking-wider block text-[10px]">Estimasi Hasil Panen</span>
                            <span class="text-sm font-bold text-emerald-700 mt-1 block">
                                {{ number_format($sch->estimated_harvest, 0, ',', '.') }} Kg
                            </span>
                        </div>
                    </div>

                    <!-- Right: Realization Form -->
                    <div class="p-6">
                        <form action="/production/harvest/{{ $sch->id }}" method="POST" class="space-y-3.5">
                            @csrf
                            @method('PATCH')
                            
                            <!-- Pilih Hasil Produk Padi -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Simpan ke Produk Padi</label>
                                <select name="product_id" required
                                    class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2.5 py-1.5 text-gray-800 focus:outline-none focus:border-emerald-500 transition">
                                    @foreach($riceProducts as $rice)
                                        <option value="{{ $rice->id }}">{{ $rice->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tanggal Aktual Panen & Berat Hasil -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Tanggal Panen</label>
                                    <input type="date" name="actual_harvest_date" value="{{ date('Y-m-d') }}" required
                                        class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2.5 py-1.5 text-gray-800 focus:outline-none focus:border-emerald-500 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Berat Riil (Kg)</label>
                                    <input type="number" name="total_harvest" placeholder="Misal: 550" min="1" required
                                        class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2.5 py-1.5 text-gray-800 focus:outline-none focus:border-emerald-500 transition">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                class="w-full text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 active:bg-amber-800 rounded py-2 transition shadow-sm">
                                Kunci & Selesaikan Panen
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection