<nav class="flex-1 px-3 py-4 text-sm overflow-y-auto" role="navigation">

    <p class="px-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">Menu Utama</p>

    <div class="space-y-1">
        <x-nav-link-koor-ta route="koor-ta.dashboard" icon="home">
            Dashboard
        </x-nav-link-koor-ta>
    </div>

    <p class="px-4 pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">Manajemen</p>

    <div class="space-y-1">
        <x-nav-link-koor-ta route="koor-ta.users.index" icon="users">
            User Management
        </x-nav-link-koor-ta>
        <x-nav-link-koor-ta route="koor-ta.periode.index" icon="calendar-days">
            Periode Pengajuan
        </x-nav-link-koor-ta>
        <x-nav-link-koor-ta route="koor-ta.pengumuman.index" icon="megaphone">
            Pengumuman
        </x-nav-link-koor-ta>
    </div>

    <p class="px-4 pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">Monitoring</p>

    <div class="space-y-1">
        <x-nav-link-koor-ta route="koor-ta.monitoring.index" icon="chart-bar">
            Overview
        </x-nav-link-koor-ta>
        <x-nav-link-koor-ta route="koor-ta.monitoring.pengajuan" icon="document-text">
            Semua Pengajuan
        </x-nav-link-koor-ta>
        <x-nav-link-koor-ta route="koor-ta.monitoring.judul" icon="book-open">
            Semua Judul
        </x-nav-link-koor-ta>
        <x-nav-link-koor-ta route="koor-ta.export.index" icon="arrow-down-tray">
            Export Data
        </x-nav-link-koor-ta>
    </div>

    <p class="px-4 pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-indigo-300/70">Lainnya</p>

    <div class="space-y-1">
        <x-nav-link-koor-ta route="koor-ta.notifikasi" icon="bell">
            Notifikasi
        </x-nav-link-koor-ta>
        <x-nav-link-koor-ta route="koor-ta.pengaturan" icon="cog-6-tooth">
            Pengaturan
        </x-nav-link-koor-ta>
    </div>

</nav>
