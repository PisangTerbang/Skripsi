<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body class="bg-gradient-to-br from-slate-50 via-indigo-50 to-slate-100 h-screen overflow-hidden">

    <div x-data="{ mobileMenu: false, open: false, scrolled: false }" class="h-screen flex overflow-hidden">

        <div x-show="mobileMenu" @click="mobileMenu=false" class="fixed inset-0 bg-black/40 z-40 md:hidden"></div>

        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:relative inset-y-0 left-0 w-64 h-full bg-indigo-600 text-white flex flex-col shadow-2xl transform transition duration-300 md:translate-x-0 z-50">

            <div class="md:hidden flex justify-end p-4">
                <button @click="mobileMenu=false">✕</button>
            </div>

            <div class="p-6 text-3xl font-bold">
                Dosen
            </div>

            <x-navbar-dosen></x-navbar-dosen>

        </aside>

        <div class="flex-1 flex flex-col h-screen">

            <div class="sticky top-0 z-30 transition-shadow duration-300"
                :class="scrolled ? 'shadow-md bg-white/80 backdrop-blur' : ''">
                <x-header-dosen>{{ $title }}</x-header-dosen>
            </div>

            <main class="flex-1 overflow-y-auto" @scroll="scrolled = $el.scrollTop > 10">
                <div class="max-w-7xl mx-auto p-6 space-y-6">
                    {{ $slot }}
                </div>
            </main>

        </div>

    </div>
</body>

</html>
