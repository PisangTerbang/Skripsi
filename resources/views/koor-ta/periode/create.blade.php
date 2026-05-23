<x-layout-koor-ta title="Tambah Periode">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- TOP BAR --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('koor-ta.periode.index') }}"
                        class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-600">
                        <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                    </a>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <h1 class="text-lg font-extrabold text-gray-900">Tambah Periode</h1>
                        <p class="mt-0.5 text-xs text-gray-400">Buat periode pengajuan baru</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-2xl">
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    <div class="border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-calendar-days class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">Detail Periode</h2>
                                <p class="text-xs text-indigo-200">Isi informasi periode pengajuan</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('koor-ta.periode.store') }}" class="p-6 space-y-5">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Nama Periode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                placeholder="Contoh: Semester Ganjil 2025/2026"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('nama')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('tanggal_mulai') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('tanggal_mulai')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('tanggal_selesai') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('tanggal_selesai')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Aktifkan Sekarang --}}
                        <div class="flex items-center gap-3 rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer">
                                Aktifkan periode ini sekarang
                            </label>
                            <p class="text-xs text-gray-400 ml-auto">Periode lain akan dinonaktifkan otomatis</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 border-t-2 border-gray-100 pt-5">
                            <a href="{{ route('koor-ta.periode.index') }}"
                                class="rounded-xl border-2 border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                <x-heroicon-o-plus class="h-4 w-4" />
                                Buat Periode
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</x-layout-koor-ta>
