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

        {{-- GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach ($judul as $j)
                @php
                    $sudahDipilih = in_array($j->id, $pengajuanSaya);
                @endphp

                <div class="bg-white border rounded-xl shadow-sm p-6 flex flex-col justify-between">

                    {{-- HEADER --}}
                    <div class="flex justify-between mb-3">
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                            {{ $j->kode ?? '-' }}
                        </span>

                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                            {{ $j->laboratorium->nama ?? '-' }}
                        </span>
                    </div>

                    {{-- TITLE --}}
                    <h2 class="text-lg font-semibold mb-2">
                        {{ $j->nama_judul }}
                    </h2>

                    {{-- DESC --}}
                    <p class="text-sm text-gray-500 mb-4">
                        {{ $j->deskripsi }}
                    </p>

                    {{-- INFO --}}
                    <div class="flex justify-between text-sm mb-4">
                        <div>
                            <b>Dosen:</b> {{ $j->dosen->name ?? '-' }}
                        </div>

                        <div>
                            <b>Peminat:</b> {{ $j->jumlahPeminat() }}
                        </div>
                    </div>

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('Mahasiswa.pengajuan.store') }}"
                        class="space-y-3 border-t pt-4">
                        @csrf

                        <input type="hidden" name="judul_id" value="{{ $j->id }}">

                        {{-- PRIORITAS --}}
                        <div>
                            <label class="text-xs text-gray-500">Prioritas</label>
                            <input type="number" name="prioritas" min="1" max="2"
                                class="w-16 border rounded px-2 py-1 text-center">
                        </div>

                        {{-- ALASAN --}}
                        <textarea name="alasan" rows="2" placeholder="Alasan memilih judul" class="w-full border rounded-lg p-2 text-sm"></textarea>

                        {{-- BUTTON LOGIC --}}
                        @if ($sudahDipilih)
                            <button disabled class="bg-green-500 text-white w-full py-2 rounded-lg">
                                Sudah Dipilih
                            </button>
                        @elseif ($jumlahPengajuan >= 2)
                            <button disabled class="bg-gray-400 text-white w-full py-2 rounded-lg">
                                Batas Pengajuan Tercapai
                            </button>
                        @else
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white w-full py-2 rounded-lg">
                                Ajukan
                            </button>
                        @endif

                    </form>

                </div>
            @endforeach

        </div>
    </div>
</x-layout>
