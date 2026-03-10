<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <div class="bg-white rounded-xl shadow p-6 space-y-4">
                    <h2 class="font-semibold">Informasi Akun</h2>
                    <input type="text" placeholder="Nama" class="w-full border rounded-lg px-4 py-2">
                    <input type="email" placeholder="Email" class="w-full border rounded-lg px-4 py-2">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
                        Simpan
                    </button>
                </div>
</x-layout>