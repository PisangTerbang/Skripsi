@php
    $hour = (int) now()->format('H');
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }
    $tanggal = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    $namaDepan = Str::before(auth()->user()->name, ' ');
    $avatarUrl = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
    $initials = strtoupper(substr(auth()->user()->name, 0, 2));
@endphp

<header class="px-6 py-4">
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-4">
            <button @click="mobileMenu = !mobileMenu"
                class="md:hidden text-gray-500 hover:text-gray-800 transition-colors p-2 rounded-lg hover:bg-gray-100 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">{{ $slot }}</h1>
                <p class="text-xs md:text-sm text-gray-400 mt-0.5">
                    {{ $tanggal }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">

            {{-- Notifikasi: klik bell → preview dropdown (tak pindah halaman); klik item → pindah --}}
            <div x-data="{ notifOpen: false }" class="relative">
                <button @click="notifOpen = !notifOpen; if (notifOpen) $store.notif.fetch()"
                    class="relative p-2.5 text-gray-400 hover:text-indigo-600 rounded-xl hover:bg-indigo-50 transition-all duration-200 active:scale-95">
                    <x-heroicon-o-bell class="w-5 h-5" />
                    <span x-cloak x-show="$store.notif.unread > 0" x-transition
                        x-text="$store.notif.unread > 9 ? '9+' : $store.notif.unread"
                        class="absolute -top-0.5 -right-0.5 min-w-[1.25rem] h-5 flex items-center justify-center bg-red-500 text-white text-[10px] font-bold px-1.5 rounded-full ring-2 ring-white animate-pulse">
                    </span>
                </button>

                <div x-cloak x-show="notifOpen" @click.away="notifOpen = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">

                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-gray-800">Notifikasi</p>
                            <span x-show="$store.notif.unread > 0" x-text="$store.notif.unread"
                                class="min-w-[1.25rem] h-5 flex items-center justify-center bg-red-100 text-red-600 text-[10px] font-black px-1.5 rounded-full"></span>
                        </div>
                        <button x-show="$store.notif.unread > 0" @click="$store.notif.markAllRead()"
                            class="text-[11px] font-bold text-indigo-500 hover:text-indigo-700">Tandai dibaca</button>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        <template x-if="$store.notif.items.length === 0">
                            <div class="px-4 py-10 text-center">
                                <x-heroicon-o-inbox class="w-10 h-10 mx-auto text-gray-200" />
                                <p class="mt-2 text-xs text-gray-400">Belum ada notifikasi</p>
                            </div>
                        </template>
                        <template x-for="item in $store.notif.items" :key="item.id">
                            <a :href="item.link || '{{ route('mahasiswa.notifikasi') }}'"
                                class="flex items-start gap-3 px-4 py-3 border-b border-gray-50 hover:bg-indigo-50/50 transition-colors">
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full"
                                    :class="item.is_read ? 'bg-gray-200' : 'bg-indigo-500'"></span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs leading-relaxed text-gray-700 line-clamp-2"
                                        :class="!item.is_read ? 'font-semibold' : ''" x-text="item.pesan"></p>
                                    <p class="mt-0.5 text-[11px] text-gray-400" x-text="item.waktu"></p>
                                </div>
                            </a>
                        </template>
                    </div>

                    <a href="{{ route('mahasiswa.notifikasi') }}"
                        class="block px-4 py-2.5 text-center text-xs font-bold text-indigo-600 hover:bg-indigo-50 border-t border-gray-100">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>

            <div class="hidden md:block w-px h-8 bg-gray-200"></div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl hover:bg-gray-100 transition-all duration-200 active:scale-95">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-lg object-cover shadow-sm">
                    @else
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ $initials }}
                        </div>
                    @endif
                <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-700 leading-tight">{{ Str::limit(auth()->user()->name, 15) }}</p>
                        <p class="text-[11px] text-gray-400 leading-tight">Mahasiswa</p>
                    </div>
                    <svg class="hidden md:block w-4 h-4 text-gray-400 transition-transform duration-200"
                        x-bind:class="open ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-cloak x-show="open" @click.away="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border-gray-100 py-1.5 z-50 overflow-hidden">

                    <div class="px-4 py-3 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="Avatar" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="py-1.5">
                        <a href="{{ route('mahasiswa.pengaturan') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                            <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                Pengaturan
                        </a>
                    </div>

                    <div class="border-t border-gray-100 pt-1.5">
                        <button type="button" @click="logoutModal = true; open = false"
                            class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                            Keluar
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</header>
