<x-layout-prodi>
    <x-slot:title>Tambah Periode</x-slot>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('prodi.periode.index') }}"
            class="inline-flex items-center gap-2 text-violet-600 hover:text-violet-700 font-medium transition">
            <x-heroicon-o-arrow-left class="w-5 h-5" />
            <span>Kembali ke Daftar Periode</span>
        </a>
    </div>

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Periode Baru</h2>
        <p class="text-sm text-gray-600 mt-1">Buat periode tugas akhir baru dengan jadwal pengajuan</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('prodi.periode.store') }}" method="POST">
            @csrf

            {{-- Nama Periode --}}
            <div class="mb-5">
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Periode <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    placeholder="Contoh: Semester Ganjil"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('nama') border-red-500 @enderror"
                    required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                        <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tahun Akademik --}}
            <div class="mb-5">
                <label for="tahun_akademik" class="block text-sm font-semibold text-gray-700 mb-2">
                    Tahun Akademik <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tahun_akademik" id="tahun_akademik" value="{{ old('tahun_akademik') }}"
                    placeholder="Contoh: 2025/2026"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('tahun_akademik') border-red-500 @enderror"
                    required>
                @error('tahun_akademik')
                    <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                        <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tanggal Buka & Tutup --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label for="tanggal_buka" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Buka <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_buka" id="tanggal_buka" value="{{ old('tanggal_buka') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('tanggal_buka') border-red-500 @enderror"
                        required>
                    @error('tanggal_buka')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_tutup" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Tutup <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_tutup" id="tanggal_tutup" value="{{ old('tanggal_tutup') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('tanggal_tutup') border-red-500 @enderror"
                        required>
                    @error('tanggal_tutup')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="mb-5">
                <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                    Keterangan <span class="text-gray-400 text-xs font-normal">(Opsional)</span>
                </label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    placeholder="Catatan atau informasi tambahan tentang periode ini"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent transition @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
                        <x-heroicon-o-exclamation-circle class="w-4 h-4" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="mb-6 p-4 bg-violet-50 border border-violet-200 rounded-lg">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                        class="mt-0.5 w-4 h-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-gray-900 block">Aktifkan periode ini</span>
                        <span class="text-xs text-gray-600 block mt-1">Periode lain akan otomatis dinonaktifkan jika
                            opsi ini dicentang</span>
                    </div>
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit"
                    class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-sm hover:shadow-md flex items-center gap-2">
                    <x-heroicon-o-check class="w-5 h-5" />
                    <span>Simpan Periode</span>
                </button>
                <a href="{{ route('prodi.periode.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>

</x-layout-prodi>
