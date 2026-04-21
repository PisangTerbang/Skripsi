<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data x-init="$store.dashboard.init()" class="space-y-6">

        {{-- ================= GREETING ================= --}}
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-6 rounded-xl shadow-lg">
            <h1 class="text-2xl font-bold">
                Halo, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-sm opacity-90 mt-1">
                Pantau status pengajuan judul skripsi Anda di sini.
            </p>
        </div>

        {{-- ================= HERO ================= --}}
        @if ($disetujui)
            <div
                class="bg-gradient-to-r from-green-400 to-emerald-500 text-white p-6 rounded-xl shadow-lg relative overflow-hidden">
                <div class="absolute right-4 top-4 text-5xl opacity-10">🎓</div>

                <p class="text-sm font-semibold">🎉 Judul Disetujui</p>

                <h2 class="text-2xl font-bold mt-2">
                    {{ $disetujui->judul->nama_judul ?? $disetujui->judul_mandiri }}
                </h2>

                <p class="text-sm mt-1 opacity-90">
                    Lanjut ke tahap bimbingan dengan dosen.
                </p>
            </div>
        @endif

        {{-- ================= PROGRESS (REALTIME) ================= --}}
        <div class="bg-white p-6 rounded-xl shadow">

            <h3 class="font-semibold mb-4">Progress Pengajuan</h3>

            <div class="flex justify-between text-xs mb-2">
                <span :class="$store.dashboard.step >= 1 && 'text-indigo-600 font-semibold'">Ajukan</span>
                <span :class="$store.dashboard.step >= 2 && 'text-indigo-600 font-semibold'">Diproses</span>
                <span :class="$store.dashboard.step >= 3 && 'text-indigo-600 font-semibold'">Review</span>
                <span :class="$store.dashboard.step >= 4 && 'text-indigo-600 font-semibold'">Selesai</span>
            </div>

            <div class="w-full bg-gray-200 h-2 rounded overflow-hidden">
                <div class="h-2 bg-indigo-500 transition-all duration-700"
                    :style="`width: ${$store.dashboard.progressWidth}%`"></div>
            </div>

        </div>

        {{-- ================= USER STAT ================= --}}
        <div class="grid md:grid-cols-3 gap-4">

            <div class="bg-white shadow rounded-xl p-4">
                <p class="text-xs text-gray-400">Total Pengajuan</p>
                <p class="text-2xl font-bold">{{ $total }}</p>
            </div>

            <div class="bg-yellow-50 shadow rounded-xl p-4">
                <p class="text-xs text-yellow-600">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pending }}</p>
            </div>

            <div class="bg-red-50 shadow rounded-xl p-4">
                <p class="text-xs text-red-600">Ditolak</p>
                <p class="text-2xl font-bold text-red-600">{{ $ditolak }}</p>
            </div>

        </div>

        {{-- ================= GLOBAL ================= --}}
        <div class="grid md:grid-cols-2 gap-4">

            <div class="bg-indigo-50 shadow rounded-xl p-4">
                <p class="text-xs text-indigo-600">Total Semua Pengajuan</p>
                <p class="text-2xl font-bold text-indigo-700">{{ $totalSemua }}</p>
            </div>

            <div class="bg-green-50 shadow rounded-xl p-4">
                <p class="text-xs text-green-600">Total Disetujui</p>
                <p class="text-2xl font-bold text-green-700">{{ $disetujuiSemua }}</p>
            </div>

        </div>

        {{-- ================= RIWAYAT (REALTIME + ANIMASI) ================= --}}
        <div class="bg-white shadow rounded-xl p-5">

            <h3 class="font-semibold text-gray-800 mb-3">
                Riwayat Pengajuan Terakhir
            </h3>

            <template x-for="item in $store.dashboard.riwayat" :key="item.judul">

                <div class="border-b py-3 flex justify-between transition-all duration-500"
                    :class="item.isNew ? 'bg-yellow-100 animate-pulse' : ''">

                    <div>
                        <div class="font-medium" x-text="item.judul"></div>
                        <div class="text-xs text-gray-400" x-text="item.waktu"></div>
                    </div>

                    <span class="px-2 py-1 text-xs rounded"
                        :class="{
                            'bg-yellow-100 text-yellow-600': item.status === 'pending',
                            'bg-green-100 text-green-600': item.status === 'disetujui',
                            'bg-red-100 text-red-600': item.status === 'ditolak'
                        }"
                        x-text="item.status"></span>

                </div>

            </template>

        </div>

        {{-- ================= ACTION ================= --}}
        <div class="flex gap-3">

            <a href="{{ route('mahasiswa.pengajuan') }}"
                class="flex-1 bg-indigo-500 hover:bg-indigo-600 text-white text-center py-3 rounded-xl font-semibold">
                Ajukan Judul
            </a>

            <a href="{{ route('mahasiswa.riwayat') }}"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-center py-3 rounded-xl">
                Lihat Riwayat
            </a>

        </div>

    </div>
</x-layout>
<script>
    document.addEventListener('alpine:init', () => {

        Alpine.store('dashboard', {

            // ================= STATE =================
            jumlah: 0,
            riwayat: [],
            lastRiwayat: [],
            status: null,
            lastStatus: null,

            notif: 0,
            lastNotif: 0,

            step: 1,
            progressWidth: 25,

            interval: null,

            // ================= STATUS MAP =================
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

            // ================= FETCH (FIXED) =================
            async fetch() {
                try {

                    const res = await fetch("{{ route('mahasiswa.beranda.data') }}", {
                        method: 'GET',
                        credentials: 'same-origin', // 🔥 WAJIB
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!res.ok) throw new Error('Network error');

                    const data = await res.json();

                    // ===== DETECT NEW RIWAYAT =====
                    const old = this.lastRiwayat.map(i => i.judul);

                    data.riwayat = data.riwayat.map(item => ({
                        ...item,
                        isNew: !old.includes(item.judul)
                    }));

                    this.lastRiwayat = data.riwayat;

                    // ===== UPDATE STATE =====
                    this.riwayat = data.riwayat;
                    this.status = data.status;
                    this.jumlah = data.jumlah;
                    this.notif = data.notif ?? 0;

                    // ===== UPDATE PROGRESS =====
                    this.updateProgress(data.status);

                    // ===== TOAST STATUS =====
                    if (this.lastStatus && this.lastStatus !== data.status) {
                        this.toast("Status berubah: " + data.status);
                    }

                    // ===== TOAST NOTIF =====
                    if (this.notif > this.lastNotif) {
                        this.toast("Ada notifikasi baru 🔔");
                    }

                    this.lastStatus = data.status;
                    this.lastNotif = this.notif;

                } catch (e) {
                    console.error("Fetch error:", e);
                }
            },

            // ================= TOAST =================
            toast(msg) {
                const el = document.createElement('div');
                el.innerText = msg;

                el.className =
                    "fixed top-5 right-5 bg-indigo-600 text-white px-4 py-2 rounded shadow z-50 animate-bounce";

                document.body.appendChild(el);

                setTimeout(() => el.remove(), 3000);
            },

            // ================= INIT =================
            init() {
                this.fetch();

                if (this.interval) clearInterval(this.interval);

                this.interval = setInterval(() => {
                    this.fetch();
                }, 5000);
            }
        });

    });
</script>
