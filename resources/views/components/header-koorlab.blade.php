@php
    $tanggal = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    $avatarUrl = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
    $initials = strtoupper(substr(auth()->user()->name, 0, 2));
@endphp

<header class="px-6 py-4">
    <div class="flex items-center justify-between">

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

        <div class="flex items-center gap-2">
            <div class="hidden md:block w-px h-8 bg-gray-200"></div>

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
                            {{ Str::limit(auth()->user()->name, 15) }}</p>
                        <p class="text-[11px] text-gray-400 leading-tight">Koordinator Lab</p>
                    </div>
                    <x-heroicon-o-chevron-down class="hidden md:block w-4 h-4 text-gray-400" />
                </button>

                <div x-cloak x-show="open" @click.away="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border-gray-100 py-1.5 z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        <p class="text-xs text-sky-600 font-medium mt-1">Lab:
                            {{ auth()->user()->laboratorium->nama ?? '-' }}</p>
                    </div>
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
