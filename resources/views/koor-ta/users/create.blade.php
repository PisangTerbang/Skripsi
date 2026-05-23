<x-layout-koor-ta title="Tambah User">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('koor-ta.users.index') }}"
                        class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-600">
                        <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                    </a>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <h1 class="text-lg font-extrabold text-gray-900">Tambah User Baru</h1>
                        <p class="mt-0.5 text-xs text-gray-400">Buat akun pengguna baru untuk sistem</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="mx-auto max-w-2xl">
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    {{-- Card Header --}}
                    <div class="border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-user-plus class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">Informasi User</h2>
                                <p class="text-xs text-indigo-200">Isi semua field yang diperlukan</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('koor-ta.users.store') }}" class="p-6 space-y-5">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
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
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="contoh@email.com"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('email')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIM (opsional) --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                NIM
                                <span class="text-gray-400 font-normal">(opsional, untuk mahasiswa)</span>
                            </label>
                            <input type="text" name="nim" value="{{ old('nim') }}"
                                placeholder="Contoh: 20523001"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('nim') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('nim')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('role') ? 'border-red-400 bg-red-50' : '' }}">
                                <option value="">-- Pilih Role --</option>
                                @foreach ([
        'mahasiswa' => 'Mahasiswa',
        'dosen' => 'Dosen',
        'ka_lab' => 'Kepala Lab',
        'prodi' => 'Program Studi (Kaprodi)',
        'koordinator_ta' => 'Koordinator TA',
    ] as $val => $label)
                                    <option value="{{ $val }}" {{ old('role') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                :type="show ? 'text' : 'password'"
                                <input :type="show ? 'text' : 'password'" name="password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 pr-12 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                        {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}" />
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div x-data="{ show: false }">
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    placeholder="Ulangi password"
                                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 pr-12 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition" />
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                    <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        {{-- Info password default --}}
                        <div class="rounded-xl border-2 border-indigo-100 bg-indigo-50 px-4 py-3">
                            <p class="text-xs font-semibold text-indigo-700">
                                <x-heroicon-o-information-circle class="inline h-4 w-4 mr-1" />
                                Jika user lupa password, Koordinator TA dapat mereset password ke NIM atau username
                                email melalui tombol Reset di halaman daftar user.
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 border-t-2 border-gray-100 pt-5">
                            <a href="{{ route('koor-ta.users.index') }}"
                                class="rounded-xl border-2 border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                <x-heroicon-o-user-plus class="h-4 w-4" />
                                Tambah User
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</x-layout-koor-ta>
