<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="riwayatPage()" x-init="init()" class="space-y-6">

        {{-- ================= HEADER CARD ================= --}}
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">Riwayat Pengajuan</h2>
                    <p class="text-indigo-100 text-sm">Lihat semua pengajuan judul skripsi Anda</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold">{{ $pengajuan->count() }}</p>
                        <p class="text-xs text-indigo-100 mt-1">Total Pengajuan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= STATS SUMMARY ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Pending --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Menunggu Review</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pengajuan->where('status', 'pending')->count() }}</p>
            </div>

            {{-- Disetujui --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Disetujui</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pengajuan->where('status', 'disetujui')->count() }}</p>
            </div>

            {{-- Ditolak --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Ditolak</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pengajuan->where('status', 'ditolak')->count() }}</p>
            </div>

        </div>

        {{-- ================= FILTER & SORT ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row gap-4">

                {{-- Filter Status --}}
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label>
                    <select x-model="filterStatus" @change="applyFilter()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="all">Semua Status</option>
                        <option value="pending">Menunggu Review</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Urutkan</label>
                    <select x-model="sortBy" @change="applyFilter()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="priority">Prioritas</option>
                    </select>
                </div>

                {{-- Result Count --}}
                <div class="flex items-end">
                    <div class="px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl font-medium text-sm">
                        <span x-text="filteredData.length"></span> dari {{ $pengajuan->count() }} pengajuan
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= TIMELINE ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">

            <template x-if="filteredData.length > 0">
                <div class="relative">
                    {{-- Timeline Line --}}
                    <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                    {{-- Timeline Items --}}
                    <div class="space-y-8">
                        <template x-for="(item, index) in filteredData" :key="item.id">
                            <div class="relative pl-20">

                                {{-- Timeline Dot --}}
                                <div class="absolute left-6 top-6 w-4 h-4 rounded-full border-4 border-white"
                                    :class="{
                                        'bg-yellow-500': item.status === 'pending',
                                        'bg-green-500': item.status === 'disetujui',
                                        'bg-red-500': item.status === 'ditolak'
                                    }">
                                </div>

                                {{-- Card --}}
                                <div class="bg-gray-50 border-2 rounded-xl p-6 hover:shadow-lg transition-all group"
                                    :class="{
                                        'border-yellow-200 hover:border-yellow-300': item.status === 'pending',
                                        'border-green-200 hover:border-green-300': item.status === 'disetujui',
                                        'border-red-200 hover:border-red-300': item.status === 'ditolak'
                                    }">

                                    {{-- Header --}}
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-3 py-1 text-xs font-bold rounded-full"
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-700': item.status === 'pending',
                                                        'bg-green-100 text-green-700': item.status === 'disetujui',
                                                        'bg-red-100 text-red-700': item.status === 'ditolak'
                                                    }"
                                                    x-text="item.status === 'pending' ? 'Menunggu Review' : item.status === 'disetujui' ? 'Disetujui' : 'Ditolak'">
                                                </span>
                                                <span
                                                    class="px-3 py-1 text-xs font-bold bg-indigo-100 text-indigo-700 rounded-full">
                                                    Prioritas <span x-text="item.prioritas"></span>
                                                </span>
                                                <span x-show="item.jenis === 'mandiri'"
                                                    class="px-3 py-1 text-xs font-bold bg-purple-100 text-purple-700 rounded-full">
                                                    Mandiri
                                                </span>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="item.judul"></h3>
                                            <p class="text-sm text-gray-500" x-text="item.waktu"></p>
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="space-y-3">

                                        {{-- Kode (if pilih) --}}
                                        <div x-show="item.kode">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Kode:</span>
                                                <span class="font-mono" x-text="item.kode"></span>
                                            </p>
                                        </div>

                                        {{-- Deskripsi (if mandiri) --}}
                                        <div x-show="item.deskripsi">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Deskripsi:</span>
                                                <span x-text="item.deskripsi"></span>
                                            </p>
                                        </div>

                                        {{-- Lab (if pilih) --}}
                                        <div x-show="item.lab">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold">Laboratorium:</span>
                                                <span x-text="item.lab"></span>
                                            </p>
                                        </div>

                                        {{-- Alasan --}}
                                        <div x-show="item.alasan">
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <p class="text-xs text-blue-600 font-semibold mb-1">Alasan Anda:</p>
                                                <p class="text-sm text-blue-800" x-text="item.alasan"></p>
                                            </div>
                                        </div>

                                        {{-- Catatan Dosen --}}
                                        <div x-show="item.catatan_dosen">
                                            <div class="rounded-lg p-4"
                                                :class="{
                                                    'bg-green-50 border border-green-200': item.status === 'disetujui',
                                                    'bg-red-50 border border-red-200': item.status === 'ditolak'
                                                }">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                                        :class="{
                                                            'bg-green-200': item.status === 'disetujui',
                                                            'bg-red-200': item.status === 'ditolak'
                                                        }">
                                                        <svg class="w-4 h-4"
                                                            :class="{
                                                                'text-green-700': item.status === 'disetujui',
                                                                'text-red-700': item.status === 'ditolak'
                                                            }"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-semibold mb-1"
                                                            :class="{
                                                                'text-green-700': item.status === 'disetujui',
                                                                'text-red-700': item.status === 'ditolak'
                                                            }">
                                                            Catatan Dosen:
                                                        </p>
                                                        <p class="text-sm"
                                                            :class="{
                                                                'text-green-800': item.status === 'disetujui',
                                                                'text-red-800': item.status === 'ditolak'
                                                            }"
                                                            x-text="item.catatan_dosen">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <button @click="openDetail(item)"
                                            class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold flex items-center gap-2 group">
                                            Lihat Detail Lengkap
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </template>
                    </div>

                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="filteredData.length === 0">
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">
                        <span x-show="filterStatus === 'all'">Belum ada pengajuan</span>
                        <span x-show="filterStatus !== 'all'">Tidak ada pengajuan dengan status ini</span>
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                        <span x-show="filterStatus === 'all'">Mulai ajukan judul skripsi Anda</span>
                        <span x-show="filterStatus !== 'all'">Coba ubah filter untuk melihat pengajuan lain</span>
                    </p>
                    <a href="{{ route('mahasiswa.pengajuan') }}" x-show="filterStatus === 'all'"
                        class="inline-block mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                        Ajukan Judul Sekarang
                    </a>
                </div>
            </template>

        </div>



        {{-- ================= DETAIL MODAL ================= --}}
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
                    <h3 class="text-xl font-bold text-gray-800">Detail Pengajuan</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-6">

                    {{-- Status Badge --}}
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 text-sm font-bold rounded-full"
                            :class="{
                                'bg-yellow-100 text-yellow-700': selectedItem.status === 'pending',
                                'bg-green-100 text-green-700': selectedItem.status === 'disetujui',
                                'bg-red-100 text-red-700': selectedItem.status === 'ditolak'
                            }"
                            x-text="selectedItem.status === 'pending' ? 'Menunggu Review' : selectedItem.status === 'disetujui' ? 'Disetujui' : 'Ditolak'">
                        </span>
                        <span class="px-4 py-2 text-sm font-bold bg-indigo-100 text-indigo-700 rounded-full">
                            Prioritas <span x-text="selectedItem.prioritas"></span>
                        </span>
                    </div>

                    {{-- Judul --}}
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-2" x-text="selectedItem.judul"></h4>
                        <p class="text-sm text-gray-500" x-text="selectedItem.waktu"></p>
                    </div>

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Jenis Pengajuan</p>
                            <p class="font-semibold text-gray-800"
                                x-text="selectedItem.jenis === 'mandiri' ? 'Judul Mandiri' : 'Pilih Judul Dosen'"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Tanggal Pengajuan</p>
                            <p class="font-semibold text-gray-800" x-text="selectedItem.tanggal"></p>
                        </div>
                    </div>

                    {{-- Full Details --}}
                    <div class="space-y-4">
                        <div x-show="selectedItem.kode">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Kode Judul</p>
                            <p class="text-gray-800 font-mono" x-text="selectedItem.kode"></p>
                        </div>

                        <div x-show="selectedItem.deskripsi">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Deskripsi</p>
                            <p class="text-gray-800" x-text="selectedItem.deskripsi"></p>
                        </div>

                        <div x-show="selectedItem.lab">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Laboratorium</p>
                            <p class="text-gray-800" x-text="selectedItem.lab"></p>
                        </div>

                        <div x-show="selectedItem.alasan">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Alasan Anda</p>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-gray-800" x-text="selectedItem.alasan"></p>
                            </div>
                        </div>

                        <div x-show="selectedItem.catatan_dosen">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Catatan Dosen</p>
                            <div class="rounded-lg p-4"
                                :class="{
                                    'bg-green-50 border border-green-200': selectedItem.status === 'disetujui',
                                    'bg-red-50 border border-red-200': selectedItem.status === 'ditolak'
                                }">
                                <p class="text-gray-800" x-text="selectedItem.catatan_dosen"></p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- ================= ALPINE SCRIPT ================= --}}
    <script>
        function riwayatPage() {
            return {
                filterStatus: 'all',
                sortBy: 'newest',
                showModal: false,
                selectedItem: {},
                allData: [],
                filteredData: [],

                init() {
                    this.allData = [
                        @foreach ($pengajuan as $p)
                            {
                                id: {{ $p->id }},
                                judul: '{{ $p->jenis === 'pilih' ? addslashes($p->judul->nama_judul ?? '-') : addslashes($p->judul_mandiri) }}',
                                jenis: '{{ $p->jenis }}',
                                status: '{{ $p->status }}',
                                prioritas: {{ $p->prioritas }},
                                kode: '{{ $p->jenis === 'pilih' ? $p->judul->kode ?? '' : '' }}',
                                deskripsi: '{{ $p->jenis === 'mandiri' ? addslashes($p->deskripsi_mandiri) : '' }}',
                                lab: '{{ $p->jenis === 'pilih' && $p->judul ? $p->judul->laboratorium->nama ?? '' : '' }}',
                                alasan: '{{ addslashes($p->alasan ?? '') }}',
                                catatan_dosen: '{{ addslashes($p->catatan_dosen ?? '') }}',
                                waktu: '{{ $p->created_at->diffForHumans() }}',
                                tanggal: '{{ $p->created_at->format('d M Y H:i') }}',
                                timestamp: {{ $p->created_at->timestamp }}
                            }
                            {{ $loop->last ? '' : ',' }}
                        @endforeach
                    ];

                    this.applyFilter();
                },

                applyFilter() {
                    let result = this.allData;

                    // Filter by status
                    if (this.filterStatus !== 'all') {
                        result = result.filter(item => item.status === this.filterStatus);
                    }

                    // Sort
                    if (this.sortBy === 'newest') {
                        result.sort((a, b) => b.timestamp - a.timestamp);
                    } else if (this.sortBy === 'oldest') {
                        result.sort((a, b) => a.timestamp - b.timestamp);
                    } else if (this.sortBy === 'priority') {
                        result.sort((a, b) => a.prioritas - b.prioritas);
                    }

                    this.filteredData = result;
                },

                openDetail(item) {
                    this.selectedItem = item;
                    this.showModal = true;
                }
            }
        }
    </script>

</x-layout>
