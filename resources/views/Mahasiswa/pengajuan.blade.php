<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengajuanPage()" x-init="init()" class="space-y-6">

        {{-- Alert --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-green-500" />
                <span class="font-semibold">{{ session('success') }}</span>
                <button @click="show = false"
                    class="ml-auto rounded-lg p-1 text-green-400 hover:bg-green-100 transition">
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

        {{-- ===== HEADER BANNER ===== --}}
        <div
            class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-purple-700 to-pink-700 p-7 shadow-xl">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative flex items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border-2 border-white/30 bg-white/20 backdrop-blur-sm shadow-lg">
                        <x-heroicon-o-document-text class="h-7 w-7 text-white" />
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Mahasiswa</p>
                        <h2 class="mt-1 text-xl font-black text-white leading-tight">Pengajuan Judul Tugas Akhir</h2>
                        <p class="mt-1 text-sm text-indigo-200">Ajukan usulan judul sendiri dan pilih 3 alternatif dari
                            dosen</p>
                    </div>
                </div>
                <div class="hidden lg:flex shrink-0 gap-3">
                    <div
                        class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-3 text-center backdrop-blur-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Status</p>
                        <p class="mt-1 text-lg font-black text-white">
                            {{ $jumlahPengajuan > 0 ? '✓ Sudah' : '○ Belum' }}
                        </p>
                    </div>
                    <div
                        class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-3 text-center backdrop-blur-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Tersedia</p>
                        <p class="mt-1 text-2xl font-black text-white">{{ $judul->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== MY SUBMISSIONS ===== --}}
        @if ($mySubmissions->count() > 0)
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-clipboard-document-list class="h-3 w-3" />
                    Pengajuan Anda
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="space-y-4">
                @foreach ($mySubmissions as $submission)
                    <div
                        class="overflow-hidden rounded-2xl border-2 shadow-md
                        {{ $submission->status === 'pending' ? 'border-yellow-200' : ($submission->status === 'disetujui' ? 'border-emerald-200' : 'border-red-200') }}
                        bg-white">

                        {{-- Submission Header --}}
                        <div
                            class="flex items-center justify-between border-b-4 px-6 py-4
                            {{ $submission->status === 'pending'
                                ? 'border-yellow-200 bg-gradient-to-r from-yellow-500 to-orange-500'
                                : ($submission->status === 'disetujui'
                                    ? 'border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700'
                                    : 'border-red-200 bg-gradient-to-r from-red-600 to-rose-700') }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                    @if ($submission->status === 'pending')
                                        <x-heroicon-o-clock class="h-5 w-5 text-white" />
                                    @elseif ($submission->status === 'disetujui')
                                        <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                                    @else
                                        <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                                    @endif
                                </div>
                                <div>
                                    <p class="font-extrabold text-white">
                                        {{ $submission->status === 'pending' ? 'Menunggu Review' : ($submission->status === 'disetujui' ? 'Disetujui' : 'Ditolak') }}
                                    </p>
                                    <p class="text-xs text-white/70">{{ $submission->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                <span
                                    class="h-1.5 w-1.5 rounded-full {{ $submission->status === 'pending' ? 'animate-pulse bg-yellow-200' : 'bg-white' }}"></span>
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>

                        <div class="p-6 space-y-4">

                            {{-- Judul Mandiri --}}
                            @if ($submission->judul_mandiri)
                                <div class="rounded-xl border-2 border-violet-200 bg-violet-50 p-4">
                                    <p class="text-xs font-black uppercase tracking-widest text-violet-500 mb-2">Usulan
                                        Judul Sendiri</p>
                                    <p class="font-black text-gray-800 text-base leading-relaxed">
                                        {{ $submission->judul_mandiri }}</p>
                                    @if ($submission->deskripsi_mandiri)
                                        <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                            {{ $submission->deskripsi_mandiri }}</p>
                                    @endif
                                </div>
                            @endif

                            {{-- 3 Pilihan --}}
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                                @foreach ([['label' => 'Pilihan 1', 'judul' => $submission->pilihan1, 'alasan' => $submission->alasan_1, 'color' => 'blue'], ['label' => 'Pilihan 2', 'judul' => $submission->pilihan2, 'alasan' => $submission->alasan_2, 'color' => 'emerald'], ['label' => 'Pilihan 3', 'judul' => $submission->pilihan3, 'alasan' => $submission->alasan_3, 'color' => 'orange']] as $p)
                                    @if ($p['judul'])
                                        <div
                                            class="rounded-xl border-2 border-{{ $p['color'] }}-200 bg-{{ $p['color'] }}-50 p-4">
                                            <p
                                                class="text-xs font-black uppercase tracking-widest text-{{ $p['color'] }}-600 mb-2">
                                                {{ $p['label'] }}</p>
                                            <p class="text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                                {{ $p['judul']->nama_judul }}</p>
                                            <div class="space-y-1 text-xs text-gray-500">
                                                <p><span class="font-bold">Kode:</span> {{ $p['judul']->kode ?? '-' }}
                                                </p>
                                                <p><span class="font-bold">Dosen:</span>
                                                    {{ $p['judul']->dosen->name ?? '-' }}</p>
                                                <p><span class="font-bold">Lab:</span>
                                                    {{ $p['judul']->laboratorium->nama ?? '-' }}</p>
                                            </div>
                                            @if ($p['alasan'])
                                                <div class="mt-3 border-t-2 border-{{ $p['color'] }}-200 pt-2">
                                                    <p class="text-xs font-black text-{{ $p['color'] }}-600 mb-1">
                                                        Alasan</p>
                                                    <p class="text-xs text-gray-600 leading-relaxed">
                                                        {{ $p['alasan'] }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Catatan Penolakan --}}
                            @if ($submission->status === 'ditolak' && $submission->catatan)
                                <div class="rounded-xl border-2 border-red-200 bg-red-50 p-4">
                                    <p class="text-xs font-black uppercase tracking-widest text-red-500 mb-2">Catatan
                                        Penolakan</p>
                                    <p class="text-sm text-red-800 leading-relaxed">{{ $submission->catatan }}</p>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ===== FORM ===== --}}
        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
            <span
                class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                <x-heroicon-o-document-plus class="h-3 w-3" />
                Form Pengajuan
            </span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
        </div>
        <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="pilihan_1_id" x-bind:value="pilihan1?.id || ''">
            <input type="hidden" name="pilihan_2_id" x-bind:value="pilihan2?.id || ''">
            <input type="hidden" name="pilihan_3_id" x-bind:value="pilihan3?.id || ''">

            {{-- ===== SECTION 0: JUDUL MANDIRI ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-violet-200 bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-4">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <span class="text-base font-black text-white">0</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-white">Usulan Judul Sendiri</h4>
                        <p class="text-xs text-violet-200">Opsional — Ajukan judul hasil ide Anda sendiri</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            Judul Usulan
                        </label>
                        <input type="text" name="judul_mandiri" maxlength="255"
                            placeholder="Contoh: Sistem Informasi Manajemen Perpustakaan Berbasis Web"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 transition" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            Deskripsi Usulan
                        </label>
                        <textarea name="deskripsi_mandiri" rows="4" maxlength="1000"
                            placeholder="Jelaskan latar belakang, tujuan, dan ruang lingkup penelitian..."
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 resize-none transition">
                        </textarea>
                    </div>
                </div>
            </div>

            {{-- ===== REUSABLE SECTION TEMPLATE ===== --}}
            @foreach ([['num' => 1, 'label' => 'Pilihan Pertama', 'sub' => 'Wajib — Pilih 1 judul sebagai prioritas utama', 'color' => 'blue', 'grad' => 'from-blue-600 to-indigo-700', 'border' => 'border-blue-200'], ['num' => 2, 'label' => 'Pilihan Kedua', 'sub' => 'Wajib — Pilih 1 judul sebagai alternatif kedua', 'color' => 'emerald', 'grad' => 'from-emerald-600 to-teal-700', 'border' => 'border-emerald-200'], ['num' => 3, 'label' => 'Pilihan Ketiga', 'sub' => 'Wajib — Pilih 1 judul sebagai alternatif ketiga', 'color' => 'orange', 'grad' => 'from-orange-500 to-amber-600', 'border' => 'border-orange-200']] as $s)
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    {{-- Section Header --}}
                    <div
                        class="flex items-center justify-between border-b-4 {{ $s['border'] }} bg-gradient-to-r {{ $s['grad'] }} px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <span class="text-base font-black text-white">{{ $s['num'] }}</span>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-white">{{ $s['label'] }}</h4>
                                <p class="text-xs text-white/70">{{ $s['sub'] }}</p>
                            </div>
                        </div>
                        <div x-show="pilihan{{ $s['num'] }}"
                            class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                            <x-heroicon-o-check class="h-3.5 w-3.5" />
                            Terpilih
                        </div>
                    </div>

                    <div class="p-6">

                        {{-- Selected Card --}}
                        <div x-show="pilihan{{ $s['num'] }}" x-transition
                            class="mb-5 rounded-xl border-2 border-{{ $s['color'] }}-200 bg-{{ $s['color'] }}-50 p-5">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex-1">
                                    <p
                                        class="text-xs font-black uppercase tracking-widest text-{{ $s['color'] }}-600 mb-1">
                                        Judul Terpilih</p>
                                    <p class="font-black text-gray-800 text-base leading-relaxed"
                                        x-text="pilihan{{ $s['num'] }}?.nama_judul"></p>
                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <x-heroicon-o-academic-cap class="h-3.5 w-3.5" />
                                            <span x-text="pilihan{{ $s['num'] }}?.dosen_name"></span>
                                        </span>
                                        <span
                                            class="inline-flex items-center rounded-full border-2 border-{{ $s['color'] }}-200 bg-{{ $s['color'] }}-100 px-2.5 py-0.5 text-xs font-black text-{{ $s['color'] }}-700"
                                            x-text="pilihan{{ $s['num'] }}?.lab_name">
                                        </span>
                                    </div>
                                </div>
                                <button type="button"
                                    @click="pilihan{{ $s['num'] }} = null; alasan{{ $s['num'] }} = ''"
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border-2 border-red-200 bg-red-50 text-red-500 transition hover:bg-red-100">
                                    <x-heroicon-o-x-mark class="h-4 w-4" />
                                </button>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                    Alasan Memilih Judul Ini
                                </label>
                                <textarea x-model="alasan{{ $s['num'] }}" name="alasan_{{ $s['num'] }}" rows="3" maxlength="500"
                                    placeholder="Jelaskan mengapa Anda tertarik dengan judul ini..."
                                    class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-{{ $s['color'] }}-400 focus:outline-none focus:ring-2 focus:ring-{{ $s['color'] }}-100 resize-none transition">
                            </textarea>
                            </div>
                        </div>

                        {{-- Filter --}}
                        <div x-show="!pilihan{{ $s['num'] }}" class="mb-4 flex flex-wrap items-center gap-3">
                            <div
                                class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                                <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                                <input type="text" x-model="searchQuery{{ $s['num'] }}"
                                    @input="filterJudul{{ $s['num'] }}()"
                                    placeholder="Cari judul, dosen, kode..."
                                    class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                            </div>
                            <select x-model="selectedLab{{ $s['num'] }}"
                                @change="filterJudul{{ $s['num'] }}()"
                                class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-{{ $s['color'] }}-400 focus:outline-none transition">
                                <option value="">Semua Lab</option>
                                @foreach ($laboratorium as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                                @endforeach
                            </select>
                            <div
                                class="flex items-center gap-1.5 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm text-xs font-bold text-gray-600">
                                <span x-text="filteredJudul{{ $s['num'] }}.length"></span>
                                <span>judul</span>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div x-show="!pilihan{{ $s['num'] }}"
                            class="overflow-hidden rounded-2xl border-2 border-gray-200 shadow-sm">

                            {{-- Table Header --}}
                            <div
                                class="border-b-2 border-gray-200 bg-gray-50 px-4 py-3 text-xs font-black uppercase tracking-wider text-gray-500 grid grid-cols-12 gap-3">
                                <div class="col-span-1">No</div>
                                <div class="col-span-2">Kode</div>
                                <div class="col-span-4">Judul</div>
                                <div class="col-span-2">Dosen / Lab</div>
                                <div class="col-span-1 text-center">Peminat</div>
                                <div class="col-span-2 text-center">Aksi</div>
                            </div>

                            {{-- Empty State --}}
                            <div x-show="filteredJudul{{ $s['num'] }}.length === 0"
                                class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 mb-4">
                                    <x-heroicon-o-document-magnifying-glass class="h-8 w-8 text-gray-400" />
                                </div>
                                <p class="text-sm font-bold text-gray-600">Tidak ada judul ditemukan</p>
                                <button type="button"
                                    @click="searchQuery{{ $s['num'] }} = ''; selectedLab{{ $s['num'] }} = ''; filterJudul{{ $s['num'] }}()"
                                    class="mt-3 text-xs font-bold text-{{ $s['color'] }}-600 hover:text-{{ $s['color'] }}-800 transition">
                                    Reset Filter
                                </button>
                            </div>

                            {{-- Rows --}}
                            <div class="divide-y-2 divide-gray-100">
                                <template x-for="(item, index) in paginatedJudul{{ $s['num'] }}"
                                    :key="item.id">
                                    <div
                                        class="grid grid-cols-12 gap-3 px-4 py-4 transition hover:bg-{{ $s['color'] }}-50/30 items-start">

                                        {{-- No --}}
                                        <div class="col-span-1">
                                            <span
                                                class="flex h-7 w-7 items-center justify-center rounded-lg border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500"
                                                x-text="(currentPage{{ $s['num'] }} - 1) * perPage + index + 1">
                                            </span>
                                        </div>

                                        {{-- Kode --}}
                                        <div class="col-span-2">
                                            <span
                                                class="rounded-lg border-2 border-gray-200 bg-gray-50 px-2 py-1 font-mono text-xs font-black text-gray-600"
                                                x-text="item.kode">
                                            </span>
                                        </div>

                                        {{-- Judul + Deskripsi --}}
                                        <div class="col-span-4" x-data="{ expanded: false }">
                                            <p class="text-sm font-bold text-gray-800 leading-relaxed mb-1"
                                                x-text="item.nama_judul"></p>
                                            <p class="text-xs text-gray-500 leading-relaxed"
                                                x-bind:class="!expanded && item.deskripsi && item.deskripsi.length > 100 ?
                                                    'line-clamp-2' : ''"
                                                x-text="item.deskripsi || 'Tidak ada deskripsi'">
                                            </p>
                                            <button type="button"
                                                x-show="item.deskripsi && item.deskripsi.length > 100"
                                                @click="expanded = !expanded"
                                                class="mt-1 text-xs font-bold text-{{ $s['color'] }}-600 hover:text-{{ $s['color'] }}-800 transition">
                                                <span x-text="expanded ? '↑ Sembunyikan' : '↓ Selengkapnya'"></span>
                                            </button>
                                        </div>

                                        {{-- Dosen / Lab --}}
                                        <div class="col-span-2">
                                            <p class="text-xs font-semibold text-gray-700 mb-1"
                                                x-text="item.dosen_name"></p>
                                            <span
                                                class="inline-flex items-center rounded-full border-2 border-{{ $s['color'] }}-200 bg-{{ $s['color'] }}-50 px-2 py-0.5 text-[10px] font-black text-{{ $s['color'] }}-700"
                                                x-text="item.lab_name">
                                            </span>
                                        </div>

                                        {{-- Peminat --}}
                                        <div class="col-span-1 text-center">
                                            <span
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-full border-2 border-violet-200 bg-violet-100 text-xs font-black text-violet-700"
                                                x-text="item.peminat">
                                            </span>
                                        </div>

                                        {{-- Aksi --}}
                                        <div class="col-span-2 text-center">
                                            <button type="button" @click="pilihan{{ $s['num'] }} = item"
                                                x-bind:disabled="isSelectedInOthers(item.id, {{ $s['num'] }})"
                                                x-bind:class="isSelectedInOthers(item.id, {{ $s['num'] }}) ?
                                                    'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed' :
                                                    '{{ $s['num'] === 1 ? 'border-blue-300 bg-blue-600 text-white hover:bg-blue-700' : ($s['num'] === 2 ? 'border-emerald-300 bg-emerald-600 text-white hover:bg-emerald-700' : 'border-orange-300 bg-orange-600 text-white hover:bg-orange-700') }} hover:shadow-md'"
                                                class="inline-flex items-center gap-1 rounded-xl border-2 px-3 py-1.5 text-xs font-black shadow-sm transition">
                                                <x-heroicon-o-plus class="h-3.5 w-3.5" />
                                                Pilih
                                            </button>
                                        </div>

                                    </div>
                                </template>
                            </div>

                            {{-- Pagination --}}
                            <div x-show="totalPages{{ $s['num'] }} > 1"
                                class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">
                                    Hal <span class="font-black text-gray-800"
                                        x-text="currentPage{{ $s['num'] }}"></span>
                                    / <span x-text="totalPages{{ $s['num'] }}"></span>
                                </p>
                                <div class="flex items-center gap-1.5">
                                    <button type="button"
                                        @click="currentPage{{ $s['num'] }}--; paginate{{ $s['num'] }}()"
                                        x-bind:disabled="currentPage{{ $s['num'] }} === 1"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-{{ $s['color'] }}-300 hover:bg-{{ $s['color'] }}-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                        <x-heroicon-o-chevron-left class="h-4 w-4" />
                                    </button>
                                    <button type="button"
                                        @click="currentPage{{ $s['num'] }}++; paginate{{ $s['num'] }}()"
                                        x-bind:disabled="currentPage{{ $s['num'] }} === totalPages{{ $s['num'] }}"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-{{ $s['color'] }}-300 hover:bg-{{ $s['color'] }}-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                        <x-heroicon-o-chevron-right class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
            {{-- ===== SUBMIT SECTION ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-paper-airplane class="h-5 w-5 text-white" />
                    </div>
                    <div>
                        <h4 class="font-extrabold text-white">Kirim Pengajuan</h4>
                        <p class="text-xs text-indigo-200">Pastikan semua pilihan sudah terisi dengan benar</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Progress Indicator --}}
                    <div class="grid grid-cols-3 gap-4">
                        @foreach ([['num' => 1, 'color' => 'blue', 'grad' => 'from-blue-500 to-indigo-600'], ['num' => 2, 'color' => 'emerald', 'grad' => 'from-emerald-500 to-green-600'], ['num' => 3, 'color' => 'orange', 'grad' => 'from-orange-500 to-amber-600']] as $p)
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full border-2 transition-all duration-300"
                                    x-bind:class="pilihan{{ $p['num'] }}
                                        ?
                                        'bg-gradient-to-br {{ $p['grad'] }} border-{{ $p['color'] }}-400 shadow-md' :
                                        'bg-gray-100 border-gray-300'">
                                    <span class="font-black text-lg"
                                        x-bind:class="pilihan{{ $p['num'] }} ? 'text-white' : 'text-gray-400'"
                                        x-text="pilihan{{ $p['num'] }} ? '✓' : '{{ $p['num'] }}'">
                                    </span>
                                </div>
                                <p class="text-xs font-bold transition-colors"
                                    x-bind:class="pilihan{{ $p['num'] }} ? 'text-{{ $p['color'] }}-600' : 'text-gray-400'">
                                    Pilihan {{ $p['num'] }}
                                </p>
                                <p class="text-[10px] text-center leading-tight"
                                    x-bind:class="pilihan{{ $p['num'] }} ? 'text-{{ $p['color'] }}-500 font-semibold' :
                                        'text-gray-400'"
                                    x-text="pilihan{{ $p['num'] }} ? pilihan{{ $p['num'] }}.nama_judul.substring(0, 30) + '...' : 'Belum dipilih'">
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Status info --}}
                    <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-center text-sm font-semibold"
                            x-bind:class="pilihan1 && pilihan2 && pilihan3 ? 'text-emerald-600' : 'text-gray-500'">
                            <template x-if="pilihan1 && pilihan2 && pilihan3">
                                <span class="flex items-center justify-center gap-2">
                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                    Semua pilihan sudah lengkap — siap dikirim
                                </span>
                            </template>
                            <template x-if="!pilihan1 || !pilihan2 || !pilihan3">
                                <span>
                                    Masih kurang:
                                    <span x-show="!pilihan1" class="font-black text-blue-600"> Pilihan 1</span>
                                    <span x-show="!pilihan2" class="font-black text-emerald-600"> Pilihan 2</span>
                                    <span x-show="!pilihan3" class="font-black text-orange-600"> Pilihan 3</span>
                                </span>
                            </template>
                        </p>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        x-bind:disabled="{{ $jumlahPengajuan }} > 0 || !pilihan1 || !pilihan2 || !pilihan3"
                        x-bind:class="{{ $jumlahPengajuan }} > 0 || !pilihan1 || !pilihan2 || !pilihan3 ?
                            'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-200' :
                            'bg-gradient-to-r from-indigo-600 to-purple-700 border-indigo-300 text-white hover:from-indigo-700 hover:to-purple-800 hover:shadow-lg'"
                        class="w-full inline-flex items-center justify-center gap-3 rounded-xl border-2 px-6 py-4 text-base font-black shadow-sm transition">
                        <x-heroicon-o-paper-airplane class="h-5 w-5" />
                        <span x-show="{{ $jumlahPengajuan }} === 0 && pilihan1 && pilihan2 && pilihan3">
                            Kirim Pengajuan Sekarang
                        </span>
                        <span x-show="{{ $jumlahPengajuan }} > 0">
                            ✓ Anda Sudah Mengajukan
                        </span>
                        <span x-show="{{ $jumlahPengajuan }} === 0 && (!pilihan1 || !pilihan2 || !pilihan3)">
                            Lengkapi 3 Pilihan Terlebih Dahulu
                        </span>
                    </button>

                </div>
            </div>

        </form>

    </div>

    @push('scripts')
        <script>
            function pengajuanPage() {
                return {
                    pilihan1: null,
                    pilihan2: null,
                    pilihan3: null,
                    alasan1: '',
                    alasan2: '',
                    alasan3: '',

                    searchQuery1: '',
                    selectedLab1: '',
                    allJudul: [],
                    filteredJudul1: [],
                    paginatedJudul1: [],
                    currentPage1: 1,
                    totalPages1: 1,

                    searchQuery2: '',
                    selectedLab2: '',
                    filteredJudul2: [],
                    paginatedJudul2: [],
                    currentPage2: 1,
                    totalPages2: 1,

                    searchQuery3: '',
                    selectedLab3: '',
                    filteredJudul3: [],
                    paginatedJudul3: [],
                    currentPage3: 1,
                    totalPages3: 1,

                    perPage: 10,

                    init() {
                        this.allJudul = @json($judulJson);
                        this.filteredJudul1 = this.allJudul;
                        this.filteredJudul2 = this.allJudul;
                        this.filteredJudul3 = this.allJudul;
                        this.paginate1();
                        this.paginate2();
                        this.paginate3();
                    },

                    filterJudul1() {
                        let result = this.allJudul;
                        if (this.searchQuery1) {
                            const q = this.searchQuery1.toLowerCase();
                            result = result.filter(j =>
                                j.nama_judul.toLowerCase().includes(q) ||
                                j.dosen_name.toLowerCase().includes(q) ||
                                j.kode.toLowerCase().includes(q)
                            );
                        }
                        if (this.selectedLab1) {
                            result = result.filter(j => j.lab_id == this.selectedLab1);
                        }
                        this.filteredJudul1 = result;
                        this.currentPage1 = 1;
                        this.paginate1();
                    },

                    paginate1() {
                        this.totalPages1 = Math.ceil(this.filteredJudul1.length / this.perPage) || 1;
                        const start = (this.currentPage1 - 1) * this.perPage;
                        this.paginatedJudul1 = this.filteredJudul1.slice(start, start + this.perPage);
                    },

                    filterJudul2() {
                        let result = this.allJudul;
                        if (this.searchQuery2) {
                            const q = this.searchQuery2.toLowerCase();
                            result = result.filter(j =>
                                j.nama_judul.toLowerCase().includes(q) ||
                                j.dosen_name.toLowerCase().includes(q) ||
                                j.kode.toLowerCase().includes(q)
                            );
                        }
                        if (this.selectedLab2) {
                            result = result.filter(j => j.lab_id == this.selectedLab2);
                        }
                        this.filteredJudul2 = result;
                        this.currentPage2 = 1;
                        this.paginate2();
                    },

                    paginate2() {
                        this.totalPages2 = Math.ceil(this.filteredJudul2.length / this.perPage) || 1;
                        const start = (this.currentPage2 - 1) * this.perPage;
                        this.paginatedJudul2 = this.filteredJudul2.slice(start, start + this.perPage);
                    },

                    filterJudul3() {
                        let result = this.allJudul;
                        if (this.searchQuery3) {
                            const q = this.searchQuery3.toLowerCase();
                            result = result.filter(j =>
                                j.nama_judul.toLowerCase().includes(q) ||
                                j.dosen_name.toLowerCase().includes(q) ||
                                j.kode.toLowerCase().includes(q)
                            );
                        }
                        if (this.selectedLab3) {
                            result = result.filter(j => j.lab_id == this.selectedLab3);
                        }
                        this.filteredJudul3 = result;
                        this.currentPage3 = 1;
                        this.paginate3();
                    },

                    paginate3() {
                        this.totalPages3 = Math.ceil(this.filteredJudul3.length / this.perPage) || 1;
                        const start = (this.currentPage3 - 1) * this.perPage;
                        this.paginatedJudul3 = this.filteredJudul3.slice(start, start + this.perPage);
                    },

                    isSelectedInOthers(judulId, currentPilihan) {
                        if (currentPilihan !== 1 && this.pilihan1 && this.pilihan1.id === judulId) return true;
                        if (currentPilihan !== 2 && this.pilihan2 && this.pilihan2.id === judulId) return true;
                        if (currentPilihan !== 3 && this.pilihan3 && this.pilihan3.id === judulId) return true;
                        return false;
                    }
                }
            }
        </script>
    @endpush


</x-layout>
