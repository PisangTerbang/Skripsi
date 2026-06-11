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

                {{-- Total Pengajuan --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalPengajuan }}</p>
                            <p class="mt-2 text-xs font-medium text-indigo-200">semua periode</p>
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

                {{-- Pending --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Menunggu Review</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $pengajuanPending }}</p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">belum diproses Ka Lab</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clock class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full {{ $pengajuanPending > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                </div>

                {{-- Selesai --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Final Disetujui</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $pengajuanSelesai }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">disetujui Kaprodi</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                </div>

                {{-- Total Judul --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-200">Total Judul</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $totalJudul }}</p>
                            <p class="mt-2 text-xs font-medium text-blue-200">judul terdaftar</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-book-open class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
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

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- User Management --}}
                <a href="{{ route('koor-ta.users.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-indigo-50 transition group-hover:bg-indigo-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-indigo-200 bg-indigo-100 transition group-hover:border-indigo-400 group-hover:bg-indigo-200">
                            <x-heroicon-o-users class="h-6 w-6 text-indigo-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-indigo-700">User
                                Management</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Kelola semua akun pengguna sistem</p>
                            <span
                                class="mt-2 inline-flex items-center gap-1 rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-600">
                                {{ $totalMahasiswa + $totalDosen }} user
                            </span>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-indigo-500" />
                    </div>
                </a>

                {{-- Periode --}}
                <a href="{{ route('koor-ta.periode.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-blue-50 transition group-hover:bg-blue-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-blue-200 bg-blue-100 transition group-hover:border-blue-400 group-hover:bg-blue-200">
                            <x-heroicon-o-calendar-days class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-blue-700">Periode
                                Pengajuan</h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Buka & tutup periode pengajuan TA</p>
                            @if ($periodeAktif)
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="mt-2 inline-flex items-center gap-1 rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-bold text-gray-500">
                                    Tidak ada periode aktif
                                </span>
                            @endif
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-blue-500" />
                    </div>
                </a>

                {{-- Pengumuman --}}
                <a href="{{ route('koor-ta.pengumuman.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-violet-50 transition group-hover:bg-violet-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-violet-200 bg-violet-100 transition group-hover:border-violet-400 group-hover:bg-violet-200">
                            <x-heroicon-o-megaphone class="h-6 w-6 text-violet-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-violet-700">Pengumuman
                            </h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Broadcast hasil ke semua mahasiswa
                            </p>
                            <span
                                class="mt-2 inline-flex items-center gap-1 rounded-full border border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-bold text-violet-600">
                                Kirim notifikasi
                            </span>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-violet-500" />
                    </div>
                </a>

                {{-- Monitoring --}}
                <a href="{{ route('koor-ta.monitoring.index') }}"
                    class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-lg">
                    <div
                        class="absolute right-0 top-0 h-24 w-24 translate-x-8 -translate-y-8 rounded-full bg-emerald-50 transition group-hover:bg-emerald-100">
                    </div>
                    <div class="relative flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-emerald-200 bg-emerald-100 transition group-hover:border-emerald-400 group-hover:bg-emerald-200">
                            <x-heroicon-o-chart-bar class="h-6 w-6 text-emerald-600" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-extrabold text-gray-800 transition group-hover:text-emerald-700">Monitoring
                            </h3>
                            <p class="mt-1 text-xs text-gray-400 leading-relaxed">Pantau semua pengajuan & judul TA</p>
                            <span
                                class="mt-2 inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600">
                                {{ $totalPengajuan }} pengajuan
                            </span>
                        </div>
                        <x-heroicon-o-arrow-right
                            class="h-4 w-4 shrink-0 text-gray-300 transition group-hover:translate-x-1 group-hover:text-emerald-500" />
                    </div>
                </a>

            </div>

            {{-- ===== SECTION: RECENT ACTIVITY ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-clock class="h-3 w-3" />
                    Aktivitas Terbaru
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- User Terbaru --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-sm">
                    <div
                        class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-users class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">User Terbaru</h3>
                        </div>
                        <a href="{{ route('koor-ta.users.index') }}"
                            class="text-xs font-bold text-indigo-200 hover:text-white transition">
                            Lihat semua →
                        </a>
                    </div>
                    <div class="divide-y-2 divide-gray-100">
                        @forelse ($userTerbaru as $user)
                            <div class="flex items-center gap-3 px-6 py-3 hover:bg-indigo-50/30 transition">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-sm font-black text-white shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                                <span
                                    class="rounded-lg border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-xs font-bold text-indigo-600">
                                    {{ $user->role }}
                                </span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-400">Belum ada user</div>
                        @endforelse
                    </div>
                </div>

                {{-- Pengajuan Terbaru --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-sm">
                    <div
                        class="flex items-center justify-between border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-extrabold text-white">Pengajuan Terbaru</h3>
                        </div>
                        <a href="{{ route('koor-ta.monitoring.pengajuan') }}"
                            class="text-xs font-bold text-emerald-200 hover:text-white transition">
                            Lihat semua →
                        </a>
                    </div>
                    <div class="divide-y-2 divide-gray-100">
                        @forelse ($pengajuanTerbaru as $item)
                            <div class="flex items-center gap-3 px-6 py-3 hover:bg-emerald-50/30 transition">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-green-600 text-sm font-black text-white shadow-sm">
                                    {{ strtoupper(substr($item->mahasiswa->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $item->mahasiswa->name }}
                                    </p>
                                    <p class="text-xs text-gray-400 truncate">
                                        {{ $item->judulDitetapkan->nama_judul ?? 'Belum ditetapkan' }}
                                    </p>
                                </div>
                                @php
                                    $statusClass = match ($item->status) {
                                        'disetujui' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                        'ditolak' => 'border-red-200 bg-red-50 text-red-700',
                                        default => 'border-yellow-200 bg-yellow-50 text-yellow-700',
                                    };
                                    $statusLabel = match ($item->status) {
                                        'disetujui' => 'Selesai',
                                        'ditolak' => 'Ditolak',
                                        default => 'Proses',
                                    };
                                @endphp
                                <span class="rounded-lg border px-2 py-0.5 text-xs font-bold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-400">Belum ada pengajuan</div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

</x-layout-koor-ta>
