@props(['route', 'icon'])

<a href="{{ route($route) }}"
    class="flex items-center gap-3 px-4 py-2 rounded-lg transition
   {{ request()->routeIs($route) ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-500/50 text-indigo-100' }}">

    <!-- Icon -->
    <span class="w-5 h-5">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5" />
    </span>

    <!-- Text -->
    <span>
        {{ $slot }}
    </span>

</a>
