<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengaturanPage()" class="space-y-6">

        {{-- Alert --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-green-500" />
                <span class="font-semibold">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto rounded-lg p-1 text-green-400 hover:bg-green-100 transition">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-2xl border-2 border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 shadow-sm">
                <x-heroicon-o-x-circle class="h-5 w-5 shrink-0 text-red-500" />
                <span class="font-semibold">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto rounded-lg p-1 text-red-400 hover:bg-red-100 transition">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                class="rounded-2xl border-2 border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 shadow-sm">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-exclamation-circle class="h-5 w-5 shrink-0 text-red-500 mt-0.5" />
                    <div class="flex-1">
                        <p class="font-black mb-2">Terjadi kesalahan:</p>
                        <ul class="space-y-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show = false" class="rounded-lg p-1 text-red-400 hover:bg-red-100 transition">
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @endif

        {{-- ===== WELCOME BANNER ===== --}}
        <div
            class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-7 shadow-xl">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative flex items-center gap-5">
                {{-- Avatar --}}
                <div class="shrink-0">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                            class="h-20 w-20 rounded-2xl border-4 border-white/30 object-cover shadow-lg">
                    @else
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-2xl border-4 border-white/30 bg-white/20 text-2xl font-black text-white shadow-lg backdrop-blur-sm">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Pengaturan Akun</p>
                    <h2 class="mt-1 text-2xl font-black text-white leading-tight">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-indigo-200">{{ $user->email }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if ($user->nim)
                            <span
                                class="inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                                NIM: {{ $user->nim }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ===== KOLOM KIRI: INFO AKUN ===== --}}
            <div class="space-y-4">

                {{-- Info Akun --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-5 py-4">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-information-circle class="h-4 w-4 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white text-sm">Informasi Akun</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Akun Dibuat</p>
                            <p class="text-sm font-black text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Terakhir
                                Diperbarui</p>
                            <p class="text-sm font-black text-gray-800">{{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="rounded-xl border-2 border-indigo-100 bg-indigo-50 px-4 py-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-400 mb-1">Role</p>
                            <p class="text-sm font-black text-indigo-800">{{ ucfirst($user->role) }}</p>
                        </div>
                        @if ($user->nim)
                            <div class="rounded-xl border-2 border-violet-100 bg-violet-50 px-4 py-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-violet-400 mb-1">NIM</p>
                                <p class="text-sm font-black text-violet-800">{{ $user->nim }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tips --}}
                <div class="overflow-hidden rounded-2xl border-2 border-blue-200 bg-blue-50 shadow-sm">
                    <div class="px-5 py-4">
                        <div class="flex items-center gap-2 mb-3">
                            <x-heroicon-o-light-bulb class="h-5 w-5 text-blue-500" />
                            <p class="text-sm font-black text-blue-700">Tips Keamanan</p>
                        </div>
                        <ul class="space-y-2 text-xs text-blue-600">
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-400"></span>
                                Gunakan password minimal 8 karakter
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-400"></span>
                                Kombinasikan huruf besar, kecil, dan angka
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-400"></span>
                                Jangan bagikan password ke siapapun
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- ===== KOLOM KANAN: FORM ===== --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- ===== FORM PROFIL ===== --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-user class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">Informasi Profil</h3>
                            <p class="text-xs text-indigo-200">Perbarui nama dan email Anda</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('mahasiswa.pengaturan.profile') }}"
                        enctype="multipart/form-data" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Avatar --}}
                        <div class="flex items-center gap-5 rounded-xl border-2 border-gray-100 bg-gray-50 p-4">
                            <div class="relative shrink-0">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                        class="h-16 w-16 rounded-full border-4 border-indigo-100 object-cover shadow-sm">
                                    <button type="button" @click="removeAvatar()"
                                        class="absolute -right-1 -top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white shadow-md transition hover:bg-red-600">
                                        <x-heroicon-o-x-mark class="h-3.5 w-3.5" />
                                    </button>
                                @else
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-xl font-black text-white shadow-sm border-4 border-indigo-100">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-700 mb-2">Foto Profil</p>
                                <label class="cursor-pointer">
                                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg"
                                        @change="previewAvatar($event)" class="hidden" />
                                    <span
                                        class="inline-flex items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-indigo-700">
                                        <x-heroicon-o-photo class="h-3.5 w-3.5" />
                                        Pilih Foto
                                    </span>
                                </label>
                                <p x-show="avatarName" x-text="avatarName" class="mt-1.5 text-xs text-gray-500"></p>
                                <p class="mt-1 text-xs text-gray-400">JPG, PNG — Maks. 2MB</p>
                            </div>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('name')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('email')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIM (readonly) --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">NIM</label>
                            <input type="text" value="{{ $user->nim ?? '-' }}" disabled
                                class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-500 cursor-not-allowed" />
                            <p class="mt-1 text-xs text-gray-400">NIM tidak dapat diubah</p>
                        </div>

                        {{-- Submit --}}
                        <div class="border-t-2 border-gray-100 pt-4">
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-6 py-3 text-sm font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                <x-heroicon-o-check class="h-4 w-4" />
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>

                {{-- ===== FORM PASSWORD ===== --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-red-200 bg-gradient-to-r from-red-600 to-rose-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-lock-closed class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">Keamanan & Password</h3>
                            <p class="text-xs text-red-200">Ubah password akun Anda</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('mahasiswa.pengaturan.password') }}"
                        class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Password Lama --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Password Lama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input x-bind:type="show ? 'text' : 'password'" name="current_password" required
                                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 pr-12 text-sm text-gray-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 transition" />
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input x-bind:type="show ? 'text' : 'password'" name="password" required
                                    @input="checkPasswordStrength($event.target.value)"
                                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 pr-12 text-sm text-gray-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 transition" />
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                </button>
                            </div>

                            {{-- Strength Indicator --}}
                            <div x-show="passwordStrength > 0" class="mt-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 overflow-hidden rounded-full bg-gray-200">
                                        <div class="h-full rounded-full transition-all duration-300"
                                            x-bind:class="{
                                                'bg-red-500': passwordStrength === 1,
                                                'bg-yellow-500': passwordStrength === 2,
                                                'bg-emerald-500': passwordStrength === 3
                                            }"
                                            x-bind:style="`width: ${passwordStrength * 33.33}%`">
                                        </div>
                                    </div>
                                    <span class="text-xs font-black"
                                        x-bind:class="{
                                            'text-red-600': passwordStrength === 1,
                                            'text-yellow-600': passwordStrength === 2,
                                            'text-emerald-600': passwordStrength === 3
                                        }"
                                        x-text="passwordStrength === 1 ? 'Lemah' : passwordStrength === 2 ? 'Sedang' : 'Kuat'">
                                    </span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Minimal 8 karakter</p>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required
                                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 pr-12 text-sm text-gray-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 transition" />
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="border-t-2 border-gray-100 pt-4">
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-6 py-3 text-sm font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                Ubah Password
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function pengaturanPage() {
                return {
                    passwordStrength: 0,
                    avatarName: '',

                    previewAvatar(event) {
                        const file = event.target.files[0];
                        if (file) this.avatarName = file.name;
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
                            const res = await fetch("{{ route('mahasiswa.pengaturan.avatar.remove') }}", {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });
                            if (res.ok) window.location.reload();
                            else alert('Gagal menghapus foto profil');
                        } catch (e) {
                            alert('Terjadi kesalahan');
                        }
                    }
                }
            }
        </script>
    @endpush

</x-layout>
