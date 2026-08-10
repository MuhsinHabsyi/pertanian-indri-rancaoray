@extends('layouts.app')

@section('title', 'Pemantauan Informasi Cuaca')

@section('content')
@php
    function getWeatherInfo($code) {
        $mapped = [
            0 => ['label' => 'Cerah', 'color' => 'text-amber-500', 'svg' => '<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 2.293a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zm2.293 4.707a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zM14 13.707a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM6 13.707a1 1 0 011.414-1.414l.707.707a1 1 0 01-1.414 1.414l-.707-.707zm-4.707-4.707a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zm2.293-4.707a1 1 0 010 1.414l-.707.707A1 1 0 011.293 4.293l.707-.707a1 1 0 011.414 0zM10 6a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd"></path>'],
            1 => ['label' => 'Cerah Berawan', 'color' => 'text-amber-400', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>'],
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
@endphp

<div class="max-w-xl space-y-6">
    
    @if(isset($error))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm space-y-3 shadow-sm">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-semibold">{{ $error }}</span>
            </div>
            <button onclick="window.location.reload();" 
                class="text-xs font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 px-3.5 py-1.5 rounded transition shadow-sm">
                Muat Ulang Halaman
            </button>
        </div>
    @endif

    @if(isset($weather['current_weather']))
        @php
            $wCode = $weather['current_weather']['weathercode'] ?? 0;
            $wInfo = getWeatherInfo($wCode);
        @endphp
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-5">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi Lahan Saat Ini (Rancaoray)</span>
                <div class="flex items-center space-x-3.5 mt-2">
                    <svg class="w-12 h-12 {{ $wInfo['color'] }}" fill="currentColor" viewBox="0 0 20 20">
                        {!! $wInfo['svg'] !!}
                    </svg>
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900">{{ $weather['current_weather']['temperature'] }} &deg;C</h2>
                        <p class="text-xs text-gray-500 font-semibold mt-0.5">{{ $wInfo['label'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100 text-sm text-gray-600">
                <div>
                    <span class="text-xs font-medium text-gray-400 block">Kecepatan Angin:</span>
                    <span class="font-semibold text-gray-800">{{ $weather['current_weather']['windspeed'] }} km/h</span>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-400 block">Arah Angin:</span>
                    <span class="font-semibold text-gray-800">{{ $weather['current_weather']['winddirection'] }}&deg;</span>
                </div>
            </div>

            <div class="text-[11px] text-gray-400 pt-2 flex justify-between">
                <span>Pembaruan Terakhir: {{ str_replace('T', ' ', $weather['current_weather']['time']) }} WIB</span>
                <span>Open-Meteo API</span>
            </div>
        </div>
    @endif

</div>
@endsection