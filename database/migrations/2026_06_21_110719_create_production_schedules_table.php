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
    Schema::create('production_schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->date('planting_start_date');
        $table->date('fertilizing_date')->nullable();
        $table->date('estimated_harvest_date')->nullable();
        $table->date('actual_harvest_date')->nullable();
        $table->integer('total_harvest')->default(0);
        $table->enum('status', ['Ongoing', 'Completed'])->default('Ongoing');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_schedules');
    }
};
