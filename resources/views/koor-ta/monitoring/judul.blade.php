<x-layout-koor-ta title="Monitoring Judul">

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
                            <h1 class="text-lg font-extrabold text-gray-900">Monitoring Judul TA</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Semua judul TA yang terdaftar di sistem</p>
                        </div>
                    </div>
                    <a href="{{ route('koor-ta.monitoring.pengajuan') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                        <x-heroicon-o-document-text class="h-3.5 w-3.5" />
                        Lihat Pengajuan
                    </a>
                </div>
            </div>

            {{-- ===== FILTER ===== --}}
            <form method="GET" action="{{ route('koor-ta.monitoring.judul') }}"
                class="flex flex-wrap items-center gap-3">

                {{-- Status Filter --}}
                <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                    @foreach ([
        'all' => 'Semua',
        'ditawarkan' => 'Ditawarkan',
        'pending_kalab' => 'Pending',
        'ditolak_kalab' => 'Ditolak',
    ] as $val => $label)
                        <button type="submit" name="status" value="{{ $val }}"
                            @if ($labId) onclick="document.querySelector('[name=lab_id]').value='{{ $labId }}'" @endif
                            class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all
                                {{ $status === $val
                                    ? 'bg-indigo-600 text-white shadow-sm'
                                    : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            {{ $label }}
                        </button>
                        @if (!$loop->last)
                            <div class="h-5 w-px bg-gray-200"></div>
                        @endif
                    @endforeach
                </div>

                {{-- Lab Filter --}}
                <select name="lab_id" onchange="this.form.submit()"
                    class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-indigo-400 focus:outline-none transition">
                    <option value="">Semua Laboratorium</option>
                    @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}" {{ $labId == $lab->id ? 'selected' : '' }}>
                            {{ $lab->nama }}
                        </option>
                    @endforeach
                </select>

                @if ($labId || $status !== 'all')
                    <a href="{{ route('koor-ta.monitoring.judul') }}"
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
                    Daftar Judul
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                <div
                    class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-book-open class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Semua Judul TA</h2>
                            <p class="text-xs text-indigo-200">
                                Filter:
                                {{ $status === 'all' ? 'Semua status' : ucfirst(str_replace('_', ' ', $status)) }}
                                {{ $labId ? '— ' . $labs->firstWhere('id', $labId)?->nama : '' }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        {{ $judul->total() }} judul
                    </span>
                </div>

                @if ($judul->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-blue-100 mb-5">
                            <x-heroicon-o-book-open class="h-10 w-10 text-indigo-300" />
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Tidak ada judul</p>
                        <p class="mt-2 text-sm text-gray-400">Coba ubah filter untuk melihat data lain</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Kode</th>
                                    <th class="px-6 py-4">Judul</th>
                                    <th class="px-6 py-4">Dosen</th>
                                    <th class="px-6 py-4">Laboratorium</th>
                                    <th class="px-6 py-4 text-center">Peminat</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($judul as $index => $item)
                                    <tr class="group transition-colors hover:bg-indigo-50/30">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full
                                                    {{ $item->status_judul === 'ditawarkan'
                                                        ? 'bg-gradient-to-b from-emerald-400 to-green-500'
                                                        : ($item->status_judul === 'ditolak_kalab'
                                                            ? 'bg-gradient-to-b from-red-400 to-rose-500'
                                                            : 'bg-gradient-to-b from-yellow-400 to-orange-500') }}">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-indigo-300 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-all">
                                                    {{ $judul->firstItem() + $index }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Kode --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="rounded-lg border-2 border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-black text-gray-600">
                                                {{ $item->kode ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Judul --}}
                                        <td class="max-w-[220px] px-6 py-4">
                                            <p class="text-sm font-semibold text-gray-800 leading-relaxed">
                                                {{ $item->nama_judul }}
                                            </p>

                                        </td>

                                        {{-- Dosen --}}
                                        <td class="px-6 py-4">
                                            @if ($item->dosen)
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-black text-blue-600">
                                                        {{ strtoupper(substr($item->dosen->name, 0, 1)) }}
                                                    </div>
                                                    <span
                                                        class="text-sm font-semibold text-gray-700">{{ $item->dosen->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>
                                        {{-- Lab --}}
                                        <td class="px-6 py-4">
                                            @if ($item->laboratorium)
                                                <span
                                                    class="rounded-lg border-2 border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-black text-violet-700">
                                                    {{ $item->laboratorium->nama }}
                                                </span>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>

                                        {{-- Peminat --}}
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="rounded-full border-2 border-blue-200 bg-blue-50 px-3 py-1 text-xs font-black text-blue-700">
                                                {{ $item->pengajuan_pilihan1_count + $item->pengajuan_pilihan2_count + $item->pengajuan_pilihan3_count }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            @if ($item->status_judul === 'ditawarkan')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Ditawarkan
                                                </span>
                                            @elseif ($item->status_judul === 'pending_kalab')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1.5 text-xs font-black text-yellow-700">
                                                    <span
                                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                                    Pending
                                                </span>
                                            @elseif ($item->status_judul === 'ditolak_kalab')
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1.5 text-xs font-black text-red-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Ditolak
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-gray-200 bg-gray-100 px-3 py-1.5 text-xs font-black text-gray-500">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                    {{ ucfirst($item->status_judul ?? 'Unknown') }}
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($judul->hasPages())
                        <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Menampilkan <span
                                    class="font-black text-gray-800">{{ $judul->firstItem() }}–{{ $judul->lastItem() }}</span>
                                dari <span class="font-black text-gray-800">{{ $judul->total() }}</span> judul
                            </p>
                            {{ $judul->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Total <span class="font-black text-gray-800">{{ $judul->total() }}</span> judul
                            </p>
                        </div>
                    @endif
                @endif

            </div>

        </div>
    </div>

</x-layout-koor-ta>
