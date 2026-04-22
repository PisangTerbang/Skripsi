<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="dashboardMahasiswa()" x-init="init()" class="space-y-6">

        {{-- ================= GREETING CARD ================= --}}
        <div
            class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 text-white rounded-2xl shadow-xl overflow-hidden">
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
                        <p class="text-indigo-200 text-sm font-medium mb-2">
                            {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                        <h1 class="text-3xl font-bold mb-2">
                            Halo, {{ auth()->user()->name }}! 👋
                        </h1>
                        <p class="text-indigo-100 text-sm max-w-md">
                            Pantau progress pengajuan judul skripsi Anda secara realtime di dashboard ini.
                        </p>
                    </div>

                    {{-- Quick Stats Badge --}}
                    <div class="hidden md:flex flex-col gap-2">
                        <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl text-center">
                            <p class="text-2xl font-bold" x-text="stats.total"></p>
                            <p class="text-xs text-indigo-200">Total Pengajuan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= HERO - JUDUL DISETUJUI ================= --}}
        @if ($disetujui)
            <div
                class="relative bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-2xl shadow-xl overflow-hidden p-8">
                {{-- Decorative Elements --}}
                <div class="absolute right-8 top-8 text-8xl opacity-10">🎓</div>
                <div class="absolute left-8 bottom-8 text-6xl opacity-10">✨</div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold">
                            🎉 Selamat!
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold mb-2">
                        Judul Anda Telah Disetujui
                    </h2>

                    <p class="text-xl font-semibold text-emerald-50 mb-4">
                        "{{ $disetujui->judul->nama_judul ?? $disetujui->judul_mandiri }}"
                    </p>

                    <div class="flex items-center gap-4 text-sm text-emerald-100">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Disetujui {{ $disetujui->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('mahasiswa.riwayat') }}"
                            class="inline-flex items-center gap-2 bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg hover:shadow-xl">
                            Lihat Detail
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= STATS CARDS ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Total Pengajuan --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => {
                let interval = setInterval(() => {
                    if (count < {{ $total }}) count++;
                    else clearInterval(interval);
                }, 50);
            }, 200)"
                class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-indigo-200 group">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-gray-500 text-sm font-medium mb-2">Total Pengajuan</p>
                        <p class="text-4xl font-bold text-gray-800 mb-1" x-text="count"></p>
                        <p class="text-xs text-gray-400">Semua pengajuan Anda</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => {
                let interval = setInterval(() => {
                    if (count < {{ $pending }}) count++;
                    else clearInterval(interval);
                }, 50);
            }, 400)"
                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-yellow-200 group">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-yellow-700 text-sm font-medium mb-2">Menunggu Review</p>
                        <p class="text-4xl font-bold text-yellow-600 mb-1" x-text="count"></p>
                        <p class="text-xs text-yellow-600">Sedang diproses dosen</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-yellow-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Ditolak --}}
            <div x-data="{ count: 0 }" x-init="setTimeout(() => {
                let interval = setInterval(() => {
                    if (count < {{ $ditolak }}) count++;
                    else clearInterval(interval);
                }, 50);
            }, 600)"
                class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-red-200 group">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-red-700 text-sm font-medium mb-2">Ditolak</p>
                        <p class="text-4xl font-bold text-red-600 mb-1" x-text="count"></p>
                        <p class="text-xs text-red-600">Perlu pengajuan ulang</p>
                    </div>
                    <div
                        class="w-14 h-14 bg-red-200 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= PROGRESS BAR ================= --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Progress Pengajuan</h3>
                <span class="text-sm text-gray-500" x-text="`${progressWidth}% Complete`"></span>
            </div>

            {{-- Steps --}}
            <div class="flex justify-between text-xs mb-3">
                <div class="flex flex-col items-center"
                    :class="step >= 1 ? 'text-indigo-600 font-semibold' : 'text-gray-400'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all"
                        :class="step >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span>Ajukan</span>
                </div>

                <div class="flex flex-col items-center"
                    :class="step >= 2 ? 'text-indigo-600 font-semibold' : 'text-gray-400'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all"
                        :class="step >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span>Diproses</span>
                </div>

                <div class="flex flex-col items-center"
                    :class="step >= 3 ? 'text-indigo-600 font-semibold' : 'text-gray-400'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all"
                        :class="step >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span>Review</span>
                </div>

                <div class="flex flex-col items-center"
                    :class="step >= 4 ? 'text-indigo-600 font-semibold' : 'text-gray-400'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all"
                        :class="step >= 4 ? 'bg-indigo-600 text-white' : 'bg-gray-200'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Selesai</span>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 h-3 rounded-full overflow-hidden">
                <div class="h-3 bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-700 rounded-full"
                    :style="`width: ${progressWidth}%`"></div>
            </div>
        </div>

        {{-- ================= RIWAYAT & QUICK ACTIONS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Riwayat --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Pengajuan Terbaru</h3>
                    <a href="{{ route('mahasiswa.riwayat') }}"
                        class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    <template x-for="item in riwayat" :key="item.judul">
                        <div class="flex items-start justify-between p-4 rounded-xl border transition-all hover:shadow-md"
                            :class="item.isNew ? 'bg-yellow-50 border-yellow-200 animate-pulse' : 'bg-gray-50 border-gray-200'">

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate" x-text="item.judul"></p>
                                <p class="text-xs text-gray-500 mt-1" x-text="item.waktu"></p>
                            </div>

                            <span class="ml-4 px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                :class="{
                                    'bg-yellow-100 text-yellow-700': item.status === 'pending',
                                    'bg-green-100 text-green-700': item.status === 'disetujui',
                                    'bg-red-100 text-red-700': item.status === 'ditolak'
                                }"
                                x-text="item.status === 'pending' ? 'Pending' : item.status === 'disetujui' ? 'Disetujui' : 'Ditolak'">
                            </span>
                        </div>
                    </template>

                    <template x-if="riwayat.length === 0">
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500">Belum ada pengajuan</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('mahasiswa.pengajuan') }}"
                            class="flex items-center gap-3 p-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:shadow-lg transition-all group">
                            <div
                                class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold">Ajukan Judul</p>
                                <p class="text-xs text-indigo-100">Buat pengajuan baru</p>
                            </div>
                        </a>

                        <a href="{{ route('mahasiswa.riwayat') }}"
                            class="flex items-center gap-3 p-4 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Lihat Riwayat</p>
                                <p class="text-xs text-gray-500">Semua pengajuan</p>
                            </div>
                        </a>

                        <a href="{{ route('mahasiswa.notifikasi') }}"
                            class="flex items-center gap-3 p-4 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all group">
                            <div
                                class="w-10 h-10 bg-white rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform relative">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="$store.notif.unread > 0"
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-[10px] flex items-center justify-center font-bold">
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">Notifikasi</p>
                                <p class="text-xs text-gray-500">Lihat update terbaru</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Global Stats --}}
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 border border-indigo-100">
                    <h3 class="text-sm font-semibold text-indigo-900 mb-4">Statistik Global</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Pengajuan</span>
                            <span class="text-lg font-bold text-indigo-600">{{ $totalSemua }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Disetujui</span>
                            <span class="text-lg font-bold text-green-600">{{ $disetujuiSemua }}</span>
                        </div>
                        <div class="pt-2 border-t border-indigo-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Approval Rate</span>
                                <span class="text-sm font-bold text-indigo-600">
                                    {{ $totalSemua > 0 ? round(($disetujuiSemua / $totalSemua) * 100) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

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
                status: '{{ $disetujui ? 'disetujui' : ($pending > 0 ? 'pending' : 'none') }}',
                lastStatus: null,
                step: 1,
                progressWidth: 25,
                interval: null,

                mapStatus(status) {
                    switch (status) {
                        case 'pending':
                            return 2;
                        case 'review':
                            return 3;
                        case 'disetujui':
                            return 4;
                        case 'ditolak':
                            return 4;
                        default:
                            return 1;
                    }
                },

                updateProgress(status) {
                    this.step = this.mapStatus(status);
                    this.progressWidth = this.step * 25;
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
                            this.showToast('Status berubah: ' + data.status, 'success');
                        }

                        this.lastStatus = data.status;

                    } catch (e) {
                        console.error("Fetch error:", e);
                    }
                },

                showToast(message, type) {
                    type = type || 'success';
                    const toast = document.createElement('div');
                    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                    toast.className =
                        'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ' +
                        bgColor;

                    const iconPath = type === 'success' ?
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';

                    toast.innerHTML =
                        '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        iconPath + '</svg><span class="text-sm font-medium">' + message + '</span>';

                    const container = document.getElementById('toast-container');
                    container.appendChild(toast);

                    setTimeout(function() {
                        toast.classList.add('translate-x-0', 'opacity-100');
                    }, 10);

                    setTimeout(function() {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(function() {
                            toast.remove();
                        }, 300);
                    }, 3000);
                },

                init() {
                    this.updateProgress(this.status);
                    this.fetch();

                    if (this.interval) clearInterval(this.interval);

                    this.interval = setInterval(() => {
                        this.fetch();
                    }, 5000);
                }
            }
        }
    </script>

</x-layout>
