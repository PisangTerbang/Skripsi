<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengaturanPage()" class="space-y-6">

        {{-- ================= ALERTS ================= --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-700 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show = false" class="text-red-700 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- ================= HEADER ================= --}}
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">Pengaturan Akun</h2>
                    <p class="text-indigo-100 text-sm">Kelola informasi profil dan keamanan akun Anda</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-16 h-16 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- ================= PROFILE SECTION ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

            {{-- Section Header --}}
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informasi Profil
                </h3>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('mahasiswa.pengaturan.profile') }}" enctype="multipart/form-data"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- Avatar Section --}}
                    <div class="flex items-start gap-6 pb-6 border-b border-gray-200">
                        {{-- Current Avatar --}}
                        <div class="relative">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-indigo-100">
                            @else
                                <div
                                    class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold border-4 border-indigo-100">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif

                            {{-- Remove Button --}}
                            @if ($user->avatar)
                                <button type="button" @click="removeAvatar()"
                                    class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        {{-- Upload Section --}}
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Foto Profil
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="cursor-pointer">
                                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg"
                                        @change="previewAvatar($event)" class="hidden">
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all shadow-sm hover:shadow-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Pilih Foto
                                    </span>
                                </label>
                                <span x-show="avatarName" x-text="avatarName" class="text-sm text-gray-600"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG. Maksimal 2MB</p>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    {{-- NIM --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            NIM (Nomor Induk Mahasiswa)
                        </label>
                        <input type="text" name="nim" value="{{ old('nim', $user->nim) }}" maxlength="20"
                            placeholder="Contoh: 2021110001"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    {{-- Role (Read Only) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Role
                        </label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>

            </form>

        </div>

        {{-- ================= PASSWORD SECTION ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

            {{-- Section Header --}}
            <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Keamanan & Password
                </h3>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('mahasiswa.pengaturan.password') }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- Current Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Lama <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showCurrentPassword ? 'text' : 'password'" name="current_password" required
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showCurrentPassword" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showCurrentPassword" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showNewPassword ? 'text' : 'password'" name="password" required
                                @input="checkPasswordStrength($event.target.value)"
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <button type="button" @click="showNewPassword = !showNewPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        {{-- Password Strength Indicator --}}
                        <div x-show="passwordStrength > 0" class="mt-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full transition-all duration-300"
                                        :class="{
                                            'bg-red-500': passwordStrength === 1,
                                            'bg-yellow-500': passwordStrength === 2,
                                            'bg-green-500': passwordStrength === 3
                                        }"
                                        :style="`width: ${passwordStrength * 33.33}%`">
                                    </div>
                                </div>
                                <span class="text-xs font-medium"
                                    :class="{
                                        'text-red-600': passwordStrength === 1,
                                        'text-yellow-600': passwordStrength === 2,
                                        'text-green-600': passwordStrength === 3
                                    }"
                                    x-text="passwordStrength === 1 ? 'Lemah' : passwordStrength === 2 ? 'Sedang' : 'Kuat'">
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">Minimal 8 karakter</p>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation"
                                required
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                            Ubah Password
                        </button>
                    </div>

                </div>

            </form>

        </div>

        {{-- ================= ACCOUNT INFO ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Akun
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Akun Dibuat</p>
                    <p class="font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Terakhir Diperbarui</p>
                    <p class="font-semibold text-gray-800">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= ALPINE SCRIPT ================= --}}
    <script>
        function pengaturanPage() {
            return {
                showCurrentPassword: false,
                showNewPassword: false,
                showConfirmPassword: false,
                passwordStrength: 0,
                avatarName: '',

                previewAvatar(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.avatarName = file.name;
                    }
                },

                checkPasswordStrength(password) {
                    let strength = 0;

                    if (password.length >= 8) strength++;
                    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                    if (password.match(/[0-9]/) && password.match(/[^a-zA-Z0-9]/)) strength++;

                    this.passwordStrength = strength;
                },

                async removeAvatar() {
                    if (!confirm('Yakin ingin menghapus foto profil?')) return;

                    try {
                        const response = await fetch("{{ route('mahasiswa.pengaturan.avatar.remove') }}", {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            window.location.reload();
                        } else {
                            alert('Gagal menghapus foto profil');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    }
                }
            }
        }
    </script>

</x-layout>
