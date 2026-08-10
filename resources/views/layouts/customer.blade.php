<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Beras') — Tani Rancaoray</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
    <style>body { font-family: 'Inter', sans-serif; }</style>
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col justify-between">

    <!-- ==================== TOP E-COMMERCE NAVBAR ==================== -->
    <nav class="border-b border-gray-200 bg-white sticky top-0 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <!-- Left: Logo -->
            <a href="/" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                    KTR
                </div>
                <span class="text-base font-bold text-emerald-800 tracking-tight">Tani Rancaoray</span>
            </a>

            <!-- Right: Account Status & Logout -->
            <div class="flex items-center space-x-3.5">
                @auth
                <a href="/orders" class="text-sm font-semibold {{ Request::is('orders*') ? 'text-emerald-700' : 'text-gray-600 hover:text-gray-900' }} transition">
                    Katalog
                </a>
                <a href="/cart" class="text-sm font-semibold {{ Request::is('cart*') ? 'text-emerald-700' : 'text-gray-600 hover:text-gray-900' }} transition flex items-center space-x-1.5">
                    <span>Keranjang</span>
                    @if(session()->has('cart') && count(session('cart')) > 0)
                        <span class="bg-emerald-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shrink-0">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
                <a href="/profile" class="text-sm font-semibold {{ Request::is('profile*') ? 'text-emerald-700' : 'text-gray-600 hover:text-gray-900' }} transition">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-md border border-emerald-100 hidden sm:inline-block">
                        Profil: {{ auth()->user()->username }}
                    </span>
                </a>
                    <a href="/logout" 
                        class="text-xs font-semibold text-red-600 hover:text-red-700 border border-red-100 hover:bg-red-50/50 px-3 py-1.5 rounded-lg transition">
                        Keluar
                    </a>
                @else
                    <a href="/login" 
                        class="text-xs font-semibold text-emerald-700 border border-emerald-200 hover:bg-emerald-50 px-3.5 py-1.5 rounded-lg transition">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <main class="max-w-6xl mx-auto px-6 py-10 w-full flex-1">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-center space-x-2 mb-6 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center space-x-2 mb-6 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- ==================== E-COMMERCE FOOTER ==================== -->
    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400">
            <span>&copy; {{ date('Y') }} Tani Rancaoray. Hak Cipta Dilindungi.</span>
            <span class="mt-2 sm:mt-0">Toko Beras Rancaoray — Bandung Selatan</span>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
