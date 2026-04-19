<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="p-6">

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM TAMBAH --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">

            <h3 class="font-semibold mb-3">Tambah Judul</h3>

            <form method="POST" action="{{ route('dosen.judul.store') }}">
                @csrf

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
                </div>

                <input type="text" name="nama_judul" placeholder="Nama Judul" class="border rounded p-2 w-full mb-2">

                <textarea name="deskripsi" class="border rounded p-2 w-full mb-2" placeholder="Deskripsi"></textarea>

                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Tambah
                </button>
            </form>

        </div>

        {{-- LIST JUDUL --}}
        <div class="bg-white rounded-lg shadow p-4">

            <h3 class="font-semibold mb-3">Daftar Judul</h3>

            @forelse ($judul as $j)
                <div class="border-b py-3 flex justify-between items-center">

                    <div>
                        <p class="text-xs text-indigo-600">{{ $j->kode }}</p>
                        <p class="font-semibold">{{ $j->nama_judul }}</p>
                        <p class="text-sm text-gray-500">{{ $j->deskripsi }}</p>
                    </div>

                    <form method="POST" action="{{ route('dosen.judul.destroy', $j->id) }}">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-500 hover:underline">
                            Hapus
                        </button>
                    </form>

                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada judul</p>
            @endforelse

        </div>

    </div>
</x-layout-dosen>
