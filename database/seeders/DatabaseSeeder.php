<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. Buat Akun Dummy (Hanya menggunakan username dan password) ---
        User::create([
            'full_name' => 'Ibu Indri Apriliani',
            'username' => 'pemilik',
            'password' => bcrypt('password123'),
            'role' => 'Owner',
        ]);

        User::create([
            'full_name' => 'Staf Operasional',
            'username' => 'operasional',
            'password' => bcrypt('password123'),
            'role' => 'Operational',
        ]);

        User::create([
            'full_name' => 'Pelanggan',
            'username' => 'pelanggan',
            'password' => bcrypt('password123'),
            'role' => 'Customer',
        ]);

        // --- 2. Buat Stok Barang Dummy ---
        Product::create([
            'category' => 'Seed', 'name' => 'Bibit IR64', 'unit' => 'Kg', 'price' => 10000, 'stock_available' => 50
        ]);

        Product::create([
            'category' => 'Fertilizer', 'name' => 'Pupuk Urea', 'unit' => 'Kg', 'price' => 5000, 'stock_available' => 100
        ]);

        Product::create([
            'category' => 'Rice', 'name' => 'Beras Pandan Wangi', 'unit' => 'Kg', 'price' => 15000, 'stock_available' => 200
        ]);
    }
}