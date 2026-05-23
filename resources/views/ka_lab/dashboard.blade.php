<x-layout-kalab>
    <x-slot name="title">{{ $title }}</x-slot>

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== WELCOME BANNER ===== --}}
            <div
                class="relative overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-br from-sky-600 via-sky-700 to-blue-800 p-7 shadow-xl">
                <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
                <div class="absolute right-32 bottom-4 h-20 w-20 rounded-full bg-white/5"></div>
                <div class="absolute right-16 top-4 h-10 w-10 rounded-full bg-white/10"></div>

                <div class="relative flex items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border-2 border-white/30 bg-white/20 text-2xl font-black text-white backdrop-blur-sm shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-300">Kepala Laboratorium</p>
                            <h2 class="mt-1 text-2xl font-black text-white leading-tight">
                                Selamat Datang, {{ auth()->user()->name }}
                            </h2>
                            <p class="mt-1 text-sm text-sky-200">
                                Validasi dan monitoring judul Tugas Akhir
                            </p>
                            <div class="mt-3 flex items-center gap-2">
                                <span
                                    class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                                    <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                </span>
                                <span
                                    class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                                    <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                    <span id="current-time">{{ now()->format('H:i') }}</span> WIB
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats di Banner --}}
                    <div class="hidden lg:grid shrink-0 grid-cols-2 gap-3">
                        <div
                            class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Perlu Validasi</p>
                            <p class="mt-1 text-4xl font-black text-white">{{ $stats['pending_kalab'] }}</p>
                            <p class="text-xs text-sky-300">judul</p>
                        </div>
                        <div
                            class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Pengajuan</p>
                            <p class="mt-1 text-4xl font-black text-white">{{ $pengajuanStats['pending_review'] }}</p>
                            <p class="text-xs text-sky-300">pending</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION: STATS JUDUL ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Ringkasan Judul
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Draft --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-gray-300 bg-gradient-to-br from-gray-500 via-gray-600 to-gray-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-200">Draft</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['draft'] }}</p>
                            <p class="mt-2 text-xs font-medium text-gray-200">judul dari dosen</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-document class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('ka-lab.judul.index') }}?status=draft"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-gray-200 transition hover:text-white">
                        Lihat draft <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Pending Validasi --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Perlu Validasi</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['pending_kalab'] }}
                            </p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">menunggu review</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clock class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full {{ $stats['pending_kalab'] > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                    <a href="{{ route('ka-lab.validasi.index') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-yellow-100 transition hover:text-white">
                        Validasi sekarang <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Ditawarkan --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Ditawarkan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['ditawarkan'] }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">tersedia mahasiswa</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('ka-lab.judul.index') }}?status=ditawarkan"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-emerald-200 transition hover:text-white">
                        Lihat judul <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Ditolak --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['ditolak'] }}</p>
                            <p class="mt-2 text-xs font-medium text-red-200">perlu revisi</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <p class="mt-3 text-xs font-bold text-red-200">Perlu tindak lanjut</p>
                </div>

            </div>

            {{-- ===== SECTION: STATS PENGAJUAN ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-document-text class="h-3 w-3" />
                    Ringkasan Pengajuan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Pengajuan Pending --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-br from-sky-500 via-sky-600 to-blue-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Pengajuan Pending</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">
                                {{ $pengajuanStats['pending_review'] }}</p>
                            <p class="mt-2 text-xs font-medium text-sky-200">menunggu review Ka Lab</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full {{ $pengajuanStats['pending_review'] > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                    <a href="{{ route('ka-lab.pengajuan.index') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-sky-200 transition hover:text-white">
                        Review pengajuan <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Total Pengajuan --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 via-violet-600 to-purple-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">
                                {{ $pengajuanStats['total_pengajuan'] }}</p>
                            <p class="mt-2 text-xs font-medium text-violet-200">semua periode</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-users class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <p class="mt-3 text-xs font-bold text-violet-200">Semua status</p>
                </div>

            </div>
            {{-- ===== SECTION: QUICK ACCESS ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-bolt class="h-3 w-3" />
                    Akses Cepat
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                {{-- Validasi Judul --}}
                <a href="{{ route('ka-lab.validasi.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-yellow-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-yellow-50 transition group-hover:bg-yellow-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-yellow-200 bg-yellow-100 transition group-hover:border-yellow-400 group-hover:bg-yellow-200">
                            <x-heroicon-o-clipboard-document-check class="h-6 w-6 text-yellow-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-yellow-700">Validasi
                                Judul</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Review dan validasi judul dari dosen
                            </p>
                            @if ($stats['pending_kalab'] > 0)
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-yellow-200 bg-yellow-50 px-2.5 py-1 text-xs font-black text-yellow-700">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                    {{ $stats['pending_kalab'] }} menunggu
                                </span>
                            @else
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-bold text-green-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Semua selesai
                                </span>
                            @endif
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-yellow-500" />
                    </div>
                </a>

                {{-- Pengajuan Mahasiswa --}}
                <a href="{{ route('ka-lab.pengajuan.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-sky-50 transition group-hover:bg-sky-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-sky-200 bg-sky-100 transition group-hover:border-sky-400 group-hover:bg-sky-200">
                            <x-heroicon-o-clipboard-document-list class="h-6 w-6 text-sky-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-sky-700">Pengajuan
                                Mahasiswa</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Review pengajuan judul TA mahasiswa
                            </p>
                            @if ($pengajuanStats['pending_review'] > 0)
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-black text-sky-700">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-sky-500"></span>
                                    {{ $pengajuanStats['pending_review'] }} menunggu
                                </span>
                            @else
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-bold text-green-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Semua selesai
                                </span>
                            @endif
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-sky-500" />
                    </div>
                </a>

                {{-- Monitoring Judul --}}
                <a href="{{ route('ka-lab.judul.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-50 transition group-hover:bg-emerald-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-emerald-200 bg-emerald-100 transition group-hover:border-emerald-400 group-hover:bg-emerald-200">
                            <x-heroicon-o-document-text class="h-6 w-6 text-emerald-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-emerald-700">Monitoring
                                Judul</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Pantau semua judul TA yang terdaftar
                            </p>
                            <span
                                class="mt-2 inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600">
                                {{ $stats['total_judul'] }} total judul
                            </span>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                    </div>
                </a>

            </div>

            {{-- ===== SECTION: CONTENT ROW ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-clock class="h-3 w-3" />
                    Perlu Tindakan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Judul Perlu Validasi --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center justify-between border-b-4 border-yellow-200 bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-clock class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">Judul Perlu Validasi</h3>
                        </div>
                        <a href="{{ route('ka-lab.validasi.index') }}"
                            class="text-xs font-bold text-yellow-100 hover:text-white transition">
                            Lihat semua →
                        </a>
                    </div>

                    @if ($judulPerluValidasi->count() > 0)
                        <div class="divide-y-2 divide-gray-100">
                            @foreach ($judulPerluValidasi as $judul)
                                <div class="flex items-start gap-3 px-6 py-4 hover:bg-yellow-50/30 transition">
                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-sm font-black text-white shadow-sm">
                                        {{ strtoupper(substr($judul->dosen->name ?? 'D', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 line-clamp-1">
                                            {{ $judul->nama_judul }}</p>
                                        <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-400">
                                            <span>{{ $judul->dosen->name ?? 'N/A' }}</span>
                                            <span>•</span>
                                            <span>{{ $judul->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-2.5 py-1 text-xs font-black text-yellow-700 shrink-0">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                        Pending
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t-2 border-gray-100 bg-gray-50 px-6 py-3">
                            <a href="{{ route('ka-lab.validasi.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-yellow-300 bg-yellow-500 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-yellow-600">
                                <x-heroicon-o-clipboard-document-check class="h-3.5 w-3.5" />
                                Validasi Sekarang
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 mb-4">
                                <x-heroicon-o-check-circle class="h-8 w-8 text-emerald-500" />
                            </div>
                            <p class="text-sm font-bold text-gray-700">Semua judul sudah divalidasi</p>
                            <p class="mt-1 text-xs text-gray-400">Tidak ada judul yang perlu divalidasi</p>
                        </div>
                    @endif
                </div>

                {{-- Pengajuan Perlu Review --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center justify-between border-b-4 border-sky-200 bg-gradient-to-r from-sky-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">Pengajuan Mahasiswa</h3>
                        </div>
                        <a href="{{ route('ka-lab.pengajuan.index') }}"
                            class="text-xs font-bold text-sky-200 hover:text-white transition">
                            Lihat semua →
                        </a>
                    </div>

                    @if ($pengajuanPerluReview->count() > 0)
                        <div class="divide-y-2 divide-gray-100">
                            @foreach ($pengajuanPerluReview as $pengajuan)
                                <div class="flex items-start gap-3 px-6 py-4 hover:bg-sky-50/30 transition">
                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-sm font-black text-white shadow-sm">
                                        {{ strtoupper(substr($pengajuan->mahasiswa->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800">
                                            {{ $pengajuan->mahasiswa->name ?? 'N/A' }}</p>
                                        <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-400">
                                            <span>NIM: {{ $pengajuan->mahasiswa->nim ?? '-' }}</span>
                                            <span>•</span>
                                            <span>{{ $pengajuan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-sky-200 bg-sky-100 px-2.5 py-1 text-xs font-black text-sky-700 shrink-0">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-sky-500"></span>
                                        Pending
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t-2 border-gray-100 bg-gray-50 px-6 py-3">
                            <a href="{{ route('ka-lab.pengajuan.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-sky-300 bg-sky-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-sky-700">
                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                Review Pengajuan
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 mb-4">
                                <x-heroicon-o-inbox class="h-8 w-8 text-gray-400" />
                            </div>
                            <p class="text-sm font-bold text-gray-700">Belum ada pengajuan</p>
                            <p class="mt-1 text-xs text-gray-400">Pengajuan mahasiswa akan muncul di sini</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- ===== SECTION: AKTIVITAS TERBARU ===== --}}
            @if ($recentActivities->count() > 0)
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-bolt class="h-3 w-3" />
                        Aktivitas Terbaru
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    <div
                        class="flex items-center justify-between border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 to-blue-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-bolt class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">Aktivitas Terbaru</h3>
                        </div>
                        <span
                            class="rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                            {{ $recentActivities->count() }} aktivitas
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Judul</th>
                                    <th class="px-6 py-4">Aksi</th>
                                    <th class="px-6 py-4">Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($recentActivities as $activity)
                                    <tr class="group transition-colors hover:bg-sky-50/30">
                                        <td class="px-6 py-4">
                                            <div
                                                class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2 text-center">
                                                <p class="text-xs font-black text-gray-700">
                                                    {{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y') }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="max-w-[220px] px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800 line-clamp-1">
                                                {{ $activity->nama_judul }}</p>
                                            <span
                                                class="mt-1 inline-block rounded-lg bg-gray-100 px-2 py-0.5 text-xs font-black text-gray-600">
                                                {{ $activity->kode }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full border-2 border-sky-200 bg-sky-100 px-3 py-1 text-xs font-black text-sky-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                                                {{ $activity->aksi }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-xs font-black text-white">
                                                    {{ strtoupper(substr($activity->user_name, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="text-sm font-semibold text-gray-700">{{ $activity->user_name }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                        <p class="text-xs font-semibold text-gray-500">
                            Menampilkan <span class="font-black text-gray-800">{{ $recentActivities->count() }}</span>
                            aktivitas terbaru
                        </p>
                    </div>

                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        <script>
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const el = document.getElementById('current-time');
                if (el) el.textContent = `${hours}:${minutes}`;
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>
    @endpush

</x-layout-kalab>
