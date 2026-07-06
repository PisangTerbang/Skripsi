@props([
    'periode' => collect(),
    'color' => 'sky',
])

@php
    // Kelas fokus dipetakan penuh agar tidak terpangkas Tailwind (bukan string dinamis).
    $focus = match ($color) {
        'emerald' => 'focus:border-emerald-400 focus:ring-emerald-100',
        'red' => 'focus:border-red-400 focus:ring-red-100',
        default => 'focus:border-sky-400 focus:ring-sky-100',
    };
    $select = "w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 transition {$focus}";
@endphp

{{-- Jenis Laporan --}}
<div>
    <label class="mb-1.5 block text-sm font-bold text-gray-700">Jenis Laporan</label>
    <select name="jenis" class="{{ $select }}">
        <option value="informasi">Laporan Informasi (ringkas)</option>
        <option value="lengkap">Laporan Lengkap (termasuk log)</option>
    </select>
</div>

{{-- Periode --}}
<div>
    <label class="mb-1.5 block text-sm font-bold text-gray-700">
        Periode <span class="font-normal text-gray-400">(opsional)</span>
    </label>
    <select name="periode_id" class="{{ $select }}">
        <option value="">Semua Periode</option>
        @foreach ($periode as $p)
            <option value="{{ $p->id }}">{{ $p->nama }}</option>
        @endforeach
    </select>
</div>

{{-- Status Akhir --}}
<div>
    <label class="mb-1.5 block text-sm font-bold text-gray-700">Status Akhir</label>
    <select name="status" class="{{ $select }}">
        <option value="all">Semua Status</option>
        <option value="diterima">Diterima</option>
        <option value="ditolak">Ditolak</option>
        <option value="proses">Masih Diproses</option>
    </select>
</div>
