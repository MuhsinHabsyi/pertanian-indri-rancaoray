<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Waktu pembayaran hangus (1 jam setelah order dibuat)
            $table->timestamp('payment_expires_at')->nullable()->after('order_status');
            // Catatan nomor nota
            $table->string('nota_number')->nullable()->after('payment_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_expires_at', 'nota_number']);
        });
    }
};
