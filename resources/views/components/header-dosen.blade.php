<header class="bg-white/70 backdrop-blur-md px-6 py-4 border-b flex items-center justify-between">
    <div class="flex items-center space-x-4">
        <button @click="mobileMenu=true" class="md:hidden text-2xl">☰</button>
        <h1 class="text-2xl font-bold text-slate-800">
            Panel Dosen - {{ $slot }}
        </h1>
    </div>

    <div class="relative">
        <button @click="open=!open">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e"
                class="w-10 h-10 rounded-full border-2 border-indigo-600 object-cover">
        </button>

        <div x-show="open" @click.away="open=false" x-transition
            class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl py-2 border z-50">

            <a href="#" class="flex items-center space-x-3 px-4 py-2 hover:bg-slate-100">
                <x-heroicon-o-user-circle class="w-5 h-5 text-slate-500" />
                <span>Profil Dosen</span>
            </a>

            <a href="/dosen/pengaturan" class="flex items-center space-x-3 px-4 py-2 hover:bg-slate-100">
                <x-heroicon-o-cog-6-tooth class="w-5 h-5 text-slate-500" />
                <span>Pengaturan</span>
            </a>

            <div class="border-t my-2"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left flex items-center space-x-3 px-4 py-2 text-red-600 hover:bg-red-50">
                    <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />
                    <span>Keluar</span>
                </button>
            </form>

        </div>

    </div>
</header>
