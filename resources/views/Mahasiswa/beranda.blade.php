<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="dashboardMahasiswa()" x-init="init()" class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">



            {{-- ===== WELCOME BANNER ===== --}}
            <div
                class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-7 shadow-xl">
                <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
                <div class="absolute right-32 bottom-4 h-20 w-20 rounded-full bg-white/5"></div>

                <div class="relative flex items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border-2 border-white/30 bg-white/20 text-2xl font-black text-white backdrop-blur-sm shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Mahasiswa</p>
                            <h2 class="mt-1 text-2xl font-black text-white leading-tight">
                                Halo, {{ auth()->user()->name }}! 👋
                            </h2>
                            <p class="mt-1 text-sm text-indigo-200">
                                Pantau progress pengajuan judul skripsi Anda secara realtime
                            </p>
                            <div class="mt-3 flex items-center gap-2">
                                <span
                                    class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                                    <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                    {{ now()->format('H:i') }} WIB
                                </span>
                                @if (auth()->user()->nim)
                                    <span
                                        class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                                        <x-heroicon-o-identification class="h-3.5 w-3.5" />
                                        {{ auth()->user()->nim }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Quick stat --}}
                    <div class="hidden lg:flex shrink-0 flex-col items-end gap-3">
                        <div
                            class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total Pengajuan</p>
                            <p class="mt-1 text-4xl font-black text-white">{{ $total }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== HERO: JUDUL DISETUJUI (hanya muncul setelah pengumuman KoorTA) ===== --}}
            @if ($disetujui && $sudahDiumumkan)
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-7 shadow-xl">
                    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>

                    <div class="relative flex items-start justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                    🎉 Selamat!
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-300/50 bg-emerald-400/20 px-3 py-1 text-xs font-black text-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                    Resmi Diumumkan
                                </span>
                            </div>
                            <h2 class="text-xl font-black text-white mb-2">Judul TA Anda Telah Ditetapkan</h2>
                            <p class="text-base font-bold text-emerald-100 leading-relaxed mb-4">
                                "{{ $disetujui->judulDitetapkan->nama_judul ?? ($disetujui->judulDitetapkan->judul ?? ($disetujui->judul_mandiri ?? '-')) }}"
                            </p>
                            <div class="flex items-center gap-3 text-xs text-emerald-200">
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                    Disetujui {{ $disetujui->updated_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-megaphone class="h-4 w-4" />
                                    Pengumuman sudah dikirim
                                </span>
                            </div>
                            <a href="{{ route('mahasiswa.riwayat') }}"
                                class="mt-5 inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white px-5 py-2.5 text-sm font-black text-emerald-700 shadow-md transition hover:bg-emerald-50">
                                <x-heroicon-o-eye class="h-4 w-4" />
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ===== HERO: MENUNGGU PENGUMUMAN (Kaprodi sudah approve tapi belum diumumkan) ===== --}}
            @elseif ($disetujui && !$sudahDiumumkan)
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-7 shadow-xl">
                    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>

                    <div class="relative flex items-start justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                    ✅ Disetujui Kaprodi
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-300/50 bg-yellow-400/20 px-3 py-1 text-xs font-black text-yellow-200">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-300"></span>
                                    Menunggu Pengumuman
                                </span>
                            </div>
                            <h2 class="text-xl font-black text-white mb-2">Pengajuan Disetujui — Menunggu Pengumuman
                                Resmi</h2>
                            <p class="text-base font-bold text-blue-100 leading-relaxed mb-2">
                                "{{ $disetujui->judulDitetapkan->nama_judul ?? ($disetujui->judulDitetapkan->judul ?? ($disetujui->judul_mandiri ?? '-')) }}"
                            </p>
                            <p class="text-xs text-blue-200 leading-relaxed mb-4">
                                Pengajuan Anda telah disetujui oleh Kaprodi. Hasil resmi akan diumumkan oleh Koordinator
                                TA. Harap menunggu pengumuman.
                            </p>
                            <div class="flex items-center gap-3 text-xs text-blue-200">
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                    Disetujui {{ $disetujui->updated_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-clock class="h-4 w-4" />
                                    Menunggu pengumuman KoorTA
                                </span>
                            </div>
                        </div>
                    </div>
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

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                {{-- Total --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $total }}</p>
                            <p class="mt-2 text-xs font-medium text-indigo-200">semua pengajuan</p>
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

                {{-- Pending --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Menunggu Review</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $pending }}</p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">sedang diproses</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clock class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full {{ $pending > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $ditolak }}</p>
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

            {{-- ===== PROGRESS BAR ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                    </div>
                    <div class="flex-1">
                        <h3 class="font-extrabold text-white">Progress Pengajuan</h3>
                        <p class="text-xs text-indigo-200" x-text="`${progressWidth}% selesai`"></p>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white"
                        x-text="progressWidth + '%'">
                    </span>
                </div>

                <div class="p-6">
                    {{-- Steps --}}
                    <div class="relative mb-6">
                        {{-- Line background --}}
                        <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-200 z-0"></div>
                        {{-- Line progress --}}
                        <div class="absolute top-4 left-4 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 z-0 transition-all duration-700"
                            x-bind:style="`width: calc(${Math.min(progressWidth, 100)}% - 2rem)`">
                        </div>

                        <div class="relative z-10 flex justify-between">
                            @foreach ([['icon' => 'plus', 'label' => 'Ajukan', 'step' => 1], ['icon' => 'clock', 'label' => 'Ka Lab', 'step' => 2], ['icon' => 'eye', 'label' => 'Kaprodi', 'step' => 3], ['icon' => 'megaphone', 'label' => 'Pengumuman', 'step' => 4]] as $s)
                                <div class="flex flex-col items-center gap-2">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full border-2 transition-all duration-300"
                                        x-bind:class="step >= {{ $s['step'] }} ?
                                            'bg-gradient-to-br from-indigo-500 to-purple-600 border-indigo-400 shadow-md shadow-indigo-200' :
                                            'bg-white border-gray-300'">
                                        <x-dynamic-component :component="'heroicon-o-' . $s['icon']"
                                            x-bind:class="step >= {{ $s['step'] }} ? 'text-white' : 'text-gray-400'"
                                            class="h-4 w-4" />
                                    </div>
                                    <span class="text-xs font-bold transition-colors text-center"
                                        x-bind:class="step >= {{ $s['step'] }} ? 'text-indigo-600' : 'text-gray-400'">
                                        {{ $s['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="h-3 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-700 shadow-sm"
                            x-bind:style="`width: ${progressWidth}%`">
                        </div>
                    </div>

                    {{-- Step label --}}
                    <p class="mt-3 text-center text-xs font-semibold text-gray-500" x-text="stepLabel"></p>
                </div>
            </div>

            {{-- ===== SECTION: RIWAYAT + QUICK ACCESS ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-bolt class="h-3 w-3" />
                    Aktivitas & Akses Cepat
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Riwayat Terbaru --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md lg:col-span-2">
                    <div
                        class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-clock class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">Riwayat Pengajuan Terbaru</h3>
                        </div>
                        <a href="{{ route('mahasiswa.riwayat') }}"
                            class="text-xs font-bold text-indigo-200 hover:text-white transition">
                            Lihat Semua →
                        </a>
                    </div>

                    <template x-if="riwayat.length > 0">
                        <div class="divide-y-2 divide-gray-100">
                            <template x-for="item in riwayat" :key="item.judul">
                                <div class="flex items-start gap-4 px-6 py-4 transition"
                                    x-bind:class="item.isNew ? 'bg-yellow-50/60' : 'hover:bg-indigo-50/20'">

                                    {{-- Status indicator --}}
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 text-xs font-black text-white shadow-sm"
                                        x-bind:class="{
                                            'bg-gradient-to-br from-yellow-400 to-orange-500 border-yellow-200': item
                                                .status === 'pending',
                                            'bg-gradient-to-br from-emerald-500 to-green-600 border-emerald-200': item
                                                .status === 'disetujui',
                                            'bg-gradient-to-br from-red-500 to-rose-600 border-red-200': item
                                                .status === 'ditolak'
                                        }">
                                        <template x-if="item.status === 'pending'">
                                            <x-heroicon-o-clock class="h-4 w-4" />
                                        </template>
                                        <template x-if="item.status === 'disetujui'">
                                            <x-heroicon-o-check class="h-4 w-4" />
                                        </template>
                                        <template x-if="item.status === 'ditolak'">
                                            <x-heroicon-o-x-mark class="h-4 w-4" />
                                        </template>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 line-clamp-1" x-text="item.judul">
                                        </p>
                                        <p class="mt-0.5 text-xs text-gray-400" x-text="item.waktu"></p>
                                        <template x-if="item.isNew">
                                            <span
                                                class="mt-1 inline-flex items-center gap-1 text-xs font-black text-yellow-600">
                                                <span
                                                    class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                                Baru diperbarui
                                            </span>
                                        </template>
                                    </div>

                                    <span
                                        class="shrink-0 inline-flex items-center gap-1.5 rounded-full border-2 px-2.5 py-1 text-xs font-black"
                                        x-bind:class="{
                                            'border-yellow-200 bg-yellow-100 text-yellow-700': item
                                                .status === 'pending',
                                            'border-emerald-200 bg-emerald-100 text-emerald-700': item
                                                .status === 'disetujui',
                                            'border-red-200 bg-red-100 text-red-700': item.status === 'ditolak'
                                        }">
                                        <span class="h-1.5 w-1.5 rounded-full"
                                            x-bind:class="{
                                                'bg-yellow-500 animate-pulse': item.status === 'pending',
                                                'bg-emerald-500': item.status === 'disetujui',
                                                'bg-red-500': item.status === 'ditolak'
                                            }">
                                        </span>
                                        <span
                                            x-text="item.status === 'pending' ? 'Pending' : item.status === 'disetujui' ? 'Disetujui' : 'Ditolak'"></span>
                                    </span>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="riwayat.length === 0">
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 mb-4">
                                <x-heroicon-o-document-text class="h-8 w-8 text-indigo-400" />
                            </div>
                            <p class="text-sm font-bold text-gray-700">Belum ada pengajuan</p>
                            <p class="mt-1 text-xs text-gray-400">Mulai ajukan judul TA Anda sekarang</p>
                            <a href="{{ route('mahasiswa.pengajuan') }}"
                                class="mt-4 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                                <x-heroicon-o-plus class="h-3.5 w-3.5" />
                                Ajukan Sekarang
                            </a>
                        </div>
                    </template>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">

                    {{-- Quick Access --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-5 py-4">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-bolt class="h-4 w-4 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white text-sm">Akses Cepat</h3>
                        </div>
                        <div class="p-4 space-y-3">

                            <a href="{{ route('mahasiswa.pengajuan') }}"
                                class="group relative overflow-hidden flex items-center gap-3 rounded-xl border-2 border-indigo-200 bg-indigo-50 p-3.5 transition hover:-translate-y-0.5 hover:border-indigo-400 hover:shadow-md">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 shadow-sm">
                                    <x-heroicon-o-plus class="h-5 w-5 text-white" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-black text-indigo-800">Ajukan Judul</p>
                                    <p class="text-xs text-indigo-500">Buat pengajuan baru</p>
                                </div>
                                <x-heroicon-o-arrow-right
                                    class="h-4 w-4 text-indigo-400 transition group-hover:translate-x-1 group-hover:text-indigo-600" />
                            </a>

                            <a href="{{ route('mahasiswa.riwayat') }}"
                                class="group relative overflow-hidden flex items-center gap-3 rounded-xl border-2 border-gray-200 bg-gray-50 p-3.5 transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 shadow-sm">
                                    <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-white" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-black text-gray-800">Lihat Riwayat</p>
                                    <p class="text-xs text-gray-500">Semua pengajuan</p>
                                </div>
                                <x-heroicon-o-arrow-right
                                    class="h-4 w-4 text-gray-400 transition group-hover:translate-x-1 group-hover:text-emerald-600" />
                            </a>

                            <a href="{{ route('mahasiswa.notifikasi') }}"
                                class="group relative overflow-hidden flex items-center gap-3 rounded-xl border-2 border-gray-200 bg-gray-50 p-3.5 transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-600 shadow-sm relative">
                                    <x-heroicon-o-bell class="h-5 w-5 text-white" />
                                    <span x-show="$store.notif && $store.notif.unread > 0"
                                        class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-black text-white">
                                        <span x-text="$store.notif.unread > 9 ? '9+' : $store.notif.unread"></span>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-black text-gray-800">Notifikasi</p>
                                    <p class="text-xs text-gray-500">Lihat update terbaru</p>
                                </div>
                                <x-heroicon-o-arrow-right
                                    class="h-4 w-4 text-gray-400 transition group-hover:translate-x-1 group-hover:text-violet-600" />
                            </a>

                        </div>
                    </div>

                    {{-- Global Stats --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-3 border-b-4 border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 px-5 py-4">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-chart-bar class="h-4 w-4 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white text-sm">Statistik Global</h3>
                        </div>
                        <div class="p-4 space-y-3">

                            <div
                                class="flex items-center justify-between rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                                <span class="text-xs font-bold text-gray-500">Total Pengajuan</span>
                                <span class="text-lg font-black text-indigo-600">{{ $totalSemua }}</span>
                            </div>

                            <div
                                class="flex items-center justify-between rounded-xl border-2 border-emerald-100 bg-emerald-50 px-4 py-3">
                                <span class="text-xs font-bold text-emerald-600">Total Disetujui</span>
                                <span class="text-lg font-black text-emerald-700">{{ $disetujuiSemua }}</span>
                            </div>

                            @php
                                $approvalRate = $totalSemua > 0 ? round(($disetujuiSemua / $totalSemua) * 100) : 0;
                            @endphp

                            <div class="rounded-xl border-2 border-indigo-100 bg-indigo-50 px-4 py-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-indigo-600">Approval Rate</span>
                                    <span class="text-sm font-black text-indigo-700">{{ $approvalRate }}%</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-indigo-200">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-700"
                                        style="width: {{ $approvalRate }}%">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    @push('scripts')
        <script>
            function dashboardMahasiswa() {
                return {
                    stats: {
                        total: {{ $total }},
                        pending: {{ $pending }},
                        ditolak: {{ $ditolak }}
                    },
                    riwayat: @json($riwayat),
                    lastRiwayat: [],
                    // ✅ pakai statusProgress dari controller
                    status: '{{ $statusProgress }}',
                    lastStatus: null,
                    step: 1,
                    progressWidth: 25,
                    stepLabel: '',
                    interval: null,

                    // ✅ 4 step sekarang
                    mapStatus(status) {
                        switch (status) {
                            case 'pending':
                                return 2;
                            case 'review':
                                return 3;
                            case 'disetujui':
                                return 3; // kaprodi approve, belum diumumkan
                            case 'diumumkan':
                                return 4; // sudah diumumkan = selesai
                            case 'ditolak':
                                return 4; // ditolak juga step 4 setelah diumumkan
                            default:
                                return 1;
                        }
                    },

                    // ✅ Label per step
                    getStepLabel(status) {
                        switch (status) {
                            case 'none':
                                return 'Belum ada pengajuan';
                            case 'pending':
                                return 'Menunggu review Ka Lab';
                            case 'review':
                                return 'Menunggu review Kaprodi';
                            case 'disetujui':
                                return 'Menunggu pengumuman Koordinator TA';
                            case 'diumumkan':
                                return '✓ Pengumuman sudah dikirim';
                            case 'ditolak':
                                return 'Pengajuan ditolak';
                            default:
                                return '';
                        }
                    },

                    updateProgress(status) {
                        this.step = this.mapStatus(status);
                        this.progressWidth = this.step * 25;
                        this.stepLabel = this.getStepLabel(status);
                    },

                    async fetch() {
                        try {
                            const res = await fetch("{{ route('mahasiswa.beranda.data') }}", {
                                method: 'GET',
                                credentials: 'same-origin',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            if (!res.ok) throw new Error('Network error');
                            const data = await res.json();

                            const old = this.lastRiwayat.map(i => i.judul);
                            data.riwayat = data.riwayat.map(item => ({
                                ...item,
                                isNew: !old.includes(item.judul)
                            }));

                            this.lastRiwayat = data.riwayat;
                            this.riwayat = data.riwayat;
                            this.status = data.status;

                            this.updateProgress(data.status);

                            if (this.lastStatus && this.lastStatus !== data.status) {
                                this.showToast('Status pengajuan diperbarui', 'success');
                            }
                            this.lastStatus = data.status;

                        } catch (e) {
                            console.error('Fetch error:', e);
                        }
                    },

                    showToast(message, type = 'success') {
                        const toast = document.createElement('div');
                        toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white text-sm font-semibold transition-all duration-300 ${
                    type === 'success' ? 'bg-emerald-500' : 'bg-red-500'
                }`;
                        toast.innerHTML = `<span>${message}</span>`;
                        document.getElementById('toast-container').appendChild(toast);
                        setTimeout(() => {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);
                    },

                    init() {
                        this.updateProgress(this.status);
                        this.fetch();
                        if (this.interval) clearInterval(this.interval);
                        this.interval = setInterval(() => this.fetch(), 5000);
                    }
                }
            }
        </script>
    @endpush


</x-layout>
