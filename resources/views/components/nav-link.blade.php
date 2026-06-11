@props(['route', 'icon', 'badge' => null, 'match' => null])

@php
    $isActive = request()->routeIs($match ?? $route . '*');
    $href = Route::has($route) ? route($route) : '#';
@endphp

<a href="{{ $href }}" x-data="{ hover: false, pressed: false }" @mouseenter="hover = true"
    @mouseleave="hover = false; pressed = false" @mousedown="pressed = true" @mouseup="pressed = false"
    class="group relative flex items-center gap-3 px-4 py-2.5 rounded-xl
           transition-all duration-200 ease-out select-none
           {{ $isActive
               ? 'bg-white/20 text-white shadow-lg shadow-indigo-900/20'
               : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
    :class="pressed && {{ $isActive ? 'false' : 'true' }} ? 'scale-[0.98]' : ''" role="menuitem"
    aria-current="{{ $isActive ? 'page' : 'false' }}">

    {{-- Active Indicator Bar --}}
    <span
        class="absolute left-0 top-1/2 -translate-y-1/2 w-1 rounded-r-full bg-white
                transition-all duration-300 ease-out
                 {{ $isActive ? 'h-8 opacity-100' : 'h-0 opacity-0' }}">
    </span>

    {{-- Icon --}}
    <span
        class="relative w-5 h-5 flex-shrink-0 transition-all duration-200
                 {{ $isActive ? 'scale-110' : 'group-hover:scale-110' }}">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 transition-all duration-200" />

        {{-- Active Glow --}}
        @if ($isActive)
            <span class="absolute inset-0 bg-white/25 blur-md rounded-full -z-10 animate-pulse"></span>
        @endif
    </span>

    {{-- Text --}}
    <span
        class="flex-1 truncate transition-all duration-200
                 {{ $isActive ? 'font-semibold tracking-wide' : 'font-medium' }}">
        {{ $slot }}
    </span>

    {{-- Badge Slot --}}
    @if ($badge)
        {{ $badge }}
    @endif

    {{-- Hover Background --}}
    <span x-show="hover && {{ $isActive ? 'false' : 'true' }}" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-white/5 rounded-xl pointer-events-none">
    </span>
</a>
