<x-layout-koor-ta title="Dashboard">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            <x-periode-banner />

            {{-- ===== WELCOME BANNER ===== --}}
            <div
                class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-7 shadow-xl">
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
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Koordinator Tugas
                                Akhir</p>
                            <h2 class="mt-1 text-2xl font-black text-white leading-tight">
                                Selamat Datang, {{ auth()->user()->name }}
                            </h2>
                            <p class="mt-1 text-sm text-indigo-200">
                                Panel Administrasi Sistem Pengajuan Judul TA
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
                                @if ($periodeAktif)
                                    <span
                                        class="flex items-center gap-1.5 rounded-full border border-emerald-300/40 bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-200">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span>
                                        {{ $periodeAktif->nama }}
                                    </span>
                                @else
                                    <span
                                        class="flex items-center gap-1.5 rounded-full border border-red-300/40 bg-red-500/20 px-3 py-1 text-xs font-semibold text-red-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                        Tidak ada periode aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats di Banner --}}
                    <div class="hidden lg:flex shrink-0 gap-3">
                        <div
                            class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Mahasiswa</p>
                            <p class="mt-1 text-4xl font-black text-white">{{ $totalMahasiswa }}</p>
                        </div>
                        <div
                            class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Dosen</p>
                            <p class="mt-1 text-4xl font-black text-white">{{ $totalDosen }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== AKSES CEPAT (di atas agar langsung terlihat) ===== --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('koor-ta.periode.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-blue-200 bg-blue-100 text-blue-600 transition group-hover:bg-blue-200">
                        <x-heroicon-o-calendar-days class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-blue-700">Periode</p>
                        <span class="text-xs {{ $periodeAktif ? 'font-bold text-emerald-600' : 'text-gray-400' }}">
                            {{ $periodeAktif ? 'Aktif' : 'Belum ada aktif' }}</span>
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-blue-500" />
                </a>
                <a href="{{ route('koor-ta.pengumuman.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-violet-200 bg-violet-100 text-violet-600 transition group-hover:bg-violet-200">
                        <x-heroicon-o-megaphone class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-violet-700">Pengumuman</p>
                        <span class="text-xs text-gray-400">Broadcast hasil</span>
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-violet-500" />
                </a>
                <a href="{{ route('koor-ta.monitoring.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-emerald-200 bg-emerald-100 text-emerald-600 transition group-hover:bg-emerald-200">
                        <x-heroicon-o-chart-bar class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-emerald-700">Monitoring</p>
                        <span class="text-xs text-gray-400">{{ $totalPengajuan }} pengajuan</span>
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                </a>
                <a href="{{ route('koor-ta.users.index') }}"
                    class="group flex items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-indigo-200 bg-indigo-100 text-indigo-600 transition group-hover:bg-indigo-200">
                        <x-heroicon-o-users class="h-5 w-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-extrabold text-gray-800 group-hover:text-indigo-700">Pengguna</p>
                        <span class="text-xs text-gray-400">{{ $totalMahasiswa + $totalDosen }} user</span>
                    </div>
                    <x-heroicon-o-arrow-right
                        class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-indigo-500" />
                </a>
            </div>

            {{-- ===== RINGKASAN (kartu ringan) ===== --}}
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Total Pengajuan</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                            <x-heroicon-o-document-text class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-gray-800">{{ $totalPengajuan }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Menunggu Review</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">
                            <x-heroicon-o-clock class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-yellow-600">{{ $pengajuanPending }}</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-400">Final Disetujui</p>
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                            <x-heroicon-o-check-circle class="h-4 w-4" />
                        </div>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600">{{ $pengajuanSelesai }}</p>
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
            </div>

            {{-- ===== SECTION: VISUALISASI DATA ===== --}}
            @php $totalKeputusanChart = array_sum($distribusiKeputusan); @endphp
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar-square class="h-3 w-3" />
                    Visualisasi Data
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Donut: Distribusi Keputusan (Periode Aktif) --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-chart-pie class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">Distribusi Keputusan</h3>
                            <p class="text-xs text-indigo-200">Periode aktif{{ $periodeAktif ? ' · ' . $periodeAktif->nama : '' }}</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            @if ($totalKeputusanChart > 0)
                                <canvas id="keputusanDonut"></canvas>
                            @else
                                <div class="flex h-full flex-col items-center justify-center text-center">
                                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-300" />
                                    <p class="mt-3 text-sm font-semibold text-gray-400">Belum ada pengajuan di periode aktif</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Line: Tren Pengajuan per Periode --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div class="flex items-center gap-3 border-b-4 border-sky-200 bg-gradient-to-r from-sky-600 to-blue-700 px-6 py-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-arrow-trending-up class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Tren Pengajuan (per Periode)</h3>
                    </div>
                    <div class="p-6">
                        <div class="relative h-[280px]">
                            @if ($trenPengajuan->sum('total') > 0)
                                <canvas id="trenPengajuanChart"></canvas>
                            @else
                                <div class="flex h-full flex-col items-center justify-center text-center">
                                    <x-heroicon-o-inbox class="h-12 w-12 text-gray-300" />
                                    <p class="mt-3 text-sm font-semibold text-gray-400">Belum ada data pengajuan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bar: Tren Keputusan Final per Periode --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Tren Keputusan Final (per Periode)</h3>
                </div>
                <div class="p-6">
                    <div class="relative h-[300px]">
                        @if ($trenKeputusan->sum('disetujui') + $trenKeputusan->sum('ditolak') > 0)
                            <canvas id="trenKeputusanChart"></canvas>
                        @else
                            <div class="flex h-full flex-col items-center justify-center text-center">
                                <x-heroicon-o-inbox class="h-12 w-12 text-gray-300" />
                                <p class="mt-3 text-sm font-semibold text-gray-400">Belum ada keputusan final</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartFont = { size: 11 };

            // Donut: Distribusi Keputusan (periode aktif)
            @if ($totalKeputusanChart > 0)
                new Chart(document.getElementById('keputusanDonut'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Selesai', 'Diproses', 'Menunggu', 'Ditolak'],
                        datasets: [{
                            data: [
                                {{ $distribusiKeputusan['selesai'] }},
                                {{ $distribusiKeputusan['proses'] }},
                                {{ $distribusiKeputusan['pending'] }},
                                {{ $distribusiKeputusan['ditolak'] }}
                            ],
                            backgroundColor: ['#10b981', '#6366f1', '#fbbf24', '#ef4444'],
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
                                labels: { padding: 16, font: { size: 12, weight: 'bold' }, usePointStyle: true, pointStyleWidth: 10 }
                            }
                        }
                    }
                });
            @endif

            // Line: Tren Pengajuan per Periode
            @if ($trenPengajuan->sum('total') > 0)
                new Chart(document.getElementById('trenPengajuanChart'), {
                    type: 'line',
                    data: {
                        labels: [@foreach ($trenPengajuan as $t)"{{ $t->nama }}", @endforeach],
                        datasets: [{
                            label: 'Pengajuan',
                            data: [@foreach ($trenPengajuan as $t){{ $t->total }}, @endforeach],
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
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: chartFont, precision: 0 } },
                            x: { grid: { display: false }, ticks: { font: chartFont } }
                        }
                    }
                });
            @endif

            // Bar: Tren Keputusan Final per Periode
            @if ($trenKeputusan->sum('disetujui') + $trenKeputusan->sum('ditolak') > 0)
                new Chart(document.getElementById('trenKeputusanChart'), {
                    type: 'bar',
                    data: {
                        labels: [@foreach ($trenKeputusan as $t)"{{ $t->nama }}", @endforeach],
                        datasets: [
                            {
                                label: 'Disetujui',
                                data: [@foreach ($trenKeputusan as $t){{ $t->disetujui }}, @endforeach],
                                backgroundColor: '#10b981',
                                borderRadius: 8,
                                borderSkipped: false,
                            },
                            {
                                label: 'Ditolak',
                                data: [@foreach ($trenKeputusan as $t){{ $t->ditolak }}, @endforeach],
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
                                labels: { padding: 16, font: { size: 12, weight: 'bold' }, usePointStyle: true, pointStyleWidth: 10 }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: chartFont, precision: 0 } },
                            x: { grid: { display: false }, ticks: { font: chartFont } }
                        }
                    }
                });
            @endif
        </script>
    @endpush

</x-layout-koor-ta>
