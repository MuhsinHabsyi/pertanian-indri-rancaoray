<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Toko Beras Rancaoray</title>
    <meta name="description" content="Masuk ke akun Anda untuk membeli beras berkualitas dari Tani Rancaoray.">

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
</head>
<body class="bg-white min-h-screen antialiased">

    <div class="flex min-h-screen">

        <!-- ========== LEFT: Branding Panel (hidden on mobile) ========== -->
        <div class="hidden lg:flex lg:w-1/2 bg-green-50 relative flex-col items-center justify-center p-16">
            <!-- Image -->
            <img src="{{ asset('images/fotopadi.jpeg') }}" alt="Foto Sawah Rancaoray" 
                class="w-full max-w-sm aspect-[4/3] object-cover rounded-2xl shadow-md border border-green-100 hover:scale-[1.02] transition duration-300">

            <!-- Quote Overlay -->
            <div class="mt-10 text-center max-w-xs">
                <p class="text-sm text-green-800 font-medium italic leading-relaxed">
                    "Menyediakan pangan terbaik untuk keluarga Anda."
                </p>
                <span class="text-xs text-green-600/70 mt-2 block font-medium">— Tani Rancaoray</span>
            </div>

            <!-- Bottom Attribution -->
            <div class="absolute bottom-8 left-0 right-0 text-center">
                <span class="text-[11px] text-green-500/60 font-medium">&copy; {{ date('Y') }} Toko Beras Rancaoray</span>
            </div>
        </div>

        <!-- ========== RIGHT: Login Form ========== -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-sm space-y-8">

                <!-- Header -->
                <div>
                    <a href="/" class="flex items-center space-x-2 mb-8">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xs">
                            KTR
                        </div>
                        <span class="text-sm font-bold text-gray-900">Tani Rancaoray</span>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Selamat Datang</h1>
                    <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda untuk mulai berbelanja beras.</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-md text-xs font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="nama@gmail.com" required
                            class="w-full text-sm bg-white border border-gray-300 rounded-md px-3.5 py-2.5 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            class="w-full text-sm bg-white border border-gray-300 rounded-md px-3.5 py-2.5 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-md py-2.5 transition shadow-sm">
                        Masuk
                    </button>
                </form>

                <!-- Register Link -->
                <p class="text-center text-sm text-gray-500">
                    Belum punya akun?
                    <a href="/register" class="font-semibold text-emerald-600 hover:text-emerald-700 transition">Daftar di sini</a>
                </p>

            </div>
        </div>

    </div>

</body>
</html>
