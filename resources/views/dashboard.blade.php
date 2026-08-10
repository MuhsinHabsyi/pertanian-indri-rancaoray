@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    function getWeatherInfo($code) {
        $mapped = [
            0 => ['label' => 'Cerah', 'color' => 'text-amber-500', 'type' => 'fill', 'svg' => '<path fill-rule="evenodd" d="..." clip-rule="evenodd"></path>'],
            1 => ['label' => 'Cerah Berawan', 'color' => 'text-amber-400', 'type' => 'stroke', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="..."></path>'],
            2 => ['label' => 'Berawan', 'color' => 'text-gray-400', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>'],
            3 => ['label' => 'Berawan Tebal', 'color' => 'text-gray-500', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>'],
            45 => ['label' => 'Berkabut', 'color' => 'text-gray-300', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>'],
            48 => ['label' => 'Berkabut', 'color' => 'text-gray-300', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>'],
            51 => ['label' => 'Gerimis', 'color' => 'text-blue-300', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            53 => ['label' => 'Gerimis', 'color' => 'text-blue-300', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            55 => ['label' => 'Gerimis', 'color' => 'text-blue-300', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            61 => ['label' => 'Hujan', 'color' => 'text-blue-400', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            63 => ['label' => 'Hujan', 'color' => 'text-blue-500', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            65 => ['label' => 'Hujan Lebat', 'color' => 'text-blue-600', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            80 => ['label' => 'Hujan Ringan', 'color' => 'text-blue-400', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            81 => ['label' => 'Hujan', 'color' => 'text-blue-500', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            82 => ['label' => 'Hujan Lebat', 'color' => 'text-blue-600', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'],
            95 => ['label' => 'Badai Petir', 'color' => 'text-purple-600', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>'],
            96 => ['label' => 'Hujan Es', 'color' => 'text-purple-700', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>'],
            99 => ['label' => 'Hujan Es', 'color' => 'text-purple-700', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>'],
        ];
        return $mapped[$code] ?? ['label' => 'Berawan', 'color' => 'text-gray-400', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>'];
    }

    function getIndonesianDayName($dateStr) {
        $dayNames = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $engDay = date('l', strtotime($dateStr));
        return $dayNames[$engDay] ?? $engDay;
    }
@endphp

<!-- Top Section: 4 Summary Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
    <!-- Card 1 (Border left green) -->
    <div class="bg-white border-l-4 border-emerald-500 border-y border-r border-gray-200 rounded-lg p-5 flex flex-col justify-between shadow-sm">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch Tanam Aktif</span>
            <div class="mt-2 text-2xl font-bold text-gray-900">{{ $activeBatches ?? 0 }}</div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Musim Tanam Berjalan
        </div>
    </div>

    <!-- Card 2 (Border left orange) -->
    <div class="bg-white border-l-4 border-amber-500 border-y border-r border-gray-200 rounded-lg p-5 flex flex-col justify-between shadow-sm">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran Bulan Ini</span>
            <div class="mt-2 text-2xl font-bold text-gray-900">Rp {{ number_format($monthlyExpense ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Pembiayaan disetujui
        </div>
    </div>

    <!-- Card 3 (Border left green) -->
    <div class="bg-white border-l-4 border-emerald-500 border-y border-r border-gray-200 rounded-lg p-5 flex flex-col justify-between shadow-sm">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Panen</span>
            <div class="mt-2 text-2xl font-bold text-gray-900">{{ number_format($totalHarvest ?? 0, 0, ',', '.') }} Kg</div>
        </div>
        <div class="mt-3 text-xs text-gray-500">
            Akumulasi panen terakhir
        </div>
    </div>

    <!-- Card 4 (Border left blue) -->
    <div class="bg-white border-l-4 border-blue-500 border-y border-r border-gray-200 rounded-lg p-5 flex flex-col justify-between shadow-sm">
        <div>
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Cuaca Hari Ini</span>
            <div class="mt-2 text-2xl font-bold text-gray-900">
                {{ $weather['current_weather']['temperature'] ?? '28' }} °C
            </div>
        </div>
        <div class="mt-3 text-xs text-gray-500 flex justify-between items-center">
            <span>Kecepatan Angin: {{ $weather['current_weather']['windspeed'] ?? '10' }} km/h</span>
            @if(isset($weather['current_weather']['weathercode']))
                <span class="font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px]">
                    {{ getWeatherInfo($weather['current_weather']['weathercode'])['label'] }}
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Bottom Section (Split Grid: 2/3 left, 1/3 right) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Bottom Left (Recent Activities Table/List) -->
    <div class="bg-white border border-gray-200 rounded-lg lg:col-span-2 flex flex-col shadow-sm">
        <!-- Section Title -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50 rounded-t-lg">
            <h2 class="text-sm font-semibold text-gray-900">Aktivitas Terbaru</h2>
            <span class="text-xs text-gray-500 font-medium">Log Kegiatan Operasional</span>
        </div>

        <!-- Clean List -->
        <div class="divide-y divide-gray-100 flex-1 flex flex-col justify-center">
            <!-- Activity 1 -->
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/40 transition-colors">
                <div class="flex items-center space-x-3.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Penanaman Bibit - Batch #1</h4>
                        <p class="text-xs text-gray-500">Padi Varietas Ciherang berhasil disemai pada petak A-1</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                        Planting
                    </span>
                    <span class="text-[11px] text-gray-400 font-medium">10:30 WIB</span>
                </div>
            </div>

            <!-- Activity 2 -->
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/40 transition-colors">
                <div class="flex items-center space-x-3.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-blue-50"></div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Pemupukan - Urea</h4>
                        <p class="text-xs text-gray-500">Pemberian pupuk dosis pertama pada tanaman usia 15 HST</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                        Fertilizing
                    </span>
                    <span class="text-[11px] text-gray-400 font-medium">Kemarin</span>
                </div>
            </div>

            <!-- Activity 3 -->
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/40 transition-colors">
                <div class="flex items-center space-x-3.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-amber-50"></div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Pengajuan Pembiayaan Pupuk NPK</h4>
                        <p class="text-xs text-gray-500">Pengajuan anggaran pembelian 5 karung pupuk NPK bersubsidi</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                        Finance
                    </span>
                    <span class="text-[11px] text-gray-400 font-medium">3 hari lalu</span>
                </div>
            </div>

            <!-- Activity 4 -->
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/40 transition-colors">
                <div class="flex items-center space-x-3.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-purple-500 ring-4 ring-purple-50"></div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Pencatatan Hasil Panen - Batch #2</h4>
                        <p class="text-xs text-gray-500">Penguncian data panen dengan total berat bersih 1.240 Kg</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                        Harvest
                    </span>
                    <span class="text-[11px] text-gray-400 font-medium">4 hari lalu</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Right (Weather Forecast Widget) -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 flex flex-col justify-between shadow-sm">
        <div>
            <h2 class="text-sm font-semibold text-gray-900 mb-5">Prakiraan Cuaca</h2>
            
            <!-- Forecast List (iOS Weather Widget style) -->
            <div class="space-y-4">
                @if(isset($weather['daily']['time']))
                    @foreach($weather['daily']['time'] as $index => $time)
                        @if($index > 0 && $index < 6)
                            @php
                                $wCode = $weather['daily']['weather_code'][$index] ?? 0;
                                $wInfo = getWeatherInfo($wCode);
                                $tempMax = $weather['daily']['temperature_2m_max'][$index] ?? '28';
                            @endphp
                            <div class="flex items-center justify-between text-sm py-1 border-b border-gray-50 pb-3">
                                <span class="w-24 text-gray-600 font-medium">{{ getIndonesianDayName($time) }}</span>
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 {{ $wInfo['color'] }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        {!! $wInfo['svg'] !!}
                                    </svg>
                                    <span class="text-gray-500">{{ $wInfo['label'] }}</span>
                                </span>
                                <span class="text-gray-900 font-semibold">{{ round($tempMax) }}°C</span>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Fallback if API fails or offline -->
                    <div class="flex items-center justify-between text-sm py-1 border-b border-gray-50 pb-3">
                        <span class="w-24 text-gray-600 font-medium">Selasa</span>
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 2.293a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zm2.293 4.707a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zM14 13.707a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM6 13.707a1 1 0 011.414-1.414l.707.707a1 1 0 01-1.414 1.414l-.707-.707zm-4.707-4.707a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zm2.293-4.707a1 1 0 010 1.414l-.707.707A1 1 0 011.293 4.293l.707-.707a1 1 0 011.414 0zM10 6a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-500">Cerah</span>
                        </span>
                        <span class="text-gray-900 font-semibold">29°C</span>
                    </div>

                    <div class="flex items-center justify-between text-sm py-1 border-b border-gray-50 pb-3">
                        <span class="w-24 text-gray-600 font-medium">Rabu</span>
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                            </svg>
                            <span class="text-gray-500">Berawan</span>
                        </span>
                        <span class="text-gray-900 font-semibold">27°C</span>
                    </div>

                    <div class="flex items-center justify-between text-sm py-1 border-b border-gray-50 pb-3">
                        <span class="w-24 text-gray-600 font-medium">Kamis</span>
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 5a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-gray-500">Hujan Ringan</span>
                        </span>
                        <span class="text-gray-900 font-semibold">25°C</span>
                    </div>

                    <div class="flex items-center justify-between text-sm py-1">
                        <span class="w-24 text-gray-600 font-medium">Jumat</span>
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                            </svg>
                            <span class="text-gray-500">Berawan</span>
                        </span>
                        <span class="text-gray-900 font-semibold">28°C</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Open-Meteo Attribution or info -->
        <div class="mt-8 pt-4 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
            <span>Sumber: Open-Meteo API</span>
            <span>Bandung, ID</span>
        </div>
    </div>

</div>
@endsection
