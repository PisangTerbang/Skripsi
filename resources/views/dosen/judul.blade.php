<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="p-6">

        <div class="bg-white rounded-lg shadow p-4 mb-6">

            <h3 class="font-semibold mb-3">Tambah Judul</h3>

            <form method="POST" action="/dosen/judul">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-slate-600 mb-1">
                        Pilih Laboratorium
                    </label>

                    <select name="laboratorium_id" class="border rounded p-2 w-full">
                        <option value="">-- Pilih Laboratorium --</option>
                        @foreach ($laboratorium as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">
                        Judul akan dikategorikan berdasarkan laboratorium ini
                    </p>
                </div>
                @csrf
                <input type="text" name="nama_judul" placeholder="Nama Judul" class="border rounded p-2 w-full mb-2">

                <textarea name="deskripsi" class="border rounded p-2 w-full mb-2" placeholder="Deskripsi"></textarea>

                <button class="bg-indigo-600 text-white px-3 py-1 rounded">
                    Tambah
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold mb-3">Daftar Judul</h3>

            @foreach ($judul as $j)
                <div class="border-b py-3 flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $j->nama_judul }}</p>
                        <p class="text-sm text-gray-500">{{ $j->deskripsi }}</p>
                    </div>

                    <form method="POST" action="/dosen/judul/{{ $j->id }}">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-500">
                            Hapus
                        </button>
                    </form>
                </div>
            @endforeach

        </div>

    </div>
</x-layout-dosen>
