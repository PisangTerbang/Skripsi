<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="notifikasiDosenPage()" x-init="init()" class="space-y-4">

        {{-- Header Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <x-heroicon-o-bell class="w-6 h-6 text-emerald-600" />
                            </span>
                            Notifikasi
                        </h2>
                        <p class="text-sm text-gray-500 mt-2">
                            <span x-text="unreadCount"></span> belum dibaca dari
                            <span x-text="totalCount"></span> total
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Refresh Button --}}
                        <button @click="fetchNotifications()" :disabled="loading"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                            <span x-text="loading ? 'Memuat...' : 'Refresh'"></span>
                        </button>

                        <button @click="markAllAsRead()" :disabled="loading || unreadCount === 0"
                            class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-all disabled:bg-gray-300 disabled:cursor-not-allowed flex items-center gap-2 shadow-sm hover:shadow-md">
                            <x-heroicon-o-check class="w-4 h-4" />
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
                                <div @click="markAsRead(notif.id); if (notif.link) window.location.href = notif.link"
                                    :class="!notif.is_read ? 'bg-emerald-50/50 hover:bg-emerald-50' : 'hover:bg-gray-50'"
                                    class="p-4 transition-all duration-200 cursor-pointer group">
                                    <div class="flex items-start gap-4">
                                        <div :class="!notif.is_read ? 'bg-emerald-100' : 'bg-gray-100'"
                                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200 group-hover:scale-110">
                                            <x-heroicon-o-bell class="w-5 h-5 text-emerald-600" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p :class="!notif.is_read ? 'text-gray-900 font-medium' : 'text-gray-600'"
                                                class="text-sm" x-text="notif.pesan"></p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-400" x-text="notif.waktu"></span>
                                                <template x-if="!notif.is_read">
                                                    <span class="text-xs text-emerald-600 font-medium">Baru</span>
                                                </template>
                                            </div>
                                        </div>
                                        <template x-if="!notif.is_read">
                                            <div class="w-2 h-2 bg-emerald-600 rounded-full flex-shrink-0 mt-2"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="filteredNotifications.length === 0">
                        <div class="text-center py-16">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-heroicon-o-inbox class="w-10 h-10 text-gray-400" />
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
    <div id="toast-container-dosen" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script>
        function notifikasiDosenPage() {
            return {
                notifications: [],
                loading: false,
                filter: 'all',
                unreadCount: 0,
                readCount: 0,
                totalCount: 0,

                get filteredNotifications() {
                    if (this.filter === 'all') return this.notifications;
                    if (this.filter === 'unread') return this.notifications.filter(function(n) {
                        return !n.is_read;
                    });
                    if (this.filter === 'read') return this.notifications.filter(function(n) {
                        return n.is_read;
                    });
                    return this.notifications;
                },

                init() {
                    this.notifications = [
                        @foreach ($aktivitas as $item)
                            {
                                id: {{ $item->id }},
                                pesan: '{{ addslashes($item->pesan) }}',
                                tipe: '{{ $item->tipe ?? 'info' }}',
                                is_read: {{ $item->is_read ? 'true' : 'false' }},
                                waktu: '{{ $item->created_at->diffForHumans() }}'
                            }
                            {{ $loop->last ? '' : ',' }}
                        @endforeach
                    ];
                    this.updateCounts();
                    var self = this;
                    setInterval(function() {
                        self.fetchNotifications(true);
                    }, 20000);
                },

                updateCounts() {
                    this.totalCount = this.notifications.length;
                    this.unreadCount = this.notifications.filter(function(n) {
                        return !n.is_read;
                    }).length;
                    this.readCount = this.totalCount - this.unreadCount;
                },

                async fetchNotifications(silent) {
                    if (!silent) this.loading = true;
                    try {
                        var response = await fetch("{{ route('dosen.notifikasi.data') }}", {
                            credentials: 'same-origin'
                        });
                        if (!response.ok) throw new Error('Failed');
                        var data = await response.json();
                        this.notifications = data.data.map(function(item) {
                            return {
                                id: item.id,
                                pesan: item.pesan,
                                link: item.link ?? null,
                                tipe: item.tipe,
                                is_read: item.is_read,
                                waktu: item.waktu
                            };
                        });
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
                    var notif = this.notifications.find(function(n) {
                        return n.id === id;
                    });
                    if (!notif || notif.is_read) return;
                    notif.is_read = true;
                    this.updateCounts();
                    try {
                        var response = await fetch('/dosen/notifikasi/' + id + '/read', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (!response.ok) throw new Error('Failed');
                        if (this.$store.notifDosen) {
                            this.$store.notifDosen.unread = this.unreadCount;
                        }
                    } catch (error) {
                        notif.is_read = false;
                        this.updateCounts();
                        this.showToast('Gagal menandai notifikasi', 'error');
                    }
                },

                async markAllAsRead() {
                    if (this.unreadCount === 0) return;
                    this.loading = true;
                    try {
                        var response = await fetch("{{ route('dosen.notifikasi.read') }}", {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (!response.ok) throw new Error('Failed');
                        this.notifications.forEach(function(n) {
                            n.is_read = true;
                        });
                        this.updateCounts();
                        if (this.$store.notifDosen) {
                            this.$store.notifDosen.unread = 0;
                        }
                        this.showToast('Semua notifikasi ditandai dibaca', 'success');
                    } catch (error) {
                        this.showToast('Gagal menandai notifikasi', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                getEmptyMessage() {
                    if (this.filter === 'unread') return 'Semua notifikasi sudah dibaca';
                    if (this.filter === 'read') return 'Belum ada notifikasi yang dibaca';
                    return 'Notifikasi akan muncul di sini';
                },

                showToast(message, type) {
                    var bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                    var toast = document.createElement('div');
                    toast.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white ' + bgColor;
                    toast.innerHTML = '<span class="text-sm font-medium">' + message + '</span>';
                    var container = document.getElementById('toast-container-dosen');
                    container.appendChild(toast);
                    setTimeout(function() {
                        toast.remove();
                    }, 3000);
                }
            }
        }
    </script>


</x-layout-dosen>
