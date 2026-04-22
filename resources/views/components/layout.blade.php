<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - Sistem Skripsi</title>

    @vite('resources/css/app.css')

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>

    {{-- Alpine Cloak: Hide x-cloak elements until Alpine loads --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Alpine Store: Notification --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('notif', {
            unread: 0,
            loading: false,
            interval: null,

            async fetch() {
                try {
                    const res = await fetch("{{ route('mahasiswa.notifikasi.data') }}", {
                        credentials: 'same-origin'
                    });

                    if (!res.ok) throw new Error('Network response was not ok');

                    const data = await res.json();

                    const oldCount = this.unread;
                    this.unread = data.unread || 0;

                    if (this.unread > oldCount && this.unread > 0) {
                        this.showNotification();
                    }

                } catch (e) {
                    console.error('Notif fetch error:', e);
                }
            },

            async markAllRead() {
                try {
                    const res = await fetch("{{ route('mahasiswa.notifikasi.read') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (!res.ok) throw new Error('Failed to mark as read');
                    this.unread = 0;

                } catch (e) {
                    console.error('Mark read error:', e);
                }
            },

            showNotification() {
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Notifikasi Baru', {
                        body: `Anda memiliki ${this.unread} notifikasi baru`,
                        icon: '/favicon.ico',
                        tag: 'skripsi-notification'
                    });
                }
            },

            requestPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            },

            init() {
                this.loading = true;
                this.fetch().finally(() => {
                    this.loading = false;
                });

                this.requestPermission();

                if (this.interval) clearInterval(this.interval);

                this.interval = setInterval(() => {
                    this.fetch();
                }, 5000);
            },

            destroy() {
                if (this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }
        });
    });

    window.addEventListener('beforeunload', () => {
        if (Alpine.store('notif')) {
            Alpine.store('notif').destroy();
        }
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom scrollbar for main content */
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
        .main-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-100 h-screen overflow-hidden antialiased">

    <div x-data="{ mobileMenu: false, scrolled: false }" class="h-screen flex overflow-hidden">

        {{-- Mobile Backdrop --}}
        <div x-cloak x-show="mobileMenu"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenu = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden">
        </div>

        {{-- Sidebar --}}
        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:relative inset-y-0 left-0 w-64 h-full
                      bg-gradient-to-b from-indigo-600 via-indigo-700 to-indigo-800
                      text-white flex flex-col shadow-2xl z-50
                      transition-transform duration-300 ease-out">

            {{-- Close Button (Mobile) --}}
            <div class="md:hidden flex justify-end p-3">
                <button @click="mobileMenu = false"
                class="text-white/70 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/10
                           active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Logo/Brand --}}
            <div class="p-6 pb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center
                                shadow-lg shadow-indigo-900/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">Sistem Skripsi</h1>
                        <p class="text-[11px] text-indigo-300">Panel Mahasiswa</p>
                </div>
                </div>
            </div>

            {{-- Navigation --}}
            <x-navbar />

            {{-- User Info (Bottom) --}}
            <div class="p-3 border-t border-white/10">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/10">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-lg
                                flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate leading-tight">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[11px] text-indigo-300 truncate leading-tight mt-0.5">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>

        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            {{-- Header --}}
            <div class="sticky top-0 z-30 transition-all duration-300"
                 :class="scrolled ? 'shadow-lg bg-white/90 backdrop-blur-md' : 'bg-white shadow-sm'">
                <x-header>{{ $title ?? 'Dashboard' }}</x-header>
            </div>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto main-scroll"
                  @scroll="scrolled = $el.scrollTop > 10">

                <div class="max-w-7xl mx-auto p-6 space-y-6">
                    {{ $slot }}
                </div>

                {{-- Scroll to Top --}}
                <button x-cloak x-show="scrolled"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                        @click="$el.parentElement.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="fixed bottom-6 right-6 w-10 h-10 bg-indigo-600 hover:bg-indigo-700
                               text-white rounded-xl shadow-lg hover:shadow-xl
                               transition-all duration-200 flex items-center justify-center
                               hover:scale-105 active:scale-95 z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </button>

            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>
