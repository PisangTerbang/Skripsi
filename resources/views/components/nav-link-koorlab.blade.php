@props(['route', 'icon', 'badge' => null])
@php
    $isActive = request()->routeIs($route . '*');
    $href = Route::has($route) ? route($route) : '#';
@endphp

<a href="{{ $href }}"
    class="group relative flex items-center gap-3 px-4 py-2.5 rounded-xl
           transition-all duration-200 ease-out select-none
           {{ $isActive
               ? 'bg-white/20 text-white shadow-lg shadow-sky-900/20'
               : 'text-sky-200 hover:bg-white/10 hover:text-white' }}"
    role="menuitem" aria-current="{{ $isActive ? 'page' : 'false' }}">

    <span
        class="absolute left-0 top-1/2 -translate-y-1/2 w-1 rounded-r-full bg-white
                transition-all duration-300 ease-out
                {{ $isActive ? 'h-8 opacity-100' : 'h-0 opacity-0' }}"></span>

    <span
        class="relative w-5 h-5 flex-shrink-0 transition-all duration-200
                {{ $isActive ? 'scale-110' : 'group-hover:scale-110' }}">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5" />
        @if ($isActive)
            <span class="absolute inset-0 bg-white/25 blur-md rounded-full -z-10 animate-pulse"></span>
        @endif
    </span>

    <span class="flex-1 truncate {{ $isActive ? 'font-semibold tracking-wide' : 'font-medium' }}">
        {{ $slot }}
    </span>

    @if ($badge)
        {{ $badge }}
    @endif
</a>
