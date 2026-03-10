@props(['route', 'icon'])

@php
    $isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
    {{ $attributes->merge([
        'class' =>
            'group relative flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-300 ' .
            ($isActive ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-200 hover:bg-slate-700/70 hover:text-white'),
    ]) }}>

    {{-- Active Indicator --}}
    @if ($isActive)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-emerald-400 rounded-r-full"></span>
    @endif

    {{-- Auto Icon Switch --}}
    <x-dynamic-component :component="$isActive ? 'heroicon-s-' . $icon : 'heroicon-o-' . $icon"
        class="w-5 h-5 transition
               {{ $isActive ? '' : 'opacity-80 group-hover:opacity-100' }}" />

    <span class="tracking-wide">
        {{ $slot }}
    </span>

</a>
