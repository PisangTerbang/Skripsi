<x-layout-kalab>
    <x-slot name="title">{{ $title }}</x-slot>

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            <x-periode-banner />

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

                </div>
            </div>

            {{-- ===== AKSES CEPAT (di atas agar langsung terlihat) ===== --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="{{ route('ka-lab.validasi.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-yellow-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-yellow-200 bg-yellow-100 text-yellow-600 transition group-hover:bg-yellow-200">
                        <x-heroicon-o-clipboard-document-check class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-yellow-700">Validasi Judul</p>
                        @if ($stats['pending_kalab'] > 0)
                            <span class="text-xs font-bold text-yellow-600">{{ $stats['pending_kalab'] }} menunggu</span>
                        @else
                            <span class="text-xs text-gray-400">Validasi judul dosen</span>
                        @endif
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-yellow-500" />
                </a>
                <a href="{{ route('ka-lab.pengajuan.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-sky-200 bg-sky-100 text-sky-600 transition group-hover:bg-sky-200">
                        <x-heroicon-o-clipboard-document-list class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-sky-700">Pengajuan Mahasiswa</p>
                        @if ($pengajuanStats['pending_review'] > 0)
                            <span class="text-xs font-bold text-sky-600">{{ $pengajuanStats['pending_review'] }}
                                menunggu</span>
                        @else
                            <span class="text-xs text-gray-400">Review pengajuan TA</span>
                        @endif
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-sky-500" />
                </a>
                <a href="{{ route('ka-lab.judul.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-emerald-200 bg-emerald-100 text-emerald-600 transition group-hover:bg-emerald-200">
                        <x-heroicon-o-document-text class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-emerald-700">Monitoring Judul</p>
                        <span class="text-xs text-gray-400">{{ $stats['total_judul'] }} total judul</span>
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                </a>
            </div>

            {{-- ===== RINGKASAN JUDUL (kartu ringan) ===== --}}
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Draft</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                            <x-heroicon-o-document class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-gray-800">{{ $stats['draft'] }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Perlu Validasi</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">
                            <x-heroicon-o-clock class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-yellow-600">{{ $stats['pending_kalab'] }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Ditawarkan</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                            <x-heroicon-o-check-circle class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600">{{ $stats['ditawarkan'] }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Ditolak</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 text-red-600">
                            <x-heroicon-o-x-circle class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-red-600">{{ $stats['ditolak'] }}</p>
                </div>
            </div>

            {{-- ===== SECTION: VISUALISASI DATA ===== --}}
            @php
                $totalJudulChart = $stats['draft'] + $stats['pending_kalab'] + $stats['ditawarkan'] + $stats['ditolak_kalab'];
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
                {{-- Donut: Distribusi Status Judul --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div class="flex items-center gap-3 border-b-4 border-sky-200 bg-gradient-to-r from-sky-600 to-blue-700 px-6 py-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-chart-pie class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Distribusi Status Judul</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            @if ($totalJudulChart > 0)
                                <canvas id="judulDonut"></canvas>
                            @else
                                <div class="flex h-full flex-col items-center justify-center text-center">
                                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-300" />
                                    <p class="mt-3 text-sm font-semibold text-gray-400">Belum ada judul</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Line: Tren Pengajuan Masuk --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Tren Pengajuan Masuk</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            <canvas id="trenPengajuanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bar: Tren Keputusan Validasi --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Tren Keputusan Validasi (per Periode)</h3>
                </div>
                <div class="p-6">
                    <div class="relative h-[300px]">
                        <canvas id="trenKeputusanChart"></canvas>
                    </div>
                </div>
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

        {{-- ===== CHARTS ===== --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartFont = {
                size: 11
            };

            // Donut: Distribusi Status Judul
            @if ($totalJudulChart > 0)
                new Chart(document.getElementById('judulDonut'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Draft', 'Pending Validasi', 'Ditawarkan', 'Ditolak'],
                        datasets: [{
                            data: [{{ $stats['draft'] }}, {{ $stats['pending_kalab'] }}, {{ $stats['ditawarkan'] }}, {{ $stats['ditolak_kalab'] }}],
                            backgroundColor: ['#94a3b8', '#fbbf24', '#10b981', '#ef4444'],
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

            // Line: Tren Pengajuan Masuk
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

            // Bar: Tren Keputusan Validasi
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

</x-layout-kalab>
