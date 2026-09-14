<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite tidak support ALTER COLUMN untuk enum — kita ubah via raw query
        // Untuk MySQL:
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('Pending','Paid','Processing','Completed','Cancelled') DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('Pending','Paid','Processing','Completed') DEFAULT 'Pending'");
    }
};
