@php
    $layoutMap = [
        'mahasiswa' => 'layout',
        'dosen' => 'layout-dosen',
        'ka_lab' => 'layout-kalab',
        'prodi' => 'layout-prodi',
        'koordinator_ta' => 'layout-koor-ta',
    ];
    $layoutComponent = $layoutMap[auth()->user()->role] ?? 'layout';
    $diterima = $hasil->where('status', 'disetujui')->count();
    $ditolak = $hasil->where('status', 'ditolak')->count();
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- Back --}}
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                <x-heroicon-o-arrow-left class="h-4 w-4" /> Kembali
            </a>

            {{-- Header pengumuman --}}
            <div class="overflow-hidden rounded-2xl border-2 border-indigo-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-megaphone class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-lg font-extrabold text-white leading-tight">{{ $pengumuman->judul }}</h1>
                        <p class="text-xs text-indigo-200">
                            Periode {{ $pengumuman->nama_periode }}
                            @if ($pengumuman->nama_pembuat)
                                · oleh {{ $pengumuman->nama_pembuat }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $pengumuman->isi }}</p>

                    @if ($tampilkanHasil)
                        <div class="mt-4 flex flex-wrap gap-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-700">
                                <x-heroicon-o-check-circle class="h-3.5 w-3.5" /> {{ $diterima }} Diterima
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-50 px-3 py-1 text-xs font-black text-red-700">
                                <x-heroicon-o-x-circle class="h-3.5 w-3.5" /> {{ $ditolak }} Ditolak
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabel hasil (hanya untuk pengumuman hasil pengajuan) --}}
            @if ($tampilkanHasil)
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div class="border-b-2 border-gray-100 px-6 py-4">
                    <h2 class="font-extrabold text-gray-800">Hasil Penetapan Judul TA</h2>
                    <p class="text-xs text-gray-400">Periode {{ $pengumuman->nama_periode }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="border-b-2 border-gray-100 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Mahasiswa</th>
                                <th class="px-6 py-3">Judul Ditetapkan</th>
                                <th class="px-6 py-3">Dosen Pembimbing</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($hasil as $i => $p)
                                @php $isMe = $p->mahasiswa_id === $myId; @endphp
                                <tr class="{{ $isMe ? 'bg-amber-50 ring-2 ring-inset ring-amber-300' : 'hover:bg-gray-50/60' }} transition-colors">
                                    <td class="px-6 py-3 text-gray-400">{{ $i + 1 }}</td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-gray-800">{{ $p->mahasiswa->name ?? '-' }}</p>
                                            @if ($isMe)
                                                <span class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-black text-white">Anda</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $p->status === 'disetujui' ? ($p->judulDitetapkan->nama_judul ?? '-') : '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $p->status === 'disetujui' ? ($p->judulDitetapkan->dosen->name ?? '-') : '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        @if ($p->status === 'disetujui')
                                            <span class="inline-flex items-center gap-1 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                                Diterima
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">
                                        Belum ada hasil yang diputuskan untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-dynamic-component>
