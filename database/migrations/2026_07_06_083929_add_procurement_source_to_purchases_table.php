<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Menambahkan kolom sumber pengadaan dengan default 'Mandiri'
            $table->enum('procurement_source', ['Mandiri', 'Subsidi'])->default('Mandiri')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('procurement_source');
        });
    }
};