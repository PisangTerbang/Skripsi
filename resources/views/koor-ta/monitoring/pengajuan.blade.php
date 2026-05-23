<x-layout-koor-ta title="Monitoring Pengajuan">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('koor-ta.monitoring.index') }}"
                            class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-600">
                            <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                        </a>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Monitoring Pengajuan</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Semua pengajuan judul TA lintas periode</p>
                        </div>
                    </div>
                    <a href="{{ route('koor-ta.monitoring.judul') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                        <x-heroicon-o-book-open class="h-3.5 w-3.5" />
                        Lihat Judul
                    </a>
                </div>
            </div>

            {{-- ===== FILTER ===== --}}
            <form method="GET" action="{{ route('koor-ta.monitoring.pengajuan') }}"
                class="flex flex-wrap items-center gap-3">

                {{-- Status Filter --}}
                <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                    @foreach ([
        'all' => ['label' => 'Semua', 'color' => 'indigo'],
        'pending' => ['label' => 'Pending', 'color' => 'yellow'],
        'proses' => ['label' => 'Proses', 'color' => 'blue'],
        'selesai' => ['label' => 'Selesai', 'color' => 'emerald'],
        'ditolak' => ['label' => 'Ditolak', 'color' => 'red'],
    ] as $val => $cfg)
                        <button type="submit" name="status" value="{{ $val }}"
                            @if ($periodeId) onclick="document.querySelector('[name=periode_id]').value='{{ $periodeId }}'" @endif
                            class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all
                                {{ $status === $val
                                    ? 'bg-' . $cfg['color'] . '-600 text-white shadow-sm'
                                    : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            {{ $cfg['label'] }}
                        </button>
                        @if (!$loop->last)
                            <div class="h-5 w-px bg-gray-200"></div>
                        @endif
                    @endforeach
                </div>

                {{-- Periode Filter --}}
                <select name="periode_id" onchange="this.form.submit()"
                    class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-indigo-400 focus:outline-none transition">
                    <option value="">Semua Periode</option>
                    @foreach ($periode as $p)
                        <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>

                @if ($periodeId || $status !== 'all')
                    <a href="{{ route('koor-ta.monitoring.pengajuan') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border-2 border-gray-200 bg-white px-3 py-2 text-xs font-bold text-gray-500 transition hover:bg-gray-50">
                        <x-heroicon-o-x-mark class="h-3.5 w-3.5" />
                        Reset
                    </a>
                @endif

            </form>

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
                    class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Semua Pengajuan</h2>
                            <p class="text-xs text-indigo-200">
                                Filter: {{ ucfirst($status === 'all' ? 'semua' : $status) }}
                                {{ $periodeId ? '— ' . $periode->firstWhere('id', $periodeId)?->nama : '' }}
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
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-blue-100 mb-5">
                            <x-heroicon-o-inbox class="h-10 w-10 text-indigo-300" />
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Tidak ada pengajuan</p>
                        <p class="mt-2 text-sm text-gray-400">Coba ubah filter untuk melihat data lain</p>
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
                                    <th class="px-6 py-4">Status Ka Lab</th>
                                    <th class="px-6 py-4">Status Kaprodi</th>
                                    <th class="px-6 py-4">Progress</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($pengajuan as $index => $item)
                                    <tr class="group transition-colors hover:bg-indigo-50/30">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full
                                                    {{ $item->status_kaprodi === 'disetujui'
                                                        ? 'bg-gradient-to-b from-emerald-400 to-green-500'
                                                        : ($item->status_kalab === 'ditolak' || $item->status_kaprodi === 'ditolak'
                                                            ? 'bg-gradient-to-b from-red-400 to-rose-500'
                                                            : 'bg-gradient-to-b from-indigo-400 to-blue-500') }}">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-indigo-300 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-all">
                                                    {{ $pengajuan->firstItem() + $index }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Mahasiswa --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-sm font-black text-white shadow-sm ring-2 ring-indigo-200">
                                                    {{ strtoupper(substr($item->mahasiswa->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $item->mahasiswa->name }}</p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ $item->mahasiswa->nim ?? $item->mahasiswa->email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Judul --}}
                                        <td class="max-w-[200px] px-6 py-4">
                                            @if ($item->judulDitetapkan)
                                                <p class="line-clamp-2 text-sm font-semibold text-gray-800">
                                                    {{ $item->judulDitetapkan->nama_judul ?? $item->judulDitetapkan->judul }}
                                                </p>
                                            @else
                                                <span class="italic text-gray-400 text-xs">Belum ditetapkan</span>
                                            @endif
                                        </td>

                                        {{-- Dosen / Lab --}}
                                        <td class="px-6 py-4">
                                            @if ($item->judulDitetapkan)
                                                <p class="text-sm font-bold text-gray-700">
                                                    {{ $item->judulDitetapkan->dosen->name ?? '-' }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $item->judulDitetapkan->laboratorium->nama ?? '-' }}
                                                </p>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>

                                        {{-- Periode --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="rounded-lg border-2 border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-black text-indigo-700">
                                                {{ $item->periode->nama ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Status Ka Lab --}}
                                        <td class="px-6 py-4">
                                            @if ($item->status_kalab === 'disetujui')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Disetujui
                                                </span>
                                            @elseif ($item->status_kalab === 'ditolak')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Ditolak
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1 text-xs font-black text-yellow-700">
                                                    <span
                                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                                    Menunggu
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Status Kaprodi --}}
                                        <td class="px-6 py-4">
                                            @if ($item->status_kaprodi === 'disetujui')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Disetujui
                                                </span>
                                            @elseif ($item->status_kaprodi === 'ditolak')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Ditolak
                                                </span>
                                            @elseif ($item->status_kalab === 'disetujui')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-blue-200 bg-blue-100 px-3 py-1 text-xs font-black text-blue-700">
                                                    <span
                                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500"></span>
                                                    Menunggu
                                                </span>
                                            @else
                                                <span class="text-gray-300 text-xs">—</span>
                                            @endif
                                        </td>

                                        {{-- Progress --}}
                                        <td class="px-6 py-4">
                                            @php $pct = $item->progress_percentage; @endphp
                                            <div class="w-24">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span
                                                        class="text-xs font-black text-gray-700">{{ round($pct) }}%</span>
                                                </div>
                                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                                    <div class="h-full rounded-full transition-all
                                                        {{ $pct >= 100 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-blue-500' : 'bg-yellow-500') }}"
                                                        style="width: {{ $pct }}%">
                                                    </div>
                                                </div>
                                                <p class="mt-1 text-xs text-gray-400">{{ $item->current_step }}</p>
                                            </div>
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
                        <div class="border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Total <span class="font-black text-gray-800">{{ $pengajuan->total() }}</span>
                                pengajuan
                            </p>
                        </div>
                    @endif
                @endif

            </div>

        </div>
    </div>

</x-layout-koor-ta>
