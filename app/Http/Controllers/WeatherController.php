<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        // Koordinat area Rancaoray, Bandung
        $latitude = -7.0345;
        $longitude = 107.6186;

        try {
            // SKENARIO NORMAL LANGKAH 2: Sistem mengirimkan permintaan data
            // Batas waktu (timeout) diset 5 detik. Jika lebih, otomatis dianggap gagal.
            $response = Http::timeout(5)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'timezone' => 'Asia/Jakarta'
            ]);

            if ($response->successful()) {
                $weather = $response->json();
                
                // SKENARIO NORMAL LANGKAH 4: Sistem menampilkan data
                return view('weather.index', compact('weather'));
            } else {
                // Memicu catch block jika API Open-Meteo sedang down
                throw new \Exception('API Error Response');
            }

        } catch (\Exception $e) {
            // SKENARIO ALTERNATIF LANGKAH 2 & 4: Koneksi gagal, tampilkan pesan spesifik
            return view('weather.index')->with('error', 'Data cuaca sementara tidak tersedia dan disarankan untuk mencoba kembali.');
        }
    }
}