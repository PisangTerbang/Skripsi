<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <!-- 🔥 AUTO RESET BADGE -->
    <div x-data x-init="$store.notif.init();
    $store.notif.markAllRead()" class="p-6 space-y-4">

        <div class="bg-white rounded-xl shadow p-5">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Notifikasi</h3>

                <button @click="$store.notif.markAllRead()"
                    class="text-sm bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded transition">
                    Tandai semua dibaca
                </button>
            </div>

            <!-- LIST -->
            @forelse($aktivitas as $a)
                <div class="border-b py-3 px-3 rounded-lg transition duration-300" {{-- 🔥 HIGHLIGHT UNREAD --}}
                    @class([
                        'bg-indigo-50 border-l-4 border-indigo-500' => !$a->is_read,
                        'bg-white' => $a->is_read,
                    ])>
                    <p class="text-sm text-gray-800">
                        {{ $a->pesan }}
                    </p>

                    <span class="text-xs text-gray-500">
                        {{ $a->created_at->format('d M Y H:i') }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-sm">
                    Belum ada notifikasi.
                </p>
            @endforelse

        </div>

    </div>
</x-layout>
