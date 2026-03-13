<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="mt-4">

        <div class="bg-white border rounded-lg p-4 mb-6 flex justify-between items-center">

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


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @foreach ($judul as $j)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">

                    <div class="flex justify-between items-center mb-3">

                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                            {{ $j->kode ?? '-' }}
                        </span>

                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                            {{ $j->laboratorium->nama ?? '-' }}
                        </span>

                    </div>

                    <h2 class="text-lg font-semibold text-gray-800 leading-snug mb-2">
                        {{ $j->nama_judul }}
                    </h2>

                    <p class="text-sm text-gray-500 mb-4">
                        {{ $j->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>

                    <div class="flex justify-between text-sm text-gray-600 mb-4">

                        <div>
                            <span class="font-medium">Dosen:</span>
                            {{ $j->dosen?->name ?? '-' }}
                        </div>

                        <div>
                            <span class="font-medium">Peminat:</span>
                            {{ $j->pengajuan_count ?? 0 }}
                        </div>

                    </div>

                    <form method="POST" action="{{ route('Mahasiswa.pengajuan.store') }}"
                        class="space-y-3 border-t pt-4">

                        @csrf

                        <input type="hidden" name="judul_id" value="{{ $j->id }}">

                        <div>
                            <label class="text-xs text-gray-500">Prioritas</label>

                            <input type="number" name="prioritas" min="1" max="2"
                                class="w-20 border rounded px-2 py-1 text-sm text-center">
                        </div>

                        <textarea name="alasan" rows="2" placeholder="Alasan memilih judul" class="w-full border rounded-lg p-2 text-sm"></textarea>

                        @if ($jumlahPengajuan >= 2)
                            <button type="button" disabled
                                class="bg-gray-400 text-white px-4 py-2 rounded-lg text-sm cursor-not-allowed w-full">
                                Batas Pengajuan Tercapai
                            </button>
                        @else
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 w-full">
                                Ajukan
                            </button>
                        @endif

                    </form>

                </div>
            @endforeach

        </div>

    </div>

</x-layout>
