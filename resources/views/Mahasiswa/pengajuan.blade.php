<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="mt-4 space-y-6">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- INFO --}}
        <div class="bg-white border rounded-lg p-4 flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-gray-700">Pengajuan Anda</h3>
                <p class="text-sm text-gray-500">
                    {{ $jumlahPengajuan }} / 2 Judul
                </p>
            </div>

            <div class="text-sm text-gray-500">
                Maksimal 2 pengajuan
            </div>
        </div>

        {{-- FORM JUDUL MANDIRI --}}
        <div class="bg-white border rounded-xl shadow-sm p-6">
            <h2 class="font-semibold mb-3 text-purple-600">
                📌 Ajukan Judul Mandiri
            </h2>

            <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="jenis" value="mandiri">

                <input type="text" name="judul_mandiri" placeholder="Judul Anda" class="w-full border rounded p-2">

                <textarea name="deskripsi_mandiri" placeholder="Deskripsi" class="w-full border rounded p-2"></textarea>

                <input type="number" name="prioritas" min="1" max="2" placeholder="Prioritas (1 / 2)"
                    class="w-24 border rounded p-2">

                <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    Ajukan Judul Mandiri
                </button>
            </form>
        </div>

        {{-- LIST JUDUL DOSEN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach ($judul as $j)
                @php
                    $sudahDipilih = in_array($j->id, $pengajuanSaya);

                    $approved = $j->pengajuan->where('status', 'disetujui')->first();
                    $sudahDiambil = $approved !== null;
                @endphp

                <div class="bg-white border rounded-xl shadow-sm p-6 flex flex-col justify-between">

                    {{-- HEADER --}}
                    <div class="flex justify-between mb-2">
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                            {{ $j->kode ?? '-' }}
                        </span>

                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                            {{ $j->laboratorium->nama ?? '-' }}
                        </span>
                    </div>

                    {{-- TITLE --}}
                    <h2 class="font-semibold text-lg mb-1">
                        {{ $j->nama_judul }}
                    </h2>

                    {{-- DESC --}}
                    <p class="text-sm text-gray-500 mb-3">
                        {{ $j->deskripsi }}
                    </p>

                    {{-- INFO --}}
                    <div class="text-sm mb-2">
                        👨‍🏫 Dosen: {{ $j->dosen->name ?? '-' }}
                    </div>

                    <div class="text-sm mb-3">
                        📊 Peminat: {{ $j->peminat ?? 0 }}
                    </div>

                    {{-- STATUS --}}
                    @if ($sudahDiambil)
                        <div class="text-sm text-green-600 mb-3 flex items-center gap-2">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                Sudah Diambil
                            </span>
                            <span>
                                oleh <b>{{ $approved->mahasiswa->name ?? '-' }}</b>
                            </span>
                        </div>
                    @endif

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" class="mt-auto">
                        @csrf

                        <input type="hidden" name="jenis" value="pilih">
                        <input type="hidden" name="judul_id" value="{{ $j->id }}">

                        <div class="mb-2">
                            <label class="text-xs text-gray-500">Prioritas</label>
                            <input type="number" name="prioritas" min="1" max="2"
                                class="w-16 border rounded px-2 py-1 text-center">
                        </div>

                        <textarea name="alasan" placeholder="Alasan memilih judul" class="w-full border rounded p-2 mb-3 text-sm"></textarea>

                        {{-- BUTTON LOGIC --}}
                        @if ($sudahDiambil)
                            <button disabled class="bg-gray-400 text-white w-full py-2 rounded-lg cursor-not-allowed">
                                Tidak tersedia
                            </button>
                        @elseif ($sudahDipilih)
                            <button disabled class="bg-green-500 text-white w-full py-2 rounded-lg">
                                ✔ Sudah Anda Pilih
                            </button>
                        @elseif ($jumlahPengajuan >= 2)
                            <button disabled class="bg-gray-400 text-white w-full py-2 rounded-lg">
                                Batas Pengajuan Tercapai
                            </button>
                        @else
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white w-full py-2 rounded-lg">
                                Ajukan Judul
                            </button>
                        @endif

                    </form>

                </div>
            @endforeach

        </div>

    </div>
</x-layout>
