<x-layout-kalab title="Export Laporan">

    <div class="space-y-6">

        {{-- ===== TOP BAR ===== --}}
        <div class="rounded-2xl border-2 border-sky-100 bg-white px-6 py-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-sky-200 bg-sky-50">
                    <x-heroicon-o-arrow-down-tray class="h-5 w-5 text-sky-600" />
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div>
                    <h1 class="text-lg font-extrabold text-gray-900">Export Laporan Pengajuan</h1>
                    <p class="mt-0.5 text-xs text-gray-400">
                        Unduh rekap pengajuan judul TA dalam format Excel atau PDF
                    </p>
                </div>
            </div>
        </div>

        {{-- ===== PENJELASAN JENIS LAPORAN ===== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border-2 border-sky-100 bg-sky-50/50 p-4">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-document-text class="h-4 w-4 text-sky-600" />
                    <span class="text-sm font-black text-sky-800">Laporan Informasi</span>
                </div>
                <p class="mt-1.5 text-xs font-medium text-sky-700">
                    Ringkas — identitas mahasiswa, dosen pembimbing, laboratorium, dan status akhir
                    (Diterima / Ditolak). Tanpa jejak review.
                </p>
            </div>
            <div class="rounded-2xl border-2 border-indigo-100 bg-indigo-50/50 p-4">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-document-magnifying-glass class="h-4 w-4 text-indigo-600" />
                    <span class="text-sm font-black text-indigo-800">Laporan Lengkap</span>
                </div>
                <p class="mt-1.5 text-xs font-medium text-indigo-700">
                    Seluruh detail — pilihan judul, sumber judul, catatan, dan jejak review (log)
                    Ka Lab &amp; Prodi.
                </p>
            </div>
        </div>

        {{-- ===== SECTION LABEL ===== --}}
        <div class="flex items-center gap-3">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
            <span
                class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                <x-heroicon-o-funnel class="h-3 w-3" />
                Filter &amp; Export
            </span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- ===== EXPORT EXCEL ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-table-cells class="h-5 w-5 text-white" />
                    </div>
                    <div>
                        <h2 class="font-extrabold text-white">Export Excel</h2>
                        <p class="text-xs text-emerald-200">Format .xlsx — cocok untuk analisis data</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('ka-lab.export.excel') }}" class="space-y-5 p-6">
                    @csrf
                    <x-ka-lab.export-fields color="emerald" :periode="$periode" />
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                        <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                        Download Excel (.xlsx)
                    </button>
                </form>
            </div>

            {{-- ===== EXPORT PDF ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-red-200 bg-gradient-to-r from-red-600 to-rose-700 px-6 py-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-document-text class="h-5 w-5 text-white" />
                    </div>
                    <div>
                        <h2 class="font-extrabold text-white">Export PDF</h2>
                        <p class="text-xs text-red-200">Format .pdf — cocok untuk laporan &amp; cetak</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('ka-lab.export.pdf') }}" class="space-y-5 p-6">
                    @csrf
                    <x-ka-lab.export-fields color="red" :periode="$periode" />
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                        <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                        Download PDF (.pdf)
                    </button>
                </form>
            </div>

        </div>

    </div>

</x-layout-kalab>
