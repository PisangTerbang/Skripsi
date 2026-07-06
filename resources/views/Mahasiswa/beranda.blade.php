<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="dashboardMahasiswa()" x-init="init()" class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">



            <x-periode-banner />

            {{-- ===== HEADER: Greeting + Status (satu baris agar progress langsung terlihat) ===== --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Greeting (ringkas) --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-6 shadow-xl">
                    <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="relative flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border-2 border-white/30 bg-white/20 text-xl font-black text-white shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-widest text-indigo-300">Mahasiswa</p>
                            <h2 class="mt-0.5 truncate text-xl font-black leading-tight text-white">
                                Halo, {{ Str::before(auth()->user()->name, ' ') }}! 👋
                            </h2>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span
                                    class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-2.5 py-1 text-xs font-semibold text-white">
                                    <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                    {{ now()->format('H:i') }} WIB
                                </span>
                                @if (auth()->user()->nim)
                                    <span
                                        class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/15 px-2.5 py-1 text-xs font-semibold text-white">
                                        <x-heroicon-o-identification class="h-3.5 w-3.5" />
                                        {{ auth()->user()->nim }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status pengajuan periode aktif --}}
                @if ($disetujui && $sudahDiumumkan)
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-xl">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 px-2.5 py-0.5 text-xs font-black text-white">🎉
                                    Selamat!</span>
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-300/50 bg-emerald-400/20 px-2.5 py-0.5 text-xs font-black text-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-200"></span> Resmi Diumumkan
                                </span>
                            </div>
                            <h3 class="text-base font-black text-white">Judul TA Anda Telah Ditetapkan</h3>
                            <p class="mt-1 line-clamp-2 text-sm font-bold text-emerald-50">
                                "{{ $disetujui->judulDitetapkan->nama_judul ?? ($disetujui->judulDitetapkan->judul ?? ($disetujui->judul_mandiri ?? '-')) }}"
                            </p>
                            <a href="{{ route('mahasiswa.riwayat') }}"
                                class="mt-4 inline-flex items-center gap-2 rounded-xl border-2 border-white/30 bg-white px-4 py-2 text-sm font-black text-emerald-700 shadow-md transition hover:bg-emerald-50">
                                <x-heroicon-o-eye class="h-4 w-4" /> Lihat Detail
                            </a>
                        </div>
                    </div>
                @elseif ($adaProsesBerjalan)
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-6 shadow-xl">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 px-2.5 py-0.5 text-xs font-black text-white">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span> Sedang Diproses
                            </span>
                            <h3 class="mt-2 text-base font-black text-white">Pengajuan Anda Sedang Diproses</h3>
                            <p class="mt-1 text-sm font-medium text-blue-100">
                                Sedang ditinjau. Hasil resmi akan diumumkan oleh Koordinator TA — mohon menunggu.
                            </p>
                        </div>
                    </div>
                @else
                    <div
                        class="flex flex-col justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100">
                                <x-heroicon-o-document-plus class="h-6 w-6 text-indigo-600" />
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-800">Belum ada pengajuan</h3>
                                <p class="text-sm text-gray-400">Ajukan judul TA Anda untuk periode ini.</p>
                            </div>
                        </div>
                        <a href="{{ route('mahasiswa.pengajuan') }}"
                            class="mt-4 inline-flex w-fit items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                            <x-heroicon-o-plus class="h-4 w-4" /> Ajukan Sekarang
                        </a>
                    </div>
                @endif

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
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 text-white shadow-sm"
                                        x-bind:class="{
                                            'bg-gradient-to-br from-yellow-400 to-orange-500 border-yellow-200': item
                                                .status === 'pending',
                                            'bg-gradient-to-br from-emerald-500 to-green-600 border-emerald-200': item
                                                .status === 'disetujui',
                                            'bg-gradient-to-br from-red-500 to-rose-600 border-red-200': item
                                                .status === 'ditolak'
                                        }">
                                        <template x-if="item.status === 'pending'">
                                            <x-heroicon-o-clock class="h-5 w-5" />
                                        </template>
                                        <template x-if="item.status === 'disetujui'">
                                            <x-heroicon-o-check class="h-5 w-5" />
                                        </template>
                                        <template x-if="item.status === 'ditolak'">
                                            <x-heroicon-o-x-mark class="h-5 w-5" />
                                        </template>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        {{-- Label --}}
                                        <p
                                            class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-0.5">
                                            Pengajuan Judul TA
                                        </p>

                                        {{-- Judul utama --}}
                                        <p class="text-sm font-black text-gray-800 leading-snug line-clamp-1"
                                            x-text="item.judul"></p>

                                        {{-- Meta info --}}
                                        <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                            <span class="text-xs text-gray-400" x-text="item.waktu"></span>
                                            <span class="text-gray-300 text-xs">·</span>

                                            {{-- Badge 3 pilihan --}}
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[10px] font-black text-indigo-600">
                                                <x-heroicon-o-list-bullet class="h-3 w-3" />
                                                3 pilihan judul
                                            </span>

                                            {{-- Badge baru diperbarui --}}
                                            <template x-if="item.isNew">
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full border border-yellow-200 bg-yellow-50 px-2 py-0.5 text-[10px] font-black text-yellow-600">
                                                    <span
                                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                                    Baru diperbarui
                                                </span>
                                            </template>
                                        </div>

                                        {{-- Progress mini --}}
                                        <div class="mt-2 flex items-center gap-1.5">
                                            {{-- Step 1: Submit --}}
                                            <div class="flex items-center gap-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-indigo-500"></div>
                                                <span class="text-[10px] text-gray-400">Submit</span>
                                            </div>
                                            <div class="h-px w-4 bg-gray-200"></div>
                                            {{-- Step 2: Ka Lab --}}
                                            <div class="flex items-center gap-1">
                                                <div class="h-1.5 w-1.5 rounded-full"
                                                    x-bind:class="item.status !== 'pending' ? 'bg-indigo-500' : 'bg-gray-300'">
                                                </div>
                                                <span class="text-[10px] text-gray-400">Ka Lab</span>
                                            </div>
                                            <div class="h-px w-4 bg-gray-200"></div>
                                            {{-- Step 3: Kaprodi --}}
                                            <div class="flex items-center gap-1">
                                                <div class="h-1.5 w-1.5 rounded-full"
                                                    x-bind:class="item.status === 'disetujui' ? 'bg-indigo-500' : 'bg-gray-300'">
                                                </div>
                                                <span class="text-[10px] text-gray-400">Kaprodi</span>
                                            </div>
                                            <div class="h-px w-4 bg-gray-200"></div>
                                            {{-- Step 4: Pengumuman --}}
                                            <div class="flex items-center gap-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-gray-300"></div>
                                                <span class="text-[10px] text-gray-400">Pengumuman</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Status badge --}}
                                    <span
                                        class="shrink-0 inline-flex items-center gap-1.5 rounded-full border-2 px-3 py-1.5 text-xs font-black"
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
                            case 'diproses':
                                return 2; // sedang diproses — hasil dirahasiakan s/d pengumuman
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
                            case 'diproses':
                                return 'Sedang diproses — menunggu pengumuman resmi';
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
                        this.interval = setInterval(() => this.fetch(), 30000);
                    }
                }
            }
        </script>
    @endpush


</x-layout>
