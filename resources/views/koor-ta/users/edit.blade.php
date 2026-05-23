<x-layout-koor-ta title="Edit User">

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
                        <h1 class="text-lg font-extrabold text-gray-900">Edit User</h1>
                        <p class="mt-0.5 text-xs text-gray-400">Perbarui informasi akun {{ $user->name }}</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-2xl space-y-6">

                {{-- Form Edit --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    <div class="border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 text-xl font-black text-white border-2 border-white/30">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">{{ $user->name }}</h2>
                                <p class="text-xs text-indigo-200">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('koor-ta.users.update', $user) }}" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
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
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('email')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIM --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                NIM
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="text" name="nim" value="{{ old('nim', $user->nim) }}"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
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
                            <select name="role" {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $user->id === auth()->id() ? 'bg-gray-50 cursor-not-allowed' : '' }}
                                    {{ $errors->has('role') ? 'border-red-400 bg-red-50' : '' }}">
                                @foreach ([
        'mahasiswa' => 'Mahasiswa',
        'dosen' => 'Dosen',
        'ka_lab' => 'Kepala Lab',
        'prodi' => 'Program Studi (Kaprodi)',
        'koordinator_ta' => 'Koordinator TA',
    ] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ old('role', $user->role) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($user->id === auth()->id())
                                <p class="mt-1.5 text-xs text-gray-400">Role tidak dapat diubah untuk akun sendiri</p>
                                {{-- Hidden input supaya value tetap terkirim --}}
                                <input type="hidden" name="role" value="{{ $user->role }}" />
                            @endif
                            @error('role')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 border-t-2 border-gray-100 pt-5">
                            <a href="{{ route('koor-ta.users.index') }}"
                                class="rounded-xl border-2 border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                <x-heroicon-o-check class="h-4 w-4" />
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Danger Zone --}}
                @if ($user->id !== auth()->id())
                    <div class="overflow-hidden rounded-2xl border-2 border-red-200 bg-white shadow-sm">
                        <div class="border-b-2 border-red-200 bg-red-50 px-6 py-4">
                            <h3 class="font-extrabold text-red-700">Zona Berbahaya</h3>
                            <p class="text-xs text-red-500 mt-0.5">Tindakan berikut tidak dapat dibatalkan</p>
                        </div>
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <p class="text-sm font-bold text-gray-800">Hapus Akun</p>
                                <p class="text-xs text-gray-400 mt-0.5">Hapus permanen akun {{ $user->name }} dari
                                    sistem</p>
                            </div>
                            <form method="POST" action="{{ route('koor-ta.users.destroy', $user) }}"
                                onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-red-700">
                                    <x-heroicon-o-trash class="h-4 w-4" />
                                    Hapus Akun
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-layout-koor-ta>
