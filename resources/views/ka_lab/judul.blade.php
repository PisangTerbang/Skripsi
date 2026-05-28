<x-layout-kalab>
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

                {{-- ===== HEADER BANNER ===== --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-br from-sky-600 via-sky-700 to-blue-800 p-7 shadow-xl">
                    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
                    <div class="relative flex items-center justify-between gap-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-300">Kepala Laboratorium</p>
                            <h2 class="mt-1 text-2xl font-black text-white">Monitoring Judul TA</h2>
                            <p class="mt-1 text-sm text-sky-200">Pantau semua judul yang terdaftar di laboratorium</p>
                        </div>
                        <div class="hidden lg:flex shrink-0 gap-3">
                            <div
                                class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-3 text-center backdrop-blur-sm">
                                <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Total Judul</p>
                                <p class="mt-1 text-4xl font-black text-white">{{ $totalJudul }}</p>
                            </div>
                            <div
                                class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-3 text-center backdrop-blur-sm">
                                <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Peminat</p>
                                <p class="mt-1 text-4xl font-black text-white">{{ $totalPeminat }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== STATS ===== --}}
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
                        class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 to-blue-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document-text class="h-6 w-6 text-indigo-200 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $totalJudul }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-gray-300 bg-gradient-to-br from-gray-500 to-gray-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document class="h-6 w-6 text-gray-200 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-200">Draft</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $judulDraft }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 to-orange-500 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-clock class="h-6 w-6 text-yellow-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Pending</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $pendingKalab }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 to-green-600 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-check-badge class="h-6 w-6 text-emerald-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-100">Ditawarkan</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $ditawarkan }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 to-rose-600 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-x-circle class="h-6 w-6 text-red-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-red-100">Ditolak</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $ditolakKalab }}</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 to-purple-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-users class="h-6 w-6 text-violet-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-100">Peminat</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $totalPeminat }}</p>
                        </div>
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

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($judulPerLab as $labId => $stats)
                        @php $lab = $laboratorium->firstWhere('id', $labId); @endphp
                        <div
                            class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="border-b-2 border-sky-200 bg-gradient-to-r from-sky-600 to-blue-700 px-4 py-3">
                                <p class="text-xs font-black text-white truncate">{{ $lab->nama ?? 'N/A' }}</p>
                            </div>
                            <div class="grid grid-cols-3 divide-x-2 divide-gray-100 p-0">
                                <div class="px-3 py-3 text-center">
                                    <p class="text-xl font-black text-gray-800">{{ $stats['total'] }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400 mt-0.5">Total
                                    </p>
                                </div>
                                <div class="px-3 py-3 text-center">
                                    <p class="text-xl font-black text-emerald-600">{{ $stats['tersedia'] }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400 mt-0.5">
                                        Tersedia</p>
                                </div>
                                <div class="px-3 py-3 text-center">
                                    <p class="text-xl font-black text-violet-600">{{ $stats['peminat'] }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400 mt-0.5">
                                        Peminat</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

                    {{-- Status Filter Pills --}}
                    <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                        @foreach ([
        'all' => 'Semua',
        'draft' => 'Draft',
        'pending_kalab' => 'Pending',
        'ditawarkan' => 'Ditawarkan',
        'ditolak_kalab' => 'Ditolak',
    ] as $val => $label)
                            <button type="button" @click="filterStatusJudul = '{{ $val }}'; filterData()"
                                x-bind:class="filterStatusJudul === '{{ $val }}'
                                    ?
                                    '{{ $val === 'ditolak_kalab' ? 'bg-red-600' : ($val === 'pending_kalab' ? 'bg-yellow-500' : ($val === 'ditawarkan' ? 'bg-emerald-600' : ($val === 'draft' ? 'bg-gray-600' : 'bg-sky-600'))) }} text-white shadow-sm' :
                                    'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                                class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all">
                                {{ $label }}
                            </button>
                            @if (!$loop->last)
                                <div class="h-5 w-px bg-gray-200"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Lab Filter --}}
                    <select x-model="filterLab" @change="filterData()"
                        class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-sky-400 focus:outline-none transition">
                        <option value="all">Semua Lab</option>
                        @foreach ($laboratorium as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                        @endforeach
                    </select>

                    {{-- Search --}}
                    <div
                        class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                        <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                        <input type="text" x-model="search" @input="filterData()"
                            placeholder="Cari judul, dosen, kode..."
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
                        <span>judul</span>
                    </div>

                </div>

                {{-- Table Card --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    {{-- Card Header --}}
                    <div
                        class="flex items-center justify-between border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 via-sky-600 to-blue-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">Daftar Judul</h2>
                                <p class="text-xs text-sky-200">Monitoring semua judul TA</p>
                            </div>
                        </div>
                        <span
                            class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                            <span x-text="filteredData.length"></span> data
                        </span>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="filteredData.length === 0"
                        class="flex flex-col items-center justify-center py-24 text-center">
                        <div
                            class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-sky-100 bg-gradient-to-br from-sky-50 to-blue-100 mb-6">
                            <x-heroicon-o-document-magnifying-glass class="h-12 w-12 text-sky-300" />
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Tidak ada judul</p>
                        <p class="mt-2 text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
                    </div>

                    {{-- Table --}}
                    <div x-show="filteredData.length > 0" class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4 whitespace-nowrap">Kode</th>
                                    <th class="px-6 py-4 min-w-[200px]">Judul</th>
                                    <th class="px-6 py-4">Dosen</th>
                                    <th class="px-6 py-4">Lab</th>
                                    <th class="px-6 py-4 text-center">Peminat</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                <template x-for="(item, index) in paginatedData" :key="item.id">
                                    <tr class="group transition-colors hover:bg-sky-50/30">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="h-9 w-1.5 rounded-full"
                                                    x-bind:class="{
                                                        'bg-gradient-to-b from-gray-400 to-gray-500': item
                                                            .status_judul === 'draft',
                                                        'bg-gradient-to-b from-yellow-400 to-orange-500': item
                                                            .status_judul === 'pending_kalab',
                                                        'bg-gradient-to-b from-emerald-400 to-green-500': item
                                                            .status_judul === 'ditawarkan',
                                                        'bg-gradient-to-b from-red-400 to-rose-500': item
                                                            .status_judul === 'ditolak_kalab'
                                                    }">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-sky-300 group-hover:bg-sky-50 group-hover:text-sky-700 transition-all"
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
                                                x-text="item.judul">
                                            </p>
                                        </td>


                                        {{-- Dosen --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-[10px] font-black text-white"
                                                    x-text="item.dosen.charAt(0).toUpperCase()">
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700 line-clamp-1"
                                                    x-text="item.dosen">
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Lab --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center rounded-full border-2 border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-black text-sky-700"
                                                x-text="item.lab">
                                            </span>
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
                                                    'border-gray-200 bg-gray-100 text-gray-600': item
                                                        .status_judul === 'draft',
                                                    'border-yellow-200 bg-yellow-100 text-yellow-700': item
                                                        .status_judul === 'pending_kalab',
                                                    'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                        .status_judul === 'ditawarkan',
                                                    'border-red-200 bg-red-100 text-red-700': item
                                                        .status_judul === 'ditolak_kalab'
                                                }">
                                                <span class="h-1.5 w-1.5 rounded-full"
                                                    x-bind:class="{
                                                        'bg-gray-400': item.status_judul === 'draft',
                                                        'bg-yellow-500 animate-pulse': item
                                                            .status_judul === 'pending_kalab',
                                                        'bg-emerald-500': item.status_judul === 'ditawarkan',
                                                        'bg-red-500': item.status_judul === 'ditolak_kalab'
                                                    }">
                                                </span>
                                                <span x-text="item.status_judul_label"></span>
                                            </span>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 text-center">
                                            <button @click="openModal(item)"
                                                class="inline-flex items-center gap-1.5 rounded-xl border-2 border-sky-300 bg-sky-600 px-3.5 py-2 text-xs font-black text-white shadow-sm transition hover:bg-sky-700 hover:shadow-md">
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
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 disabled:opacity-40 disabled:cursor-not-allowed">
                                <x-heroicon-o-chevron-left class="h-4 w-4" />
                            </button>
                            <template x-for="page in totalPages" :key="page">
                                <button @click="currentPage = page"
                                    x-bind:class="currentPage === page ?
                                        'bg-sky-600 text-white border-sky-600 shadow-sm' :
                                        'bg-white text-gray-500 border-gray-200 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700'"
                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 text-xs font-black transition"
                                    x-text="page">
                                </button>
                            </template>
                            <button @click="currentPage++" x-bind:disabled="currentPage === totalPages"
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-xs font-black text-gray-500 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 disabled:opacity-40 disabled:cursor-not-allowed">
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
                    </div>

                </div>

            </div>
        </div>
        {{-- ===== MODAL DETAIL ===== --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-200 bg-white shadow-2xl">

                {{-- Modal Header --}}
                <div
                    class="sticky top-0 z-10 flex items-center justify-between border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 to-blue-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">Detail Judul</h3>
                            <p class="text-xs text-sky-200" x-text="selectedItem.kode"></p>
                        </div>
                    </div>
                    <button @click="showModal = false"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 text-white transition hover:bg-white/30">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5">

                    {{-- Status Badge --}}
                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1.5 text-xs font-black"
                            x-bind:class="{
                                'border-gray-200 bg-gray-100 text-gray-600': selectedItem.status_judul === 'draft',
                                'border-yellow-200 bg-yellow-100 text-yellow-700': selectedItem
                                    .status_judul === 'pending_kalab',
                                'border-emerald-200 bg-emerald-100 text-emerald-700': selectedItem
                                    .status_judul === 'ditawarkan',
                                'border-red-200 bg-red-100 text-red-700': selectedItem.status_judul === 'ditolak_kalab'
                            }">
                            <span class="h-1.5 w-1.5 rounded-full"
                                x-bind:class="{
                                    'bg-gray-400': selectedItem.status_judul === 'draft',
                                    'bg-yellow-500 animate-pulse': selectedItem.status_judul === 'pending_kalab',
                                    'bg-emerald-500': selectedItem.status_judul === 'ditawarkan',
                                    'bg-red-500': selectedItem.status_judul === 'ditolak_kalab'
                                }">
                            </span>
                            <span x-text="selectedItem.status_judul_label"></span>
                        </span>
                    </div>

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
                        <p class="font-black text-gray-900 leading-relaxed" x-text="selectedItem.judul"></p>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-700 leading-relaxed" x-text="selectedItem.deskripsi || '-'"></p>
                    </div>

                    {{-- Dosen --}}
                    <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Dosen Pembimbing</p>
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-xs font-black text-white"
                                x-text="selectedItem.dosen ? selectedItem.dosen.charAt(0).toUpperCase() : 'D'">
                            </div>
                            <p class="text-sm font-bold text-gray-800" x-text="selectedItem.dosen"></p>
                        </div>
                    </div>

                    {{-- Statistik --}}
                    <div class="flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Statistik</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div class="rounded-xl border-2 border-violet-200 bg-violet-50 p-4 text-center">
                            <p class="text-4xl font-black text-violet-700" x-text="selectedItem.total_peminat"></p>
                            <p class="text-xs font-bold text-violet-500 mt-1">Total Peminat</p>
                        </div>
                    </div>

                    {{-- Kuota --}}
                    <div x-show="selectedItem.kuota_maksimal"
                        class="rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-1">Kuota
                                    Maksimal</p>
                                <p class="text-3xl font-black text-blue-700" x-text="selectedItem.kuota_maksimal"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-1">Sisa Kuota
                                </p>
                                <p class="text-3xl font-black text-blue-700"
                                    x-text="Math.max(0, selectedItem.kuota_maksimal - selectedItem.jumlah_ditetapkan)">
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Info Status --}}
                    <div x-show="selectedItem.status_judul === 'draft'"
                        class="rounded-xl border-2 border-gray-200 bg-gray-50 p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-document class="h-5 w-5 shrink-0 text-gray-500 mt-0.5" />
                            <div>
                                <p class="text-sm font-black text-gray-700">Draft</p>
                                <p class="text-xs text-gray-500 mt-1">Judul masih dalam tahap draft dari dosen</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="selectedItem.status_judul === 'pending_kalab'"
                        class="rounded-xl border-2 border-yellow-200 bg-yellow-50 p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-clock class="h-5 w-5 shrink-0 text-yellow-500 mt-0.5" />
                            <div>
                                <p class="text-sm font-black text-yellow-700">Menunggu Validasi</p>
                                <p class="text-xs text-yellow-600 mt-1">Judul sedang dalam proses validasi oleh Kepala
                                    Lab</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="selectedItem.status_judul === 'ditawarkan'"
                        class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-check-badge class="h-5 w-5 shrink-0 text-emerald-500 mt-0.5" />
                            <div>
                                <p class="text-sm font-black text-emerald-700">Disetujui & Ditawarkan</p>
                                <p class="text-xs text-emerald-600 mt-1">Judul telah divalidasi dan dapat dipilih
                                    mahasiswa</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="selectedItem.status_judul === 'ditolak_kalab'"
                        class="rounded-xl border-2 border-red-200 bg-red-50 p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-x-circle class="h-5 w-5 shrink-0 text-red-500 mt-0.5" />
                            <div class="flex-1">
                                <p class="text-sm font-black text-red-700">Ditolak</p>
                                <p class="text-xs text-red-600 mt-1">Judul perlu diperbaiki sesuai catatan</p>
                                <div x-show="selectedItem.catatan_penolakan_kalab"
                                    class="mt-2 rounded-lg border border-red-200 bg-white px-3 py-2">
                                    <p class="text-xs font-bold text-red-500 mb-1">Catatan:</p>
                                    <p class="text-xs text-red-700 italic"
                                        x-text="selectedItem.catatan_penolakan_kalab"></p>
                                </div>
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
                allData: @json($judulsJson),
                filteredData: [],
                paginatedData: [],
                search: '',
                filterLab: 'all',
                filterStatusJudul: 'all',
                showModal: false,
                selectedItem: {},
                currentPage: 1,
                perPage: 10,
                totalPages: 1,

                init() {
                    this.filterData();
                    this.$watch('currentPage', () => this.paginate());
                },

                filterData() {
                    let result = this.allData;

                    if (this.search) {
                        const q = this.search.toLowerCase();
                        result = result.filter(item =>
                            item.judul.toLowerCase().includes(q) ||
                            item.dosen.toLowerCase().includes(q) ||
                            item.kode.toLowerCase().includes(q)
                        );
                    }

                    if (this.filterLab !== 'all') {
                        result = result.filter(item => item.lab_id == this.filterLab);
                    }

                    if (this.filterStatusJudul !== 'all') {
                        result = result.filter(item => item.status_judul === this.filterStatusJudul);
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
