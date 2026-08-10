@extends('layouts.app')

@section('title', 'Kelola Data Pengguna Internal')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Kelola Data Pengguna Internal</h2>
            <p class="text-xs text-gray-500 mt-1">Kelola data akun Pemilik (Owner) dan Staf Operasional tani.</p>
        </div>
        <button type="button" onclick="openAddModal()"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-lg shadow-sm transition space-x-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Pengguna Baru</span>
        </button>
    </div>

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 block">Total Pengguna Internal</span>
                <span class="text-2xl font-bold text-gray-900">{{ count($users) }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 block">Akun Pemilik (Owner)</span>
                <span class="text-2xl font-bold text-gray-900">{{ $ownerCount }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 block">Akun Staf Operasional</span>
                <span class="text-2xl font-bold text-gray-900">{{ $operationalCount }}</span>
            </div>
        </div>
    </div>

    <!-- Validation Error Alert -->
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs space-y-1">
            <div class="font-bold flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Terdapat kesalahan pada input form:</span>
            </div>
            <ul class="list-disc list-inside pl-5 space-y-0.5 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- User List Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900">Daftar Pengguna Internal</h3>
            <span class="text-xs text-gray-500">Menampilkan {{ count($users) }} akun</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Nama Lengkap</th>
                        <th class="py-3.5 px-6">Username</th>
                        <th class="py-3.5 px-6">Peran (Role)</th>
                        <th class="py-3.5 px-6">Tanggal Terdaftar</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 text-gray-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-gray-900">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $u->role === 'Owner' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ strtoupper(substr($u->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="block font-semibold text-gray-900">{{ $u->full_name }}</span>
                                        @if(auth('internal')->id() === $u->id)
                                            <span class="text-[10px] text-emerald-600 font-semibold">(Akun Anda Saat Ini)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-600 font-mono">{{ $u->username }}</td>
                            <td class="py-4 px-6">
                                @if($u->role === 'Owner')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Pemilik (Owner)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Staf Operasional
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                {{ $u->created_at ? $u->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <!-- Edit Button -->
                                <button type="button" onclick="openEditModal('{{ $u->id }}', '{{ addslashes($u->full_name) }}', '{{ addslashes($u->username) }}', '{{ $u->role }}')"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>

                                <!-- Delete Button -->
                                @if(auth('internal')->id() === $u->id)
                                    <button type="button" disabled title="Tidak dapat menghapus akun Anda sendiri"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                @else
                                    <button type="button" onclick="openDeleteModal('{{ $u->id }}', '{{ addslashes($u->full_name) }}')"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-red-200 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 transition shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-gray-500">
                                Belum ada data pengguna internal terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ==================== MODAL TAMBAH PENGGUNA ==================== -->
<div id="modal-add-user" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-gray-200 transform transition-all">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-base font-bold text-gray-900">Tambah Pengguna Internal Baru</h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form method="POST" action="/internal/users" class="space-y-4 mt-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="full_name" required placeholder="Contoh: Budi Santoso"
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Username</label>
                <input type="text" name="username" required placeholder="Contoh: budi"
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Peran (Role)</label>
                <select name="role" required
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white">
                    <option value="Operational">Staf Operasional</option>
                    <option value="Owner">Pemilik (Owner)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()"
                    class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL EDIT PENGGUNA ==================== -->
<div id="modal-edit-user" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-gray-200 transform transition-all">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-base font-bold text-gray-900">Edit Data Pengguna Internal</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="form-edit-user" method="POST" action="" class="space-y-4 mt-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" id="edit-full-name" name="full_name" required
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Username</label>
                <input type="text" id="edit-username" name="username" required
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Peran (Role)</label>
                <select id="edit-role" name="role" required
                    class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white">
                    <option value="Operational">Staf Operasional</option>
                    <option value="Owner">Pemilik (Owner)</option>
                </select>
            </div>

            <div class="border-t border-gray-100 pt-3">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Ubah Password (Opsional)</p>
                <p class="text-[10px] text-gray-400 mb-3">*Biarkan kosong jika tidak ingin merubah password pengguna.</p>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL KONFIRMASI HAPUS ==================== -->
<div id="modal-delete-user" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-sm w-full p-6 shadow-xl border border-gray-200 text-center space-y-4">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-bold text-gray-900">Konfirmasi Hapus Pengguna</h3>
            <p class="text-xs text-gray-500 mt-1">Apakah Anda yakin ingin menghapus akun <span id="delete-user-name" class="font-bold text-gray-800"></span>? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form id="form-delete-user" method="POST" action="" class="flex items-center justify-center space-x-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()"
                class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 transition">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                Ya, Hapus Akun
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() {
        document.getElementById('modal-add-user').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('modal-add-user').classList.add('hidden');
    }

    function openEditModal(id, fullName, username, role) {
        document.getElementById('form-edit-user').action = '/internal/users/' + id;
        document.getElementById('edit-full-name').value = fullName;
        document.getElementById('edit-username').value = username;
        document.getElementById('edit-role').value = role;
        document.getElementById('modal-edit-user').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modal-edit-user').classList.add('hidden');
    }

    function openDeleteModal(id, fullName) {
        document.getElementById('form-delete-user').action = '/internal/users/' + id;
        document.getElementById('delete-user-name').innerText = fullName;
        document.getElementById('modal-delete-user').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('modal-delete-user').classList.add('hidden');
    }
</script>
@endsection
