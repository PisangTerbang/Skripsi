<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ WAJIB: CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>

    @vite('resources/css/app.css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('notif', {

                unread: 0,
                interval: null,

                async fetch() {
                    try {
                        const res = await fetch("{{ route('mahasiswa.notifikasi.data') }}", {
                            credentials: 'same-origin'
                        });

                        const data = await res.json();
                        this.unread = data.unread;

                    } catch (e) {
                        console.error('Notif error:', e);
                    }
                },

                // 🔥 mark semua sebagai dibaca
                async markAllRead() {
                    try {
                        await fetch("{{ route('mahasiswa.notifikasi.read') }}", {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        });

                        this.unread = 0;

                    } catch (e) {
                        console.error('Mark read error:', e);
                    }
                },

                init() {
                    this.fetch();

                    // 🔥 cegah interval dobel
                    if (this.interval) clearInterval(this.interval);

                    this.interval = setInterval(() => {
                        this.fetch();
                    }, 5000);
                }
            });

        });
    </script>
</head>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<body class="bg-gradient-to-br from-slate-50 via-indigo-50 to-slate-100 h-screen overflow-hidden">

    <div x-data="{ mobileMenu: false, open: false, scrolled: false }" class="h-screen flex overflow-hidden">

        <!-- Backdrop -->
        <div x-show="mobileMenu" @click="mobileMenu=false" class="fixed inset-0 bg-black/40 z-40 md:hidden"></div>

        <!-- Sidebar -->
        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:relative inset-y-0 left-0 w-64 h-full bg-indigo-600 text-white flex flex-col shadow-2xl transform transition duration-300 md:translate-x-0 z-50">

            <div class="md:hidden flex justify-end p-4">
                <button @click="mobileMenu=false">✕</button>
            </div>

            <div class="p-6 text-3xl font-bold">
                Mahasiswa
            </div>

            <x-navbar></x-navbar>

        </aside>

        <!-- Main Area -->
        <div class="flex-1 flex flex-col h-screen">

            <!-- Header (Sticky) -->
            <div class="sticky top-0 z-30 transition-shadow duration-300"
                :class="scrolled ? 'shadow-md bg-white/80 backdrop-blur' : ''">
                <x-header>{{ $title }}</x-header>
            </div>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto" @scroll="scrolled = $el.scrollTop > 10">
                <div class="max-w-7xl mx-auto p-6 space-y-6">
                    {{ $slot }}
                </div>
            </main>

        </div>

    </div>
</body>

</html>
