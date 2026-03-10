<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <div class="grid gap-4 mt-4">

        @foreach ($judul as $j)
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-indigo-600">

                <h2 class="font-semibold">{{ $j->nama_judul }}</h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $j->deskripsi }}
                </p>
                <p class="text-sm text-gray-500">
                    Peminat: {{ $j->pengajuan->count() }}
                </p>

                <form method="POST" action="/pengajuan/ajukan" class="mt-3">
                    @csrf
                    <input type="hidden" name="judul_id" value="{{ $j->id }}">

                    <label class="text-sm">Prioritas</label>
                    <select name="prioritas" class="border rounded p-1 ml-2">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>

                    <textarea name="alasan" placeholder="Alasan memilih" class="w-full mt-2 border rounded p-2 text-sm"></textarea>

                    <button class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded">
                        Ajukan
                    </button>
                </form>

            </div>
        @endforeach

    </div>
</x-layout>
