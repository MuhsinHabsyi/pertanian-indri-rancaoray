<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Portal — Rancaoray Agriculture Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
<body class="bg-[#f8fafc] min-h-screen antialiased flex items-center justify-center p-4">

    <div class="w-full max-w-[420px]">

        <!-- Login Card -->
        <div class="bg-white border border-gray-200/80 rounded-2xl shadow-sm p-8 sm:p-10 space-y-6">

            <!-- Logo & Header -->
            <div class="text-center">
                <!-- Sprout Icon -->
                <div class="flex justify-center mb-4">
                    <svg class="w-12 h-12 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22V12" />
                        <path d="M12 12C12 7.5 7.5 4.5 2.5 6.5c0 6 5.5 8.5 9.5 5.5" />
                        <path d="M12 12c0-4.5 4.5-7.5 9.5-5.5 0 6-5.5 8.5-9.5 5.5" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back</h1>
                <p class="text-sm text-gray-500 mt-1.5">
                    Enter your credentials to access your account
                </p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="/internal/login" class="space-y-5">
                @csrf

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-800 mb-2">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="name@example.com" required autocomplete="username"
                        class="w-full text-sm bg-white border border-gray-200 rounded-xl px-3.5 py-3 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-800">Password</label>
                        <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password"
                            class="w-full text-sm bg-white border border-gray-200 rounded-xl px-3.5 py-3 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition pr-10">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center space-x-2.5 pt-1">
                    <input type="checkbox" id="remember" name="remember" 
                        class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 transition cursor-pointer">
                    <label for="remember" class="text-sm font-medium text-gray-700 cursor-pointer select-none">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-xl py-3 transition shadow-sm">
                    Sign in
                </button>
            </form>

        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.04 10.04 0 013.122-.363c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-1.378-1.378a3 3 0 11-4.243-4.243"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18"></path>`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
            }
        }
    </script>
</body>
</html>
