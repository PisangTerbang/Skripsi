@php
    $tanggal = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    $avatarUrl = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
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

            {{-- Bell Notifikasi --}}
            <div x-data="bellNotifKaLab()" x-init="init()" class="relative">
                <a href="{{ route('ka-lab.notifikasi') }}"
                    class="relative flex h-9 w-9 items-center justify-center rounded-xl text-gray-500 transition hover:bg-gray-100">
                    <x-heroicon-o-bell class="h-5 w-5" />
                    <span x-show="unread > 0" x-cloak
                        class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white shadow-sm">
                        <span x-text="unread > 9 ? '9+' : unread"></span>
                    </span>
                </a>
            </div>

            <div class="hidden md:block w-px h-8 bg-gray-200"></div>

            {{-- User Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl hover:bg-gray-100 transition-all duration-200 active:scale-95">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-lg object-cover shadow-sm">
                    @else
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-sky-500 to-blue-700 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-700 leading-tight">
                            {{ Str::limit(auth()->user()->name, 15) }}
                        </p>
                        <p class="text-[11px] text-gray-400 leading-tight">Kepala Lab</p>
                    </div>
                    <x-heroicon-o-chevron-down class="hidden md:block w-4 h-4 text-gray-400" />
                </button>

                {{-- Dropdown --}}
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
                        <p class="text-xs text-sky-600 font-medium mt-1">Kepala Laboratorium</p>
                    </div>

                    {{-- Notifikasi Link --}}
                    <a href="{{ route('ka-lab.notifikasi') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-sky-50 hover:text-sky-700 transition-colors">
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
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                                Keluar
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</header>

{{-- Alpine Store + Bell Polling --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('notif', {
            unread: {{ $unread }},
        });
    });

    function bellNotifKaLab() {
        return {
            unread: {{ $unread }},

            init() {
                this.$watch('unread', val => {
                    if (this.$store.notif) this.$store.notif.unread = val;
                });
                setInterval(() => this.fetchUnread(), 15000);
            },

            async fetchUnread() {
                try {
                    const res = await fetch("{{ route('ka-lab.notifikasi.data') }}", {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.unread = data.unread ?? 0;
                } catch (e) {
                    // silent fail
                }
            }
        }
    }
</script>
