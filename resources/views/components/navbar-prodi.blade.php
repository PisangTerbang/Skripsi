<nav class="flex-1 px-3 py-4 text-sm overflow-y-auto" role="navigation">

    <p class="px-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-violet-300/70">Menu Utama</p>

    <div class="space-y-1">
        <x-nav-link-prodi route="prodi.dashboard" icon="home">
            Dashboard
        </x-nav-link-prodi>
        <x-nav-link-prodi route="prodi.pengajuan.index" match="prodi.pengajuan.*" icon="document-text">
            Review Pengajuan
        </x-nav-link-prodi>
        <x-nav-link-prodi route="prodi.monitoring" icon="eye">
            Monitoring Sistem
        </x-nav-link-prodi>
    </div>

    <p class="px-4 pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-violet-300/70">Lainnya</p>

    <div class="space-y-1">
        <x-nav-link-prodi route="prodi.notifikasi" icon="bell">
            Notifikasi
        </x-nav-link-prodi>
        <x-nav-link-prodi route="prodi.pengaturan" icon="cog-6-tooth">
            Pengaturan
        </x-nav-link-prodi>
    </div>

</nav>
