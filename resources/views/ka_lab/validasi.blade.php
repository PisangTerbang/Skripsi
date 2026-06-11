<x-layout-kalab>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="validasiPage()" x-init="init()">
        <div class="space-y-6">

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
                    <button @click="show = false"
                        class="ml-auto rounded-lg p-1 text-red-400 hover:bg-red-100 transition">
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                    </button>
                </div>
            @endif

            {{-- ===== STATS ===== --}}
            @php
                $pctValidasi = $totalJudul > 0 ? round(($divalidasi / $totalJudul) * 100) : 0;
                $pctDitolak = $totalJudul > 0 ? round(($ditolak / $totalJudul) * 100) : 0;
            @endphp

            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Ringkasan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-br from-sky-600 via-sky-700 to-blue-800 p-4 shadow-lg transition hover:-translate-y-0.5">
                    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                    <div class="relative">
                        <x-heroicon-o-document-text class="h-6 w-6 text-sky-200 mb-2" />
                        <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Total</p>
                        <p class="mt-1 text-3xl font-black text-white">{{ $totalJudul }}</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-4 shadow-lg transition hover:-translate-y-0.5">
                    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                    <div class="relative">
                        <x-heroicon-o-clock class="h-6 w-6 text-yellow-100 mb-2" />
                        <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Pending</p>
                        <p class="mt-1 text-3xl font-black text-white">{{ $judulPending->count() }}</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                    <div class="relative">
                        <x-heroicon-o-check-circle class="h-6 w-6 text-emerald-100 mb-2" />
                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-100">Divalidasi</p>
                        <p class="mt-1 text-3xl font-black text-white">{{ $divalidasi }}</p>
                        <p class="text-[10px] text-emerald-200 mt-0.5">{{ $pctValidasi }}% dari total</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                    <div class="relative">
                        <x-heroicon-o-x-circle class="h-6 w-6 text-red-100 mb-2" />
                        <p class="text-xs font-bold uppercase tracking-widest text-red-100">Ditolak</p>
                        <p class="mt-1 text-3xl font-black text-white">{{ $ditolak }}</p>
                        <p class="text-[10px] text-red-200 mt-0.5">{{ $pctDitolak }}% dari total</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 via-violet-600 to-purple-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                    <div class="relative">
                        <x-heroicon-o-users class="h-6 w-6 text-violet-100 mb-2" />
                        <p class="text-xs font-bold uppercase tracking-widest text-violet-100">Peminat</p>
                        <p class="mt-1 text-3xl font-black text-white">{{ $totalPeminat }}</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-500 via-indigo-600 to-blue-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                    <div class="relative">
                        <x-heroicon-o-user-group class="h-6 w-6 text-indigo-100 mb-2" />
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-100">Ditetapkan</p>
                        <p class="mt-1 text-3xl font-black text-white">{{ $totalDitetapkan }}</p>
                    </div>
                </div>

            </div>

            {{-- ===== FILTER + TABLE ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-table-cells class="h-3 w-3" />
                    Daftar Judul
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Filter Toolbar --}}
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                    @foreach ([
        'pending' => ['label' => 'Pending', 'count' => $judulPending->count()],
        'divalidasi' => ['label' => 'Divalidasi', 'count' => $divalidasi],
        'ditolak' => ['label' => 'Ditolak', 'count' => $ditolak],
        'all' => ['label' => 'Semua', 'count' => $totalJudul],
    ] as $val => $cfg)
                        <button type="button" @click="filterStatus = '{{ $val }}'; filterData()"
                            x-bind:class="filterStatus === '{{ $val }}'
                                ?
                                '{{ $val === 'divalidasi' ? 'bg-emerald-600' : ($val === 'ditolak' ? 'bg-red-600' : ($val === 'pending' ? 'bg-yellow-500' : 'bg-sky-600')) }} text-white shadow-sm' :
                                'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                            class="flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs font-bold transition-all">
                            {{ $cfg['label'] }}
                            <span class="rounded-full px-1.5 py-0.5 text-xs font-black"
                                x-bind:class="filterStatus === '{{ $val }}' ? 'bg-white/25 text-white' :
                                    'bg-gray-100 text-gray-500'">
                                {{ $cfg['count'] }}
                            </span>
                        </button>
                        @if (!$loop->last)
                            <div class="h-5 w-px bg-gray-200"></div>
                        @endif
                    @endforeach
                </div>

                <div
                    class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                    <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                    <input type="text" x-model="search" @input="filterData()"
                        placeholder="Cari judul, dosen, kode..."
                        class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                    <template x-if="search !== ''">
                        <button @click="search = ''; filterData()" class="text-gray-400 hover:text-gray-600 transition">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    </template>
                </div>

                <div
                    class="flex items-center gap-1.5 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm text-xs font-bold text-gray-600">
                    <span x-text="filteredData.length"></span>
                    <span>judul</span>
                </div>
            </div>
            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                <div
                    class="flex items-center justify-between border-b-4 border-yellow-200 bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Daftar Judul</h2>
                            <p class="text-xs text-yellow-100">
                                Filter: <span
                                    x-text="filterStatus === 'pending' ? 'Menunggu Validasi' : (filterStatus === 'divalidasi' ? 'Divalidasi' : (filterStatus === 'ditolak' ? 'Ditolak' : 'Semua'))"></span>
                            </p>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        <span x-text="filteredData.length"></span> data
                    </span>
                </div>

                <div x-show="filteredData.length === 0"
                    class="flex flex-col items-center justify-center py-24 text-center">
                    <div
                        class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-yellow-100 bg-gradient-to-br from-yellow-50 to-orange-100 mb-6">
                        <x-heroicon-o-document-magnifying-glass class="h-12 w-12 text-yellow-300" />
                    </div>
                    <p class="text-lg font-extrabold text-gray-800">Tidak ada judul</p>
                    <p class="mt-2 text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
                </div>

                <div x-show="filteredData.length > 0" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr
                                class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4 whitespace-nowrap">Kode</th>
                                <th class="px-6 py-4 min-w-[200px]">Judul</th>
                                <th class="px-6 py-4">Dosen</th>
                                <th class="px-6 py-4 text-center">Peminat</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-100">
                            <template x-for="(item, index) in paginatedData" :key="item.id">
                                <tr class="group transition-colors hover:bg-yellow-50/30">

                                    {{-- No --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="h-9 w-1.5 rounded-full"
                                                x-bind:class="{
                                                    'bg-gradient-to-b from-yellow-400 to-orange-500': item
                                                        .status === 'pending_kalab',
                                                    'bg-gradient-to-b from-emerald-400 to-green-500': item
                                                        .status === 'ditawarkan',
                                                    'bg-gradient-to-b from-red-400 to-rose-500': item
                                                        .status === 'ditolak_kalab'
                                                }">
                                            </div>
                                            <span
                                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-yellow-300 group-hover:bg-yellow-50 group-hover:text-yellow-700 transition-all"
                                                x-text="(currentPage - 1) * perPage + index + 1">
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Kode --}}
                                    <td class="px-6 py-4">
                                        <span
                                            class="rounded-lg border-2 border-gray-200 bg-gray-50 px-2.5 py-1 font-mono text-xs font-black text-gray-600 whitespace-nowrap"
                                            x-text="item.kode">
                                        </span>
                                    </td>

                                    {{-- Judul --}}
                                    <td class="px-6 py-4 min-w-[200px]">
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                            x-text="item.nama_judul"></p>
                                        <p class="mt-0.5 text-xs text-gray-400" x-text="item.lab"></p>
                                    </td>

                                    {{-- Dosen --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-[10px] font-black text-white"
                                                x-text="item.dosen.charAt(0).toUpperCase()">
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700 line-clamp-1"
                                                x-text="item.dosen"></span>
                                        </div>
                                    </td>

                                    {{-- Peminat --}}
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-violet-200 bg-violet-100 text-xs font-black text-violet-700"
                                            x-text="item.total_peminat">
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1.5 text-xs font-black"
                                            x-bind:class="{
                                                'border-yellow-200 bg-yellow-100 text-yellow-700': item
                                                    .status === 'pending_kalab',
                                                'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                    .status === 'ditawarkan',
                                                'border-red-200 bg-red-100 text-red-700': item
                                                    .status === 'ditolak_kalab'
                                            }">
                                            <span class="h-1.5 w-1.5 rounded-full"
                                                x-bind:class="{
                                                    'bg-yellow-500 animate-pulse': item.status === 'pending_kalab',
                                                    'bg-emerald-500': item.status === 'ditawarkan',
                                                    'bg-red-500': item.status === 'ditolak_kalab'
                                                }">
                                            </span>
                                            <span x-text="item.status_label"></span>
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        <button @click="openModal(item)"
                                            class="inline-flex items-center gap-1.5 rounded-xl border-2 border-yellow-300 bg-yellow-500 px-3.5 py-2 text-xs font-black text-white shadow-sm transition hover:bg-yellow-600 hover:shadow-md">
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
                        <button @click="currentPage--" x-bind:disabled="currentPage === 1"
                            class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700 disabled:opacity-40 disabled:cursor-not-allowed">
                            <x-heroicon-o-chevron-left class="h-4 w-4" />
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button @click="currentPage = page"
                                x-bind:class="currentPage === page ?
                                    'bg-yellow-500 text-white border-yellow-500 shadow-sm' :
                                    'bg-white text-gray-500 border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700'"
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 text-xs font-black transition"
                                x-text="page">
                            </button>
                        </template>
                        <button @click="currentPage++" x-bind:disabled="currentPage === totalPages"
                            class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700 disabled:opacity-40 disabled:cursor-not-allowed">
                            <x-heroicon-o-chevron-right class="h-4 w-4" />
                        </button>
                    </div>
                </div>

                {{-- Table Footer --}}
                <div x-show="filteredData.length > 0 && totalPages <= 1"
                    class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                    <p class="text-xs font-semibold text-gray-500">
                        Total <span class="font-black text-gray-800" x-text="filteredData.length"></span> judul
                    </p>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="flex items-center gap-1.5 font-bold text-yellow-600">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                            {{ $judulPending->count() }} pending
                        </span>
                        <div class="h-4 w-px bg-gray-300"></div>
                        <span class="flex items-center gap-1.5 font-bold text-emerald-600">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            {{ $divalidasi }} divalidasi
                        </span>
                        <div class="h-4 w-px bg-gray-300"></div>
                        <span class="flex items-center gap-1.5 font-bold text-red-500">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            {{ $ditolak }} ditolak
                        </span>
                    </div>
                </div>

            </div>

        </div>


        {{-- ✅ MODAL di dalam x-data scope --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-200 bg-white shadow-2xl">

                {{-- Modal Header --}}
                <div
                    class="sticky top-0 z-10 flex items-center justify-between border-b-4 border-yellow-200 bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">Detail & Validasi Judul</h3>
                            <p class="text-xs text-yellow-100" x-text="selectedItem.kode"></p>
                        </div>
                    </div>
                    <button @click="showModal = false"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 text-white transition hover:bg-white/30">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5">

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Kode</p>
                            <p class="font-mono font-black text-gray-800" x-text="selectedItem.kode"></p>
                        </div>
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Laboratorium</p>
                            <p class="font-bold text-gray-800" x-text="selectedItem.lab"></p>
                        </div>
                    </div>

                    {{-- Judul --}}
                    <div class="rounded-xl border-2 border-sky-200 bg-sky-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-sky-500 mb-2">Judul</p>
                        <p class="font-black text-gray-900 leading-relaxed" x-text="selectedItem.nama_judul"></p>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-700 leading-relaxed" x-text="selectedItem.deskripsi || '-'"></p>
                    </div>

                    {{-- Dosen & Skills --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Dosen Pembimbing
                            </p>
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-xs font-black text-white"
                                    x-text="selectedItem.dosen ? selectedItem.dosen.charAt(0).toUpperCase() : 'D'">
                                </div>
                                <p class="text-sm font-bold text-gray-800" x-text="selectedItem.dosen"></p>
                            </div>
                        </div>
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Skills Relevan
                            </p>
                            <p class="text-sm text-gray-700 mt-1" x-text="selectedItem.skills || '-'"></p>
                        </div>
                    </div>

                    {{-- Statistik --}}
                    <div class="flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Statistik</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Total Peminat --}}
                        <div class="rounded-xl border-2 border-violet-200 bg-violet-50 p-4 text-center">
                            <p class="text-4xl font-black text-violet-700" x-text="selectedItem.total_peminat"></p>
                            <p class="text-xs font-bold text-violet-500 mt-1">Total Peminat</p>
                        </div>

                        {{-- Mahasiswa Ditetapkan --}}
                        <template x-if="selectedItem.mahasiswa_ditetapkan">
                            <div class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-emerald-500 mb-2">Mahasiswa
                                    Ditetapkan</p>
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-green-600 text-sm font-black text-white shadow-sm"
                                        x-text="selectedItem.mahasiswa_ditetapkan.nama.charAt(0).toUpperCase()">
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800"
                                            x-text="selectedItem.mahasiswa_ditetapkan.nama"></p>
                                        <p class="text-xs text-gray-500"
                                            x-text="selectedItem.mahasiswa_ditetapkan.nim"></p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 truncate mb-2"
                                    x-text="selectedItem.mahasiswa_ditetapkan.email"></p>
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 px-2.5 py-1 text-[10px] font-black"
                                    x-bind:class="{
                                        'border-emerald-200 bg-emerald-100 text-emerald-700': selectedItem
                                            .mahasiswa_ditetapkan.status === 'disetujui',
                                        'border-yellow-200 bg-yellow-100 text-yellow-700': !selectedItem
                                            .mahasiswa_ditetapkan.status || selectedItem.mahasiswa_ditetapkan
                                            .status === 'pending',
                                        'border-red-200 bg-red-100 text-red-700': selectedItem.mahasiswa_ditetapkan
                                            .status === 'ditolak'
                                    }">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                        x-bind:class="{
                                            'bg-emerald-500': selectedItem.mahasiswa_ditetapkan.status === 'disetujui',
                                            'bg-yellow-500 animate-pulse': !selectedItem.mahasiswa_ditetapkan.status ||
                                                selectedItem.mahasiswa_ditetapkan.status === 'pending',
                                            'bg-red-500': selectedItem.mahasiswa_ditetapkan.status === 'ditolak'
                                        }">
                                    </span>
                                    <span
                                        x-text="selectedItem.mahasiswa_ditetapkan.status === 'disetujui'
                    ? 'Final Disetujui'
                    : (selectedItem.mahasiswa_ditetapkan.status === 'ditolak'
                        ? 'Ditolak Kaprodi'
                        : 'Menunggu Kaprodi')">
                                    </span>
                                </span>
                            </div>
                        </template>

                        {{-- Belum ada yang ditetapkan --}}
                        <template x-if="!selectedItem.mahasiswa_ditetapkan">
                            <div
                                class="rounded-xl border-2 border-gray-200 bg-gray-50 p-4 flex flex-col items-center justify-center text-center">
                                <x-heroicon-o-user class="h-8 w-8 text-gray-300 mb-2" />
                                <p class="text-xs font-bold text-gray-400">Belum ada mahasiswa</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">yang ditetapkan</p>
                            </div>
                        </template>
                    </div>


                    {{-- Form Validasi (hanya pending) --}}
                    <div x-show="selectedItem.status === 'pending_kalab'">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-px flex-1 bg-gray-200"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Keputusan
                                Validasi</span>
                            <div class="h-px flex-1 bg-gray-200"></div>
                        </div>

                        <form method="POST" x-bind:action="'/ka-lab/validasi/' + selectedItem.id + '/approve'"
                            class="space-y-3 mb-4">
                            @csrf
                            <textarea name="catatan_kalab" rows="2" placeholder="Catatan validasi (opsional)..."
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 resize-none transition">
                        </textarea>
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-4 py-3 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                                <x-heroicon-o-check-circle class="h-5 w-5" />
                                Validasi & Tawarkan ke Mahasiswa
                            </button>
                        </form>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t-2 border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-3 text-xs font-bold text-gray-400">atau</span>
                            </div>
                        </div>

                        <form method="POST" x-bind:action="'/ka-lab/validasi/' + selectedItem.id + '/reject'"
                            class="space-y-3">
                            @csrf
                            <textarea name="catatan_kalab" required rows="2" placeholder="Alasan penolakan (wajib)..."
                                class="w-full rounded-xl border-2 border-red-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 resize-none transition">
                        </textarea>
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-4 py-3 text-sm font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                                <x-heroicon-o-x-circle class="h-5 w-5" />
                                Tolak & Kembalikan ke Dosen
                            </button>
                        </form>
                    </div>

                    {{-- Info sudah divalidasi --}}
                    <div x-show="selectedItem.status !== 'pending_kalab'">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-px flex-1 bg-gray-200"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Status
                                Validasi</span>
                            <div class="h-px flex-1 bg-gray-200"></div>
                        </div>
                        <div class="rounded-xl border-2 p-4"
                            x-bind:class="selectedItem.status === 'ditawarkan' ?
                                'border-emerald-200 bg-emerald-50' :
                                'border-red-200 bg-red-50'">
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1.5 text-xs font-black"
                                    x-bind:class="selectedItem.status === 'ditawarkan' ?
                                        'border-emerald-200 bg-emerald-100 text-emerald-700' :
                                        'border-red-200 bg-red-100 text-red-700'">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                        x-bind:class="selectedItem.status === 'ditawarkan' ? 'bg-emerald-500' : 'bg-red-500'">
                                    </span>
                                    <span
                                        x-text="selectedItem.status === 'ditawarkan' ? 'Sudah Divalidasi' : 'Ditolak'"></span>
                                </span>
                            </div>
                            <p class="text-sm font-semibold"
                                x-bind:class="selectedItem.status === 'ditawarkan' ? 'text-emerald-700' : 'text-red-700'"
                                x-text="selectedItem.status === 'ditawarkan'
                                ? 'Judul sudah divalidasi dan ditawarkan ke mahasiswa.'
                                : 'Judul ditolak dan dikembalikan ke dosen.'">
                            </p>
                            <div x-show="selectedItem.catatan_kalab" class="mt-3 pt-3 border-t"
                                x-bind:class="selectedItem.status === 'ditawarkan' ? 'border-emerald-200' : 'border-red-200'">
                                <p class="text-xs font-bold uppercase tracking-widest mb-1"
                                    x-bind:class="selectedItem.status === 'ditawarkan' ? 'text-emerald-500' : 'text-red-500'">
                                    Catatan Anda
                                </p>
                                <p class="text-sm italic"
                                    x-bind:class="selectedItem.status === 'ditawarkan' ? 'text-emerald-700' : 'text-red-700'"
                                    x-text="selectedItem.catatan_kalab">
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- ✅ closing x-data --}}

    <script>
        function validasiPage() {
            return {
                allData: [],
                filteredData: [],
                paginatedData: [],
                search: '',
                filterStatus: 'pending',
                showModal: false,
                selectedItem: {},
                currentPage: 1,
                perPage: 10,
                totalPages: 1,

                init() {
                    this.allData = [
                        ...@json($judulPendingJson),
                        ...@json($judulSelesaiJson)
                    ];
                    this.filterData();
                    this.$watch('currentPage', () => this.paginate());
                },

                filterData() {
                    let result = this.allData;
                    if (this.search) {
                        const q = this.search.toLowerCase();
                        result = result.filter(item =>
                            item.nama_judul.toLowerCase().includes(q) ||
                            item.dosen.toLowerCase().includes(q) ||
                            item.kode.toLowerCase().includes(q)
                        );
                    }
                    if (this.filterStatus === 'pending') {
                        result = result.filter(item => item.status === 'pending_kalab');
                    } else if (this.filterStatus === 'divalidasi') {
                        result = result.filter(item => item.status === 'ditawarkan');
                    } else if (this.filterStatus === 'ditolak') {
                        result = result.filter(item => item.status === 'ditolak_kalab');
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

</x-layout-kalab>
