@extends('layouts.app')

@section('title', 'Laporan Operasional')

@section('content')
<div class="space-y-8">

    <!-- Form Pilih Periode Laporan -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm max-w-xl">
        <div class="mb-5">
            <h3 class="text-sm font-semibold text-gray-900 font-medium">Penyusunan Laporan Operasional</h3>
            <p class="text-xs text-gray-500 mt-1">Tentukan rentang tanggal untuk merekapitulasi aktivitas pertanian</p>
        </div>

        @if(session('info'))
            <div class="mb-4 p-3 bg-amber-50 border border-amber-100 text-amber-800 rounded text-xs font-medium flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <form action="/reports/generate" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Mulai Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ $start ?? '' }}" required
                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $end ?? '' }}" required
                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg py-2.5 transition shadow-sm">
                Tampilkan Laporan
            </button>
        </form>
    </div>

    <!-- Hasil Rekapitulasi Summary -->
    @if(isset($summary))
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden max-w-4xl">
            <!-- Header Summary -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Hasil Rekapitulasi</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Periode: {{ date('d M Y', strtotime($start)) }} s/d {{ date('d M Y', strtotime($end)) }}</p>
                </div>
                
                <form action="/reports/download" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $start }}">
                    <input type="hidden" name="end_date" value="{{ $end }}">
                    <button type="submit" 
                        class="text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 px-4 py-2 rounded-lg transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Cetak & Unduh PDF</span>
                    </button>
                </form>
            </div>

            <!-- Summary Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <!-- Total Pengeluaran -->
                <div class="p-6">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Pengeluaran</span>
                    <span class="text-xl font-bold text-red-600 mt-1 block">
                        Rp{{ number_format($summary['total_expense'], 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 mt-2 block">Pembiayaan disetujui</span>
                </div>

                <!-- Total Pemasukan -->
                <div class="p-6">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Pemasukan</span>
                    <span class="text-xl font-bold text-emerald-600 mt-1 block">
                        Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 mt-2 block">Penjualan selesai</span>
                </div>

                <!-- Total Panen -->
                <div class="p-6">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Panen Padi</span>
                    <span class="text-xl font-bold text-blue-600 mt-1 block">
                        {{ number_format($summary['total_harvest'], 0, ',', '.') }} Kg
                    </span>
                    <span class="text-xs text-gray-400 mt-2 block">Akumulasi hasil panen riil</span>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection