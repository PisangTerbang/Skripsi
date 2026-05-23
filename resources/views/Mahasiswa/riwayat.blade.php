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

            {{-- Pending --}}
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

            {{-- Disetujui --}}
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

            {{-- Ditolak --}}
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

            {{-- Status Filter Pills --}}
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

            {{-- Sort --}}
            <select x-model="sortBy" @change="applyFilter()"
                class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-indigo-400 focus:outline-none transition">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="priority">Prioritas</option>
            </select>

            {{-- Count --}}
            <div
                class="flex items-center gap-1.5 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm text-xs font-bold text-gray-600">
                <span x-text="filteredData.length"></span>
                <span>dari {{ $pengajuan->count() }}</span>
            </div>

        </div>

        {{-- ===== TIMELINE ===== --}}
        <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

            {{-- Card Header --}}
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

            {{-- Empty State --}}
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

            {{-- Timeline Items --}}
            <template x-if="filteredData.length > 0">
                <div class="p-6">
                    <div class="relative">

                        {{-- Timeline Line --}}
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
                                            class="h-1.5 w-1.5 animate-pulse rounded-full bg-white">
                                        </span>
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
                                                        <span
                                                            class="inline-flex items-center rounded-full border-2 border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-black text-indigo-700">
                                                            Prioritas <span x-text="item.prioritas"
                                                                class="ml-1"></span>
                                                        </span>
                                                        <span x-show="item.jenis === 'mandiri'"
                                                            class="inline-flex items-center rounded-full border-2 border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-black text-violet-700">
                                                            Mandiri
                                                        </span>
                                                    </div>
                                                    <h3 class="text-base font-black text-gray-800 leading-relaxed"
                                                        x-text="item.judul"></h3>
                                                    <p class="mt-1 text-xs text-gray-400" x-text="item.waktu"></p>
                                                </div>
                                            </div>

                                            {{-- Details --}}
                                            <div class="space-y-3">

                                                {{-- Kode --}}
                                                <div x-show="item.kode" class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-gray-500">Kode:</span>
                                                    <span
                                                        class="rounded-lg border-2 border-gray-200 bg-gray-50 px-2 py-0.5 font-mono text-xs font-black text-gray-600"
                                                        x-text="item.kode">
                                                    </span>
                                                </div>

                                                {{-- Lab --}}
                                                <div x-show="item.lab" class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-gray-500">Lab:</span>
                                                    <span
                                                        class="rounded-full border-2 border-indigo-200 bg-indigo-50 px-2.5 py-0.5 text-xs font-black text-indigo-700"
                                                        x-text="item.lab">
                                                    </span>
                                                </div>

                                                {{-- Deskripsi --}}
                                                <div x-show="item.deskripsi">
                                                    <p class="text-xs font-bold text-gray-500 mb-1">Deskripsi:</p>
                                                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-2"
                                                        x-text="item.deskripsi"></p>
                                                </div>

                                                {{-- Alasan --}}
                                                <div x-show="item.alasan">
                                                    <div
                                                        class="rounded-xl border-2 border-blue-200 bg-blue-50 px-4 py-3">
                                                        <p
                                                            class="text-xs font-black uppercase tracking-widest text-blue-500 mb-1">
                                                            Alasan Anda</p>
                                                        <p class="text-sm text-blue-800 leading-relaxed"
                                                            x-text="item.alasan"></p>
                                                    </div>
                                                </div>

                                                {{-- Catatan Dosen --}}
                                                <div x-show="item.catatan_dosen">
                                                    <div class="rounded-xl border-2 px-4 py-3"
                                                        x-bind:class="{
                                                            'border-emerald-200 bg-emerald-50': item
                                                                .status === 'disetujui',
                                                            'border-red-200 bg-red-50': item.status === 'ditolak'
                                                        }">
                                                        <p class="text-xs font-black uppercase tracking-widest mb-1"
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
                        <span
                            class="inline-flex items-center rounded-full border-2 border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-black text-indigo-700">
                            Prioritas <span x-text="selectedItem.prioritas" class="ml-1"></span>
                        </span>
                        <span x-show="selectedItem.jenis === 'mandiri'"
                            class="inline-flex items-center rounded-full border-2 border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-black text-violet-700">
                            Judul Mandiri
                        </span>
                    </div>

                    {{-- Judul --}}
                    <div class="rounded-xl border-2 border-indigo-200 bg-indigo-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-indigo-500 mb-2">Judul</p>
                        <p class="text-lg font-black text-gray-900 leading-relaxed" x-text="selectedItem.judul"></p>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Jenis</p>
                            <p class="text-sm font-black text-gray-800"
                                x-text="selectedItem.jenis === 'mandiri' ? 'Judul Mandiri' : 'Pilih Judul Dosen'">
                            </p>
                        </div>
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Tanggal</p>
                            <p class="text-sm font-black text-gray-800" x-text="selectedItem.tanggal"></p>
                        </div>
                    </div>

                    {{-- Kode --}}
                    <div x-show="selectedItem.kode">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Kode Judul</p>
                        <span
                            class="rounded-lg border-2 border-gray-200 bg-gray-50 px-3 py-1.5 font-mono text-sm font-black text-gray-700"
                            x-text="selectedItem.kode">
                        </span>
                    </div>

                    {{-- Lab --}}
                    <div x-show="selectedItem.lab">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Laboratorium</p>
                        <span
                            class="inline-flex items-center rounded-full border-2 border-indigo-200 bg-indigo-50 px-3 py-1.5 text-sm font-black text-indigo-700"
                            x-text="selectedItem.lab">
                        </span>
                    </div>

                    {{-- Deskripsi --}}
                    <div x-show="selectedItem.deskripsi">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Deskripsi</p>
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                            <p class="text-sm text-gray-700 leading-relaxed" x-text="selectedItem.deskripsi"></p>
                        </div>
                    </div>

                    {{-- Alasan --}}
                    <div x-show="selectedItem.alasan">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Alasan Anda</p>
                        <div class="rounded-xl border-2 border-blue-200 bg-blue-50 px-4 py-3">
                            <p class="text-sm text-blue-800 leading-relaxed" x-text="selectedItem.alasan"></p>
                        </div>
                    </div>

                    {{-- Catatan Dosen --}}
                    <div x-show="selectedItem.catatan_dosen">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Catatan Dosen</p>
                        <div class="rounded-xl border-2 px-4 py-3"
                            x-bind:class="{
                                'border-emerald-200 bg-emerald-50': selectedItem.status === 'disetujui',
                                'border-red-200 bg-red-50': selectedItem.status === 'ditolak',
                                'border-gray-200 bg-gray-50': selectedItem.status === 'pending'
                            }">
                            <p class="text-sm italic leading-relaxed"
                                x-bind:class="{
                                    'text-emerald-800': selectedItem.status === 'disetujui',
                                    'text-red-800': selectedItem.status === 'ditolak',
                                    'text-gray-700': selectedItem.status === 'pending'
                                }"
                                x-text="selectedItem.catatan_dosen">
                            </p>
                        </div>
                    </div>

                    {{-- Close Button --}}
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
