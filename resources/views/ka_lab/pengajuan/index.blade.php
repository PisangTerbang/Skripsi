<x-layout-kalab>
    <x-slot name="title">Pengajuan Mahasiswa</x-slot>

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-sky-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-sky-200 bg-sky-50">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-sky-600" />
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Pengajuan Mahasiswa</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Review dan tetapkan judul TA mahasiswa</p>
                        </div>
                    </div>
                    <span
                        class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-sky-200 bg-sky-50 px-3 py-1.5 text-xs font-semibold text-sky-600">
                        <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
                    </span>
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                    class="flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                    <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-green-500" />
                    <span class="font-semibold">{{ session('success') }}</span>
                    <button @click="show = false"
                        class="ml-auto rounded-lg p-1 text-green-400 hover:bg-green-100 transition">
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                    class="flex items-center gap-3 rounded-2xl border-2 border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 shadow-sm">
                    <x-heroicon-o-x-circle class="h-5 w-5 shrink-0 text-red-500" />
                    <span class="font-semibold">{{ session('error') }}</span>
                    <button @click="show = false"
                        class="ml-auto rounded-lg p-1 text-red-400 hover:bg-red-100 transition">
                        <x-heroicon-o-x-mark class="h-4 w-4" />
                    </button>
                </div>
            @endif

            {{-- ===== STATS ===== --}}
            @php
                $pctDisetujui = $stats['total'] > 0 ? round(($stats['disetujui'] / $stats['total']) * 100) : 0;
                $pctDitolak = $stats['total'] > 0 ? round(($stats['ditolak'] / $stats['total']) * 100) : 0;
            @endphp

            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-chart-bar class="h-3 w-3" />
                    Ringkasan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Total --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-br from-sky-600 via-sky-700 to-blue-800 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['total'] }}</p>
                            <p class="mt-2 text-xs font-medium text-sky-200">semua status</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-white" />
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
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['pending'] }}</p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">perlu ditindaklanjuti</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clock class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full {{ $stats['pending'] > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                </div>

                {{-- Disetujui --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Disetujui</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['disetujui'] }}</p>
                            <p class="mt-2 text-xs font-medium text-emerald-200">{{ $pctDisetujui }}% dari total</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full rounded-full bg-white/60 transition-all duration-700"
                            style="width: {{ $pctDisetujui }}%"></div>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['ditolak'] }}</p>
                            <p class="mt-2 text-xs font-medium text-red-200">{{ $pctDitolak }}% dari total</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full rounded-full bg-white/60 transition-all duration-700"
                            style="width: {{ $pctDitolak }}%"></div>
                    </div>
                </div>

            </div>

            {{-- ===== FILTER + TABLE ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-table-cells class="h-3 w-3" />
                    Daftar Pengajuan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Filter & Search --}}
            <form method="GET" action="{{ route('ka-lab.pengajuan.index') }}"
                class="flex flex-wrap items-center gap-3">

                {{-- Filter Periode (navigasi langsung, pertahankan status & pencarian) --}}
                <div class="flex items-center gap-2 rounded-2xl border-2 border-sky-200 bg-white px-3 py-2 shadow-sm">
                    <x-heroicon-o-calendar-days class="h-4 w-4 shrink-0 text-sky-500" />
                    <select
                        onchange="window.location.href='{{ route('ka-lab.pengajuan.index') }}?status={{ $status }}&search={{ urlencode($search) }}&periode_id=' + this.value"
                        class="cursor-pointer bg-transparent pr-1 text-xs font-bold text-gray-700 focus:outline-none">
                        <option value="semua" @selected($selectedPeriode === 'semua')>Semua Periode</option>
                        @foreach ($periodeList as $per)
                            <option value="{{ $per->id }}" @selected((string) $selectedPeriode === (string) $per->id)>
                                {{ $per->nama }}{{ $per->id === $aktifId ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter Pills --}}
                <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                    @foreach ([
        'all' => ['label' => 'Semua', 'count' => $stats['total']],
        'pending' => ['label' => 'Menunggu', 'count' => $stats['pending']],
        'disetujui' => ['label' => 'Disetujui', 'count' => $stats['disetujui']],
        'ditolak' => ['label' => 'Ditolak', 'count' => $stats['ditolak']],
    ] as $val => $cfg)
                        <button type="submit" name="status" value="{{ $val }}"
                            class="flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs font-bold transition-all
                                {{ $status === $val
                                    ? ($val === 'disetujui'
                                        ? 'bg-emerald-600 text-white shadow-sm'
                                        : ($val === 'ditolak'
                                            ? 'bg-red-600 text-white shadow-sm'
                                            : ($val === 'pending'
                                                ? 'bg-yellow-500 text-white shadow-sm'
                                                : 'bg-sky-600 text-white shadow-sm')))
                                    : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            {{ $cfg['label'] }}
                            <span
                                class="rounded-full px-1.5 py-0.5 text-xs font-black
                                {{ $status === $val ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cfg['count'] }}
                            </span>
                        </button>
                        @if (!$loop->last)
                            <div class="h-5 w-px bg-gray-200"></div>
                        @endif
                    @endforeach
                </div>

                {{-- Search --}}
                <div
                    class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                    <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Cari nama atau NIM mahasiswa..."
                        class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                    @if ($search)
                        <a href="{{ route('ka-lab.pengajuan.index', ['status' => $status]) }}"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl border-2 border-sky-300 bg-sky-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-sky-700">
                    <x-heroicon-o-magnifying-glass class="h-3.5 w-3.5" />
                    Cari
                </button>

                @if ($status !== 'all' || $search)
                    <a href="{{ route('ka-lab.pengajuan.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border-2 border-gray-200 bg-white px-3 py-2 text-xs font-bold text-gray-500 transition hover:bg-gray-50">
                        <x-heroicon-o-x-mark class="h-3.5 w-3.5" />
                        Reset
                    </a>
                @endif

            </form>

            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                {{-- Card Header --}}
                <div
                    class="flex items-center justify-between border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 via-sky-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Daftar Pengajuan</h2>
                            <p class="text-xs text-sky-200">
                                Filter: {{ $status === 'all' ? 'Semua status' : ucfirst($status) }}
                                {{ $search ? "— \"{$search}\"" : '' }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        {{ $pengajuan->total() }} data
                    </span>
                </div>

                @if ($pengajuan->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="relative mb-6">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-sky-100 bg-gradient-to-br from-sky-50 to-blue-100 shadow-inner">
                                <x-heroicon-o-clipboard-document-list class="h-12 w-12 text-sky-300" />
                            </div>
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Tidak ada pengajuan</p>
                        <p class="mt-2 max-w-xs text-sm text-gray-400">
                            {{ $search || $status !== 'all' ? 'Coba ubah filter atau kata kunci pencarian' : 'Belum ada pengajuan mahasiswa yang masuk' }}
                        </p>
                        @if ($search || $status !== 'all')
                            <a href="{{ route('ka-lab.pengajuan.index') }}"
                                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-sky-700">
                                <x-heroicon-o-x-mark class="h-4 w-4" />
                                Reset Filter
                            </a>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Mahasiswa</th>
                                    <th class="px-6 py-4">Periode</th>
                                    <th class="px-6 py-4">Pilihan Judul</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($pengajuan as $index => $item)
                                    <tr class="group transition-colors hover:bg-sky-50/30">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full
                                                    {{ $item->status_kalab === 'disetujui'
                                                        ? 'bg-gradient-to-b from-emerald-400 to-green-500'
                                                        : ($item->status_kalab === 'ditolak'
                                                            ? 'bg-gradient-to-b from-red-400 to-rose-500'
                                                            : 'bg-gradient-to-b from-yellow-400 to-orange-500') }}">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-sky-300 group-hover:bg-sky-50 group-hover:text-sky-700 transition-all">
                                                    {{ $pengajuan->firstItem() + $index }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Mahasiswa --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-sm font-black text-white shadow-md ring-2 ring-sky-200">
                                                    {{ strtoupper(substr($item->mahasiswa->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">
                                                        {{ $item->mahasiswa->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ $item->mahasiswa->nim ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Periode --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="rounded-lg border-2 border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-black text-violet-700">
                                                {{ $item->periode->nama ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Pilihan Judul --}}
                                        <td class="max-w-[260px] px-6 py-4">
                                            <div class="space-y-2">
                                                @foreach ([['label' => 'P1', 'judul' => $item->pilihan1, 'color' => 'emerald'], ['label' => 'P2', 'judul' => $item->pilihan2, 'color' => 'sky'], ['label' => 'P3', 'judul' => $item->pilihan3, 'color' => 'violet']] as $p)
                                                    @if ($p['judul'])
                                                        <div class="flex items-start gap-2">
                                                            <span
                                                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-{{ $p['color'] }}-600 text-[10px] font-black text-white shadow-sm">
                                                                {{ $p['label'] }}
                                                            </span>
                                                            <p
                                                                class="text-xs font-semibold text-gray-700 leading-relaxed line-clamp-1">
                                                                {{ $p['judul']->nama_judul }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if (!$item->pilihan1 && !$item->pilihan2 && !$item->pilihan3)
                                                    <span class="text-xs italic text-gray-400">Belum ada pilihan</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            @if ($item->status_kalab === 'disetujui')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                    Disetujui
                                                </span>
                                            @elseif ($item->status_kalab === 'ditolak')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1.5 text-xs font-black text-red-700">
                                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                    Ditolak
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1.5 text-xs font-black text-yellow-700">
                                                    <span
                                                        class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                                                    Menunggu
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('ka-lab.pengajuan.show', $item->id) }}"
                                                class="inline-flex items-center gap-1.5 rounded-xl border-2 border-sky-300 bg-sky-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-sky-700 hover:shadow-md">
                                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                                Detail
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($pengajuan->hasPages())
                        <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Menampilkan <span
                                    class="font-black text-gray-800">{{ $pengajuan->firstItem() }}–{{ $pengajuan->lastItem() }}</span>
                                dari <span class="font-black text-gray-800">{{ $pengajuan->total() }}</span> pengajuan
                            </p>
                            {{ $pengajuan->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Total <span class="font-black text-gray-800">{{ $pengajuan->total() }}</span>
                                pengajuan
                            </p>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="flex items-center gap-1.5 font-bold text-emerald-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    {{ $stats['disetujui'] }} disetujui
                                </span>
                                <div class="h-4 w-px bg-gray-300"></div>
                                <span class="flex items-center gap-1.5 font-bold text-red-500">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                    {{ $stats['ditolak'] }} ditolak
                                </span>
                                <div class="h-4 w-px bg-gray-300"></div>
                                <span class="flex items-center gap-1.5 font-bold text-yellow-600">
                                    <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                                    {{ $stats['pending'] }} pending
                                </span>
                            </div>
                        </div>
                    @endif
                @endif

            </div>

        </div>
    </div>

</x-layout-kalab>
