<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="space-y-6">

        {{-- ================= GREETING CARD ================= --}}
        <div
            class="relative bg-gradient-to-br from-emerald-600 via-green-700 to-teal-700 text-white rounded-2xl shadow-xl overflow-hidden">
            {{-- Background Pattern --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-1/2 translate-x-1/2">
                </div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-1/2 -translate-x-1/2">
                </div>
            </div>

            <div class="relative p-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-emerald-200 text-sm font-medium mb-2">
                            {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                        <h1 class="text-3xl font-bold mb-2">
                            Selamat Datang, {{ auth()->user()->name }}! 👨‍🏫
                        </h1>
                        <p class="text-emerald-100 text-sm max-w-md">
                            Periode: <span class="font-semibold">Semester {{ ucfirst($periodeAktif->semester) }}
                                {{ $periodeAktif->tahun_ajaran }}</span>
                        </p>
                    </div>

                    {{-- Quick Stats Badge --}}
                    <div class="hidden md:flex flex-col gap-2">
                        <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl text-center">
                            <p class="text-2xl font-bold">{{ $pending }}</p>
                            <p class="text-xs text-emerald-200">Perlu Review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= STATS CARDS ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Total Pengajuan --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => { let interval = setInterval(() => { if (count < {{ $total }}) count++;
                    else clearInterval(interval); }, 30); }, 100)"
                class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-indigo-200 group">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Pengajuan</p>
                <p class="text-3xl font-bold text-gray-800" x-text="count"></p>
                <p class="text-xs text-gray-400 mt-1">Periode ini</p>
            </div>

            {{-- Disetujui --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => { let interval = setInterval(() => { if (count < {{ $disetujui }}) count++;
                    else clearInterval(interval); }, 30); }, 200)"
                class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-green-200 group">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-green-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-green-700 text-sm font-medium mb-1">Disetujui</p>
                <p class="text-3xl font-bold text-green-600" x-text="count"></p>
                <p class="text-xs text-green-600 mt-1">{{ $approvalRate }}% approval rate</p>
            </div>

            {{-- Ditolak --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => { let interval = setInterval(() => { if (count < {{ $ditolak }}) count++;
                    else clearInterval(interval); }, 30); }, 300)"
                class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-red-200 group">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-red-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-red-700 text-sm font-medium mb-1">Ditolak</p>
                <p class="text-3xl font-bold text-red-600" x-text="count"></p>
                <p class="text-xs text-red-600 mt-1">Perlu feedback</p>
            </div>

            {{-- Pending --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => { let interval = setInterval(() => { if (count < {{ $pending }}) count++;
                    else clearInterval(interval); }, 30); }, 400)"
                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-yellow-200 group">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-yellow-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-yellow-700 text-sm font-medium mb-1">Menunggu Review</p>
                <p class="text-3xl font-bold text-yellow-600" x-text="count"></p>
                <p class="text-xs text-yellow-600 mt-1">Perlu tindakan</p>
            </div>

        </div>

        {{-- ================= RECENT SUBMISSIONS & QUICK ACTIONS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Recent Submissions --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Pengajuan Terbaru (Pending)</h3>
                    <a href="{{ route('dosen.pengajuan') }}"
                        class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentSubmissions as $submission)
                        <div
                            class="flex items-start justify-between p-4 rounded-xl border border-gray-200 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all group">

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-gray-800">{{ $submission->mahasiswa->name }}</p>
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">
                                        Pending
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 truncate">
                                    {{ $submission->jenis === 'mandiri' ? $submission->judul_mandiri : $submission->judul->nama_judul }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $submission->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <a href="{{ route('dosen.pengajuan') }}"
                                class="ml-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                Review
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500">Tidak ada pengajuan pending</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('dosen.pengajuan') }}"
                            class="flex items-center gap-3 p-4 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl hover:shadow-lg transition-all group">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold">Review Pengajuan</p>
                                <p class="text-xs text-emerald-100">{{ $pending }} menunggu</p>
                            </div>
                        </a>

                        <a href="{{ route('dosen.judul.index') }}"
                            class="flex items-center gap-3 p-4 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Kelola Judul</p>
                                <p class="text-xs text-gray-500">Tambah/edit judul</p>
                            </div>
                        </a>

                        <a href="{{ route('dosen.notifikasi') }}"
                            class="flex items-center gap-3 p-4 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform relative">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="$store.notifDosen.unread > 0"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Notifikasi</p>
                                <p class="text-xs text-gray-500">Lihat update</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= CHARTS ROW 1 ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Donut Chart --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Keputusan</h3>
                <div class="relative h-[280px]">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>

            {{-- Tren Pengajuan --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Pengajuan</h3>
                <div class="relative h-[280px]">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>

        </div>

        {{-- ================= CHART ROW 2 ================= --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Keputusan</h3>
            <div class="relative h-[300px]">
                <canvas id="keputusanChart"></canvas>
            </div>
        </div>

        {{-- ================= LAB STATS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Lab Disetujui --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Lab dengan Persetujuan Terbanyak
                </h3>

                @forelse($labDisetujui as $lab)
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">{{ $lab->nama }}</span>
                            <span class="font-bold text-green-600">{{ $lab->total }}</span>
                        </div>
                        <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2.5 rounded-full transition-all duration-700"
                                style="width: {{ min($lab->total * 10, 100) }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada data</p>
                @endforelse
            </div>

            {{-- Lab Ditolak --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Lab dengan Penolakan Tinggi
                </h3>

                @forelse($labDitolak as $lab)
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">{{ $lab->nama }}</span>
                            <span class="font-bold text-red-600">{{ $lab->total }}</span>
                        </div>
                        <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-red-500 to-pink-500 h-2.5 rounded-full transition-all duration-700"
                                style="width: {{ min($lab->total * 10, 100) }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada data</p>
                @endforelse
            </div>

        </div>

        {{-- ================= RASIO LAB ================= --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Rasio Persetujuan per Lab</h3>

            @foreach ($rasioLab as $lab)
                @php
                    $total = $lab->disetujui + $lab->ditolak;
                    $rasio = $total > 0 ? round(($lab->disetujui / $total) * 100) : 0;
                @endphp

                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">{{ $lab->nama }}</span>
                        <span class="font-bold text-indigo-600">{{ $rasio }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2.5 rounded-full transition-all duration-700"
                            style="width: {{ $rasio }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- ================= CHART SCRIPTS ================= --}}
    <script>
        // Donut Chart
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [{{ $pending }}, {{ $disetujui }}, {{ $ditolak }}],
                    backgroundColor: ['#fbbf24', '#10b981', '#ef4444'],
                    borderWidth: 0
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
                            padding: 15,
                            font: {
                                size: 12
                            }
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
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
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
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
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
                        borderRadius: 8
                    },
                    {
                        label: 'Ditolak',
                        data: [
                            @foreach ($trenKeputusan as $t)
                                {{ $t->ditolak }},
                            @endforeach
                        ],
                        backgroundColor: '#ef4444',
                        borderRadius: 8
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
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

</x-layout-dosen>
