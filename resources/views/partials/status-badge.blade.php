@php
    $badgeClass = match ($status ?? null) {
        'disetujui' => 'bg-green-100 text-green-700 ring-green-200',
        'ditolak' => 'bg-red-100 text-red-700 ring-red-200',
        default => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
    };
    $badgeLabel = match ($status ?? null) {
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        default => 'Menunggu',
    };
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $badgeClass }}">
    {{ $badgeLabel }}
</span>
