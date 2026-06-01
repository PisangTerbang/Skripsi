<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="riwayatPage()" x-init="init()" class="space-y-6">

        {{-- ===== HEADER BANNER ===== --}}
        <div
            class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-7 shadow-xl">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative flex items-center justify-between gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Mahasiswa</p>
                    <h2 class="mt-1 text-2xl font-black text-white">Riwayat Pengajuan</h2>
                    <p class="mt-1 text-sm text-indigo-200">Lihat semua pengajuan judul skripsi Anda</p>
                </div>
                <div class="hidden lg:flex shrink-0 gap-3">
                    <div
                        class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total</p>
                        <p class="mt-1 text-4xl font-black text-white">{{ $pengajuan->count() }}</p>
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

        @php
            $totalPengajuan = $pengajuan->count();
            $totalPending = $pengajuan->where('status', 'pending')->count();
            $totalDisetujui = $pengajuan->where('status', 'disetujui')->count();
            $totalDitolak = $pengajuan->where('status', 'ditolak')->count();
        @endphp

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div
                class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Menunggu Review</p>
                        <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalPending }}</p>
                        <p class="mt-2 text-xs font-medium text-yellow-100">sedang diproses</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                        <x-heroicon-o-clock class="h-5 w-5 text-white" />
                    </div>
                </div>
                <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                    <div class="h-full {{ $totalPending > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                    </div>
                </div>
            </div>

            <div
                class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Disetujui</p>
                        <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalDisetujui }}</p>
                        <p class="mt-2 text-xs font-medium text-emerald-200">pengajuan berhasil</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                    </div>
                </div>
                <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                    @php $pctDisetujui = $totalPengajuan > 0 ? round(($totalDisetujui / $totalPengajuan) * 100) : 0; @endphp
                    <div class="h-full rounded-full bg-white/60 transition-all duration-700"
                        style="width: {{ $pctDisetujui }}%"></div>
                </div>
            </div>

            <div
                class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                        <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalDitolak }}</p>
                        <p class="mt-2 text-xs font-medium text-red-200">perlu pengajuan ulang</p>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                        <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                    </div>
                </div>
                <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                    <div class="h-full w-full rounded-full bg-white/60"></div>
                </div>
            </div>

        </div>

        {{-- ===== FILTER ===== --}}
        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
            <span
                class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                <x-heroicon-o-clock class="h-3 w-3" />
                Timeline
            </span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                @foreach ([
        'all' => 'Semua',
        'pending' => 'Pending',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
    ] as $val => $label)
                    <button type="button" @click="filterStatus = '{{ $val }}'; applyFilter()"
                        x-bind:class="filterStatus === '{{ $val }}'
                            ?
                            '{{ $val === 'ditolak' ? 'bg-red-600' : ($val === 'pending' ? 'bg-yellow-500' : ($val === 'disetujui' ? 'bg-emerald-600' : 'bg-indigo-600')) }} text-white shadow-sm' :
                            'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                        class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all">
                        {{ $label }}
                    </button>
                    @if (!$loop->last)
                        <div class="h-5 w-px bg-gray-200"></div>
                    @endif
                @endforeach
            </div>

            <select x-model="sortBy" @change="applyFilter()"
                class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-indigo-400 focus:outline-none transition">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="priority">Prioritas</option>
            </select>

            <div
                class="flex items-center gap-1.5 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm text-xs font-bold text-gray-600">
                <span x-text="filteredData.length"></span>
                <span>dari {{ $pengajuan->count() }}</span>
            </div>
        </div>

        {{-- ===== TIMELINE ===== --}}
        <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

            <div
                class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-clock class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Timeline Pengajuan</h3>
                </div>
                <span class="rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                    <span x-text="filteredData.length"></span> pengajuan
                </span>
            </div>

            <template x-if="filteredData.length === 0">
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <div
                        class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-purple-100 shadow-inner mb-6">
                        <x-heroicon-o-document-text class="h-12 w-12 text-indigo-300" />
                    </div>
                    <p class="text-lg font-extrabold text-gray-800">
                        <span x-show="filterStatus === 'all'">Belum ada pengajuan</span>
                        <span x-show="filterStatus !== 'all'">Tidak ada pengajuan dengan status ini</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-400">
                        <span x-show="filterStatus === 'all'">Mulai ajukan judul skripsi Anda</span>
                        <span x-show="filterStatus !== 'all'">Coba ubah filter untuk melihat pengajuan lain</span>
                    </p>
                    <a href="{{ route('mahasiswa.pengajuan') }}" x-show="filterStatus === 'all'"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-indigo-700">
                        <x-heroicon-o-plus class="h-4 w-4" />
                        Ajukan Judul Sekarang
                    </a>
                </div>
            </template>

            <template x-if="filteredData.length > 0">
                <div class="p-6">
                    <div class="relative">
                        <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-300 to-gray-200">
                        </div>
                        <div class="space-y-6">
                            <template x-for="(item, index) in filteredData" :key="item.id">
                                <div class="relative pl-16">

                                    {{-- Timeline Dot --}}
                                    <div class="absolute left-3 top-5 flex h-5 w-5 items-center justify-center rounded-full border-4 border-white shadow-md"
                                        x-bind:class="{
                                            'bg-yellow-500': item.status === 'pending',
                                            'bg-emerald-500': item.status === 'disetujui',
                                            'bg-red-500': item.status === 'ditolak'
                                        }">
                                        <span x-show="item.status === 'pending'"
                                            class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span>
                                    </div>

                                    {{-- Card --}}
                                    <div class="overflow-hidden rounded-2xl border-2 transition hover:shadow-md"
                                        x-bind:class="{
                                            'border-yellow-200 hover:border-yellow-300': item.status === 'pending',
                                            'border-emerald-200 hover:border-emerald-300': item.status === 'disetujui',
                                            'border-red-200 hover:border-red-300': item.status === 'ditolak'
                                        }">

                                        {{-- Color bar --}}
                                        <div class="h-1.5 w-full"
                                            x-bind:class="{
                                                'bg-gradient-to-r from-yellow-400 to-orange-500': item
                                                    .status === 'pending',
                                                'bg-gradient-to-r from-emerald-500 to-green-500': item
                                                    .status === 'disetujui',
                                                'bg-gradient-to-r from-red-500 to-rose-500': item.status === 'ditolak'
                                            }">
                                        </div>

                                        <div class="p-5">

                                            {{-- Header --}}
                                            <div class="flex items-start justify-between gap-3 mb-4">
                                                <div class="flex-1">
                                                    <div class="flex flex-wrap items-center gap-2 mb-2">
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
                                                                x-text="item.status === 'pending' ? 'Menunggu Review' : item.status === 'disetujui' ? 'Disetujui' : 'Ditolak'"></span>
                                                        </span>
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-400" x-text="item.waktu"></p>
                                                </div>
                                            </div>

                                            {{-- Details --}}
                                            <div class="space-y-3">
                                                {{-- Judul Ditetapkan --}}
                                                <div x-show="item.judul_ditetapkan">
                                                    <div
                                                        class="rounded-xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3">
                                                        <p
                                                            class="text-xs font-black uppercase tracking-widest text-emerald-500 mb-1">
                                                            ✓ Judul Ditetapkan</p>
                                                        <p class="text-sm font-black text-gray-800 leading-relaxed"
                                                            x-text="item.judul_ditetapkan"></p>
                                                    </div>
                                                </div>

                                                {{-- Usulan Judul Mandiri --}}
                                                <div x-show="item.jenis === 'mandiri' && item.judul_mandiri">
                                                    <p
                                                        class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">
                                                        Usulan Judul Mandiri
                                                    </p>
                                                    <div
                                                        class="rounded-xl border-2 border-violet-200 bg-violet-50 p-3">
                                                        <div class="flex items-start gap-2">
                                                            <span
                                                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-600 text-[10px] font-black text-white">M</span>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                                                    x-text="item.judul_mandiri"></p>
                                                                <div x-show="item.deskripsi_mandiri"
                                                                    class="mt-1.5 rounded-lg bg-white border border-violet-100 px-2 py-1">
                                                                    <p class="text-xs text-gray-500 italic"
                                                                        x-text="item.deskripsi_mandiri"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- 3 Pilihan Judul --}}
                                                <div x-show="item.pilihan1 || item.pilihan2 || item.pilihan3">
                                                    <p
                                                        class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">
                                                        Usulan Judul Pilihan</p>
                                                    <div class="space-y-2">

                                                        <div x-show="item.pilihan1"
                                                            class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-3">
                                                            <div class="flex items-start gap-2">
                                                                <span
                                                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-[10px] font-black text-white">1</span>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                                                        x-text="item.pilihan1?.judul"></p>
                                                                    <div
                                                                        class="mt-1 flex flex-wrap gap-2 text-xs text-gray-500">
                                                                        <span x-text="item.pilihan1?.dosen"></span>
                                                                        <span
                                                                            class="rounded-full border border-emerald-200 bg-white px-2 py-0.5 text-[10px] font-black text-emerald-700"
                                                                            x-text="item.pilihan1?.lab"></span>
                                                                    </div>
                                                                    <div x-show="item.pilihan1?.alasan"
                                                                        class="mt-1.5 rounded-lg bg-white border border-emerald-100 px-2 py-1">
                                                                        <p class="text-xs text-gray-500 italic"
                                                                            x-text="`Alasan: ${item.pilihan1?.alasan}`">
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div x-show="item.pilihan2"
                                                            class="rounded-xl border-2 border-sky-200 bg-sky-50 p-3">
                                                            <div class="flex items-start gap-2">
                                                                <span
                                                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-sky-600 text-[10px] font-black text-white">2</span>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                                                        x-text="item.pilihan2?.judul"></p>
                                                                    <div
                                                                        class="mt-1 flex flex-wrap gap-2 text-xs text-gray-500">
                                                                        <span x-text="item.pilihan2?.dosen"></span>
                                                                        <span
                                                                            class="rounded-full border border-sky-200 bg-white px-2 py-0.5 text-[10px] font-black text-sky-700"
                                                                            x-text="item.pilihan2?.lab"></span>
                                                                    </div>
                                                                    <div x-show="item.pilihan2?.alasan"
                                                                        class="mt-1.5 rounded-lg bg-white border border-sky-100 px-2 py-1">
                                                                        <p class="text-xs text-gray-500 italic"
                                                                            x-text="`Alasan: ${item.pilihan2?.alasan}`">
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div x-show="item.pilihan3"
                                                            class="rounded-xl border-2 border-violet-200 bg-violet-50 p-3">
                                                            <div class="flex items-start gap-2">
                                                                <span
                                                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-600 text-[10px] font-black text-white">3</span>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                                                        x-text="item.pilihan3?.judul"></p>
                                                                    <div
                                                                        class="mt-1 flex flex-wrap gap-2 text-xs text-gray-500">
                                                                        <span x-text="item.pilihan3?.dosen"></span>
                                                                        <span
                                                                            class="rounded-full border border-violet-200 bg-white px-2 py-0.5 text-[10px] font-black text-violet-700"
                                                                            x-text="item.pilihan3?.lab"></span>
                                                                    </div>
                                                                    <div x-show="item.pilihan3?.alasan"
                                                                        class="mt-1.5 rounded-lg bg-white border border-violet-100 px-2 py-1">
                                                                        <p class="text-xs text-gray-500 italic"
                                                                            x-text="`Alasan: ${item.pilihan3?.alasan}`">
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>



                                                {{-- Progress Review --}}
                                                <div
                                                    class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3 space-y-2">
                                                    <p
                                                        class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">
                                                        Progress Review</p>

                                                    <div class="flex items-center justify-between">
                                                        <span
                                                            class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                                            <span class="h-1.5 w-1.5 rounded-full"
                                                                x-bind:class="{
                                                                    'bg-emerald-500': item
                                                                        .status_kalab === 'disetujui',
                                                                    'bg-red-500': item.status_kalab === 'ditolak',
                                                                    'bg-yellow-500 animate-pulse': !item.status_kalab
                                                                }">
                                                            </span>
                                                            Ka Lab
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-black"
                                                            x-bind:class="{
                                                                'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                                    .status_kalab === 'disetujui',
                                                                'border-red-200 bg-red-100 text-red-700': item
                                                                    .status_kalab === 'ditolak',
                                                                'border-yellow-200 bg-yellow-100 text-yellow-700': !item
                                                                    .status_kalab
                                                            }"
                                                            x-text="item.status_kalab === 'disetujui' ? 'Disetujui' : (item.status_kalab === 'ditolak' ? 'Ditolak' : 'Menunggu')">
                                                        </span>
                                                    </div>
                                                    <div x-show="item.catatan_kalab" class="ml-3">
                                                        <p class="text-xs italic text-gray-500"
                                                            x-text="`&quot;${item.catatan_kalab}&quot;`"></p>
                                                    </div>

                                                    <div class="flex items-center justify-between">
                                                        <span
                                                            class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                                            <span class="h-1.5 w-1.5 rounded-full"
                                                                x-bind:class="{
                                                                    'bg-emerald-500': item
                                                                        .status_kaprodi === 'disetujui',
                                                                    'bg-red-500': item.status_kaprodi === 'ditolak',
                                                                    'bg-gray-400': !item.status_kaprodi && item
                                                                        .status_kalab === 'disetujui',
                                                                    'bg-gray-300': !item.status_kalab
                                                                }">
                                                            </span>
                                                            Kaprodi
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-black"
                                                            x-bind:class="{
                                                                'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                                    .status_kaprodi === 'disetujui',
                                                                'border-red-200 bg-red-100 text-red-700': item
                                                                    .status_kaprodi === 'ditolak',
                                                                'border-gray-200 bg-gray-100 text-gray-500': !item
                                                                    .status_kaprodi
                                                            }"
                                                            x-text="item.status_kaprodi === 'disetujui' ? 'Disetujui' : (item.status_kaprodi === 'ditolak' ? 'Ditolak' : 'Belum')">
                                                        </span>
                                                    </div>
                                                    <div x-show="item.catatan_kaprodi" class="ml-3">
                                                        <p class="text-xs italic text-gray-500"
                                                            x-text="`&quot;${item.catatan_kaprodi}&quot;`"></p>
                                                    </div>

                                                    <div
                                                        class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1">
                                                        <span
                                                            class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                                            <span class="h-1.5 w-1.5 rounded-full"
                                                                x-bind:class="item.sudah_diumumkan ? 'bg-indigo-500' :
                                                                    'bg-gray-300 animate-pulse'">
                                                            </span>
                                                            Pengumuman KoorTA
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-black"
                                                            x-bind:class="item.sudah_diumumkan ?
                                                                'border-indigo-200 bg-indigo-100 text-indigo-700' :
                                                                'border-gray-200 bg-gray-100 text-gray-400'"
                                                            x-text="item.sudah_diumumkan ? 'Sudah Diumumkan' : 'Menunggu'">
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Banner pengumuman sudah dikirim --}}
                                                <div x-show="item.sudah_diumumkan && item.status_kaprodi === 'disetujui'"
                                                    class="rounded-xl border-2 border-emerald-200 bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <x-heroicon-o-megaphone
                                                            class="h-4 w-4 shrink-0 text-emerald-500" />
                                                        <div>
                                                            <p class="text-xs font-black text-emerald-700">Pengumuman
                                                                Resmi Sudah Dikirim</p>
                                                            <p class="text-xs text-emerald-600 mt-0.5">Judul TA Anda
                                                                telah resmi ditetapkan oleh Koordinator TA</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Banner menunggu pengumuman disetujui --}}
                                                <div x-show="!item.sudah_diumumkan && item.status_kaprodi === 'disetujui'"
                                                    class="rounded-xl border-2 border-blue-200 bg-blue-50 px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <x-heroicon-o-clock class="h-4 w-4 shrink-0 text-blue-500" />
                                                        <div>
                                                            <p class="text-xs font-black text-blue-700">Menunggu
                                                                Pengumuman Resmi</p>
                                                            <p class="text-xs text-blue-600 mt-0.5">Disetujui Kaprodi —
                                                                hasil resmi akan diumumkan oleh Koordinator TA</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Banner ditolak menunggu pengumuman --}}
                                                <div x-show="!item.sudah_diumumkan && item.status_kaprodi === 'ditolak'"
                                                    class="rounded-xl border-2 border-orange-200 bg-orange-50 px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <x-heroicon-o-clock class="h-4 w-4 shrink-0 text-orange-500" />
                                                        <div>
                                                            <p class="text-xs font-black text-orange-700">Menunggu
                                                                Pengumuman Resmi</p>
                                                            <p class="text-xs text-orange-600 mt-0.5">Ditolak Kaprodi —
                                                                hasil resmi akan diumumkan oleh Koordinator TA</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            {{-- Action --}}
                                            <div
                                                class="mt-4 flex items-center justify-between border-t-2 border-gray-100 pt-3">
                                                <p class="text-xs text-gray-400" x-text="item.tanggal"></p>
                                                <button @click="openDetail(item)"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border-2 border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-black text-indigo-700 transition hover:bg-indigo-100">
                                                    <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                                    Detail
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Footer --}}
            <template x-if="filteredData.length > 0">
                <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                    <p class="text-xs font-semibold text-gray-500">
                        Menampilkan <span class="font-black text-gray-800" x-text="filteredData.length"></span>
                        dari <span class="font-black text-gray-800">{{ $pengajuan->count() }}</span> pengajuan
                    </p>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="flex items-center gap-1.5 font-bold text-yellow-600">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                            {{ $totalPending }} pending
                        </span>
                        <div class="h-4 w-px bg-gray-300"></div>
                        <span class="flex items-center gap-1.5 font-bold text-emerald-600">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            {{ $totalDisetujui }} disetujui
                        </span>
                        <div class="h-4 w-px bg-gray-300"></div>
                        <span class="flex items-center gap-1.5 font-bold text-red-500">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            {{ $totalDitolak }} ditolak
                        </span>
                    </div>
                </div>
            </template>

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
                    class="sticky top-0 z-10 flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">Detail Pengajuan</h3>
                            <p class="text-xs text-indigo-200" x-text="selectedItem.waktu"></p>
                        </div>
                    </div>
                    <button @click="showModal = false"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 text-white transition hover:bg-white/30">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5">

                    {{-- Status Badges --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1.5 text-xs font-black"
                            x-bind:class="{
                                'border-yellow-200 bg-yellow-100 text-yellow-700': selectedItem.status === 'pending',
                                'border-emerald-200 bg-emerald-100 text-emerald-700': selectedItem
                                    .status === 'disetujui',
                                'border-red-200 bg-red-100 text-red-700': selectedItem.status === 'ditolak'
                            }">
                            <span class="h-1.5 w-1.5 rounded-full"
                                x-bind:class="{
                                    'bg-yellow-500 animate-pulse': selectedItem.status === 'pending',
                                    'bg-emerald-500': selectedItem.status === 'disetujui',
                                    'bg-red-500': selectedItem.status === 'ditolak'
                                }">
                            </span>
                            <span
                                x-text="selectedItem.status === 'pending' ? 'Menunggu Review' : selectedItem.status === 'disetujui' ? 'Disetujui' : 'Ditolak'"></span>
                        </span>
                        <span x-show="selectedItem.sudah_diumumkan"
                            class="inline-flex items-center gap-1 rounded-full border-2 border-indigo-200 bg-indigo-100 px-3 py-1.5 text-xs font-black text-indigo-700">
                            <x-heroicon-o-megaphone class="h-3 w-3" />
                            Sudah Diumumkan
                        </span>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Tanggal</p>
                            <p class="text-sm font-black text-gray-800" x-text="selectedItem.tanggal"></p>
                        </div>
                    </div>

                    {{-- Judul Ditetapkan --}}
                    <div x-show="selectedItem.judul_ditetapkan">
                        <div
                            class="rounded-xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3">
                            <p class="text-xs font-black uppercase tracking-widest text-emerald-500 mb-1">✓ Judul
                                Ditetapkan</p>
                            <p class="text-sm font-black text-gray-800 leading-relaxed"
                                x-text="selectedItem.judul_ditetapkan"></p>
                        </div>
                    </div>

                    {{-- Judul Mandiri --}}
                    <div x-show="selectedItem.judul_mandiri">
                        <div class="rounded-xl border-2 border-violet-200 bg-violet-50 px-4 py-3">
                            <p class="text-xs font-black uppercase tracking-widest text-violet-500 mb-1">Usulan Judul
                                Mandiri</p>
                            <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                x-text="selectedItem.judul_mandiri"></p>
                            <p x-show="selectedItem.deskripsi_mandiri"
                                class="text-xs text-gray-500 mt-1 leading-relaxed"
                                x-text="selectedItem.deskripsi_mandiri"></p>
                        </div>
                    </div>

                    {{-- Semua Pilihan Judul --}}
                    <div x-show="selectedItem.pilihan1 || selectedItem.pilihan2 || selectedItem.pilihan3">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Pilihan Judul</p>
                        <div class="space-y-2">

                            <div x-show="selectedItem.pilihan1"
                                class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-3">
                                <div class="flex items-start gap-2">
                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-[10px] font-black text-white">1</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                            x-text="selectedItem.pilihan1?.judul"></p>
                                        <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-500">
                                            <span x-text="selectedItem.pilihan1?.dosen"></span>
                                            <span
                                                class="rounded-full border border-emerald-200 bg-white px-2 py-0.5 text-[10px] font-black text-emerald-700"
                                                x-text="selectedItem.pilihan1?.lab"></span>
                                            <span class="font-mono text-[10px] text-gray-400"
                                                x-text="selectedItem.pilihan1?.kode"></span>
                                        </div>
                                        <div x-show="selectedItem.pilihan1?.alasan"
                                            class="mt-1.5 rounded-lg bg-white border border-emerald-100 px-2 py-1">
                                            <p class="text-xs text-gray-500 italic"
                                                x-text="`Alasan: ${selectedItem.pilihan1?.alasan}`"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="selectedItem.pilihan2"
                                class="rounded-xl border-2 border-sky-200 bg-sky-50 p-3">
                                <div class="flex items-start gap-2">
                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-sky-600 text-[10px] font-black text-white">2</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                            x-text="selectedItem.pilihan2?.judul"></p>
                                        <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-500">
                                            <span x-text="selectedItem.pilihan2?.dosen"></span>
                                            <span
                                                class="rounded-full border border-sky-200 bg-white px-2 py-0.5 text-[10px] font-black text-sky-700"
                                                x-text="selectedItem.pilihan2?.lab"></span>
                                            <span class="font-mono text-[10px] text-gray-400"
                                                x-text="selectedItem.pilihan2?.kode"></span>
                                        </div>
                                        <div x-show="selectedItem.pilihan2?.alasan"
                                            class="mt-1.5 rounded-lg bg-white border border-sky-100 px-2 py-1">
                                            <p class="text-xs text-gray-500 italic"
                                                x-text="`Alasan: ${selectedItem.pilihan2?.alasan}`"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="selectedItem.pilihan3"
                                class="rounded-xl border-2 border-violet-200 bg-violet-50 p-3">
                                <div class="flex items-start gap-2">
                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-600 text-[10px] font-black text-white">3</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                            x-text="selectedItem.pilihan3?.judul"></p>
                                        <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-500">
                                            <span x-text="selectedItem.pilihan3?.dosen"></span>
                                            <span
                                                class="rounded-full border border-violet-200 bg-white px-2 py-0.5 text-[10px] font-black text-violet-700"
                                                x-text="selectedItem.pilihan3?.lab"></span>
                                            <span class="font-mono text-[10px] text-gray-400"
                                                x-text="selectedItem.pilihan3?.kode"></span>
                                        </div>
                                        <div x-show="selectedItem.pilihan3?.alasan"
                                            class="mt-1.5 rounded-lg bg-white border border-violet-100 px-2 py-1">
                                            <p class="text-xs text-gray-500 italic"
                                                x-text="`Alasan: ${selectedItem.pilihan3?.alasan}`"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Progress Review --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Progress Review</p>
                        <div class="space-y-2 rounded-xl border-2 border-gray-100 bg-gray-50 p-3">

                            {{-- Ka Lab --}}
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-600 flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                        x-bind:class="{
                                            'bg-emerald-500': selectedItem.status_kalab === 'disetujui',
                                            'bg-red-500': selectedItem.status_kalab === 'ditolak',
                                            'bg-yellow-500 animate-pulse': !selectedItem.status_kalab
                                        }">
                                    </span>
                                    Ka Lab
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-black"
                                    x-bind:class="{
                                        'border-emerald-200 bg-emerald-100 text-emerald-700': selectedItem
                                            .status_kalab === 'disetujui',
                                        'border-red-200 bg-red-100 text-red-700': selectedItem
                                            .status_kalab === 'ditolak',
                                        'border-yellow-200 bg-yellow-100 text-yellow-700': !selectedItem.status_kalab
                                    }"
                                    x-text="selectedItem.status_kalab === 'disetujui' ? 'Disetujui' : (selectedItem.status_kalab === 'ditolak' ? 'Ditolak' : 'Menunggu')">
                                </span>
                            </div>
                            <div x-show="selectedItem.catatan_kalab"
                                class="rounded-lg bg-white border border-gray-200 px-3 py-2">
                                <p class="text-xs italic text-gray-500"
                                    x-text="`Catatan: ${selectedItem.catatan_kalab}`"></p>
                            </div>

                            {{-- Kaprodi --}}
                            <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                                <span class="text-xs font-bold text-gray-600 flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                        x-bind:class="{
                                            'bg-emerald-500': selectedItem.status_kaprodi === 'disetujui',
                                            'bg-red-500': selectedItem.status_kaprodi === 'ditolak',
                                            'bg-gray-400': !selectedItem.status_kaprodi
                                        }">
                                    </span>
                                    Kaprodi
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-black"
                                    x-bind:class="{
                                        'border-emerald-200 bg-emerald-100 text-emerald-700': selectedItem
                                            .status_kaprodi === 'disetujui',
                                        'border-red-200 bg-red-100 text-red-700': selectedItem
                                            .status_kaprodi === 'ditolak',
                                        'border-gray-200 bg-gray-100 text-gray-500': !selectedItem.status_kaprodi
                                    }"
                                    x-text="selectedItem.status_kaprodi === 'disetujui' ? 'Disetujui' : (selectedItem.status_kaprodi === 'ditolak' ? 'Ditolak' : 'Belum')">
                                </span>
                            </div>
                            <div x-show="selectedItem.catatan_kaprodi"
                                class="rounded-lg bg-white border border-gray-200 px-3 py-2">
                                <p class="text-xs italic text-gray-500"
                                    x-text="`Catatan: ${selectedItem.catatan_kaprodi}`"></p>
                            </div>

                            {{-- Pengumuman --}}
                            <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                                <span class="text-xs font-bold text-gray-600 flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                        x-bind:class="selectedItem.sudah_diumumkan ? 'bg-indigo-500' : 'bg-gray-300 animate-pulse'">
                                    </span>
                                    Pengumuman KoorTA
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 rounded-full border px-2.5 py-1 text-xs font-black"
                                    x-bind:class="selectedItem.sudah_diumumkan ?
                                        'border-indigo-200 bg-indigo-100 text-indigo-700' :
                                        'border-gray-200 bg-gray-100 text-gray-400'"
                                    x-text="selectedItem.sudah_diumumkan ? 'Sudah Diumumkan' : 'Menunggu'">
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- Banner kontekstual --}}
                    <div x-show="selectedItem.sudah_diumumkan && selectedItem.status_kaprodi === 'disetujui'"
                        class="rounded-xl border-2 border-emerald-200 bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-megaphone class="h-4 w-4 shrink-0 text-emerald-500" />
                            <div>
                                <p class="text-xs font-black text-emerald-700">Pengumuman Resmi Sudah Dikirim</p>
                                <p class="text-xs text-emerald-600 mt-0.5">Judul TA Anda telah resmi ditetapkan oleh
                                    Koordinator TA</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="!selectedItem.sudah_diumumkan && selectedItem.status_kaprodi === 'disetujui'"
                        class="rounded-xl border-2 border-blue-200 bg-blue-50 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="h-4 w-4 shrink-0 text-blue-500" />
                            <div>
                                <p class="text-xs font-black text-blue-700">Menunggu Pengumuman Resmi</p>
                                <p class="text-xs text-blue-600 mt-0.5">Disetujui Kaprodi — hasil resmi akan diumumkan
                                    oleh Koordinator TA</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="!selectedItem.sudah_diumumkan && selectedItem.status_kaprodi === 'ditolak'"
                        class="rounded-xl border-2 border-orange-200 bg-orange-50 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="h-4 w-4 shrink-0 text-orange-500" />
                            <div>
                                <p class="text-xs font-black text-orange-700">Menunggu Pengumuman Resmi</p>
                                <p class="text-xs text-orange-600 mt-0.5">Ditolak Kaprodi — hasil resmi akan diumumkan
                                    oleh Koordinator TA</p>
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

    </div>

    {{-- ✅ Data embed di luar modal dan di luar x-data div --}}
    <script id="riwayat-data" type="application/json">
        {!! json_encode($pengajuanJson) !!}
    </script>

    @push('scripts')
        <script>
            function riwayatPage() {
                return {
                    filterStatus: 'all',
                    sortBy: 'newest',
                    showModal: false,
                    selectedItem: {},
                    allData: [],
                    filteredData: [],

                    init() {
                        const el = document.getElementById('riwayat-data');
                        this.allData = el ? JSON.parse(el.textContent) : [];
                        this.applyFilter();
                    },

                    applyFilter() {
                        let result = [...this.allData];

                        if (this.filterStatus !== 'all') {
                            result = result.filter(item => item.status === this.filterStatus);
                        }

                        if (this.sortBy === 'newest') {
                            result.sort((a, b) => b.timestamp - a.timestamp);
                        } else if (this.sortBy === 'oldest') {
                            result.sort((a, b) => a.timestamp - b.timestamp);
                        } else if (this.sortBy === 'priority') {
                            result.sort((a, b) => a.prioritas - b.prioritas);
                        }

                        this.filteredData = result;
                    },

                    openDetail(item) {
                        this.selectedItem = item;
                        this.showModal = true;
                    }
                }
            }
        </script>
    @endpush

</x-layout>
