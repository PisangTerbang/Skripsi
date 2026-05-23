<x-layout-koor-ta title="Monitoring Overview">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-indigo-200 bg-indigo-50">
                            <x-heroicon-o-chart-bar class="h-5 w-5 text-indigo-600" />
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Monitoring Overview</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Pantau seluruh aktivitas sistem pengajuan TA</p>
                        </div>
                    </div>
                    <span
                        class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                        <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
                    </span>
                </div>
            </div>

            {{-- ===== SECTION: STATS PENGAJUAN ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-document-text class="h-3 w-3" />
                    Status Pengajuan
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Total --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total Pengajuan</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['total_pengajuan'] }}
                            </p>
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
                    <a href="{{ route('koor-ta.monitoring.pengajuan') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-indigo-200 transition hover:text-white">
                        Lihat semua <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Pending Ka Lab --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-yellow-300 bg-gradient-to-br from-yellow-400 via-yellow-500 to-orange-500 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-yellow-100">Pending Ka Lab</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['pending_kalab'] }}
                            </p>
                            <p class="mt-2 text-xs font-medium text-yellow-100">belum direview</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-clock class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full {{ $stats['pending_kalab'] > 0 ? 'animate-pulse' : '' }} w-full rounded-full bg-white/60">
                        </div>
                    </div>
                    <a href="{{ route('koor-ta.monitoring.pengajuan', ['status' => 'pending']) }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-yellow-100 transition hover:text-white">
                        Lihat detail <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Disetujui Ka Lab --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-200">Disetujui Ka Lab</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['disetujui_kalab'] }}
                            </p>
                            <p class="mt-2 text-xs font-medium text-blue-200">menunggu Kaprodi</p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-check class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('koor-ta.monitoring.pengajuan', ['status' => 'proses']) }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-blue-200 transition hover:text-white">
                        Lihat detail <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Final Disetujui --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Final Disetujui</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">
                                {{ $stats['disetujui_kaprodi'] }}</p>
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
                    <a href="{{ route('koor-ta.monitoring.pengajuan', ['status' => 'selesai']) }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-emerald-200 transition hover:text-white">
                        Lihat detail <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Ditolak --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-red-300 bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-red-200">Ditolak</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">
                                {{ $stats['ditolak_kalab'] + $stats['ditolak_kaprodi'] }}
                            </p>
                            <p class="mt-2 text-xs font-medium text-red-200">
                                {{ $stats['ditolak_kalab'] }} Ka Lab · {{ $stats['ditolak_kaprodi'] }} Kaprodi
                            </p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('koor-ta.monitoring.pengajuan', ['status' => 'ditolak']) }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-red-200 transition hover:text-white">
                        Lihat detail <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

                {{-- Total Judul --}}
                <div
                    class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 via-violet-600 to-purple-700 p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-6 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-200">Total Judul</p>
                            <p class="mt-3 text-5xl font-black leading-none text-white">{{ $stats['total_judul'] }}</p>
                            <p class="mt-2 text-xs font-medium text-violet-200">
                                {{ $stats['judul_tersedia'] }} tersedia
                            </p>
                        </div>
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-white/20 bg-white/20">
                            <x-heroicon-o-book-open class="h-5 w-5 text-white" />
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/20">
                        <div class="h-full w-full rounded-full bg-white/60"></div>
                    </div>
                    <a href="{{ route('koor-ta.monitoring.judul') }}"
                        class="mt-3 flex items-center gap-1 text-xs font-bold text-violet-200 transition hover:text-white">
                        Lihat semua <x-heroicon-o-arrow-right class="h-3 w-3" />
                    </a>
                </div>

            </div>

            {{-- ===== SECTION: PERIODE ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-calendar-days class="h-3 w-3" />
                    Periode
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-calendar-days class="h-5 w-5 text-white" />
                        </div>
                        <h3 class="font-extrabold text-white">Semua Periode</h3>
                    </div>
                    <a href="{{ route('koor-ta.periode.index') }}"
                        class="text-xs font-bold text-indigo-200 hover:text-white transition">
                        Kelola periode →
                    </a>
                </div>

                @if ($periode->isEmpty())
                    <div class="px-6 py-10 text-center text-sm text-gray-400">Belum ada periode</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-100 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-3">Nama Periode</th>
                                    <th class="px-6 py-3">Tanggal Mulai</th>
                                    <th class="px-6 py-3">Tanggal Selesai</th>
                                    <th class="px-6 py-3 text-center">Pengajuan</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($periode as $p)
                                    <tr
                                        class="group transition-colors {{ $p->is_active ? 'bg-emerald-50/40' : 'hover:bg-indigo-50/20' }}">
                                        <td class="px-6 py-3">
                                            <p class="font-bold text-gray-800">{{ $p->nama }}</p>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <span
                                                class="rounded-full border-2 border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-black text-indigo-700">
                                                {{ $p->pengajuan_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3">
                                            @if ($p->is_active)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                    <span
                                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-gray-200 bg-gray-100 px-3 py-1 text-xs font-black text-gray-500">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layout-koor-ta>
