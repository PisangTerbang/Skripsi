<x-layout-koor-ta title="Export Data">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-indigo-200 bg-indigo-50">
                        <x-heroicon-o-arrow-down-tray class="h-5 w-5 text-indigo-600" />
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div>
                        <h1 class="text-lg font-extrabold text-gray-900">Export Data Pengajuan</h1>
                        <p class="mt-0.5 text-xs text-gray-400">Download data pengajuan dalam format Excel atau PDF</p>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION LABEL ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-funnel class="h-3 w-3" />
                    Filter & Export
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- ===== EXPORT EXCEL ===== --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-table-cells class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="font-extrabold text-white">Export Excel</h2>
                            <p class="text-xs text-emerald-200">Format .xlsx — cocok untuk analisis data</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('koor-ta.export.excel') }}" class="p-6 space-y-5">
                        @csrf

                        {{-- Periode --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Periode
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <select name="periode_id"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">Semua Periode</option>
                                @foreach ($periode as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">Filter Status</label>
                            <select name="status"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="all">Semua Status</option>
                                <option value="pending">Pending (Belum Review Ka Lab)</option>
                                <option value="proses">Dalam Proses (Sudah Ka Lab, Belum Kaprodi)</option>
                                <option value="disetujui">Disetujui (Final)</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        {{-- Info --}}
                        <div class="rounded-xl border-2 border-emerald-100 bg-emerald-50 px-4 py-3">
                            <div class="flex items-start gap-2">
                                <x-heroicon-o-information-circle class="h-4 w-4 shrink-0 text-emerald-500 mt-0.5" />
                                <p class="text-xs font-semibold text-emerald-700">
                                    File Excel berisi data lengkap termasuk catatan reviewer dan tanggal review.
                                </p>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                            <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                            Download Excel (.xlsx)
                        </button>

                    </form>
                </div>

                {{-- ===== EXPORT PDF ===== --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-red-200 bg-gradient-to-r from-red-600 to-rose-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="font-extrabold text-white">Export PDF</h2>
                            <p class="text-xs text-red-200">Format .pdf — cocok untuk laporan & cetak</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('koor-ta.export.pdf') }}" class="p-6 space-y-5">
                        @csrf

                        {{-- Periode --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                Periode
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <select name="periode_id"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 transition">
                                <option value="">Semua Periode</option>
                                @foreach ($periode as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-gray-700">Filter Status</label>
                            <select name="status"
                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 transition">
                                <option value="all">Semua Status</option>
                                <option value="pending">Pending (Belum Review Ka Lab)</option>
                                <option value="proses">Dalam Proses (Sudah Ka Lab, Belum Kaprodi)</option>
                                <option value="disetujui">Disetujui (Final)</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        {{-- Info --}}
                        <div class="rounded-xl border-2 border-red-100 bg-red-50 px-4 py-3">
                            <div class="flex items-start gap-2">
                                <x-heroicon-o-information-circle class="h-4 w-4 shrink-0 text-red-500 mt-0.5" />
                                <p class="text-xs font-semibold text-red-700">
                                    File PDF dalam format landscape A4. Cocok untuk laporan resmi dan arsip.
                                </p>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                            <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                            Download PDF (.pdf)
                        </button>

                    </form>
                </div>

            </div>

            {{-- ===== INFO KOLOM ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-list-bullet class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Kolom yang Di-export</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach (['No', 'NIM', 'Nama Mahasiswa', 'Periode', 'Judul Ditetapkan', 'Dosen Pembimbing', 'Laboratorium', 'Status Ka Lab', 'Reviewer Ka Lab', 'Tgl Review Ka Lab', 'Status Kaprodi', 'Reviewer Kaprodi', 'Tgl Review Kaprodi', 'Catatan Ka Lab', 'Catatan Kaprodi', 'Tgl Pengajuan'] as $col)
                            <div
                                class="flex items-center gap-2 rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-500"></span>
                                <span class="text-xs font-semibold text-gray-700">{{ $col }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-layout-koor-ta>
