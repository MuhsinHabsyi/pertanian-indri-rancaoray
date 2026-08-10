<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Tani Rancaoray</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite/Build & CDN Fallback for safety) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex">

    <!-- Left Sidebar (Fixed) -->
    <aside class="w-64 border-r border-gray-200 bg-white fixed h-full flex flex-col justify-between z-20">
        <div>
            <!-- Brand Section -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <div class="flex items-center space-x-2.5">
                    <!-- Icon / Logo -->
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-semibold text-xs shrink-0 shadow-sm">
                        KTR
                    </div>
                    <span class="font-bold text-gray-900 tracking-tight text-xs leading-tight">Tani<br>Rancaoray</span>
                </div>
            </div>

            @php
                $role = auth('internal')->user()->role ?? request('role', 'Owner');
            @endphp

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5">
                @if($role === 'Owner')
                    <!-- Dashboard Home -->
                    <a href="/dashboard" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('dashboard') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('dashboard') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Validasi Pembiayaan -->
                    <a href="/purchases" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('purchases*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 {{ Request::is('purchases*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Validasi Pembiayaan</span>
                        </div>
                        <span class="text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200 px-1.5 py-0.5 rounded">Persetujuan</span>
                    </a>

                    <!-- Penjualan & Order -->
                    <a href="/internal/orders" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('internal/orders*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('internal/orders*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Penjualan & Order</span>
                    </a>

                    <!-- Atur Penjualan Online -->
                    <a href="/internal/online-products" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('internal/online-products*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('internal/online-products*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span>Atur Penjualan Online</span>
                    </a>

                    <!-- Kelola Data Pengguna -->
                    <a href="/internal/users" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('internal/users*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('internal/users*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Kelola Data Pengguna</span>
                    </a>

                    <!-- Laporan Operasional -->
                    <a href="/reports" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('reports*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('reports*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Laporan Operasional</span>
                    </a>

                    <!-- Pengaturan Profil -->
                    <a href="/profile" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('profile*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('profile*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Pengaturan Profil</span>
                    </a>

                @elseif($role === 'Operational')
                    <!-- Dashboard Home -->
                    <a href="/dashboard" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('dashboard') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('dashboard') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Pengajuan Pembiayaan -->
                    <a href="/purchases" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('purchases*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 {{ Request::is('purchases*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pengajuan Pembiayaan</span>
                        </div>
                        <span class="text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 px-1.5 py-0.5 rounded">Form Dana</span>
                    </a>

                    <!-- Tanam & Pupuk -->
                    <a href="/production/usage" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('production/usage*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('production/usage*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Tanam & Pupuk</span>
                    </a>

                    <!-- Hasil Panen -->
                    <a href="/production/harvest" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('production/harvest*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('production/harvest*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        <span>Hasil Panen</span>
                    </a>

                    <!-- Pengaturan Profil -->
                    <a href="/profile" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('profile*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('profile*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Pengaturan Profil</span>
                    </a>

                @elseif($role === 'Customer')
                    <!-- Katalog Beras / Shop -->
                    <a href="/" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('/') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('/') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Katalog Beras</span>
                    </a>

                    <!-- Keranjang Belanja -->
                    <a href="/cart" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('cart*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('cart*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Keranjang Belanja</span>
                    </a>

                    <!-- Riwayat Pesanan Saya -->
                    <a href="/orders" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('orders*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('orders*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Riwayat Pesanan</span>
                    </a>

                    <!-- Profil Saya & Alamat -->
                    <a href="/profile" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('profile*') ? 'text-emerald-700 bg-emerald-50/60' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 {{ Request::is('profile*') ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                @if($role === 'Owner')
                    <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-800 font-semibold text-sm shrink-0">
                        OW
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-900">Pemilik (Owner)</h4>
                        <p class="text-[10px] text-gray-500">Tani Rancaoray</p>
                    </div>
                @elseif($role === 'Operational')
                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-800 font-semibold text-sm shrink-0">
                        OP
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-900">Petugas Operasional</h4>
                        <p class="text-[10px] text-gray-500">Tani Rancaoray</p>
                    </div>
                @else
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-semibold text-sm shrink-0">
                        CS
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-900">Pelanggan</h4>
                        <p class="text-[10px] text-gray-500">Pembeli Setia</p>
                    </div>
                @endif
            </div>

            <!-- Minimalist Logout Action -->
            <a href="/internal/logout" title="Keluar" 
                class="text-gray-400 hover:text-red-600 transition p-1.5 rounded-lg hover:bg-gray-100 shrink-0">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </a>
        </div>
    </aside>

    <!-- Main Section (Margin Left to fit Sidebar) -->
    <div class="ml-64 flex-1 flex flex-col min-h-screen">

        <!-- Top Header -->
        <header class="h-16 border-b border-gray-200 bg-white px-8 flex items-center justify-between sticky top-0 z-10">
            <!-- Left: Page Title -->
            <div>
                <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
            </div>

            <!-- Right: Metadata & User Info -->
            <div class="flex items-center space-x-5">
                <!-- Current Date -->
                <span class="text-xs text-gray-500 font-medium">{{ date('l, d F Y') }}</span>
                
                <!-- Role Badge -->
                @if($role === 'Owner')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                        Owner
                    </span>
                @elseif($role === 'Operational')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        Operational
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                        Customer
                    </span>
                @endif

                <!-- Avatar Circle -->
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium text-xs border border-gray-300">
                    @if($role === 'Owner') OW @elseif($role === 'Operational') OP @else CS @endif
                </div>

                <!-- Keluar Button -->
                <a href="/internal/logout" 
                    class="text-xs font-semibold text-red-600 hover:text-red-700 border border-red-100 hover:bg-red-50/50 px-3 py-1.5 rounded-lg transition">
                    Keluar
                </a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-8 space-y-8 flex-1">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center space-x-2">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
