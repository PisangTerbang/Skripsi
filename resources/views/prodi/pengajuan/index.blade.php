<x-layout-prodi title="Pengajuan Judul TA">

    <div class="min-h-screen bg-slate-100">

        {{-- ===== TOP BAR ===== --}}
        <div class="sticky top-0 z-10 border-b-2 border-violet-100 bg-white px-6 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-violet-200 bg-violet-50">
                        <x-heroicon-o-document-text class="h-5 w-5 text-violet-600" />
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg font-extrabold text-gray-900">Review Pengajuan Judul TA</h1>
                        </div>
                        <p class="mt-0.5 text-xs text-gray-400">
                            Pengajuan yang telah disetujui Laboratorium dan menunggu keputusan Kaprodi
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-600">
                        <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                        {{ now()->translatedFormat('d F Y') }}
                    </span>
                    <a href="{{ route('prodi.pengajuan.riwayat') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700 hover:shadow-md">
                        <x-heroicon-o-clock class="h-3.5 w-3.5" />
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                class="mx-6 mt-5 flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-green-500" />
                <span class="font-semibold">{{ session('success') }}</span>
                <button @click="show = false"
                    class="ml-auto rounded-lg p-1 text-green-400 hover:bg-green-100 hover:text-green-600 transition">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>
        @endif

        <div class="px-6 py-6 space-y-6">

            {{-- ===== STATS ===== --}}
            @php
                $totalDisetujui = \App\Models\Pengajuan::where('status_kaprodi', 'disetujui')->count();
                $totalDitolak = \App\Models\Pengajuan::where('status_kaprodi', 'ditolak')->count();
            @endphp

            {{-- Section Label --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Ringkasan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                {{-- Menunggu Review --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-600 via-violet-700 to-purple-800 p-6 shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-300">Menunggu Review</p>
                            <p class="mt-3 text-6xl font-black leading-none text-white">{{ $pengajuan->count() }}</p>
                            <p class="mt-2 text-xs font-medium text-violet-300">pengajuan aktif</p>
                        </div>
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/15 backdrop-blur-sm">
                            <x-heroicon-o-clock class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="mt-5 space-y-1">
                        <div class="flex justify-between text-xs text-violet-300">
                            <span>Perlu ditindaklanjuti</span>
                            <span>{{ $pengajuan->count() }} antrian</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full w-full animate-pulse rounded-full bg-white/60"></div>
                        </div>
                    </div>
                </div>

                {{-- Total Disetujui --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Total Disetujui</p>
                            <p class="mt-3 text-6xl font-black leading-none text-white">{{ $totalDisetujui }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">sepanjang waktu</p>
                        </div>
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/15 backdrop-blur-sm">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="mt-5 space-y-1">
                        <div class="flex justify-between text-xs text-emerald-200">
                            <span>Sudah diproses</span>
                            <span>✓ Selesai</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full w-full rounded-full bg-white/60"></div>
                        </div>
                    </div>
                </div>

                {{-- Total Ditolak --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-red-200">Total Ditolak</p>
                            <p class="mt-3 text-6xl font-black leading-none text-white">{{ $totalDitolak }}</p>
                            <p class="mt-2 text-xs font-medium text-red-200">sepanjang waktu</p>
                        </div>
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/15 backdrop-blur-sm">
                            <x-heroicon-o-x-circle class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="mt-5 space-y-1">
                        <div class="flex justify-between text-xs text-red-200">
                            <span>Sudah diproses</span>
                            <span>✗ Ditolak</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full w-full rounded-full bg-white/60"></div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Section Label --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-table-cells class="h-3 w-3" />
                    Daftar Pengajuan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                {{-- Card Header --}}
                <div
                    class="flex items-center justify-between border-b-4 border-violet-200 bg-gradient-to-r from-violet-700 via-violet-600 to-purple-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 backdrop-blur-sm">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Daftar Pengajuan</h2>
                            <p class="text-xs text-violet-200">Menunggu keputusan Kaprodi</p>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        {{ $pengajuan->count() }} pengajuan
                    </span>
                </div>

                @if ($pengajuan->isEmpty())
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="relative mb-6">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-violet-100 bg-gradient-to-br from-violet-50 to-purple-100 shadow-inner">
                                <x-heroicon-o-inbox class="h-12 w-12 text-violet-300" />
                            </div>
                            <div
                                class="absolute -right-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-violet-600 shadow-md">
                                <x-heroicon-o-check class="h-4 w-4 text-white" />
                            </div>
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Tidak ada pengajuan</p>
                        <p class="mt-2 max-w-xs text-sm leading-relaxed text-gray-400">
                            Belum ada pengajuan yang menunggu review Kaprodi saat ini.
                        </p>
                        <a href="{{ route('prodi.pengajuan.riwayat') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-violet-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-violet-700 hover:shadow-lg">
                            <x-heroicon-o-clock class="h-4 w-4" />
                            Lihat Riwayat Review
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <caption class="sr-only">Daftar Pengajuan Judul TA</caption>
                            <thead>
                                <tr
                                    class="sticky top-0 z-20 border-b border-gray-200 bg-gray-50/95 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 backdrop-blur-sm">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Mahasiswa</th>
                                    <th class="px-6 py-4">Judul Ditetapkan</th>
                                    <th class="px-6 py-4">Dosen Pembimbing</th>
                                    <th class="px-6 py-4">Periode</th>
                                    <th class="px-6 py-4">Status Kalab</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($pengajuan as $index => $item)
                                    <tr class="group transition-colors duration-200 hover:bg-violet-50/40">

                                        {{-- No --}}
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full bg-gradient-to-b from-violet-400 to-purple-500">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-violet-300 group-hover:bg-violet-50 group-hover:text-violet-700 transition-all">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Mahasiswa --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-sm font-black text-white shadow-md ring-2 ring-violet-200">
                                                    {{ strtoupper(substr($item->mahasiswa->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $item->mahasiswa->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ $item->mahasiswa->nim ?? $item->mahasiswa->email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Judul --}}
                                        <td class="max-w-[240px] px-6 py-4">
                                            @if ($item->judulDitetapkan)
                                                <p
                                                    class="line-clamp-2 text-sm font-semibold leading-relaxed text-gray-800">
                                                    {{ $item->judulDitetapkan->judul }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $item->judulDitetapkan->laboratorium->nama ?? '-' }}
                                                </p>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-lg border-2 border-orange-200 bg-orange-50 px-2.5 py-1 text-xs font-bold text-orange-600">
                                                    <x-heroicon-o-exclamation-triangle class="h-3 w-3" />
                                                    Belum ditetapkan
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Dosen --}}
                                        <td class="px-6 py-4">
                                            @if ($item->judulDitetapkan?->dosen)
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-blue-100 bg-blue-100 text-xs font-black text-blue-600">
                                                        {{ strtoupper(substr($item->judulDitetapkan->dosen->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-700">
                                                            {{ $item->judulDitetapkan->dosen->name }}
                                                        </p>
                                                        <p class="text-xs text-gray-400">Dosen Pembimbing</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>

                                        {{-- Periode --}}
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full border border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700 shadow-sm transition-all hover:bg-violet-100">
                                                <svg class="h-3.5 w-3.5 text-violet-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ $item->periode->nama ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Status Kalab --}}
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="space-y-1.5">
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Disetujui
                                                </span>
                                                @if ($item->reviewerKalab)
                                                    <p class="text-xs text-gray-400">{{ $item->reviewerKalab->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            <a href="{{ route('prodi.pengajuan.show', $item->id) }}"
                                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border-2 border-violet-300 bg-violet-600 px-3 py-2 text-xs font-black text-white shadow-sm transition hover:bg-violet-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-1 sm:w-auto">
                                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                                Review
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Table Footer --}}
                    <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                        <p class="text-xs font-semibold text-gray-500">
                            Menampilkan <span class="font-black text-gray-800">{{ $pengajuan->count() }}</span>
                            pengajuan aktif
                        </p>
                        <div class="flex items-center gap-2 text-xs">
                            <span
                                class="flex items-center gap-1.5 rounded-lg border border-violet-200 bg-violet-50 px-3 py-1.5 font-bold text-violet-600">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-violet-500"></span>
                                Menunggu keputusan Kaprodi
                            </span>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-layout-prodi>
