<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengajuanDosenPage()" x-init="init()">
        <div class="min-h-screen bg-slate-100">
            <div class="px-6 py-6 space-y-6">

                {{-- ===== TOP BAR ===== --}}
                <div class="sticky top-0 z-10 border-b-2 border-emerald-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-emerald-200 bg-emerald-50">
                                <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-emerald-600" />
                            </div>
                            <div class="h-8 w-px bg-gray-200"></div>
                            <div>
                                <h1 class="text-lg font-extrabold text-gray-900">Review Pengajuan Mahasiswa</h1>
                                <p class="mt-0.5 text-xs text-gray-400">Tinjau dan berikan keputusan untuk pengajuan
                                    judul TA</p>
                            </div>
                        </div>
                        @if ($pending > 0)
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-4 py-1.5 text-xs font-black text-yellow-700">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                                {{ $pending }} perlu review
                            </span>
                        @endif
                    </div>
                </div>

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
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-chart-bar class="h-3 w-3" />
                        Ringkasan
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                @php
                    $pctDisetujui = $totalPengajuan > 0 ? round(($disetujui / $totalPengajuan) * 100) : 0;
                    $pctDitolak = $totalPengajuan > 0 ? round(($ditolak / $totalPengajuan) * 100) : 0;
                @endphp

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total Pengajuan
                                </p>
                                <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalPengajuan }}</p>
                                <p class="mt-2 text-xs font-medium text-indigo-200">semua status</p>
                            </div>
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                                <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                            </div>
                        </div>
                        <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full w-full rounded-full bg-white/60"></div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Perlu Review</p>
                                <p class="mt-3 text-5xl font-black leading-none text-white">{{ $pending }}</p>
                                <p class="mt-2 text-xs font-medium text-yellow-100">menunggu keputusan</p>
                            </div>
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                                <x-heroicon-o-clock class="h-5 w-5 text-white" />
                            </div>
                        </div>
                        <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                            <div
                                class="h-full {{ $pending > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                            </div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Disetujui</p>
                                <p class="mt-3 text-5xl font-black leading-none text-white">{{ $disetujui }}</p>
                                <p class="mt-2 text-xs font-medium text-emerald-200">{{ $pctDisetujui }}% dari total</p>
                            </div>
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                                <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                            </div>
                        </div>
                        <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full rounded-full bg-white/60 transition-all duration-700"
                                style="width: {{ $pctDisetujui }}%"></div>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                        <div class="relative flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                                <p class="mt-3 text-5xl font-black leading-none text-white">{{ $ditolak }}</p>
                                <p class="mt-2 text-xs font-medium text-red-200">{{ $pctDitolak }}% dari total</p>
                            </div>
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                                <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                            </div>
                        </div>
                        <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full rounded-full bg-white/60 transition-all duration-700"
                                style="width: {{ $pctDitolak }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- ===== FILTER ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-table-cells class="h-3 w-3" />
                        Daftar Pengajuan
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Status Filter Pills --}}
                    <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                        @foreach ([
        'all' => ['label' => 'Semua', 'color' => 'emerald'],
        'pending' => ['label' => 'Pending', 'color' => 'yellow'],
        'disetujui' => ['label' => 'Disetujui', 'color' => 'emerald'],
        'ditolak' => ['label' => 'Ditolak', 'color' => 'red'],
    ] as $val => $cfg)
                            <button type="button" @click="filterStatus = '{{ $val }}'; applyFilter()"
                                x-bind:class="filterStatus === '{{ $val }}' ?
                                    '{{ $val === 'ditolak' ? 'bg-red-600' : ($val === 'pending' ? 'bg-yellow-500' : 'bg-emerald-600') }} text-white shadow-sm' :
                                    'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                                class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all">
                                {{ $cfg['label'] }}
                            </button>
                            @if (!$loop->last)
                                <div class="h-5 w-px bg-gray-200"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Jenis Filter --}}
                    <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                        @foreach ([
        'all' => 'Semua Jenis',
        'pilih' => 'Pilih Judul',
        'mandiri' => 'Mandiri',
    ] as $val => $label)
                            <button type="button" @click="filterJenis = '{{ $val }}'; applyFilter()"
                                x-bind:class="filterJenis === '{{ $val }}' ? 'bg-indigo-600 text-white shadow-sm' :
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
                        <input type="text" x-model="searchQuery" @input="applyFilter()"
                            placeholder="Cari mahasiswa atau judul..."
                            class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                        <template x-if="searchQuery !== ''">
                            <button @click="searchQuery = ''; applyFilter()"
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

                {{-- ===== PENGAJUAN LIST ===== --}}
                <div class="space-y-5">
                    <template x-for="group in filteredData" :key="group.judul_id">
                        <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                            {{-- Group Header --}}
                            <div
                                class="border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span x-show="group.jenis === 'mandiri'"
                                                class="inline-flex items-center rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                                Judul Mandiri
                                            </span>
                                            <span x-show="group.jenis === 'pilih'"
                                                class="inline-flex items-center rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                                Pilih Judul
                                            </span>
                                            <span x-show="!group.is_owner"
                                                class="inline-flex items-center rounded-full border-2 border-orange-300/50 bg-orange-400/20 px-3 py-1 text-xs font-black text-orange-200">
                                                Judul Dosen Lain
                                            </span>
                                        </div>
                                        <h3 class="text-base font-extrabold text-white leading-relaxed line-clamp-2"
                                            x-text="group.judul"></h3>
                                        <div class="mt-1.5 flex items-center gap-3">
                                            <span x-show="group.kode" class="font-mono text-xs text-emerald-200"
                                                x-text="'Kode: ' + group.kode"></span>
                                        </div>
                                    </div>
                                    <div x-show="group.pemenang" class="shrink-0">
                                        <div
                                            class="rounded-xl border-2 border-white/30 bg-white/20 px-4 py-2.5 text-center backdrop-blur-sm">
                                            <p class="text-xs font-bold text-emerald-200 uppercase tracking-wide">Sudah
                                                Diambil</p>
                                            <p class="mt-0.5 text-sm font-black text-white" x-text="group.pemenang">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submissions List --}}
                            <div class="divide-y-2 divide-gray-100 p-4">
                                <template x-for="item in group.items" :key="item.id">
                                    <div class="rounded-xl border-2 p-5 mb-3 last:mb-0 transition-all"
                                        x-bind:class="{
                                            'border-yellow-200 bg-yellow-50/40': item.status === 'pending',
                                            'border-emerald-200 bg-emerald-50/40': item.status === 'disetujui',
                                            'border-red-200 bg-red-50/40': item.status === 'ditolak'
                                        }">

                                        {{-- Item Header --}}
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-black text-white shadow-sm ring-2"
                                                    x-bind:class="{
                                                        'bg-gradient-to-br from-yellow-400 to-orange-500 ring-yellow-200': item
                                                            .status === 'pending',
                                                        'bg-gradient-to-br from-emerald-500 to-green-600 ring-emerald-200': item
                                                            .status === 'disetujui',
                                                        'bg-gradient-to-br from-red-500 to-rose-600 ring-red-200': item
                                                            .status === 'ditolak'
                                                    }"
                                                    x-text="item.mahasiswa.charAt(0).toUpperCase()">
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-black text-gray-800" x-text="item.mahasiswa">
                                                        </p>
                                                        <span
                                                            class="inline-flex items-center gap-1.5 rounded-full border-2 px-2.5 py-1 text-xs font-black"
                                                            x-bind:class="{
                                                                'border-yellow-200 bg-yellow-100 text-yellow-700': item
                                                                    .status === 'pending',
                                                                'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                                    .status === 'disetujui',
                                                                'border-red-200 bg-red-100 text-red-700': item
                                                                    .status === 'ditolak'
                                                            }">
                                                            <span class="h-1.5 w-1.5 rounded-full"
                                                                x-bind:class="{
                                                                    'bg-yellow-500 animate-pulse': item
                                                                        .status === 'pending',
                                                                    'bg-emerald-500': item.status === 'disetujui',
                                                                    'bg-red-500': item.status === 'ditolak'
                                                                }">
                                                            </span>
                                                            <span
                                                                x-text="item.status === 'pending' ? 'Pending' : item.status === 'disetujui' ? 'Disetujui' : 'Ditolak'"></span>
                                                        </span>
                                                    </div>
                                                    <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-400">
                                                        <span x-show="item.prioritas">Pilihan ke-<span class="font-black"
                                                                x-text="item.prioritas"></span></span>
                                                        <span x-show="item.prioritas === 1"
                                                            class="font-black text-blue-600">(Utama)</span>
                                                        <span x-show="item.prioritas">•</span>
                                                        <span x-text="item.waktu"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Alasan --}}
                                        <div x-show="item.alasan" class="mb-3">
                                            <div class="rounded-xl border-2 border-blue-200 bg-blue-50 px-4 py-3">
                                                <p
                                                    class="text-xs font-black uppercase tracking-widest text-blue-500 mb-1.5">
                                                    Alasan Mahasiswa</p>
                                                <p class="text-sm text-blue-900 leading-relaxed" x-text="item.alasan">
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Catatan Dosen --}}
                                        <div x-show="item.catatan_dosen" class="mb-3">
                                            <div class="rounded-xl border-2 px-4 py-3"
                                                x-bind:class="{
                                                    'border-emerald-200 bg-emerald-50': item.status === 'disetujui',
                                                    'border-red-200 bg-red-50': item.status === 'ditolak'
                                                }">
                                                <p class="text-xs font-black uppercase tracking-widest mb-1.5"
                                                    x-bind:class="{
                                                        'text-emerald-500': item.status === 'disetujui',
                                                        'text-red-500': item.status === 'ditolak'
                                                    }">
                                                    Catatan Dosen
                                                </p>
                                                <p class="text-sm italic leading-relaxed"
                                                    x-bind:class="{
                                                        'text-emerald-800': item.status === 'disetujui',
                                                        'text-red-800': item.status === 'ditolak'
                                                    }"
                                                    x-text="item.catatan_dosen">
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Status Kaprodi --}}
                                        <div x-show="item.status === 'disetujui'" class="mb-3">
                                            <div class="flex items-center gap-2 rounded-xl border-2 px-4 py-3"
                                                x-bind:class="{
                                                    'border-violet-200 bg-violet-50': item
                                                        .status_kaprodi === 'pending' || item
                                                        .status_kaprodi === '',
                                                    'border-emerald-200 bg-emerald-50': item
                                                        .status_kaprodi === 'disetujui',
                                                    'border-red-200 bg-red-50': item.status_kaprodi === 'ditolak'
                                                }">
                                                <x-heroicon-o-academic-cap class="h-4 w-4 shrink-0 text-violet-500" />
                                                <div>
                                                    <p class="text-xs font-black uppercase tracking-widest mb-0.5"
                                                        x-bind:class="{
                                                            'text-violet-500': item.status_kaprodi === 'pending' ||
                                                                item.status_kaprodi === '',
                                                            'text-emerald-500': item.status_kaprodi === 'disetujui',
                                                            'text-red-500': item.status_kaprodi === 'ditolak'
                                                        }">
                                                        Status Kaprodi
                                                    </p>
                                                    <p class="text-sm font-semibold"
                                                        x-bind:class="{
                                                            'text-violet-700': item.status_kaprodi === 'pending' ||
                                                                item.status_kaprodi === '',
                                                            'text-emerald-700': item.status_kaprodi === 'disetujui',
                                                            'text-red-700': item.status_kaprodi === 'ditolak'
                                                        }"
                                                        x-text="item.status_kaprodi === 'disetujui' ? 'Disetujui — Mahasiswa Boleh Mulai' : (item.status_kaprodi === 'ditolak' ? 'Ditolak oleh Kaprodi' : 'Menunggu Persetujuan Final Kaprodi')">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- View-only: dosen memantau; keputusan ada di Ka Lab → Prodi --}}
                                        <div x-show="item.status === 'pending'"
                                            class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 px-4 py-3 text-center">
                                            <p class="text-xs font-semibold text-gray-400">
                                                Menunggu keputusan Ka Lab &amp; Prodi
                                            </p>
                                        </div>

                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>

                    {{-- Empty State --}}
                    <template x-if="filteredData.length === 0">
                        <div class="flex flex-col items-center justify-center py-24 text-center">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-emerald-100 bg-gradient-to-br from-emerald-50 to-teal-100 shadow-inner mb-6">
                                <x-heroicon-o-clipboard-document-list class="h-12 w-12 text-emerald-300" />
                            </div>
                            <p class="text-lg font-extrabold text-gray-800">Tidak ada pengajuan</p>
                            <p class="mt-2 text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
                        </div>
                    </template>
                </div>{{-- end space-y-5 --}}

            </div>{{-- end px-6 py-6 --}}
        </div>{{-- end min-h-screen --}}

    </div>{{-- ✅ closing x-data --}}

    <script id="pengajuan-dosen-data" type="application/json">
    {!! json_encode($pengajuanJson) !!}
</script>

    @push('scripts')
        <script>
            function pengajuanDosenPage() {
                return {
                    searchQuery: '',
                    filterStatus: 'all',
                    filterJenis: 'all',
                    allData: [],
                    filteredData: [],

                    init() {
                        const el = document.getElementById('pengajuan-dosen-data');
                        this.allData = el ? JSON.parse(el.textContent) : [];
                        this.applyFilter();
                    },

                    applyFilter() {
                        let result = this.allData;

                        if (this.searchQuery) {
                            const q = this.searchQuery.toLowerCase();
                            result = result.map(group => {
                                const filtered = group.items.filter(item =>
                                    item.mahasiswa.toLowerCase().includes(q) ||
                                    item.judul_text.toLowerCase().includes(q)
                                );
                                return filtered.length > 0 ? {
                                    ...group,
                                    items: filtered
                                } : null;
                            }).filter(g => g !== null);
                        }

                        if (this.filterStatus !== 'all') {
                            result = result.map(group => {
                                const filtered = group.items.filter(item => item.status === this.filterStatus);
                                return filtered.length > 0 ? {
                                    ...group,
                                    items: filtered
                                } : null;
                            }).filter(g => g !== null);
                        }

                        if (this.filterJenis !== 'all') {
                            result = result.filter(group => group.jenis === this.filterJenis);
                        }

                        this.filteredData = result;
                    }
                }
            }
        </script>
    @endpush

</x-layout-dosen>
