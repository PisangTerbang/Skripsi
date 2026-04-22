<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="notifikasiPage()" x-init="init()" class="space-y-4">

        {{-- Header Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- Title & Stats --}}
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </span>
                            Notifikasi
                        </h2>
                        <p class="text-sm text-gray-500 mt-2">
                            <span x-text="unreadCount"></span> notifikasi belum dibaca dari
                            <span x-text="totalCount"></span> total
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        {{-- Refresh Button --}}
                        <button @click="fetchNotifications()" :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800
                                       bg-gray-100 hover:bg-gray-200 rounded-lg transition-all
                                       disabled:opacity-50 disabled:cursor-not-allowed
                                       flex items-center gap-2">
                            <svg class="w-4 h-4" :class="loading ? 'animate-spin' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span x-text="loading ? 'Memuat...' : 'Refresh'"></span>
                        </button>

                        {{-- Mark All Read Button --}}
                        <button @click="markAllAsRead()" :disabled="loading || unreadCount === 0"
                            class="px-4 py-2 text-sm font-medium text-white
                                       bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-all
                                       disabled:bg-gray-300 disabled:cursor-not-allowed
                                       flex items-center gap-2 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Tandai Semua Dibaca
                        </button>
                    </div>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex gap-2 mt-6 border-b border-gray-200">
                    <button @click="filter = 'all'"
                        :class="filter === 'all' ? 'border-emerald-600 text-emerald-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        Semua (<span x-text="totalCount"></span>)
                    </button>
                    <button @click="filter = 'unread'"
                        :class="filter === 'unread' ? 'border-emerald-600 text-emerald-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        Belum Dibaca (<span x-text="unreadCount"></span>)
                    </button>
                    <button @click="filter = 'read'"
                        :class="filter === 'read' ? 'border-emerald-600 text-emerald-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        Sudah Dibaca (<span x-text="readCount"></span>)
                    </button>
                </div>
            </div>
        </div>

        {{-- Notifications List --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Loading Skeleton --}}
            <template x-if="loading && notifications.length === 0">
                <div class="divide-y divide-gray-100">
                    <template x-for="i in 5" :key="i">
                        <div class="p-4 animate-pulse">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Notifications --}}
            <template x-if="!loading || notifications.length > 0">
                <div>
                    <template x-if="filteredNotifications.length > 0">
                        <div class="divide-y divide-gray-100">
                            <template x-for="notif in filteredNotifications" :key="notif.id">
                                <div @click="markAsRead(notif.id)"
                                    :class="!notif.is_read ? 'bg-emerald-50/50 hover:bg-emerald-50' : 'hover:bg-gray-50'"
                                    class="p-4 transition-all duration-200 cursor-pointer group">

                                    <div class="flex items-start gap-4">
                                        {{-- Icon --}}
                                        <div :class="!notif.is_read ? 'bg-emerald-100' : 'bg-gray-100'"
                                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                                    transition-all duration-200 group-hover:scale-110">
                                            <template x-if="notif.tipe === 'pengajuan_baru'">
                                                <svg :class="!notif.is_read ? 'text-emerald-600' : 'text-gray-500'"
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </template>
                                            <template x-if="notif.tipe === 'approved'">
                                                <svg :class="!notif.is_read ? 'text-emerald-600' : 'text-gray-500'"
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </template>
                                            <template x-if="notif.tipe === 'rejected'">
                                                <svg :class="!notif.is_read ? 'text-red-600' : 'text-gray-500'"
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </template>
                                            <template
                                                x-if="notif.tipe !== 'pengajuan_baru' && notif.tipe !== 'approved' && notif.tipe !== 'rejected'">
                                                <svg :class="!notif.is_read ? 'text-emerald-600' : 'text-gray-500'"
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </template>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <p :class="!notif.is_read ? 'text-gray-900 font-medium' : 'text-gray-600'"
                                                class="text-sm" x-text="notif.pesan"></p>

                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-400" x-text="notif.waktu"></span>
                                                <template x-if="!notif.is_read">
                                                    <span class="text-xs text-emerald-600 font-medium">• Baru</span>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- Unread Indicator --}}
                                        <template x-if="!notif.is_read">
                                            <div class="w-2 h-2 bg-emerald-600 rounded-full flex-shrink-0 mt-2"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <template x-if="filteredNotifications.length === 0">
                        <div class="text-center py-16">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada notifikasi</p>
                            <p class="text-gray-400 text-sm mt-1" x-text="getEmptyMessage()"></p>
                        </div>
                    </template>
                </div>
            </template>

        </div>

    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script>
        function notifikasiPage() {
            return {
                notifications: @json($aktivitas->items()),
                loading: false,
                filter: 'all',
                unreadCount: {{ $aktivitas->where('is_read', false)->count() }},
                readCount: {{ $aktivitas->where('is_read', true)->count() }},
                totalCount: {{ $aktivitas->count() }},

                get filteredNotifications() {
                    if (this.filter === 'all') return this.notifications;
                    if (this.filter === 'unread') return this.notifications.filter(n => !n.is_read);
                    if (this.filter === 'read') return this.notifications.filter(n => n.is_read);
                    return this.notifications;
                },

                init() {
                    setInterval(() => {
                        this.fetchNotifications(true);
                    }, 10000);
                },

                async fetchNotifications(silent = false) {
                    if (!silent) this.loading = true;

                    try {
                        const response = await fetch("{{ route('dosen.notifikasi.data') }}", {
                            credentials: 'same-origin'
                        });

                        if (!response.ok) throw new Error('Failed to fetch');

                        const data = await response.json();

                        this.notifications = data.data.map(item => ({
                            id: item.id,
                            pesan: item.pesan,
                            tipe: item.tipe,
                            is_read: item.is_read,
                            waktu: item.waktu
                        }));

                        this.unreadCount = data.unread;
                        this.totalCount = this.notifications.length;
                        this.readCount = this.totalCount - this.unreadCount;

                        if (this.$store.notifDosen) {
                            this.$store.notifDosen.unread = data.unread;
                        }

                        if (!silent) {
                            this.showToast('Notifikasi diperbarui', 'success');
                        }

                    } catch (error) {
                        console.error('Fetch error:', error);
                        if (!silent) {
                            this.showToast('Gagal memuat notifikasi', 'error');
                        }
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
                        const response = await fetch(`/dosen/notifikasi/${id}/read`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error('Failed to mark as read');

                        if (this.$store.notifDosen) {
                            this.$store.notifDosen.unread = this.unreadCount;
                        }

                    } catch (error) {
                        console.error('Mark read error:', error);
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
                        const response = await fetch("{{ route('dosen.notifikasi.read') }}", {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error('Failed to mark all as read');

                        this.notifications.forEach(n => n.is_read = true);
                        this.readCount = this.totalCount;
                        this.unreadCount = 0;

                        if (this.$store.notifDosen) {
                            this.$store.notifDosen.unread = 0;
                        }

                        this.showToast('Semua notifikasi telah ditandai sebagai dibaca', 'success');

                    } catch (error) {
                        console.error('Mark all read error:', error);
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
                    toast.className = `flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    }`;

                    toast.innerHTML = `
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                            }
                        </svg>
                        <span class="text-sm font-medium">${message}</span>
                    `;

                    const container = document.getElementById('toast-container');
                    container.appendChild(toast);

                    setTimeout(() => toast.classList.add('translate-x-0', 'opacity-100'), 10);
                    setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }
            }
        }
    </script>

</x-layout-dosen>
