<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tani Rancaoray — Beras Berkualitas dari Bandung</title>
    <meta name="description" content="Beli beras berkualitas tinggi langsung dari petani lokal Rancaoray, Bandung. 100% organik, harga petani, pengiriman cepat.">

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

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    <!-- ==================== NAVBAR ==================== -->
    <nav class="border-b border-gray-100 bg-white/80 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <!-- Left: Logo -->
            <a href="/" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                    KTR
                </div>
                <span class="text-base font-bold text-emerald-800 tracking-tight">Tani Rancaoray</span>
            </a>

            <!-- Right: Buttons -->
            <div class="flex items-center space-x-3">
                @auth
                    <a href="/profile" class="text-sm font-semibold {{ Request::is('profile*') ? 'text-emerald-700' : 'text-gray-600 hover:text-gray-900' }} transition">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-md border border-emerald-100 hidden sm:inline-block">
                        Profil: {{ auth()->user()->username }}
                    </span>
                </a>
                    <a href="/logout" 
                        class="text-sm font-semibold text-red-600 hover:text-red-700 px-3 py-2 transition">
                        Keluar
                    </a>
                @else
                    <a href="/login" 
                        class="text-sm font-semibold text-emerald-700 border border-emerald-200 hover:bg-emerald-50 px-4 py-2 rounded-lg transition">
                        Masuk / Daftar
                    </a>
                    <a href="/internal/login" 
                        class="text-sm font-medium text-gray-500 hover:text-gray-700 px-3 py-2 transition hidden sm:inline-flex">
                        Portal Internal
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="max-w-6xl mx-auto px-6 pt-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left: Copy -->
            <div class="space-y-7">
                <div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
                        Beras Berkualitas<br>
                        <span class="text-emerald-700">Langsung dari Petani</span><br>
                        Rancaoray
                    </h1>
                </div>
                <p class="text-base text-gray-500 leading-relaxed max-w-lg">
                    Nikmati beras segar berkualitas tinggi, ditanam secara organik oleh petani lokal Rancaoray, Bandung.
                    Dari sawah langsung ke dapur Anda — tanpa perantara, tanpa markup.
                </p>
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    @auth
                        <a href="/orders" 
                            class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                            Beli Sekarang
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    @else
                        <a href="/register" 
                            class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                            Beli Sekarang
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right: Image -->
            <div class="hidden lg:flex items-center justify-center">
                <img src="{{ asset('images/fotopadi.jpeg') }}" alt="Foto Sawah Rancaoray" 
                    class="w-full max-w-md aspect-[4/3] object-cover rounded-2xl shadow-lg border border-gray-100 hover:scale-[1.02] transition duration-300">
            </div>
        </div>
    </section>

    <!-- ==================== FEATURES / ABOUT SECTION ==================== -->
    <section id="keunggulan" class="bg-gray-50 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-widest">Kenapa Kami?</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">Keunggulan Beras Rancaoray</h2>
                <p class="text-sm text-gray-500 mt-3 max-w-lg mx-auto">
                    Kami berkomitmen menyediakan beras terbaik langsung dari sawah ke meja makan Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: 100% Organik -->
                <div class="bg-white border border-gray-200 rounded-xl p-7 space-y-4 hover:border-emerald-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">100% Organik</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Ditanam tanpa pestisida kimia berbahaya. Kami menggunakan pupuk alami dan metode pertanian berkelanjutan untuk menghasilkan beras yang sehat dan aman.
                    </p>
                </div>

                <!-- Card 2: Harga Petani -->
                <div class="bg-white border border-gray-200 rounded-xl p-7 space-y-4 hover:border-emerald-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Harga Petani</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Langsung dari sawah, tanpa perantara. Anda mendapatkan harga terbaik yang juga membantu kesejahteraan petani lokal di kawasan Rancaoray.
                    </p>
                </div>

                <!-- Card 3: Pengiriman Cepat -->
                <div class="bg-white border border-gray-200 rounded-xl p-7 space-y-4 hover:border-emerald-200 transition">
                    <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Pengiriman Cepat</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Pesanan diproses cepat dan dikirim langsung ke alamat Anda dengan ongkir flat terjangkau. Beras segar sampai dalam kondisi terbaik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="border-t border-gray-100 bg-white">
        <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400">
            <span>&copy; {{ date('Y') }} Tani Rancaoray. Hak Cipta Dilindungi.</span>
            <span class="mt-2 sm:mt-0">Bandung, Jawa Barat — Indonesia</span>
        </div>
    </footer>

</body>
</html>
