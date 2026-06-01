<x-layout>
    <x-slot:title>Konsultasi Dosen</x-slot>

    <div class="space-y-6">

        {{-- ===== HEADER ===== --}}
        <div
            class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-7 shadow-xl">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative flex items-center justify-between gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Mahasiswa</p>
                    <h2 class="mt-1 text-2xl font-black text-white">Konsultasi Dosen</h2>
                    <p class="mt-1 text-sm text-indigo-200">Chat langsung dengan dosen pembimbing potensial</p>
                </div>
                <div class="hidden lg:flex shrink-0 gap-3">
                    <div class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Percakapan</p>
                        <p class="mt-1 text-4xl font-black text-white">{{ $conversations->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ===== DAFTAR DOSEN ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-5 py-4">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-users class="h-4 w-4 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white text-sm">Mulai Chat Baru</h3>
                </div>
                <div class="p-3 space-y-2">
                    @forelse ($dosenList as $dosen)
                        <a href="{{ route('mahasiswa.konsultasi.show', $dosen->id) }}"
                            class="group flex items-center gap-3 rounded-xl border-2 border-gray-100 bg-gray-50 p-3 transition hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-sm">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-sm font-black text-white shadow-sm">
                                {{ strtoupper(substr($dosen->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate group-hover:text-indigo-700">
                                    {{ $dosen->name }}
                                </p>
                                <p class="text-xs text-gray-400">Dosen Pembimbing</p>
                            </div>
                            <x-heroicon-o-chat-bubble-left-ellipsis
                                class="h-4 w-4 text-gray-300 transition group-hover:text-indigo-500" />
                        </a>
                    @empty
                        <p class="py-8 text-center text-sm text-gray-400">Belum ada dosen terdaftar</p>
                    @endforelse
                </div>
            </div>

            {{-- ===== RIWAYAT PERCAKAPAN ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md lg:col-span-2">
                <div
                    class="flex items-center gap-3 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-purple-700 px-5 py-4">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-chat-bubble-left-right class="h-4 w-4 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white text-sm">Riwayat Percakapan</h3>
                </div>

                @if ($conversations->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-purple-100 mb-5">
                            <x-heroicon-o-chat-bubble-left-right class="h-10 w-10 text-indigo-300" />
                        </div>
                        <p class="text-base font-extrabold text-gray-800">Belum ada percakapan</p>
                        <p class="mt-2 text-sm text-gray-400">Mulai chat dengan dosen dari daftar di sebelah kiri</p>
                    </div>
                @else
                    <div class="divide-y-2 divide-gray-100">
                        @foreach ($conversations as $conv)
                            <a href="{{ route('mahasiswa.konsultasi.show', $conv->dosen_id) }}"
                                class="group flex items-start gap-4 px-5 py-4 transition hover:bg-indigo-50/30">
                                <div
                                    class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-base font-black text-white shadow-sm ring-2 ring-indigo-200">
                                    {{ strtoupper(substr($conv->dosen->name, 0, 1)) }}
                                    @if ($conv->unread > 0)
                                        <span
                                            class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white shadow-sm">
                                            {{ $conv->unread > 9 ? '9+' : $conv->unread }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="font-black text-gray-800 group-hover:text-indigo-700 transition">
                                            {{ $conv->dosen->name }}
                                        </p>
                                        <span class="shrink-0 text-xs text-gray-400">
                                            {{ $conv->lastMessage?->created_at?->diffForHumans() ?? '' }}
                                        </span>
                                    </div>
                                    <p class="mt-0.5 text-sm text-gray-500 truncate">
                                        @if ($conv->lastMessage)
                                            @if ($conv->lastMessage->tipe === 'judul_card')
                                                📋 Judul card dikirim
                                            @else
                                                {{ $conv->lastMessage->body }}
                                            @endif
                                        @else
                                            Belum ada pesan
                                        @endif
                                    </p>
                                </div>
                                @if ($conv->unread > 0)
                                    <span
                                        class="shrink-0 inline-flex items-center rounded-full border-2 border-indigo-200 bg-indigo-100 px-2.5 py-1 text-xs font-black text-indigo-700">
                                        {{ $conv->unread }} baru
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layout>
