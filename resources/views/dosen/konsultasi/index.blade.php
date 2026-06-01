<x-layout-dosen>
    <x-slot:title>Konsultasi Mahasiswa</x-slot>

    <div class="space-y-6">

        {{-- ===== HEADER ===== --}}
        <div
            class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 p-7 shadow-xl">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-12 -left-6 h-40 w-40 rounded-full bg-white/5"></div>
            <div class="relative flex items-center justify-between gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-300">Dosen</p>
                    <h2 class="mt-1 text-2xl font-black text-white">Konsultasi Mahasiswa</h2>
                    <p class="mt-1 text-sm text-emerald-200">Pesan masuk dari mahasiswa yang ingin berkonsultasi</p>
                </div>
                <div class="hidden lg:flex shrink-0 gap-3">
                    <div class="rounded-2xl border-2 border-white/20 bg-white/15 px-5 py-4 text-center backdrop-blur-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-200">Percakapan</p>
                        <p class="mt-1 text-4xl font-black text-white">{{ $conversations->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== INBOX ===== --}}
        <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
            <div
                class="flex items-center gap-3 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                    <x-heroicon-o-inbox class="h-5 w-5 text-white" />
                </div>
                <h3 class="font-extrabold text-white">Inbox</h3>
            </div>

            @if ($conversations->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-3xl border-2 border-emerald-100 bg-gradient-to-br from-emerald-50 to-teal-100 mb-5">
                        <x-heroicon-o-inbox class="h-10 w-10 text-emerald-300" />
                    </div>
                    <p class="text-base font-extrabold text-gray-800">Belum ada pesan</p>
                    <p class="mt-2 text-sm text-gray-400">Mahasiswa belum mengirim pesan konsultasi</p>
                </div>
            @else
                <div class="divide-y-2 divide-gray-100">
                    @foreach ($conversations as $conv)
                        <a href="{{ route('dosen.konsultasi.show', $conv->id) }}"
                            class="group flex items-start gap-4 px-6 py-4 transition hover:bg-emerald-50/30
                                {{ $conv->unread > 0 ? 'bg-emerald-50/40' : '' }}">
                            <div
                                class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-base font-black text-white shadow-sm ring-2 ring-emerald-200">
                                {{ strtoupper(substr($conv->mahasiswa->name, 0, 1)) }}
                                @if ($conv->unread > 0)
                                    <span
                                        class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white shadow-sm">
                                        {{ $conv->unread > 9 ? '9+' : $conv->unread }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p
                                        class="{{ $conv->unread > 0 ? 'font-black' : 'font-bold' }} text-gray-800 group-hover:text-emerald-700 transition">
                                        {{ $conv->mahasiswa->name }}
                                    </p>
                                    <span class="shrink-0 text-xs text-gray-400">
                                        {{ $conv->lastMessage?->created_at?->diffForHumans() ?? '' }}
                                    </span>
                                </div>
                                <p
                                    class="mt-0.5 text-sm {{ $conv->unread > 0 ? 'font-semibold text-gray-700' : 'text-gray-500' }} truncate">
                                    @if ($conv->lastMessage)
                                        @if ($conv->lastMessage->tipe === 'judul_card')
                                            📋 Menanyakan tentang judul
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
                                    class="shrink-0 inline-flex items-center rounded-full border-2 border-emerald-200 bg-emerald-100 px-2.5 py-1 text-xs font-black text-emerald-700">
                                    {{ $conv->unread }} baru
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</x-layout-dosen>
