<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('package_size'); // 5, 10, or 20 (Kg per pak)
            $table->string('online_name')->nullable();
            $table->double('online_price')->nullable(); // Price per PACK (not per Kg)
            $table->integer('online_stock')->default(0); // Number of PACKS available
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['product_id', 'package_size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_packages');
    }
};
