<x-layout-prodi>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="monitoringPage()" x-init="init()">
        <div class="min-h-screen bg-slate-100">
            <div class="px-6 py-6 space-y-6">

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

                {{-- ===== HEADER BANNER ===== --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-600 via-violet-700 to-purple-800 p-7 shadow-xl">
                    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
                    <div class="relative flex items-center justify-between gap-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-300">Kaprodi</p>
                            <h2 class="mt-1 text-2xl font-black text-white">Monitoring Sistem</h2>
                            <p class="mt-1 text-sm text-violet-200">Pantau seluruh proses pengajuan Tugas Akhir</p>
                        </div>
                        <div class="hidden lg:flex shrink-0 gap-3">
                            <div
                                class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                                <p class="text-xs font-bold uppercase tracking-widest text-violet-200">Total Pengajuan
                                </p>
                                <p class="mt-1 text-4xl font-black text-white">{{ $totalPengajuan }}</p>
                            </div>
                            <div
                                class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                                <p class="text-xs font-bold uppercase tracking-widest text-violet-200">Menunggu Ka Lab
                                </p>
                                <p class="mt-1 text-4xl font-black text-white">{{ $menungguDosen }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== STATS PENGAJUAN ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-chart-bar class="h-3 w-3" />
                        Ringkasan Pengajuan
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 to-green-600 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-emerald-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-100">Disetujui Ka Lab</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $disetujuiDosen }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 to-rose-600 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-x-circle class="h-6 w-6 text-red-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-red-100">Ditolak Ka Lab</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $ditolakDosen }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 to-purple-600 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document-check class="h-6 w-6 text-violet-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-100">Ditetapkan</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $ditetapkan }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-orange-300 bg-gradient-to-br from-orange-500 to-amber-600 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-user-group class="h-6 w-6 text-orange-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-orange-100">Belum Mengajukan</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $mahasiswaBelumMengajukan }}</p>
                        </div>
                    </div>

                </div>

                {{-- ===== STATS JUDUL ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-document-text class="h-3 w-3" />
                        Ringkasan Judul
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 to-blue-700 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document-text class="h-6 w-6 text-indigo-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-100">Total Judul</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $totalJudul }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-br from-sky-500 to-blue-600 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-check-badge class="h-6 w-6 text-sky-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-100">Tersedia</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $judulTersedia }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 to-orange-500 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document class="h-6 w-6 text-yellow-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Draft</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $judulDraft }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-gray-300 bg-gradient-to-br from-gray-500 to-gray-700 p-5 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-pause-circle class="h-6 w-6 text-gray-200 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-200">Nonaktif</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $judulNonaktif }}</p>
                        </div>
                    </div>

                </div>

                {{-- ===== TABEL PENGAJUAN ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-table-cells class="h-3 w-3" />
                        Daftar Pengajuan
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                {{-- Filter --}}
                <div class="flex flex-wrap items-center gap-3">

                    {{-- Status Filter Pills --}}
                    <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                        @foreach ([
        'all' => 'Semua',
        'pending' => 'Pending',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'ditetapkan' => 'Ditetapkan',
    ] as $val => $label)
                            <button type="button" @click="filterStatus = '{{ $val }}'; filterData()"
                                x-bind:class="filterStatus === '{{ $val }}'
                                    ?
                                    '{{ $val === 'ditolak' ? 'bg-red-600' : ($val === 'pending' ? 'bg-yellow-500' : ($val === 'disetujui' ? 'bg-emerald-600' : ($val === 'ditetapkan' ? 'bg-violet-600' : 'bg-indigo-600'))) }} text-white shadow-sm' :
                                    'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                                class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all">
                                {{ $label }}
                            </button>
                            @if (!$loop->last)
                                <div class="h-5 w-px bg-gray-200"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Search --}}
                    <div
                        class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                        <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                        <input type="text" x-model="search" @input="filterData()"
                            placeholder="Cari mahasiswa, judul..."
                            class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                        <template x-if="search !== ''">
                            <button @click="search = ''; filterData()"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <x-heroicon-o-x-mark class="h-4 w-4" />
                            </button>
                        </template>
                    </div>

                    {{-- Count --}}
                    <div
                        class="flex items-center gap-1.5 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm text-xs font-bold text-gray-600">
                        <span x-text="filteredData.length"></span>
                        <span>pengajuan</span>
                    </div>

                </div>

                {{-- Table Card --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    <div
                        class="flex items-center justify-between border-b-4 border-violet-200 bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">Pengajuan Terbaru</h2>
                                <p class="text-xs text-violet-200">Monitoring semua pengajuan mahasiswa</p>
                            </div>
                        </div>
                        <span
                            class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                            <span x-text="filteredData.length"></span> data
                        </span>
                    </div>

                    <div x-show="filteredData.length === 0"
                        class="flex flex-col items-center justify-center py-20 text-center">
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-3xl border-2 border-violet-100 bg-gradient-to-br from-violet-50 to-purple-100 mb-5">
                            <x-heroicon-o-document-magnifying-glass class="h-10 w-10 text-violet-300" />
                        </div>
                        <p class="text-base font-extrabold text-gray-800">Tidak ada pengajuan</p>
                        <p class="mt-2 text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
                    </div>

                    <div x-show="filteredData.length > 0" class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Mahasiswa</th>
                                    <th class="px-6 py-4">Pilihan 1</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                <template x-for="(item, index) in paginatedData" :key="item.id">
                                    <tr class="group transition-colors hover:bg-violet-50/30">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-violet-300 group-hover:bg-violet-50 group-hover:text-violet-700 transition-all"
                                                x-text="(currentPage - 1) * perPage + index + 1">
                                            </span>
                                        </td>

                                        {{-- Mahasiswa --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-xs font-black text-white shadow-sm"
                                                    x-text="item.mahasiswa.charAt(0).toUpperCase()">
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800" x-text="item.mahasiswa"></p>
                                                    <p class="text-xs text-gray-400" x-text="item.nim"></p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Pilihan 1 --}}
                                        <td class="max-w-[250px] px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800 line-clamp-1"
                                                x-text="item.judul1"></p>
                                            <p class="text-xs text-gray-400 mt-0.5" x-text="item.dosen1"></p>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1.5 text-xs font-black"
                                                :class="{
                                                    'border-yellow-200 bg-yellow-100 text-yellow-700': item
                                                        .status_raw === 'pending' || !item.status_raw,
                                                    'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                        .status_raw === 'disetujui',
                                                    'border-red-200 bg-red-100 text-red-700': item
                                                        .status_raw === 'ditolak',
                                                    'border-violet-200 bg-violet-100 text-violet-700': item
                                                        .is_ditetapkan
                                                }">
                                                <span class="h-1.5 w-1.5 rounded-full"
                                                    :class="{
                                                        'bg-yellow-500 animate-pulse': item
                                                            .status_raw === 'pending' || !item.status_raw,
                                                        'bg-emerald-500': item.status_raw === 'disetujui',
                                                        'bg-red-500': item.status_raw === 'ditolak',
                                                        'bg-violet-500': item.is_ditetapkan
                                                    }">
                                                </span>
                                                <span x-text="item.status_display"></span>
                                            </span>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 text-center">
                                            <button @click="openModal(item)"
                                                class="inline-flex items-center gap-1.5 rounded-xl border-2 border-violet-300 bg-violet-600 px-3.5 py-2 text-xs font-black text-white shadow-sm transition hover:bg-violet-700 hover:shadow-md">
                                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                                Detail
                                            </button>
                                        </td>

                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div x-show="totalPages > 1 && filteredData.length > 0"
                        class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                        <p class="text-xs font-semibold text-gray-500">
                            Halaman <span class="font-black text-gray-800" x-text="currentPage"></span>
                            dari <span class="font-black text-gray-800" x-text="totalPages"></span>
                        </p>
                        <div class="flex items-center gap-1.5">
                            <button @click="currentPage--; paginate()" x-bind:disabled="currentPage === 1"
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 disabled:opacity-40 disabled:cursor-not-allowed">
                                <x-heroicon-o-chevron-left class="h-4 w-4" />
                            </button>
                            <template x-for="page in totalPages" :key="page">
                                <button @click="currentPage = page; paginate()"
                                    x-bind:class="currentPage === page ?
                                        'bg-violet-600 text-white border-violet-600 shadow-sm' :
                                        'bg-white text-gray-500 border-gray-200 hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700'"
                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 text-xs font-black transition"
                                    x-text="page">
                                </button>
                            </template>
                            <button @click="currentPage++; paginate()" x-bind:disabled="currentPage === totalPages"
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 disabled:opacity-40 disabled:cursor-not-allowed">
                                <x-heroicon-o-chevron-right class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                </div>
                {{-- ===== JUDUL POPULER ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-fire class="h-3 w-3" />
                        Judul Terpopuler
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-violet-200 bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-star class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Judul dengan Peminat Terbanyak</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @forelse ($judulPopuler as $index => $judul)
                            <div
                                class="flex items-start gap-4 rounded-xl border-2 border-gray-100 bg-gray-50 p-4 transition hover:border-violet-200 hover:bg-violet-50/30">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                                {{ $index === 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : ($index === 1 ? 'bg-gradient-to-br from-gray-400 to-gray-500' : ($index === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700' : 'bg-gradient-to-br from-violet-500 to-purple-600')) }}
                                text-sm font-black text-white shadow-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-black text-gray-800 leading-relaxed">{{ $judul->nama_judul }}</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <x-heroicon-o-academic-cap class="h-3.5 w-3.5" />
                                            {{ $judul->dosen->name ?? '-' }}
                                        </span>
                                        <span>·</span>
                                        <span class="flex items-center gap-1">
                                            <x-heroicon-o-building-office class="h-3.5 w-3.5" />
                                            {{ $judul->laboratorium->nama ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="shrink-0 text-center">
                                    <p class="text-2xl font-black text-violet-600">{{ $judul->total_peminat }}</p>
                                    <p class="text-xs font-bold text-gray-400">Peminat</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-sm text-gray-400">Belum ada data</div>
                        @endforelse
                    </div>
                </div>

                {{-- ===== STATISTIK PER LAB ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-building-office class="h-3 w-3" />
                        Per Laboratorium
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-sky-200 bg-gradient-to-r from-sky-600 to-blue-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-building-office class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Statistik Per Laboratorium</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">Laboratorium</th>
                                    <th class="px-6 py-4 text-center">Total Judul</th>
                                    <th class="px-6 py-4 text-center">Tersedia</th>
                                    <th class="px-6 py-4 text-center">Draft</th>
                                    <th class="px-6 py-4 text-center">Total Peminat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($statsPerLab as $stat)
                                    <tr class="group transition-colors hover:bg-sky-50/30">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-blue-600 text-[10px] font-black text-white">
                                                    {{ strtoupper(substr($stat['nama'], 0, 2)) }}
                                                </div>
                                                <p class="font-bold text-gray-800">{{ $stat['nama'] }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-700">
                                                {{ $stat['total_judul'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-sky-200 bg-sky-100 text-xs font-black text-sky-700">
                                                {{ $stat['tersedia'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-yellow-200 bg-yellow-100 text-xs font-black text-yellow-700">
                                                {{ $stat['draft'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-violet-200 bg-violet-100 text-xs font-black text-violet-700">
                                                {{ $stat['total_peminat'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== STATISTIK PER DOSEN ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-academic-cap class="h-3 w-3" />
                        Per Dosen
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-academic-cap class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Top 10 Dosen (Berdasarkan Jumlah Judul)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">Dosen</th>
                                    <th class="px-6 py-4 text-center">Total Judul</th>
                                    <th class="px-6 py-4 text-center">Tersedia</th>
                                    <th class="px-6 py-4 text-center">Total Peminat</th>
                                    <th class="px-6 py-4 text-center">Ditetapkan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($statsPerDosen as $index => $stat)
                                    <tr class="group transition-colors hover:bg-emerald-50/30">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-xs font-black text-white shadow-sm">
                                                    {{ strtoupper(substr($stat['nama'], 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $stat['nama'] }}</p>
                                                    @if (!empty($stat['nip']))
                                                        <p class="text-xs text-gray-400">{{ $stat['nip'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-700">
                                                {{ $stat['total_judul'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-sky-200 bg-sky-100 text-xs font-black text-sky-700">
                                                {{ $stat['tersedia'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-violet-200 bg-violet-100 text-xs font-black text-violet-700">
                                                {{ $stat['total_peminat'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-emerald-200 bg-emerald-100 text-xs font-black text-emerald-700">
                                                {{ $stat['ditetapkan'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- ===== MODAL ===== --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-200 bg-white shadow-2xl">

                {{-- Modal Header --}}
                <div
                    class="sticky top-0 z-10 flex items-center justify-between border-b-4 border-violet-200 bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Detail Pengajuan</h3>
                    </div>
                    <button @click="showModal = false"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 text-white transition hover:bg-white/30">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">

                    {{-- Info Mahasiswa --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Mahasiswa</p>
                            <p class="font-black text-gray-800" x-text="selectedItem.mahasiswa"></p>
                            <p class="text-xs text-gray-500 mt-0.5" x-text="selectedItem.nim"></p>
                        </div>
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Status</p>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1 text-xs font-black"
                                :class="{
                                    'border-yellow-200 bg-yellow-100 text-yellow-700': selectedItem
                                        .status_raw === 'pending' || !selectedItem.status_raw,
                                    'border-emerald-200 bg-emerald-100 text-emerald-700': selectedItem
                                        .status_raw === 'disetujui',
                                    'border-red-200 bg-red-100 text-red-700': selectedItem.status_raw === 'ditolak',
                                    'border-violet-200 bg-violet-100 text-violet-700': selectedItem.is_ditetapkan
                                }">
                                <span class="h-1.5 w-1.5 rounded-full"
                                    :class="{
                                        'bg-yellow-500 animate-pulse': selectedItem.status_raw === 'pending' || !
                                            selectedItem.status_raw,
                                        'bg-emerald-500': selectedItem.status_raw === 'disetujui',
                                        'bg-red-500': selectedItem.status_raw === 'ditolak',
                                        'bg-violet-500': selectedItem.is_ditetapkan
                                    }">
                                </span>
                                <span x-text="selectedItem.status_display"></span>
                            </span>
                        </div>
                    </div>

                    {{-- 3 Pilihan --}}
                    <div class="space-y-3">
                        <div class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-emerald-500 mb-2">Pilihan 1</p>
                            <p class="font-black text-gray-800 leading-relaxed" x-text="selectedItem.judul1"></p>
                            <p class="text-xs text-gray-500 mt-1" x-text="selectedItem.dosen1"></p>
                        </div>

                        <div x-show="selectedItem.judul2" class="rounded-xl border-2 border-sky-200 bg-sky-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-sky-500 mb-2">Pilihan 2</p>
                            <p class="font-black text-gray-800 leading-relaxed" x-text="selectedItem.judul2"></p>
                            <p class="text-xs text-gray-500 mt-1" x-text="selectedItem.dosen2"></p>
                        </div>

                        <div x-show="selectedItem.judul3"
                            class="rounded-xl border-2 border-violet-200 bg-violet-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-violet-500 mb-2">Pilihan 3</p>
                            <p class="font-black text-gray-800 leading-relaxed" x-text="selectedItem.judul3"></p>
                            <p class="text-xs text-gray-500 mt-1" x-text="selectedItem.dosen3"></p>
                        </div>
                    </div>

                    {{-- Judul Ditetapkan --}}
                    <div x-show="selectedItem.judul_ditetapkan"
                        class="rounded-xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50 to-green-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-emerald-500 mb-2">✓ Judul
                            Ditetapkan</p>
                        <p class="font-black text-gray-800 leading-relaxed" x-text="selectedItem.judul_ditetapkan">
                        </p>
                        <p class="text-xs text-gray-500 mt-1" x-text="selectedItem.dosen_ditetapkan"></p>
                    </div>

                    {{-- Info View-Only --}}
                    <div class="rounded-xl border-2 border-violet-200 bg-violet-50 p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-information-circle class="h-5 w-5 shrink-0 text-violet-500 mt-0.5" />
                            <div>
                                <p class="text-sm font-black text-violet-800">Mode Monitoring</p>
                                <p class="text-xs text-violet-600 mt-1">
                                    Halaman ini hanya untuk monitoring. Approval dilakukan oleh Kepala Lab.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Close --}}
                    <div class="border-t-2 border-gray-100 pt-4">
                        <button @click="showModal = false"
                            class="w-full rounded-xl border-2 border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- ✅ closing x-data --}}

    <script>
        function monitoringPage() {
            return {
                allData: @json($pengajuanJson),
                filteredData: [],
                paginatedData: [],
                search: '',
                filterStatus: 'all',
                showModal: false,
                selectedItem: {},
                currentPage: 1,
                perPage: 10,
                totalPages: 1,

                init() {
                    this.filterData();
                },

                filterData() {
                    let result = this.allData;

                    if (this.search) {
                        const q = this.search.toLowerCase();
                        result = result.filter(item =>
                            item.mahasiswa.toLowerCase().includes(q) ||
                            item.nim.toLowerCase().includes(q) ||
                            item.judul1.toLowerCase().includes(q)
                        );
                    }

                    if (this.filterStatus === 'pending') {
                        result = result.filter(item => item.status_raw === 'pending' || !item.status_raw);
                    } else if (this.filterStatus === 'disetujui') {
                        result = result.filter(item => item.status_raw === 'disetujui');
                    } else if (this.filterStatus === 'ditolak') {
                        result = result.filter(item => item.status_raw === 'ditolak');
                    } else if (this.filterStatus === 'ditetapkan') {
                        result = result.filter(item => item.is_ditetapkan);
                    }

                    this.filteredData = result;
                    this.currentPage = 1;
                    this.paginate();
                },

                paginate() {
                    this.totalPages = Math.ceil(this.filteredData.length / this.perPage) || 1;
                    if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;
                    const start = (this.currentPage - 1) * this.perPage;
                    this.paginatedData = this.filteredData.slice(start, start + this.perPage);
                },

                openModal(item) {
                    this.selectedItem = item;
                    this.showModal = true;
                }
            }
        }
    </script>


</x-layout-prodi>
