<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom courier_phone ke tabel orders.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_phone', 25)->nullable()->after('delivery_distance_km');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('courier_phone');
        });
    }
};
