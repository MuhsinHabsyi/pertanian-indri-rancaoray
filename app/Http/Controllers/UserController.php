<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna internal (Pemilik & Operasional).
     */
    public function index()
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses ditolak: Fitur ini khusus untuk Pemilik.');
        }

        $users = User::whereIn('role', ['Owner', 'Operational'])->latest()->get();
        $ownerCount = User::where('role', 'Owner')->count();
        $operationalCount = User::where('role', 'Operational')->count();

        return view('users.index', compact('users', 'ownerCount', 'operationalCount'));
    }

    /**
     * Menambahkan akun pengguna internal baru.
     */
    public function store(Request $request)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses ditolak: Fitur ini khusus untuk Pemilik.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\'\-]+$/',
            'username' => 'required|string|max:255|unique:users,username',
            'role' => 'required|in:Owner,Operational',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'role.required' => 'Peran pengguna wajib dipilih.',
            'role.in' => 'Peran pengguna harus Pemilik (Owner) atau Operasional (Operational).',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'full_name' => $validated['full_name'],
            'username' => strtolower(trim($validated['username'])),
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/internal/users')->with('success', 'Akun pengguna internal berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pengguna internal.
     */
    public function update(Request $request, User $user)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses ditolak: Fitur ini khusus untuk Pemilik.');
        }

        if (!in_array($user->role, ['Owner', 'Operational'])) {
            return redirect('/internal/users')->with('error', 'Hanya dapat mengelola akun pengguna internal.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:Owner,Operational',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'role.required' => 'Peran pengguna wajib dipilih.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->full_name = $validated['full_name'];
        $user->username = strtolower(trim($validated['username']));
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/internal/users')->with('success', 'Data pengguna internal berhasil diperbarui.');
    }

    /**
     * Menghapus akun pengguna internal.
     */
    public function destroy(User $user)
    {
        if (auth('internal')->user()->role !== 'Owner') {
            abort(403, 'Akses ditolak: Fitur ini khusus untuk Pemilik.');
        }

        if (auth('internal')->id() === $user->id) {
            return redirect('/internal/users')->with('error', 'Tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if (!in_array($user->role, ['Owner', 'Operational'])) {
            return redirect('/internal/users')->with('error', 'Hanya dapat menghapus akun pengguna internal.');
        }

        $user->delete();

        return redirect('/internal/users')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
