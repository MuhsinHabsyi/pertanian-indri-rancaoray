<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login pelanggan.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses login pelanggan (Customer).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = strstr($request->email, '@', true) ?: $request->email;

        // Percobaan login dengan username atau email murni
        if (Auth::guard('web')->attempt(['username' => $username, 'password' => $request->password]) ||
            Auth::guard('web')->attempt(['username' => $request->email, 'password' => $request->password])) {
            
            $request->session()->regenerate();
            
            if (Auth::guard('web')->user()->role !== 'Customer') {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Hanya untuk Pelanggan. Silakan gunakan Portal Internal untuk staf/pemilik.',
                ]);
            }
            
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email/Username atau password salah.',
        ]);
    }

    /**
     * Menampilkan form registrasi pelanggan.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi akun pelanggan baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\'\-]+$/'],
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $username = strstr($request->email, '@', true) ?: $request->email;

        if (User::where('username', $username)->exists() || User::where('username', $request->email)->exists()) {
            return back()->withErrors([
                'email' => 'Email/Username sudah digunakan.',
            ]);
        }

        $user = User::create([
            'full_name' => $request->name,
            'username' => $username,
            'password' => bcrypt($request->password),
            'role' => 'Customer',
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * Menampilkan form login staf internal (Owner / Operational).
     */
    public function showInternalLoginForm()
    {
        return view('auth.internal-login');
    }

    /**
     * Memproses login staf internal (Owner / Operational).
     */
    public function internalLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('internal')->attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::guard('internal')->user()->role;
            if ($role === 'Owner' || $role === 'Operational') {
                return redirect()->intended('/dashboard');
            }

            Auth::guard('internal')->logout();
            return back()->withErrors([
                'username' => 'Akses ditolak: Hanya untuk Owner dan staf Operasional.',
            ]);
        }

        return back()->withErrors([
            'username' => 'Username atau password internal salah.',
        ]);
    }

    /**
     * Logout untuk staf internal.
     */
    public function internalLogout(Request $request)
    {
        Auth::guard('internal')->logout();
        return redirect('/internal/login');
    }

    /**
     * Logout untuk pelanggan.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Menampilkan halaman profil akun.
     */
    public function profile()
    {
        return view('profile');
    }
}
