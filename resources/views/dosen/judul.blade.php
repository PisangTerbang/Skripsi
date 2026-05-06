<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="judulPage()" x-init="init()" class="space-y-6">

        {{-- ================= ALERTS ================= --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-700 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- ================= HEADER ================= --}}
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">Manajemen Judul Skripsi</h2>
                    <p class="text-emerald-100 text-sm">Kelola judul skripsi yang Anda tawarkan kepada mahasiswa</p>
                </div>
                <div class="hidden md:block">
                    <button @click="openCreateModal()"
                        class="bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Judul Baru
                    </button>
                </div>
            </div>
        </div>

        {{-- ================= STATS CARDS ================= --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-4">
                <x-heroicon-o-document-text class="w-8 h-8 text-indigo-600 mb-2" />
                <p class="text-gray-500 text-xs font-medium">Total</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalJudul }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <x-heroicon-o-clock class="w-8 h-8 text-yellow-600 mb-2" />
                <p class="text-gray-500 text-xs font-medium">Pending Koor</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingKoor }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <x-heroicon-o-shield-check class="w-8 h-8 text-amber-600 mb-2" />
                <p class="text-gray-500 text-xs font-medium">Pending Kalab</p>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingKalab }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <x-heroicon-o-check-circle class="w-8 h-8 text-green-600 mb-2" />
                <p class="text-gray-500 text-xs font-medium">Ditawarkan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $ditawarkan }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <x-heroicon-o-bolt class="w-8 h-8 text-blue-600 mb-2" />
                <p class="text-gray-500 text-xs font-medium">Aktif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $aktif }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <x-heroicon-o-lock-closed class="w-8 h-8 text-orange-600 mb-2" />
                <p class="text-gray-500 text-xs font-medium">Terkunci</p>
                <p class="text-2xl font-bold text-gray-800">{{ $terkunci }}</p>
            </div>
        </div>


        {{-- Aktif --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm font-medium mb-1">Judul Aktif</p>
            <p class="text-3xl font-bold text-gray-800">{{ $aktif }}</p>
        </div>

        {{-- Terkunci --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
            <p class="text-gray-500 text-sm font-medium mb-1">Terkunci</p>
            <p class="text-3xl font-bold text-gray-800">{{ $terkunci }}</p>
        </div>


        {{-- ================= ADD BUTTON (Mobile) ================= --}}
        <div class="md:hidden">
            <button @click="openCreateModal()"
                class="w-full bg-gradient-to-r from-emerald-600 to-green-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Judul Baru
            </button>
        </div>

        {{-- ================= SEARCH & FILTER ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row gap-4">

                {{-- Search --}}
                <div class="flex-1 relative">
                    <input type="text" x-model="searchQuery" @input="applyFilter()" placeholder="Cari judul..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Filter Lab --}}
                <select x-model="filterLab" @change="applyFilter()"
                    class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="all">Semua Lab</option>
                    @foreach ($laboratorium as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                    @endforeach
                </select>

                {{-- Filter Status --}}
                <select x-model="filterStatus" @change="applyFilter()"
                    class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Non-Aktif</option>
                    <option value="terkunci">Terkunci</option>
                </select>

                {{-- Result Count --}}
                <div class="flex items-center px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl font-medium text-sm">
                    <span x-text="filteredData.length"></span> judul
                </div>

            </div>
        </div>

        {{-- ================= JUDUL GRID ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <template x-for="item in filteredData" :key="item.id">
                <div
                    class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-emerald-300 hover:shadow-xl transition-all group">

                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-mono font-semibold"
                            x-text="item.kode"></span>
                        <div class="flex items-center gap-2">
                            <span x-show="item.aktif && !item.is_locked"
                                class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span x-show="!item.aktif" class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            <span x-show="item.is_locked" class="text-orange-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Lab Badge --}}
                    <div class="mb-3">
                        <span
                            class="inline-flex items-center gap-1 text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-semibold">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span x-text="item.lab_name"></span>
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors"
                        x-text="item.nama_judul"></h3>

                    {{-- Description --}}
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3" x-text="item.deskripsi"></p>

                    {{-- Stats --}}
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span x-text="item.total_peminat"></span> peminat
                        </div>
                        <div x-show="item.total_disetujui > 0" class="flex items-center gap-1 text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="item.total_disetujui"></span> disetujui
                        </div>
                    </div>

                    {{-- Status Badges --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span x-show="item.status_judul === 'pending_koor'"
                            class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">
                            Menunggu Koor Lab
                        </span>
                        <span x-show="item.status_judul === 'pending_kalab'"
                            class="px-2 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">
                            Menunggu Kepala Lab
                        </span>
                        <span x-show="item.status_judul === 'ditawarkan'"
                            class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                            Ditawarkan
                        </span>
                        <span x-show="item.status_judul === 'ditolak_kalab'"
                            class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                            Ditolak Kalab
                        </span>
                        <span x-show="item.is_locked"
                            class="px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-700 rounded-full">
                            Terkunci
                        </span>
                    </div>


                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-2">

                        {{-- Edit Button --}}
                        <button x-show="item.can_edit" @click="openEditModal(item)"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                            title="Edit Judul">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        <button x-show="!item.can_edit" disabled
                            class="p-2 text-gray-300 cursor-not-allowed rounded-lg"
                            title="Tidak dapat diedit (sudah ada mahasiswa yang disetujui)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        {{-- Toggle Status --}}
                        <button x-show="item.can_toggle" @click="toggleStatus(item.id)"
                            :class="item.aktif ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-50'"
                            class="p-2 rounded-lg transition-colors" :title="item.aktif ? 'Nonaktifkan' : 'Aktifkan'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>

                        <button x-show="!item.can_toggle" disabled
                            class="p-2 text-gray-300 cursor-not-allowed rounded-lg" title="Status terkunci">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </button>

                        {{-- Delete Button --}}
                        <button x-show="item.can_delete" @click="confirmDelete(item.id)"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Judul">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        <button x-show="!item.can_delete" disabled
                            class="p-2 text-gray-300 cursor-not-allowed rounded-lg"
                            title="Tidak dapat dihapus (sudah ada mahasiswa yang disetujui)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                    </div>

                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="filteredData.length === 0">
                <div class="col-span-full bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">
                        <span x-show="searchQuery || filterLab !== 'all' || filterStatus !== 'all'">Tidak ada judul
                            yang sesuai filter</span>
                        <span x-show="!searchQuery && filterLab === 'all' && filterStatus === 'all'">Belum ada
                            judul</span>
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                        <span x-show="searchQuery || filterLab !== 'all' || filterStatus !== 'all'">Coba ubah filter
                            atau kata kunci pencarian</span>
                        <span x-show="!searchQuery && filterLab === 'all' && filterStatus === 'all'">Mulai tambahkan
                            judul skripsi untuk mahasiswa</span>
                    </p>
                    <button @click="openCreateModal()"
                        x-show="!searchQuery && filterLab === 'all' && filterStatus === 'all'"
                        class="mt-6 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                        Tambah Judul Pertama
                    </button>
                </div>
            </template>

        </div>

        {{-- ================= CREATE/EDIT MODAL ================= --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">

            <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

                {{-- Modal Header --}}
                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">
                        <span x-show="!editMode">Tambah Judul Baru</span>
                        <span x-show="editMode">Edit Judul</span>
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form method="POST"
                    :action="editMode ? '/dosen/judul/' + selectedItem.id : '{{ route('dosen.judul.store') }}'"
                    class="p-6 space-y-6">
                    @csrf

                    {{-- Method Spoofing for Edit --}}
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Laboratorium --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Laboratorium <span class="text-red-500">*</span>
                        </label>
                        <select name="laboratorium_id" required x-model="formData.laboratorium_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach ($laboratorium as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nama Judul --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_judul" required maxlength="255"
                            x-model="formData.nama_judul"
                            placeholder="Contoh: Sistem Informasi Manajemen Perpustakaan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 255 karakter</p>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi" required rows="6" maxlength="1000" x-model="formData.deskripsi"
                            placeholder="Jelaskan latar belakang, tujuan, dan ruang lingkup penelitian..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                            <span x-show="!editMode">Tambah Judul</span>
                            <span x-show="editMode">Simpan Perubahan</span>
                        </button>
                    </div>

                </form>

            </div>

        </div>

        {{-- ================= DELETE CONFIRMATION MODAL ================= --}}
        <div x-show="showDeleteModal" x-cloak @click.self="showDeleteModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">

            <div @click.away="showDeleteModal = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Judul?</h3>
                    <p class="text-gray-600 mb-6">
                        Apakah Anda yakin ingin menghapus judul ini? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex gap-4">
                        <button type="button" @click="showDeleteModal = false"
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <form method="POST" :action="'/dosen/judul/' + deleteId" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        {{-- ================= ALPINE.JS SCRIPT ================= --}}
        <script>
            function judulPage() {
                return {
                    // Data
                    allData: @json($judul),
                    filteredData: [],

                    // Filter
                    searchQuery: '',
                    filterLab: 'all',
                    filterStatus: 'all',

                    // Modal
                    showModal: false,
                    showDeleteModal: false,
                    editMode: false,
                    selectedItem: null,
                    deleteId: null,

                    // Form Data
                    formData: {
                        laboratorium_id: '',
                        nama_judul: '',
                        deskripsi: ''
                    },

                    init() {
                        // Transform data
                        this.allData = this.allData.map(item => ({
                            id: item.id,
                            kode: item.kode,
                            nama_judul: item.nama_judul,
                            deskripsi: item.deskripsi,
                            aktif: item.aktif,
                            is_locked: item.is_locked,
                            lab_id: item.laboratorium_id,
                            lab_name: item.lab_name || 'N/A',
                            total_peminat: item.total_peminat || 0,
                            total_disetujui: item.total_disetujui || 0,
                            can_edit: item.can_edit !== false,
                            can_toggle: item.can_toggle !== false,
                            can_delete: item.can_delete !== false,
                            status_judul: item.status_judul || 'draft',

                        }));

                        this.applyFilter();
                    },

                    applyFilter() {
                        let result = this.allData;

                        // Search
                        if (this.searchQuery) {
                            const query = this.searchQuery.toLowerCase();
                            result = result.filter(item =>
                                item.nama_judul.toLowerCase().includes(query) ||
                                item.deskripsi.toLowerCase().includes(query) ||
                                item.kode.toLowerCase().includes(query)
                            );
                        }

                        // Filter Lab
                        if (this.filterLab !== 'all') {
                            result = result.filter(item => item.lab_id == this.filterLab);
                        }

                        // Filter Status
                        if (this.filterStatus === 'aktif') {
                            result = result.filter(item => item.aktif && !item.is_locked);
                        } else if (this.filterStatus === 'nonaktif') {
                            result = result.filter(item => !item.aktif);
                        } else if (this.filterStatus === 'terkunci') {
                            result = result.filter(item => item.is_locked);
                        }

                        this.filteredData = result;
                    },

                    openCreateModal() {
                        this.editMode = false;
                        this.selectedItem = null;
                        this.formData = {
                            laboratorium_id: '',
                            nama_judul: '',
                            deskripsi: ''
                        };
                        this.showModal = true;
                    },

                    openEditModal(item) {
                        this.editMode = true;
                        this.selectedItem = item;
                        this.formData = {
                            laboratorium_id: item.lab_id,
                            nama_judul: item.nama_judul,
                            deskripsi: item.deskripsi
                        };
                        this.showModal = true;
                    },

                    confirmDelete(id) {
                        this.deleteId = id;
                        this.showDeleteModal = true;
                    },

                    toggleStatus(id) {
                        if (confirm('Ubah status judul ini?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/dosen/judul/${id}/toggle`;

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'PATCH';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                }
            }
        </script>

        <style>
            [x-cloak] {
                display: none !important;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>

</x-layout-dosen>
