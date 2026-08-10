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
    Schema::create('material_usages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('production_schedule_id')->constrained('production_schedules')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products');
        $table->date('usage_date');
        $table->integer('quantity_used');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_usages');
    }
};
