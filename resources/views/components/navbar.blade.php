<nav x-data="{
        loading: true,
        bellShake: false,
    }"
    x-init="
        setTimeout(() => loading = false, 300);
        $watch('$store.notif.unread', (val, oldVal) => {
            if (val > oldVal && val > 0) {
                bellShake = true;
                setTimeout(() => bellShake = false, 1000);
            }
        });
    "
    class="flex-1 flex flex-col px-3 py-4 text-sm overflow-y-auto scrollbar-hide"
    role="navigation"
    aria-label="Main Navigation">

    {{-- ======== SECTION: Menu Utama ========== --}}
    <div class="mb-1">
        <p class="px-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">
            Menu Utama
        </p>

        <div class="space-y-1">
            {{-- Beranda --}}
            <x-nav-link route="mahasiswa.beranda" icon="home">
                Beranda
            </x-nav-link>

            {{-- Pengajuan --}}
            <x-nav-link route="mahasiswa.pengajuan" icon="document-text">
                Pengajuan
            </x-nav-link>

            {{-- Riwayat --}}
            <x-nav-link route="mahasiswa.riwayat" icon="clipboard-document-list">
                Riwayat Pengajuan
            </x-nav-link>
        </div>
    </div>

    {{-- ========== DIVIDER ========== --}}
    <div class="py-3 px-4">
        <div class="h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>
    </div>

    {{-- ========== SECTION: Lainnya ========== --}}
    <div class="mb-1">
        <p class="px-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">
            Lainnya
        </p>

        <div class="space-y-1">
            {{-- Notifikasi --}}
            <x-nav-link route="mahasiswa.notifikasi" icon="bell">
                <span :class="{ 'animate-bell-shake': bellShake }">Notifikasi</span>

                <x-slot:badge>
                    {{-- Loading Skeleton --}}
                    <span x-show="$store.notif.loading" x-transition class="ml-auto">
                        <span class="block w-6 h-6 rounded-full bg-white/20 animate-pulse"></span>
                </span>

                    {{-- Badge Counter --}}
                    <span x-cloak
                          x-show="!$store.notif.loading && $store.notif.unread > 0"
                          x-transition:enter="transition ease-out duration-300"
                          x-transition:enter-start="opacity-0 scale-0 rotate-12"
                          x-transition:enter-end="opacity-100 scale-100 rotate-0"
                          x-transition:leave="transition ease-in duration-200"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-0"
                          x-text="$store.notif.unread > 99 ? '99+' : $store.notif.unread"
                          class="ml-auto min-w-[1.5rem] h-6 flex items-center justify-center
                                 bg-gradient-to-br from-red-400 to-red-600
                                 text-white text-xs font-bold
                                 px-2 rounded-full
                                 shadow-lg shadow-red-500/40
                                 ring-2 ring-white/20">
                    </span>
                </x-slot:badge>
            </x-nav-link>

            {{-- Pengaturan --}}
            <x-nav-link route="mahasiswa.pengaturan" icon="cog-6-tooth">
                Pengaturan
            </x-nav-link>
        </div>
    </div>

    {{-- ========== SPACER ========== --}}
    <div class="flex-1"></div>

    {{-- ======== LOGOUT BUTTON ========== --}}
    <div class="pt-3 px-1">
        <div class="h-px bg-gradient-to-r from-transparent via-white/15 to-transparent mb-3"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                x-data="{ hover: false }"
                @mouseenter="hover = true"
                @mouseleave="hover = false"
                class="group relative w-full flex items-center gap-3 px-4 py-2.5 rounded-xl
                text-indigo-200 hover:text-red-300 hover:bg-red-500/10
                       transition-all duration-200 ease-out">

                <span class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                </span>

                <span class="font-medium">Keluar</span>

                {{-- Hover Effect --}}
                <span x-show="hover"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="absolute inset-0 bg-red-500/5 rounded-xl pointer-events-none">
                </span>
            </button>
        </form>
    </div>

</nav>

{{-- Bell Shake Animation --}}
<style>
    @keyframes bell-shake {
        0% { transform: rotate(0); }
        15% { transform: rotate(12deg); }
        30% { transform: rotate(-10deg); }
        45% { transform: rotate(8deg); }
        60% { transform: rotate(-6deg); }
        75% { transform: rotate(3deg); }
        90% { transform: rotate(-1deg); }
        100% { transform: rotate(0); }
    }

    .animate-bell-shake {
        animation: bell-shake 0.8s ease-in-out;
        display: inline-block;
        transform-origin: top center;
    }

    /* Hide scrollbar but keep functionality */
    .scrollbar-hide:-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
