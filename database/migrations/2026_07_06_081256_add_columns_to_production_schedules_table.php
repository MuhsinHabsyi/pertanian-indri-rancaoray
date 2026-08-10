<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('production_schedules', function (Blueprint $table) {
            // Menambahkan kolom crop_season setelah planting_start_date agar rapi
            $table->string('crop_season')->nullable()->after('planting_start_date');
            
            // Menambahkan kolom estimated_harvest setelah estimated_harvest_date
            $table->integer('estimated_harvest')->default(0)->after('estimated_harvest_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_schedules', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu Anda melakukan rollback
            $table->dropColumn(['crop_season', 'estimated_harvest']);
        });
    }
};