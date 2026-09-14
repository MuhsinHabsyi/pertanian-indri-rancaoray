<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom informasi penerima dan pengiriman ke tabel orders.
     *
     * Catatan desain:
     * - Kolom koordinat (recipient_lat, recipient_lng) nullable di level DB untuk
     *   menjaga kompatibilitas dengan data order lama (Offline/Internal) yang
     *   tidak melewati proses checkout peta. Validasi "required" diterapkan di
     *   layer Controller hanya untuk alur Online checkout.
     * - delivery_courier menggunakan string (bukan enum) agar fleksibel jika
     *   perlu menambah kurir baru tanpa membuat migration baru. Nilai valid saat
     *   ini: 'Rancaoray', 'JNT'. Keterbatasan ini dicatat di Bab V.
     * - Ongkir JNT saat ini menggunakan flat-rate sebagai simulasi (belum
     *   terintegrasi API JNT real-time). Dicatat sebagai batasan sistem.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nama penerima paket (validasi regex di Controller)
            $table->string('recipient_name')->nullable()->after('shipping_address');

            // Nomor telepon penerima — format: 08xx... atau +62xx...
            $table->string('recipient_phone', 20)->nullable()->after('recipient_name');

            // Koordinat peta dari Leaflet.js (hasil klik/drag marker pelanggan)
            // Presisi 7 desimal ≈ akurasi ~1 cm, lebih dari cukup untuk konteks ini
            $table->decimal('recipient_lat', 10, 7)->nullable()->after('recipient_phone');
            $table->decimal('recipient_lng', 10, 7)->nullable()->after('recipient_lat');

            // Kurir yang dipilih sistem berdasarkan jarak (Haversine formula):
            // ≤ 5 km → 'Rancaoray', > 5 km → 'JNT'
            $table->string('delivery_courier')->nullable()->after('recipient_lng');

            // Jarak (km) dari kawasan pertanian Rancaoray ke lokasi pelanggan
            $table->decimal('delivery_distance_km', 8, 2)->nullable()->after('delivery_courier');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name',
                'recipient_phone',
                'recipient_lat',
                'recipient_lng',
                'delivery_courier',
                'delivery_distance_km',
            ]);
        });
    }
};
