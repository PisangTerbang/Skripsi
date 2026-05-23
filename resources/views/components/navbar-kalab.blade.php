<nav class="flex-1 px-3 py-4 text-sm overflow-y-auto" role="navigation">

    <p class="px-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-sky-300/70">Menu Utama</p>

    <div class="space-y-1">
        <x-nav-link-kalab route="ka-lab.dashboard" icon="home">
            Dashboard
        </x-nav-link-kalab>
        <x-nav-link-kalab route="ka-lab.judul.index" icon="document-text">
            Monitoring Judul
        </x-nav-link-kalab>
        <x-nav-link-kalab route="ka-lab.validasi.index" icon="clipboard-document-check">
            Validasi Judul
        </x-nav-link-kalab>
        <x-nav-link-kalab route="ka-lab.pengajuan.index" icon="clipboard-document-list">
            Pengajuan Mahasiswa
        </x-nav-link-kalab>
    </div>

    <p class="px-4 pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-sky-300/70">Lainnya</p>

    <div class="space-y-1">
        <x-nav-link-kalab route="ka-lab.notifikasi" icon="bell">
            Notifikasi
        </x-nav-link-kalab>
        <x-nav-link-kalab route="ka-lab.pengaturan" icon="cog-6-tooth">
            Pengaturan
        </x-nav-link-kalab>
    </div>

</nav>
