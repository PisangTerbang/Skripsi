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

                </div>
            </div>

            {{-- ===== AKSES CEPAT (di atas agar langsung terlihat) ===== --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <a href="{{ route('prodi.pengajuan.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-violet-200 bg-violet-100 text-violet-600 transition group-hover:bg-violet-200">
                        <x-heroicon-o-document-check class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-violet-700">Review Pengajuan</p>
                        @if ($pendingFinal > 0)
                            <span class="text-xs font-bold text-yellow-600">{{ $pendingFinal }} menunggu</span>
                        @else
                            <span class="text-xs text-gray-400">Putuskan pengajuan TA</span>
                        @endif
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-violet-500" />
                </a>
                <a href="{{ route('prodi.pengajuan.riwayat') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-emerald-200 bg-emerald-100 text-emerald-600 transition group-hover:bg-emerald-200">
                        <x-heroicon-o-clock class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-emerald-700">Riwayat Review</p>
                        <span class="text-xs text-gray-400">Semua keputusan ({{ $totalPengajuan }})</span>
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                </a>
            </div>

            {{-- ===== RINGKASAN (kartu ringan) ===== --}}
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Menunggu Keputusan</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">
                            <x-heroicon-o-clock class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-yellow-600">{{ $pendingFinal }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Total Judul</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <x-heroicon-o-book-open class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-gray-800">{{ $totalJudul }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Judul Ditawarkan</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                            <x-heroicon-o-check-circle class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600">{{ $judulDitawarkan }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Total Pengajuan</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                            <x-heroicon-o-document-text class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-gray-800">{{ $totalPengajuan }}</p>
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
