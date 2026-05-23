<x-layout-prodi title="Riwayat Review Pengajuan">

    <div class="min-h-screen bg-slate-100">

        {{-- ===== TOP BAR ===== --}}
        <div class="sticky top-0 z-10 border-b-2 border-violet-100 bg-white px-6 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Back Button --}}
                    <a href="{{ route('prodi.pengajuan.index') }}"
                        class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-violet-400 hover:bg-violet-50 hover:text-violet-600">
                        <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                    </a>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="h-5 w-5 text-violet-500" />
                            <h1 class="text-lg font-extrabold text-gray-900">Riwayat Review Pengajuan</h1>
                        </div>
                        <p class="mt-0.5 text-xs text-gray-400">
                            Semua pengajuan yang sudah diproses oleh Kaprodi
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span
                        class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-600">
                        <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                        {{ now()->translatedFormat('d F Y') }}
                    </span>
                    <a href="{{ route('prodi.pengajuan.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700 hover:shadow-md">
                        <x-heroicon-o-document-text class="h-3.5 w-3.5" />
                        Pengajuan Aktif
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
                $disetujui = $pengajuan->where('status_kaprodi', 'disetujui')->count();
                $ditolak = $pengajuan->where('status_kaprodi', 'ditolak')->count();
                $total = $pengajuan->count();
                $pctDisetujui = $total > 0 ? round(($disetujui / $total) * 100) : 0;
                $pctDitolak = $total > 0 ? round(($ditolak / $total) * 100) : 0;
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

                {{-- Total --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-600 via-violet-700 to-purple-800 p-6 shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="absolute right-4 bottom-4 h-12 w-12 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-300">Total Diproses</p>
                            <p class="mt-3 text-6xl font-black text-white leading-none">{{ $total }}</p>
                            <p class="mt-2 text-xs font-medium text-violet-300">pengajuan masuk</p>
                        </div>
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/15 backdrop-blur-sm">
                            <x-heroicon-o-document-text class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="mt-5 space-y-1">
                        <div class="flex justify-between text-xs text-violet-300">
                            <span>Progress</span>
                            <span>100%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full w-full rounded-full bg-white/70 shadow-sm"></div>
                        </div>
                    </div>
                </div>

                {{-- Disetujui --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Disetujui</p>
                            <p class="mt-3 text-6xl font-black text-white leading-none">{{ $disetujui }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">{{ $pctDisetujui }}% dari total</p>
                        </div>
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/15 backdrop-blur-sm">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="mt-5 space-y-1">
                        <div class="flex justify-between text-xs text-emerald-200">
                            <span>Tingkat persetujuan</span>
                            <span>{{ $pctDisetujui }}%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full rounded-full bg-white/70 shadow-sm transition-all duration-1000"
                                style="width: {{ $pctDisetujui }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div
                    class="group relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                    <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-8 -left-4 h-24 w-24 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                            <p class="mt-3 text-6xl font-black text-white leading-none">{{ $ditolak }}</p>
                            <p class="mt-2 text-xs font-medium text-red-200">{{ $pctDitolak }}% dari total</p>
                        </div>
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/15 backdrop-blur-sm">
                            <x-heroicon-o-x-circle class="h-6 w-6 text-white" />
                        </div>
                    </div>
                    <div class="mt-5 space-y-1">
                        <div class="flex justify-between text-xs text-red-200">
                            <span>Tingkat penolakan</span>
                            <span>{{ $pctDitolak }}%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
                            <div class="h-full rounded-full bg-white/70 shadow-sm transition-all duration-1000"
                                style="width: {{ $pctDitolak }}%"></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== FILTER + TABLE ===== --}}
            <div x-data="{ filter: 'semua' }" class="space-y-4">

                {{-- Section Label --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-table-cells class="h-3 w-3" />
                        Data Riwayat
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                {{-- Toolbar --}}
                <div class="flex flex-wrap items-center justify-between gap-3">

                    {{-- Filter Pills --}}
                    <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                        <button type="button" @click="filter = 'semua'"
                            :class="filter === 'semua'
                                ?
                                'bg-violet-600 text-white shadow-md ring-2 ring-violet-300' :
                                'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                            class="flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition-all">
                            <x-heroicon-o-squares-2x2 class="h-3.5 w-3.5" />
                            Semua
                            <span class="rounded-full px-2 py-0.5 text-xs font-black"
                                :class="filter === 'semua' ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-500'">
                                {{ $total }}
                            </span>
                        </button>
                        <div class="h-6 w-px bg-gray-200"></div>
                        <button type="button" @click="filter = 'disetujui'"
                            :class="filter === 'disetujui'
                                ?
                                'bg-emerald-600 text-white shadow-md ring-2 ring-emerald-300' :
                                'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                            class="flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition-all">
                            <x-heroicon-o-check-circle class="h-3.5 w-3.5" />
                            Disetujui
                            <span class="rounded-full px-2 py-0.5 text-xs font-black"
                                :class="filter === 'disetujui' ? 'bg-white/25 text-white' : 'bg-emerald-50 text-emerald-600'">
                                {{ $disetujui }}
                            </span>
                        </button>
                        <div class="h-6 w-px bg-gray-200"></div>
                        <button type="button" @click="filter = 'ditolak'"
                            :class="filter === 'ditolak'
                                ?
                                'bg-red-600 text-white shadow-md ring-2 ring-red-300' :
                                'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                            class="flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition-all">
                            <x-heroicon-o-x-circle class="h-3.5 w-3.5" />
                            Ditolak
                            <span class="rounded-full px-2 py-0.5 text-xs font-black"
                                :class="filter === 'ditolak' ? 'bg-white/25 text-white' : 'bg-red-50 text-red-500'">
                                {{ $ditolak }}
                            </span>
                        </button>
                    </div>

                    <p class="text-xs text-gray-400">
                        Data per <span class="font-bold text-gray-600">{{ now()->translatedFormat('d F Y') }}</span>
                    </p>
                </div>
                {{-- Table Card --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    {{-- Card Header --}}
                    <div
                        class="flex items-center justify-between border-b-4 border-violet-200 bg-gradient-to-r from-violet-700 via-violet-600 to-purple-700 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 backdrop-blur-sm">
                                <x-heroicon-o-clock class="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">Riwayat Keputusan</h2>
                                <p class="text-xs text-violet-200">Diurutkan berdasarkan tanggal review terbaru</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                                {{ $total }} data
                            </span>
                        </div>
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
                                    <x-heroicon-o-clock class="h-4 w-4 text-white" />
                                </div>
                            </div>
                            <p class="text-lg font-extrabold text-gray-800">Belum ada riwayat</p>
                            <p class="mt-2 max-w-xs text-sm text-gray-400 leading-relaxed">
                                Riwayat review akan muncul setelah ada pengajuan yang diproses oleh Kaprodi.
                            </p>
                            <a href="{{ route('prodi.pengajuan.index') }}"
                                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-violet-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-violet-700 hover:shadow-lg">
                                <x-heroicon-o-document-text class="h-4 w-4" />
                                Lihat Pengajuan Aktif
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr
                                        class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                        <th class="px-6 py-4">No</th>
                                        <th class="px-6 py-4">Mahasiswa</th>
                                        <th class="px-6 py-4">Judul Ditetapkan</th>
                                        <th class="px-6 py-4">Dosen / Lab</th>
                                        <th class="px-6 py-4">Periode</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Tanggal Review</th>
                                        <th class="px-6 py-4">Catatan</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-gray-100">
                                    @foreach ($pengajuan as $index => $item)
                                        <tr x-show="filter === 'semua' || filter === '{{ $item->status_kaprodi }}'"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="group transition-colors
                                                {{ $item->status_kaprodi === 'disetujui' ? 'hover:bg-emerald-50/60' : 'hover:bg-red-50/60' }}">

                                            {{-- No + Indikator --}}
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="h-9 w-1.5 rounded-full
                                                        {{ $item->status_kaprodi === 'disetujui' ? 'bg-gradient-to-b from-emerald-400 to-green-500' : 'bg-gradient-to-b from-red-400 to-rose-500' }}">
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
                                                        <p class="font-bold text-gray-800">
                                                            {{ $item->mahasiswa->name }}</p>
                                                        <p class="text-xs text-gray-400">
                                                            {{ $item->mahasiswa->nim ?? $item->mahasiswa->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Judul --}}
                                            <td class="max-w-[220px] px-6 py-4">
                                                @if ($item->judulDitetapkan)
                                                    <p
                                                        class="line-clamp-2 text-sm font-semibold leading-relaxed text-gray-800">
                                                        {{ $item->judulDitetapkan->judul }}
                                                    </p>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>

                                            {{-- Dosen / Lab --}}
                                            <td class="px-6 py-4">
                                                @if ($item->judulDitetapkan)
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-blue-100 bg-blue-100 text-xs font-black text-blue-600">
                                                            {{ strtoupper(substr($item->judulDitetapkan->dosen->name ?? 'D', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-bold text-gray-700">
                                                                {{ $item->judulDitetapkan->dosen->name ?? '-' }}
                                                            </p>
                                                            <p class="text-xs text-gray-400">
                                                                {{ $item->judulDitetapkan->laboratorium->nama ?? '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>

                                            {{-- Periode --}}
                                            <td class="px-6 py-4">
                                                <span
                                                    class="rounded-lg border-2 border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-black text-violet-700">
                                                    {{ $item->periode->nama ?? '-' }}
                                                </span>
                                            </td>

                                            {{-- Status --}}
                                            <td class="px-6 py-4">
                                                @if ($item->status_kaprodi === 'disetujui')
                                                    <span
                                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700 shadow-sm">
                                                        <span
                                                            class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-300"></span>
                                                        Disetujui
                                                    </span>
                                                @elseif ($item->status_kaprodi === 'ditolak')
                                                    <span
                                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1.5 text-xs font-black text-red-700 shadow-sm">
                                                        <span
                                                            class="h-2 w-2 rounded-full bg-red-500 shadow-sm shadow-red-300"></span>
                                                        Ditolak
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1.5 text-xs font-black text-yellow-700 shadow-sm">
                                                        <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                                        Menunggu
                                                    </span>
                                                @endif
                                                @if ($item->reviewerKaprodi)
                                                    <p class="mt-1.5 text-xs text-gray-400">
                                                        {{ $item->reviewerKaprodi->name }}</p>
                                                @endif
                                            </td>

                                            {{-- Tanggal Review --}}
                                            <td class="px-6 py-4">
                                                @if ($item->tanggal_review_kaprodi)
                                                    <div
                                                        class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2 text-center">
                                                        <p class="text-sm font-black text-gray-700">
                                                            {{ $item->tanggal_review_kaprodi->format('d M Y') }}
                                                        </p>
                                                        <p class="mt-0.5 text-xs font-semibold text-gray-400">
                                                            {{ $item->tanggal_review_kaprodi->format('H:i') }} WIB
                                                        </p>
                                                    </div>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>

                                            {{-- Catatan --}}
                                            <td class="max-w-[160px] px-6 py-4">
                                                @if ($item->catatan_kaprodi)
                                                    <div
                                                        class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                                        <p
                                                            class="line-clamp-2 text-xs italic leading-relaxed text-gray-500">
                                                            "{{ $item->catatan_kaprodi }}"
                                                        </p>
                                                    </div>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>

                                            {{-- Aksi --}}
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('prodi.pengajuan.show', $item->id) }}"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border-2 border-violet-300 bg-violet-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-violet-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-1">
                                                    <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                                    Detail
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
                                Menampilkan <span class="font-black text-gray-800">{{ $total }}</span> data
                            </p>
                            <div class="flex items-center gap-4 text-xs">
                                <span class="flex items-center gap-2 font-bold text-emerald-600">
                                    <span
                                        class="h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></span>
                                    {{ $disetujui }} disetujui
                                </span>
                                <div class="h-4 w-px bg-gray-300"></div>
                                <span class="flex items-center gap-2 font-bold text-red-500">
                                    <span class="h-2.5 w-2.5 rounded-full bg-red-500 shadow-sm shadow-red-200"></span>
                                    {{ $ditolak }} ditolak
                                </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

</x-layout-prodi>
