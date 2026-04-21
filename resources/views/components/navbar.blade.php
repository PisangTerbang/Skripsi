<nav x-data x-init="$store.notif.init()" class="flex-1 px-3 py-6 space-y-1 text-sm font-medium">

    <x-nav-link route="mahasiswa.beranda" icon="home">
        Beranda
    </x-nav-link>

    <x-nav-link route="mahasiswa.pengajuan" icon="document-text">
        Pengajuan
    </x-nav-link>

    <x-nav-link route="mahasiswa.riwayat" icon="clipboard-document-list">
        Riwayat Pengajuan
    </x-nav-link>

    <x-nav-link route="mahasiswa.notifikasi" icon="bell">
        Notifikasi

        <span x-show="$store.notif.unread > 0" x-transition x-text="$store.notif.unread"
            class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
        </span>
    </x-nav-link>

</nav>
