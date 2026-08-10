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
        Schema::table('products', function (Blueprint $table) {
            $table->string('online_name')->nullable()->after('name');
            $table->double('online_price')->nullable()->after('price');
            $table->integer('online_stock')->default(0)->after('stock_available');
            $table->string('image_path')->nullable()->after('online_stock');
            $table->boolean('is_for_sale_online')->default(false)->after('image_path');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('sale_channel')->default('Online')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'online_name',
                'online_price',
                'online_stock',
                'image_path',
                'is_for_sale_online'
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('sale_channel');
        });
    }
};
