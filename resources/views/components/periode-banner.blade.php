@php
    $periodeAktif = \App\Models\Periode::periodeAktif();
    $namaPeriode = $periodeAktif
        ? ($periodeAktif->nama ?? trim(($periodeAktif->semester ?? '') . ' ' . ($periodeAktif->tahun_ajaran ?? '')))
        : null;
@endphp

@if ($periodeAktif)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3 rounded-2xl border-2 border-indigo-200 bg-indigo-50 px-5 py-3 shadow-sm']) }}>
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600">
            <x-heroicon-o-calendar-days class="h-5 w-5 text-white" />
        </span>
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-400">Periode Aktif</p>
            <p class="truncate text-sm font-black text-indigo-800">{{ $namaPeriode ?: 'Periode berjalan' }}</p>
        </div>
        <span
            class="ml-auto shrink-0 rounded-full border border-indigo-200 bg-white px-3 py-1 text-xs font-black text-indigo-600">
            Semua aktivitas berjalan di periode ini
        </span>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3 rounded-2xl border-2 border-amber-200 bg-amber-50 px-5 py-3 shadow-sm']) }}>
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-500">
            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-white" />
        </span>
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-widest text-amber-500">Belum Ada Periode Aktif</p>
            <p class="text-sm font-black text-amber-800">
                Aktivitas pengajuan ditutup hingga Koordinator TA membuka periode.
            </p>
        </div>
    </div>
@endif
