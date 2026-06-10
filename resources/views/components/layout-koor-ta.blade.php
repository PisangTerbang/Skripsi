<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Sistem Skripsi</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .main-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .main-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .main-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-100 h-screen overflow-hidden antialiased">

    <div x-data="{ mobileMenu: false, scrolled: false }" class="h-screen flex overflow-hidden">

        {{-- Mobile Overlay --}}
        <div x-cloak x-show="mobileMenu" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileMenu = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden">
        </div>

        {{-- Sidebar --}}
        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:relative inset-y-0 left-0 w-64 h-full
                   bg-gradient-to-b from-indigo-600 via-indigo-700 to-blue-800
                   text-white flex flex-col shadow-2xl z-50
                   transition-transform duration-300 ease-out">

            {{-- Close (Mobile) --}}
            <div class="md:hidden flex justify-end p-3">
                <button @click="mobileMenu = false"
                    class="text-white/70 hover:text-white p-2 rounded-lg hover:bg-white/10">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>

            {{-- Brand --}}
            <div class="p-6 pb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-900/20">
                        <x-heroicon-o-cog-6-tooth class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">Sistem Skripsi</h1>
                        <p class="text-[11px] text-indigo-300">Panel Koordinator TA</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <x-navbar-koor-ta />

            {{-- Logout --}}
            <div class="px-4 pb-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="group w-full flex items-center gap-3 px-4 py-2 rounded-xl text-indigo-200 hover:text-red-300 hover:bg-red-500/10 transition-all duration-200 ease-out">
                        <x-heroicon-o-arrow-right-on-rectangle
                            class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" />
                        <span class="font-medium text-sm">Keluar</span>
                    </button>
                </form>
            </div>

            {{-- User Info --}}
            <div class="p-3 border-t border-white/10">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/10">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-lg flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-indigo-300 truncate leading-tight mt-0.5">{{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <div class="sticky top-0 z-30 transition-all duration-300"
                :class="scrolled ? 'shadow-lg bg-white/90 backdrop-blur-md' : 'bg-white shadow-sm'">
                <x-header-koor-ta>{{ $title ?? 'Dashboard' }}</x-header-koor-ta>
            </div>
            <main class="flex-1 overflow-y-auto main-scroll" @scroll="scrolled = $el.scrollTop > 10">
                <div class="max-w-7xl mx-auto p-6 space-y-6">
                    {{ $slot }}
                </div>
                <button x-cloak x-show="scrolled" x-transition
                    @click="$el.parentElement.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="fixed bottom-6 right-6 w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center hover:scale-105 active:scale-95 z-20">
                    <x-heroicon-o-chevron-up class="w-5 h-5" />
                </button>
            </main>
        </div>

    </div>
    @stack('scripts')
</body>

</html>
