<x-layout-koor-ta title="Buat Pengumuman">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- TOP BAR --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('koor-ta.pengumuman.index') }}"
                        class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-600">
                        <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                    </a>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <h1 class="text-lg font-extrabold text-gray-900">Buat Pengumuman</h1>
                        <p class="mt-0.5 text-xs text-gray-400">Tulis pengumuman untuk dikirim ke mahasiswa</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-2xl">
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    <div class="border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-megaphone class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">Detail Pengumuman</h2>
                                <p class="text-xs text-indigo-200">Pengumuman akan disimpan sebagai draft sebelum
                                    dikirim</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('koor-ta.pengumuman.store') }}" class="p-6 space-y-5">
                        @csrf

                        {{-- Periode --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Periode <span class="text-red-500">*</span>
                            </label>
                            <select name="periode_id"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('periode_id') ? 'border-red-400 bg-red-50' : '' }}">
                                <option value="">-- Pilih Periode --</option>
                                @foreach ($periode as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                        {{ $p->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Judul --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Judul Pengumuman <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ old('judul') }}"
                                placeholder="Contoh: Pengumuman Hasil Pengajuan Judul TA Semester Ganjil 2025/2026"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition
                                    {{ $errors->has('judul') ? 'border-red-400 bg-red-50' : '' }}" />
                            @error('judul')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Isi --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Isi Pengumuman <span class="text-red-500">*</span>
                            </label>
                            <textarea name="isi" rows="6" placeholder="Tulis isi pengumuman di sini..."
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 resize-none transition
                                    {{ $errors->has('isi') ? 'border-red-400 bg-red-50' : '' }}">{{ old('isi') }}</textarea>
                            @error('isi')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info --}}
                        <div class="rounded-xl border-2 border-yellow-200 bg-yellow-50 px-4 py-3">
                            <p class="text-xs font-semibold text-yellow-700">
                                <x-heroicon-o-information-circle class="inline h-4 w-4 mr-1" />
                                Pengumuman akan disimpan sebagai <span class="font-black">draft</span> terlebih dahulu.
                                Klik tombol <span class="font-black">Kirim</span> di halaman daftar untuk mengirim ke
                                semua mahasiswa.
                                Pengumuman yang sudah dikirim tidak dapat dihapus.
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3 border-t-2 border-gray-100 pt-5">
                            <a href="{{ route('koor-ta.pengumuman.index') }}"
                                class="rounded-xl border-2 border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                <x-heroicon-o-document-plus class="h-4 w-4" />
                                Simpan Draft
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</x-layout-koor-ta>
