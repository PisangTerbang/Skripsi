<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="p-6">

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-4">Riwayat Notifikasi</h3>

            @forelse($aktivitas as $a)
                <div class="border-b py-3">
                    <p>{{ $a->pesan }}</p>
                    <span class="text-sm text-gray-500">
                        {{ $a->created_at->format('d M Y H:i') }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500">Belum ada notifikasi.</p>
            @endforelse

        </div>

    </div>
</x-layout>