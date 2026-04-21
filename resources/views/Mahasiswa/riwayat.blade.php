<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="mt-4 space-y-6">

        <div class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold text-lg mb-3">Riwayat Pengajuan Anda</h2>

            @forelse ($pengajuan as $p)
                <div class="border rounded-lg p-4 mb-3">

                    {{-- JUDUL --}}
                    <div class="mb-2">
                        @if ($p->jenis === 'pilih')
                            <h3 class="font-semibold">
                                {{ $p->judul->nama_judul ?? '-' }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                Kode: {{ $p->judul->kode ?? '-' }}
                            </p>
                        @else
                            <h3 class="font-semibold text-purple-600">
                                📌 {{ $p->judul_mandiri }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $p->deskripsi_mandiri }}
                            </p>
                        @endif
                    </div>

                    {{-- INFO --}}
                    <div class="text-sm text-gray-600 mb-2">
                        🎯 Prioritas: {{ $p->prioritas }}
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-2">
                        @if ($p->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                                Pending
                            </span>
                        @elseif ($p->status === 'disetujui')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                Disetujui
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                Ditolak
                            </span>
                        @endif
                    </div>

                    {{-- CATATAN DOSEN --}}
                    @if ($p->catatan_dosen)
                        <div class="text-sm text-gray-500">
                            💬 Catatan: {{ $p->catatan_dosen }}
                        </div>
                    @endif

                </div>

            @empty
                <p class="text-gray-500">Belum ada pengajuan.</p>
            @endforelse

        </div>

    </div>
</x-layout>
