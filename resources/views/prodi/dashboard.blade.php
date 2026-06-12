<x-layout-prodi>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="min-h-screen bg-slate-100">

        <div class="px-6 py-6 space-y-6">

            <x-periode-banner />

            {{-- ===== WELCOME BANNER ===== --}}
            <div
                class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-600 via-violet-700 to-purple-800 p-7 shadow-xl">
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
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-300">Program Studi —
                                Kaprodi</p>
                            <h2 class="mt-1 text-2xl font-black text-white leading-tight">
                                Selamat Datang, {{ auth()->user()->name }}
                            </h2>
                            <p class="mt-1 text-sm text-violet-200">
                                Panel Monitoring Tugas Akhir Program Studi
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
                            </div>
                        </div>
                    </div>

                    {{-- Quick Action --}}
                    @if ($pendingFinal > 0)
                        <div class="hidden lg:flex shrink-0 flex-col items-end gap-3">
                            <div
                                class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                                <p class="text-xs font-bold uppercase tracking-widest text-violet-200">Perlu Ditinjau
                                </p>
                                <p class="mt-1 text-4xl font-black text-white">{{ $pendingFinal }}</p>
                                <p class="text-xs text-violet-300">pengajuan aktif</p>
                            </div>
                            <a href="{{ route('prodi.pengajuan.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white px-4 py-2 text-xs font-black text-violet-700 shadow-md transition hover:bg-violet-50">
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
                    Ringkasan Sistem
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Pending Final --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Pending Final</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $pendingFinal }}</p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">menunggu review</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20 backdrop-blur-sm">
                            <x-heroicon-o-clock class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full {{ $pendingFinal > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                    <a href="{{ route('prodi.pengajuan.index') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-yellow-100 transition hover:text-white">
                        Lihat pengajuan
                        <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Total Judul --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-200">Total Judul</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalJudul }}</p>
                            <p class="mt-2 text-xs font-medium text-blue-200">judul terdaftar</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20 backdrop-blur-sm">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('prodi.monitoring') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-blue-200 transition hover:text-white">
                        Lihat monitoring
                        <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Judul Ditawarkan --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Judul Ditawarkan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $judulDitawarkan }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">tersedia untuk mahasiswa</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20 backdrop-blur-sm">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <p class="mt-3 text-xs font-bold text-emerald-200">Aktif ditawarkan</p>
                </div>

                {{-- Total Pengajuan --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 via-violet-600 to-purple-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalPengajuan }}</p>
                            <p class="mt-2 text-xs font-medium text-violet-200">semua periode</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20 backdrop-blur-sm">
                            <x-heroicon-o-users class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('prodi.pengajuan.riwayat') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-violet-200 transition hover:text-white">
                        Lihat riwayat
                        <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

            </div>

            {{-- ===== SECTION: VISUALISASI DATA ===== --}}
            @php
                $totalFinalChart = $finalDisetujui + $finalDitolak + $finalMenunggu;
            @endphp
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Visualisasi Data
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Donut: Keputusan Final (periode aktif) --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div class="flex items-center gap-3 border-b-4 border-violet-200 bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-chart-pie class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Keputusan Final (Periode Aktif)</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            @if ($totalFinalChart > 0)
                                <canvas id="finalDonut"></canvas>
                            @else
                                <div class="flex h-full flex-col items-center justify-center text-center">
                                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-300" />
                                    <p class="mt-3 text-sm font-semibold text-gray-400">Belum ada pengajuan pada periode
                                        aktif</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Line: Tren Pengajuan --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Tren Pengajuan</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            <canvas id="trenPengajuanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bar: Tren Keputusan Final --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Tren Keputusan Final (per Periode)</h3>
                </div>
                <div class="p-6">
                    <div class="relative h-[300px]">
                        <canvas id="trenKeputusanChart"></canvas>
                    </div>
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

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Review Pengajuan --}}
                <a href="{{ route('prodi.pengajuan.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-violet-50 transition group-hover:bg-violet-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-violet-200 bg-violet-100 transition group-hover:border-violet-400 group-hover:bg-violet-200">
                            <x-heroicon-o-document-check class="h-6 w-6 text-violet-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-violet-700">Review
                                Pengajuan</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Tinjau dan putuskan pengajuan judul
                                TA mahasiswa</p>
                            @if ($pendingFinal > 0)
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-yellow-200 bg-yellow-50 px-2.5 py-1 text-xs font-black text-yellow-700">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                    {{ $pendingFinal }} menunggu
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
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-violet-500" />
                    </div>
                </a>

                {{-- Riwayat Review --}}
                <a href="{{ route('prodi.pengajuan.riwayat') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-50 transition group-hover:bg-emerald-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-emerald-200 bg-emerald-100 transition group-hover:border-emerald-400 group-hover:bg-emerald-200">
                            <x-heroicon-o-clock class="h-6 w-6 text-emerald-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-emerald-700">Riwayat
                                Review</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Lihat semua keputusan yang sudah
                                diproses</p>
                            <span
                                class="mt-2 inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600">
                                {{ $totalPengajuan }} total
                            </span>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                    </div>
                </a>

            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartFont = {
                size: 11
            };

            // Donut: Keputusan Final Prodi (periode aktif)
            @if ($totalFinalChart > 0)
                new Chart(document.getElementById('finalDonut'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Disetujui', 'Ditolak', 'Menunggu'],
                        datasets: [{
                            data: [{{ $finalDisetujui }}, {{ $finalDitolak }}, {{ $finalMenunggu }}],
                            backgroundColor: ['#10b981', '#ef4444', '#8b5cf6'],
                            borderWidth: 0,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 16,
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    usePointStyle: true,
                                    pointStyleWidth: 10
                                }
                            }
                        }
                    }
                });
            @endif

            // Line: Tren Pengajuan
            new Chart(document.getElementById('trenPengajuanChart'), {
                type: 'line',
                data: {
                    labels: [
                        @foreach ($trenPengajuan as $t)
                            "{{ $t->nama }}",
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Pengajuan',
                        data: [
                            @foreach ($trenPengajuan as $t)
                                {{ $t->total }},
                            @endforeach
                        ],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointBackgroundColor: '#6366f1',
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
                                font: chartFont,
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: chartFont
                            }
                        }
                    }
                }
            });

            // Bar: Tren Keputusan Final
            new Chart(document.getElementById('trenKeputusanChart'), {
                type: 'bar',
                data: {
                    labels: [
                        @foreach ($trenKeputusan as $t)
                            "{{ $t->nama }}",
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
                                padding: 16,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                                pointStyleWidth: 10
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
                                font: chartFont,
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: chartFont
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

</x-layout-prodi>
