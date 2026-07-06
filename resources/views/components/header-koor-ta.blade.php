@php
    $tanggal = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    $initials = strtoupper(substr(auth()->user()->name, 0, 2));
    $unread = \Illuminate\Support\Facades\DB::table('aktivitas')
        ->where('user_id', auth()->id())
        ->where('is_read', \Illuminate\Support\Facades\DB::raw('false'))
        ->count();
@endphp

<header class="px-6 py-4">
    <div class="flex items-center justify-between">

        {{-- Kiri: Hamburger + Title --}}
        <div class="flex items-center gap-4">
            <button @click="mobileMenu = !mobileMenu"
                class="md:hidden text-gray-500 hover:text-gray-800 transition-colors p-2 rounded-lg hover:bg-gray-100 active:scale-95">
                <x-heroicon-o-bars-3 class="w-6 h-6" />
            </button>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">{{ $slot }}</h1>
                <p class="text-xs md:text-sm text-gray-400 mt-0.5">{{ $tanggal }}</p>
            </div>
        </div>

        {{-- Kanan: Bell + User Dropdown --}}
        <div class="flex items-center gap-2">

            {{-- Notifikasi: klik bell → preview dropdown; klik item → pindah --}}
            <div x-data="bellNotifKoorTA()" x-init="init()" class="relative">
                <button @click="notifOpen = !notifOpen; if (notifOpen) fetchUnread()"
                    class="relative flex h-9 w-9 items-center justify-center rounded-xl text-gray-500 transition hover:bg-gray-100">
                    <x-heroicon-o-bell class="h-5 w-5" />
                    <span x-show="unread > 0" x-cloak
                        class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white shadow-sm">
                        <span x-text="unread > 9 ? '9+' : unread"></span>
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
                            <span x-show="unread > 0" x-text="unread"
                                class="min-w-[1.25rem] h-5 flex items-center justify-center bg-red-100 text-red-600 text-[10px] font-black px-1.5 rounded-full"></span>
                        </div>
                        <button x-show="unread > 0" @click="markAllRead()"
                            class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700">Tandai dibaca</button>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        <template x-if="items.length === 0">
                            <div class="px-4 py-10 text-center">
                                <x-heroicon-o-inbox class="w-10 h-10 mx-auto text-gray-200" />
                                <p class="mt-2 text-xs text-gray-400">Belum ada notifikasi</p>
                            </div>
                        </template>
                        <template x-for="item in items" :key="item.id">
                            <a :href="item.link || '{{ route('koor-ta.notifikasi') }}'"
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

                    <a href="{{ route('koor-ta.notifikasi') }}"
                        class="block px-4 py-2.5 text-center text-xs font-bold text-indigo-600 hover:bg-indigo-50 border-t border-gray-100">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>

            <div class="hidden md:block w-px h-8 bg-gray-200"></div>

            {{-- User Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl hover:bg-gray-100 transition-all duration-200 active:scale-95">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-700 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm">
                        {{ $initials }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-700 leading-tight">
                            {{ Str::limit(auth()->user()->name, 15) }}
                        </p>
                        <p class="text-[11px] text-gray-400 leading-tight">Koordinator TA</p>
                    </div>
                    <x-heroicon-o-chevron-down class="hidden md:block w-4 h-4 text-gray-400" />
                </button>

                {{-- Dropdown Menu --}}
                <div x-cloak x-show="open" @click.away="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden">

                    {{-- User Info --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        <p class="text-xs text-indigo-600 font-medium mt-1">Koordinator Tugas Akhir</p>
                    </div>

                    {{-- Notifikasi Link --}}
                    <a href="{{ route('koor-ta.notifikasi') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                        <x-heroicon-o-bell class="w-4 h-4" />
                        Notifikasi
                        @if ($unread > 0)
                            <span
                                class="ml-auto rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-black text-white">
                                {{ $unread }}
                            </span>
                        @endif
                    </a>

                    {{-- Logout --}}
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

{{-- Alpine Store + Bell Polling --}}
<script>
    document.addEventListener('alpine:init', () => {

        // Store global untuk unread count
        Alpine.store('notif', {
            unread: {{ $unread }},
        });

    });

    function bellNotifKoorTA() {
        return {
            unread: {{ $unread }},
            items: [],
            notifOpen: false,

            init() {
                // Sync dengan store
                this.$watch('unread', val => {
                    if (this.$store.notif) this.$store.notif.unread = val;
                });

                this.fetchUnread();
                // Polling setiap 15 detik
                setInterval(() => this.fetchUnread(), 15000);
            },

            async fetchUnread() {
                try {
                    const res = await fetch("{{ route('koor-ta.notifikasi.data') }}", {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.unread = data.unread ?? 0;
                    this.items = data.data ?? [];
                } catch (e) {
                    // silent fail
                }
            },

            async markAllRead() {
                try {
                    await fetch("{{ route('koor-ta.notifikasi.read') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    this.unread = 0;
                    this.items = this.items.map(i => ({ ...i, is_read: true }));
                } catch (e) {
                    // silent fail
                }
            }
        }
    }
</script>
