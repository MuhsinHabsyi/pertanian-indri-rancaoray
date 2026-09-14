@php
    $user = auth('internal')->user() ?? auth('web')->user();
    $role = $user ? $user->role : request('role', 'Owner');
    $layout = ($role === 'Customer') ? 'layouts.customer' : 'layouts.app';
@endphp
@extends($layout)

@section('title', ($role === 'Owner' || $role === 'Operational') ? 'Profil Akun Internal' : 'Profil Pelanggan')

@section('content')
<div class="max-w-5xl">

    <!-- ==================== VARIATION A: INTERNAL STAFF PROFILE ==================== -->
    @if($role === 'Owner' || $role === 'Operational')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Avatar & Quick Info -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm flex flex-col items-center text-center space-y-4">
                <!-- Gray Avatar Circle -->
                <div class="w-24 h-24 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $user->full_name ?? 'Ahmad Operasional' }}</h3>
                    <!-- Role Badge -->
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mt-1.5 {{ $role === 'Owner' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                        {{ $role }}
                    </span>
                </div>
                <div class="w-full pt-4 border-t border-gray-100 text-xs text-gray-500 text-left space-y-2">
                    <div>
                        <span class="font-medium text-gray-400 block">ID Karyawan / NIK</span>
                        <span class="font-semibold text-gray-800">EMP-10522041</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-400 block">Bergabung Sejak</span>
                        <span class="font-semibold text-gray-800">01 Juli 2026</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Account Details Form -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Detail Informasi Akun</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola identitas operasional Anda.</p>
                    </div>
                    <button type="button" id="btn-edit-internal" onclick="toggleEditInternal()"
                        class="text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-3 py-1.5 rounded-lg transition shadow-sm">
                        Ubah
                    </button>
                </div>

                <form method="POST" action="/profile/update" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Identity Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="full_name" value="{{ $user->full_name ?? 'Ahmad Operasional' }}" readonly
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-500 focus:outline-none focus:border-gray-900 transition readonly-field">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Username</label>
                            <input type="text" name="username" value="{{ $user->username ?? 'ahmad_ktr' }}" readonly
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-500 focus:outline-none focus:border-gray-900 transition readonly-field">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">NIK / Employee ID</label>
                            <input type="text" name="employee_id" value="3273050607990002" readonly
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2 text-gray-500 focus:outline-none focus:border-gray-900 transition readonly-field">
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="border border-gray-100 rounded-lg p-5 bg-gray-50/50 space-y-4">
                        <div>
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Keamanan & Password</h4>
                            <p class="text-[11px] text-gray-500 mt-0.5">Biarkan kosong jika tidak ingin mengubah password.</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Password Saat Ini</label>
                                <input type="password" name="current_password" placeholder="••••••••" readonly
                                    class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-gray-800 focus:outline-none focus:border-gray-900 transition readonly-field">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Password Baru</label>
                                <input type="password" name="new_password" placeholder="••••••••" readonly
                                    class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-gray-800 focus:outline-none focus:border-gray-900 transition readonly-field">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" placeholder="••••••••" readonly
                                    class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2.5 py-2 text-gray-800 focus:outline-none focus:border-gray-900 transition readonly-field">
                            </div>
                        </div>
                    </div>

                    <!-- Submit (Hidden initially, shown in Edit mode) -->
                    <div id="div-submit-internal" class="hidden flex items-center justify-end space-x-3">
                        <button type="button" onclick="cancelEditInternal()"
                            class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 transition">
                            Batal
                        </button>
                        <button type="submit" 
                            class="text-xs font-semibold text-white bg-gray-900 hover:bg-gray-800 active:bg-black rounded-lg px-4 py-2 transition shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>

    <!-- ==================== VARIATION B: CUSTOMER PROFILE ==================== -->
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Avatar & Quick Info -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm flex flex-col items-center text-center space-y-4">
                <div class="w-24 h-24 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $user->full_name ?? 'Pelanggan Setia' }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 mt-1.5">
                        Customer / Pembeli
                    </span>
                </div>
                <div class="w-full pt-4 border-t border-gray-100 text-xs text-gray-500 text-left">
                    <span class="font-medium text-gray-400 block">Email Address</span>
                    <span class="font-semibold text-gray-800">{{ $user->email ?? 'pembeli@gmail.com' }}</span>
                </div>
            </div>

            <!-- Right Column: Shipping & Account Form -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-6 lg:col-span-2">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Profil Saya & Alamat Pengiriman</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola data diri dan alamat pengiriman default Anda.</p>
                </div>

                <form method="POST" action="/profile/update" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Account Form Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="full_name" value="{{ $user->full_name ?? 'Pelanggan Setia' }}" required
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2.5 text-gray-800 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email ?? 'pembeli@gmail.com' }}" required
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2.5 text-gray-800 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Nomor Telepon</label>
                            <input type="tel" name="phone" value="6281234567890" required pattern="^(62|\+62)[0-9]{8,13}$"
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2.5 text-gray-800 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition">
                        </div>
                    </div>

                    <!-- Dedicated Section: Alamat Pengiriman Utama -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat Pengiriman Utama</label>
                        <textarea name="shipping_address" rows="4" required placeholder="Tuliskan alamat pengiriman lengkap Anda..."
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3.5 py-2.5 text-gray-800 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition resize-none">{{ $user->shipping_address ?? 'Jl. Dipatiukur No. 112, Kota Bandung, Jawa Barat 40132' }}</textarea>
                        <p class="text-[11px] text-gray-500 leading-normal">*Alamat ini akan digunakan secara otomatis sebagai tujuan pengiriman default saat checkout.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-2">
                        <button type="submit" 
                            class="text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg px-6 py-2.5 transition shadow-sm">
                            Simpan Profil & Alamat
                        </button>
                    </div>
                </form>
            </div>

        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    function toggleEditInternal() {
        const fields = document.querySelectorAll('.readonly-field');
        const btnEdit = document.getElementById('btn-edit-internal');
        const divSubmit = document.getElementById('div-submit-internal');

        fields.forEach(field => {
            field.removeAttribute('readonly');
            field.classList.remove('bg-gray-50', 'text-gray-500');
            field.classList.add('bg-white', 'text-gray-800');
        });

        btnEdit.classList.add('hidden');
        divSubmit.classList.remove('hidden');
    }

    function cancelEditInternal() {
        const fields = document.querySelectorAll('.readonly-field');
        const btnEdit = document.getElementById('btn-edit-internal');
        const divSubmit = document.getElementById('div-submit-internal');

        fields.forEach(field => {
            field.setAttribute('readonly', 'true');
            field.classList.remove('bg-white', 'text-gray-800');
            field.classList.add('bg-gray-50', 'text-gray-500');
        });

        btnEdit.classList.remove('hidden');
        divSubmit.classList.add('hidden');
    }
</script>
@endsection
