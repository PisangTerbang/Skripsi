<x-layout-prodi title="Notifikasi">

    <div x-data="notifikasiProdi()" x-init="init()" class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-violet-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-violet-200 bg-violet-50">
                            <x-heroicon-o-bell class="h-5 w-5 text-violet-600" />
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Notifikasi</h1>
                            <p class="mt-0.5 text-xs text-gray-400">
                                <span x-text="unreadCount"></span> belum dibaca dari
                                <span x-text="totalCount"></span> total
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- ✅ fix: pakai x-bind:disabled dan x-bind:class --}}
                        <button @click="fetchNotifications()" x-bind:disabled="loading"
                            class="inline-flex items-center gap-2 rounded-xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 disabled:opacity-50">
                            <x-heroicon-o-arrow-path class="h-3.5 w-3.5" x-bind:class="loading ? 'animate-spin' : ''" />
                            <span x-text="loading ? 'Memuat...' : 'Refresh'"></span>
                        </button>
                        <button @click="markAllAsRead()" x-bind:disabled="loading || unreadCount === 0"
                            class="inline-flex items-center gap-2 rounded-xl border-2 border-violet-300 bg-violet-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <x-heroicon-o-check class="h-3.5 w-3.5" />
                            Tandai Semua Dibaca
                        </button>
                    </div>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm w-fit">
                <button @click="filter = 'all'"
                    x-bind:class="filter === 'all' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100'"
                    class="rounded-xl px-4 py-2 text-xs font-bold transition-all">
                    Semua
                    <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-black"
                        x-bind:class="filter === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'">
                        <span x-text="totalCount"></span>
                    </span>
                </button>
                <div class="h-5 w-px bg-gray-200"></div>
                <button @click="filter = 'unread'"
                    x-bind:class="filter === 'unread' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100'"
                    class="rounded-xl px-4 py-2 text-xs font-bold transition-all">
                    Belum Dibaca
                    <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-black"
                        x-bind:class="filter === 'unread' ? 'bg-white/20 text-white' : 'bg-violet-50 text-violet-600'">
                        <span x-text="unreadCount"></span>
                    </span>
                </button>
                <div class="h-5 w-px bg-gray-200"></div>
                <button @click="filter = 'read'"
                    x-bind:class="filter === 'read' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100'"
                    class="rounded-xl px-4 py-2 text-xs font-bold transition-all">
                    Sudah Dibaca
                    <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-black"
                        x-bind:class="filter === 'read' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'">
                        <span x-text="readCount"></span>
                    </span>
                </button>
            </div>

            {{-- List Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                <div
                    class="flex items-center justify-between border-b-4 border-violet-200 bg-gradient-to-r from-violet-700 to-purple-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-bell class="h-5 w-5 text-white" />
                        </div>
                        <h2 class="font-extrabold text-white">Daftar Notifikasi</h2>
                    </div>
                    <span x-text="`${filteredNotifications.length} notifikasi`"
                        class="rounded-full border-2 border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                    </span>
                </div>

                {{-- Loading Skeleton --}}
                <template x-if="loading && notifications.length === 0">
                    <div class="divide-y-2 divide-gray-100">
                        <template x-for="i in 5" :key="i">
                            <div class="flex items-start gap-4 p-5 animate-pulse">
                                <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 w-3/4 rounded-lg bg-gray-200"></div>
                                    <div class="h-3 w-1/2 rounded-lg bg-gray-200"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!loading || notifications.length > 0">
                    <div>
                        <template x-if="filteredNotifications.length > 0">
                            <div class="divide-y-2 divide-gray-100">
                                <template x-for="notif in filteredNotifications" :key="notif.id">
                                    <div @click="markAsRead(notif.id)"
                                        x-bind:class="!notif.is_read ? 'bg-violet-50/50 hover:bg-violet-50' : 'hover:bg-gray-50'"
                                        class="group flex items-start gap-4 p-5 transition-all cursor-pointer">

                                        <div x-bind:class="!notif.is_read ? 'bg-violet-100 border-violet-200' :
                                            'bg-gray-100 border-gray-200'"
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 transition-all group-hover:scale-105">
                                            <svg x-bind:class="!notif.is_read ? 'text-violet-600' : 'text-gray-500'"
                                                class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p x-bind:class="!notif.is_read ? 'font-bold text-gray-900' : 'font-medium text-gray-600'"
                                                class="text-sm leading-relaxed" x-text="notif.pesan"></p>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <span class="text-xs text-gray-400" x-text="notif.waktu"></span>
                                                <template x-if="!notif.is_read">
                                                    <span class="text-xs font-bold text-violet-600">• Baru</span>
                                                </template>
                                            </div>
                                        </div>

                                        <template x-if="!notif.is_read">
                                            <div
                                                class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-violet-600 shadow-sm shadow-violet-300">
                                            </div>
                                        </template>

                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="filteredNotifications.length === 0">
                            <div class="flex flex-col items-center justify-center py-24 text-center">
                                <div
                                    class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-violet-100 bg-gradient-to-br from-violet-50 to-purple-100 mb-6">
                                    <x-heroicon-o-bell-slash class="h-12 w-12 text-violet-300" />
                                </div>
                                <p class="text-lg font-extrabold text-gray-800">Tidak ada notifikasi</p>
                                <p class="mt-2 text-sm text-gray-400" x-text="getEmptyMessage()"></p>
                            </div>
                        </template>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <div id="toast-container-prodi" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    @push('scripts')
        <script>
            function notifikasiProdi() {
                return {
                    notifications: @json($aktivitas->items()),
                    loading: false,
                    filter: 'all',
                    unreadCount: {{ $aktivitas->where('is_read', false)->count() }},
                    readCount: {{ $aktivitas->where('is_read', true)->count() }},
                    totalCount: {{ $aktivitas->count() }},

                    get filteredNotifications() {
                        if (this.filter === 'unread') return this.notifications.filter(n => !n.is_read);
                        if (this.filter === 'read') return this.notifications.filter(n => n.is_read);
                        return this.notifications;
                    },

                    init() {
                        setInterval(() => this.fetchNotifications(true), 10000);
                    },

                    async fetchNotifications(silent = false) {
                        if (!silent) this.loading = true;
                        try {
                            const res = await fetch("{{ route('prodi.notifikasi.data') }}", {
                                credentials: 'same-origin'
                            });
                            if (!res.ok) throw new Error('Failed');
                            const data = await res.json();
                            this.notifications = data.data;
                            this.unreadCount = data.unread;
                            this.totalCount = this.notifications.length;
                            this.readCount = this.totalCount - this.unreadCount;
                            if (this.$store.notif) this.$store.notif.unread = data.unread;
                            if (!silent) this.showToast('Notifikasi diperbarui', 'success');
                        } catch (e) {
                            if (!silent) this.showToast('Gagal memuat notifikasi', 'error');
                        } finally {
                            this.loading = false;
                        }
                    },

                    async markAsRead(id) {
                        const notif = this.notifications.find(n => n.id === id);
                        if (!notif || notif.is_read) return;
                        notif.is_read = true;
                        this.unreadCount--;
                        this.readCount++;
                        try {
                            const res = await fetch(`/prodi/notifikasi/${id}/read`, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                }
                            });
                            if (!res.ok) throw new Error('Failed');
                            if (this.$store.notif) this.$store.notif.unread = this.unreadCount;
                        } catch (e) {
                            notif.is_read = false;
                            this.unreadCount++;
                            this.readCount--;
                            this.showToast('Gagal menandai notifikasi', 'error');
                        }
                    },

                    async markAllAsRead() {
                        if (this.unreadCount === 0) return;
                        this.loading = true;
                        try {
                            const res = await fetch("{{ route('prodi.notifikasi.read') }}", {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                }
                            });
                            if (!res.ok) throw new Error('Failed');
                            this.notifications.forEach(n => n.is_read = true);
                            this.readCount = this.totalCount;
                            this.unreadCount = 0;
                            if (this.$store.notif) this.$store.notif.unread = 0;
                            this.showToast('Semua notifikasi telah dibaca', 'success');
                        } catch (e) {
                            this.showToast('Gagal menandai semua notifikasi', 'error');
                        } finally {
                            this.loading = false;
                        }
                    },

                    getEmptyMessage() {
                        if (this.filter === 'unread') return 'Semua notifikasi sudah dibaca';
                        if (this.filter === 'read') return 'Belum ada notifikasi yang dibaca';
                        return 'Notifikasi akan muncul di sini';
                    },

                    showToast(message, type = 'success') {
                        const toast = document.createElement('div');
                        toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white text-sm font-semibold ${
                        type === 'success' ? 'bg-emerald-500' : 'bg-red-500'
                    }`;
                        toast.innerHTML = `<span>${message}</span>`;
                        document.getElementById('toast-container-prodi').appendChild(toast);
                        setTimeout(() => {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);
                    }
                }
            }
        </script>
    @endpush

</x-layout-prodi>
