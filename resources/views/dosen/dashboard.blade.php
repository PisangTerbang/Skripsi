<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            <x-periode-banner />

            {{-- ===== WELCOME BANNER ===== --}}
            <div
                class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 p-7 shadow-xl">
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
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-300">Dosen Pembimbing</p>
                            <h2 class="mt-1 text-2xl font-black text-white leading-tight">
                                Selamat Datang, {{ auth()->user()->name }}
                            </h2>
                            <p class="mt-1 text-sm text-emerald-200">
                                Panel Monitoring Pengajuan Judul Tugas Akhir
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
                                    {{ now()->format('H:i') }} WIB
                                </span>
                                @if (isset($periodeAktif))
                                    <span
                                        class="flex items-center gap-1.5 rounded-full border border-emerald-300/40 bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-200">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span>
                                        {{ $periodeAktif?->nama ?? ($periodeAktif ? $periodeAktif->semester . ' ' . $periodeAktif->tahun_ajaran : 'Belum ada periode aktif') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats di Banner --}}
                    @if ($pending > 0)
                        <div class="hidden lg:flex shrink-0 flex-col items-end gap-3">
                            <div
                                class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                                <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Perlu Review</p>
                                <p class="mt-1 text-4xl font-black text-white">{{ $pending }}</p>
                                <p class="text-xs text-emerald-300">pengajuan pending</p>
                            </div>
                            <a href="{{ route('dosen.pengajuan') }}"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white px-4 py-2 text-xs font-black text-emerald-700 shadow-md transition hover:bg-emerald-50">
                                <x-heroicon-o-arrow-right class="h-3.5 w-3.5" />
                                Review Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== SECTION: STATS ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Ringkasan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Total --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $total }}</p>
                            <p class="mt-2 text-xs font-medium text-indigo-200">periode ini</p>
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

                {{-- Disetujui --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Disetujui</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $disetujui }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">{{ $approvalRate }}% approval rate</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full rounded-full bg-white/60 transition-all duration-700"
                            style="width: {{ $approvalRate }}%"></div>
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
                            <p class="mt-2 text-xs font-medium text-red-200">perlu feedback</p>
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

                {{-- Pending --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Menunggu Review</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $pending }}</p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">perlu tindakan</p>
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

            </div>

            {{-- ===== SECTION: RECENT + QUICK ACCESS ===== --}}
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

                {{-- Recent Submissions --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md lg:col-span-2">
                    <div
                        class="flex items-center justify-between border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">Pengajuan Terbaru (Pending)</h3>
                        </div>
                        <a href="{{ route('dosen.pengajuan') }}"
                            class="text-xs font-bold text-emerald-200 hover:text-white transition">
                            Lihat Semua →
                        </a>
                    </div>

                    @forelse ($recentSubmissions as $submission)
                        <div
                            class="flex items-start gap-4 border-b-2 border-gray-100 px-6 py-4 transition hover:bg-emerald-50/30 group last:border-0">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-sm font-black text-white shadow-sm ring-2 ring-emerald-200">
                                {{ strtoupper(substr($submission->mahasiswa->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <p class="font-bold text-gray-800">{{ $submission->mahasiswa->name }}</p>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full border-2 border-yellow-200 bg-yellow-100 px-2 py-0.5 text-[10px] font-black text-yellow-700">
                                        <span class="h-1 w-1 animate-pulse rounded-full bg-yellow-500"></span>
                                        Pending
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-1">
                                    {{ $submission->jenis === 'mandiri'
                                        ? $submission->judul_mandiri
                                        : $submission->judulDitetapkan->nama_judul ?? ($submission->pilihan1->nama_judul ?? '-') }}
                                </p>
                                <p class="mt-0.5 text-xs text-gray-400">{{ $submission->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('dosen.pengajuan') }}"
                                class="shrink-0 inline-flex items-center gap-1 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-emerald-700 opacity-0 group-hover:opacity-100">
                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                Review
                            </a>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 mb-4">
                                <x-heroicon-o-check-circle class="h-8 w-8 text-emerald-500" />
                            </div>
                            <p class="text-sm font-bold text-gray-700">Tidak ada pengajuan pending</p>
                            <p class="mt-1 text-xs text-gray-400">Semua pengajuan sudah diproses</p>
                        </div>
                    @endforelse
                </div>

                {{-- Quick Access --}}
                <div class="space-y-4">

                    <a href="{{ route('dosen.pengajuan') }}"
                        class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-lg flex items-start gap-4">
                        <div
                            class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-50 transition group-hover:bg-emerald-100">
                        </div>
                        <div
                            class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-emerald-200 bg-emerald-100 transition group-hover:border-emerald-400 group-hover:bg-emerald-200">
                            <x-heroicon-o-clipboard-document-list class="h-6 w-6 text-emerald-600" />
                        </div>
                        <div class="relative flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-emerald-700">Review
                                Pengajuan</h3>
                            <p class="mt-1 text-xs text-gray-400">Tinjau pengajuan mahasiswa</p>
                            @if ($pending > 0)
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-yellow-200 bg-yellow-50 px-2.5 py-1 text-xs font-black text-yellow-700">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                    {{ $pending }} menunggu
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
                            class="relative h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                    </a>

                    <a href="{{ route('dosen.judul.index') }}"
                        class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-lg flex items-start gap-4">
                        <div
                            class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-indigo-50 transition group-hover:bg-indigo-100">
                        </div>
                        <div
                            class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-indigo-200 bg-indigo-100 transition group-hover:border-indigo-400 group-hover:bg-indigo-200">
                            <x-heroicon-o-book-open class="h-6 w-6 text-indigo-600" />
                        </div>
                        <div class="relative flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-indigo-700">Kelola
                                Judul</h3>
                            <p class="mt-1 text-xs text-gray-400">Tambah & edit judul TA</p>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="relative h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-indigo-500" />
                    </a>

                    <a href="{{ route('dosen.notifikasi') }}"
                        class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-lg flex items-start gap-4">
                        <div
                            class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-violet-50 transition group-hover:bg-violet-100">
                        </div>
                        <div
                            class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-violet-200 bg-violet-100 transition group-hover:border-violet-400 group-hover:bg-violet-200">
                            <x-heroicon-o-bell class="h-6 w-6 text-violet-600" />
                        </div>
                        <div class="relative flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-violet-700">Notifikasi
                            </h3>
                            <p class="mt-1 text-xs text-gray-400">Lihat update terbaru</p>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="relative h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-violet-500" />
                    </a>

                </div>
            </div>
            {{-- ===== SECTION: CHARTS ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Analitik
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Donut Chart --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-chart-pie class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Distribusi Keputusan</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            <canvas id="donutChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Tren Pengajuan --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Tren Pengajuan</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            <canvas id="trenChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Tren Keputusan --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-violet-200 bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Tren Keputusan</h3>
                </div>
                <div class="p-6">
                    <div class="relative h-[300px]">
                        <canvas id="keputusanChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION: LAB STATS ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-building-office class="h-3 w-3" />
                    Statistik Lab
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Lab Disetujui --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Lab dengan Persetujuan Terbanyak</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse ($labDisetujui as $lab)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-bold text-gray-700">{{ $lab->nama }}</span>
                                    <span
                                        class="rounded-full border-2 border-emerald-200 bg-emerald-100 px-2.5 py-0.5 text-xs font-black text-emerald-700">
                                        {{ $lab->total }}
                                    </span>
                                </div>
                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-green-500 transition-all duration-700"
                                        style="width: {{ min($lab->total * 10, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 mb-3">
                                    <x-heroicon-o-inbox class="h-7 w-7 text-gray-400" />
                                </div>
                                <p class="text-sm font-bold text-gray-500">Belum ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Lab Ditolak --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-red-200 bg-gradient-to-r from-red-600 to-rose-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Lab dengan Penolakan Tinggi</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse ($labDitolak as $lab)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-bold text-gray-700">{{ $lab->nama }}</span>
                                    <span
                                        class="rounded-full border-2 border-red-200 bg-red-100 px-2.5 py-0.5 text-xs font-black text-red-700">
                                        {{ $lab->total }}
                                    </span>
                                </div>
                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-red-500 to-rose-500 transition-all duration-700"
                                        style="width: {{ min($lab->total * 10, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 mb-3">
                                    <x-heroicon-o-inbox class="h-7 w-7 text-gray-400" />
                                </div>
                                <p class="text-sm font-bold text-gray-500">Belum ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Rasio Lab --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Rasio Persetujuan per Lab</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse ($rasioLab as $lab)
                        @php
                            $total = $lab->disetujui + $lab->ditolak;
                            $rasio = $total > 0 ? round(($lab->disetujui / $total) * 100) : 0;
                            $color =
                                $rasio >= 70
                                    ? 'from-emerald-500 to-green-500'
                                    : ($rasio >= 40
                                        ? 'from-yellow-400 to-orange-500'
                                        : 'from-red-500 to-rose-500');
                            $badgeColor =
                                $rasio >= 70
                                    ? 'border-emerald-200 bg-emerald-100 text-emerald-700'
                                    : ($rasio >= 40
                                        ? 'border-yellow-200 bg-yellow-100 text-yellow-700'
                                        : 'border-red-200 bg-red-100 text-red-700');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-gray-700">{{ $lab->nama }}</span>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-xs text-gray-400">{{ $lab->disetujui }}/{{ $total }}</span>
                                    <span
                                        class="rounded-full border-2 px-2.5 py-0.5 text-xs font-black {{ $badgeColor }}">
                                        {{ $rasio }}%
                                    </span>
                                </div>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $color }} transition-all duration-700"
                                    style="width: {{ $rasio }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 mb-3">
                                <x-heroicon-o-inbox class="h-7 w-7 text-gray-400" />
                            </div>
                            <p class="text-sm font-bold text-gray-500">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ===== CHART SCRIPTS ===== --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Donut Chart
            new Chart(document.getElementById('donutChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Disetujui', 'Ditolak'],
                    datasets: [{
                        data: [{{ $pending }}, {{ $disetujui }}, {{ $ditolak }}],
                        backgroundColor: ['#fbbf24', '#10b981', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                                pointStyleWidth: 10,
                            }
                        }
                    }
                }
            });

            // Tren Pengajuan
            new Chart(document.getElementById('trenChart'), {
                type: 'line',
                data: {
                    labels: [
                        @foreach ($trenPengajuan as $t)
                            "{{ $t->semester }} {{ $t->tahun_ajaran }}",
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: [
                            @foreach ($trenPengajuan as $t)
                                {{ $t->total }},
                            @endforeach
                        ],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // Tren Keputusan
            new Chart(document.getElementById('keputusanChart'), {
                type: 'bar',
                data: {
                    labels: [
                        @foreach ($trenKeputusan as $t)
                            "{{ $t->semester }} {{ $t->tahun_ajaran }}",
                        @endforeach
                    ],
                    datasets: [{
                            label: 'Disetujui',
                            data: [
                                @foreach ($trenKeputusan as $t)
                                    {{ $t->disetujui }},
                                @endforeach
                            ],
                            backgroundColor: '#10b981',
                            borderRadius: 8,
                            borderSkipped: false,
                        },
                        {
                            label: 'Ditolak',
                            data: [
                                @foreach ($trenKeputusan as $t)
                                    {{ $t->ditolak }},
                                @endforeach
                            ],
                            backgroundColor: '#ef4444',
                            borderRadius: 8,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                                pointStyleWidth: 10,
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.04)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

</x-layout-dosen>
