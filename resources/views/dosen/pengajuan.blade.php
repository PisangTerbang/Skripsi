<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="p-6 space-y-6">

        @foreach ($pengajuan as $judulId => $items)
            @php
                $first = $items->first();
                $pemenang = $items->firstWhere('status', 'disetujui');
                $sudahAdaPemenang = !is_null($pemenang);
            @endphp

            <div class="bg-white shadow rounded-xl p-5">

                {{-- HEADER --}}
                <div class="mb-4 border-b pb-2">

                    {{-- 🔵 JUDUL DOSEN --}}
                    @if ($first->jenis === 'pilih')
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ optional($first->judul)->nama_judul ?? '-' }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            Kode: {{ optional($first->judul)->kode ?? '-' }}
                        </p>
                    @endif

                    {{-- 🟣 JUDUL MANDIRI --}}
                    @if ($first->jenis === 'mandiri')
                        <h2 class="text-lg font-semibold text-purple-600">
                            📌 Judul Mandiri
                        </h2>

                        <p class="font-medium text-gray-800 mt-1">
                            {{ $first->judul_mandiri }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $first->deskripsi_mandiri }}
                        </p>
                    @endif

                    {{-- PEMENANG --}}
                    @if ($pemenang)
                        <p class="text-green-600 text-sm mt-2">
                            ✔ Diambil oleh: {{ $pemenang->mahasiswa->name }}
                        </p>
                    @endif

                </div>

                {{-- LIST MAHASISWA --}}
                <div class="space-y-3">

                    @foreach ($items as $p)
                        <div class="flex justify-between items-center border rounded-lg p-3">

                            <div>
                                <p class="font-medium text-gray-800">
                                    👤 {{ optional($p->mahasiswa)->name ?? '-' }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    🎯 Prioritas: {{ $p->prioritas }}
                                </p>

                                @if ($p->prioritas == 1)
                                    <span class="text-blue-500 font-semibold">
                                        Prioritas Utama
                                    </span>
                                @endif

                                {{-- STATUS --}}
                                <div class="mt-1">
                                    @if ($p->status == 'pending')
                                        <span class="text-yellow-500">Pending</span>
                                    @elseif ($p->status == 'disetujui')
                                        <span class="text-green-600 font-semibold">Disetujui</span>
                                    @else
                                        <span class="text-red-500">Ditolak</span>
                                    @endif
                                </div>
                            </div>

                            {{-- AKSI --}}
                            @if ($p->status == 'pending' && !$sudahAdaPemenang)
                                <div class="flex gap-2">

                                    {{-- SETUJUI --}}
                                    <form method="POST" action="{{ route('dosen.pengajuan.update', $p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="disetujui">

                                        <button class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- TOLAK --}}
                                    <form method="POST" action="{{ route('dosen.pengajuan.update', $p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="ditolak">

                                        <button class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>

            </div>
        @endforeach

    </div>
</x-layout-dosen>
