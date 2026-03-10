<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="p-6">

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-4">Daftar Pengajuan</h3>

            @foreach ($pengajuan as $p)
                <div class="border-b py-3">

                    <p class="font-semibold">
                        {{ $p->judul->nama_judul ?? $p->judul_mandiri }}
                    </p>

                    <p class="text-sm">
                        Status: {{ $p->status }}
                    </p>

                    <form method="POST" action="/dosen/pengajuan/{{ $p->id }}/update">
                        @csrf
                        @method('PUT')

                        <select name="status" class="border rounded p-1">
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>

                        <textarea name="catatan_dosen" class="border rounded p-1 w-full mt-2" placeholder="Catatan dosen"></textarea>

                        <button class="bg-indigo-600 text-white px-3 py-1 mt-2 rounded">
                            Simpan
                        </button>
                    </form>

                </div>
            @endforeach

        </div>

    </div>
</x-layout-dosen>
