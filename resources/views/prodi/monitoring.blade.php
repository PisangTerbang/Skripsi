<x-layout-prodi>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="monitoringPage()" x-init="init()" class="space-y-6">

        {{-- Alerts --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false"><x-heroicon-o-x-mark class="w-5 h-5" /></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-gradient-to-br from-violet-500 to-purple-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Monitoring Sistem</h2>
                    <p class="text-violet-100 text-sm">Pantau seluruh proses pengajuan Tugas Akhir</p>
                </div>
                <div class="hidden md:flex gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $menungguDosen }}</p>
                        <p class="text-xs text-violet-200">Menunggu Ka Lab</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $totalPengajuan }}</p>
                        <p class="text-xs text-violet-200">Total Pengajuan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Disetujui Ka Lab</p>
                        <p class="text-2xl font-bold text-green-600">{{ $disetujuiDosen }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ditolak Ka Lab</p>
                        <p class="text-2xl font-bold text-red-600">{{ $ditolakDosen }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-xl">
                        <x-heroicon-o-x-circle class="w-6 h-6 text-red-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ditetapkan</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $ditetapkan }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <x-heroicon-o-document-check class="w-6 h-6 text-purple-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Belum Mengajukan</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $mahasiswaBelumMengajukan }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <x-heroicon-o-user-group class="w-6 h-6 text-orange-600" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Judul --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Total Judul</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalJudul }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Judul Tersedia</p>
                <p class="text-2xl font-bold text-blue-600">{{ $judulTersedia }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Draft</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $judulDraft }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4">
                <p class="text-sm text-gray-500 mb-1">Nonaktif</p>
                <p class="text-2xl font-bold text-gray-600">{{ $judulNonaktif }}</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <x-heroicon-o-magnifying-glass
                        class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input type="text" x-model="search" @input="filterData()" placeholder="Cari mahasiswa, judul..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
                <select x-model="filterStatus" @change="filterData()"
                    class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-violet-500">
                    <option value="all">Semua Status</option>
                    <option value="pending">Menunggu Ka Lab</option>
                    <option value="disetujui">Disetujui Ka Lab</option>
                    <option value="ditolak">Ditolak Ka Lab</option>
                    <option value="ditetapkan">Ditetapkan</option>
                </select>
                <div class="flex items-center px-4 py-2.5 bg-violet-50 text-violet-700 rounded-xl text-sm font-medium">
                    <span x-text="filteredData.length"></span>&nbsp;pengajuan
                </div>
            </div>
        </div>

        {{-- Tabel Pengajuan Terbaru --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Pengajuan Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Mahasiswa</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Pilihan</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in paginatedData" :key="item.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-500" x-text="(currentPage - 1) * perPage + index + 1">
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800" x-text="item.mahasiswa"></p>
                                    <p class="text-xs text-gray-500" x-text="item.nim"></p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-gray-800 truncate max-w-[300px]" x-text="item.judul1"></p>
                                    <p class="text-xs text-gray-500" x-text="item.dosen1"></p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                        :class="item.status_class" x-text="item.status_display"></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button @click="openModal(item)"
                                        class="p-2 text-violet-600 hover:bg-violet-50 rounded-lg transition-colors">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="filteredData.length === 0" class="p-12 text-center">
                <x-heroicon-o-document-magnifying-glass class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <p class="text-gray-500 font-medium">Tidak ada pengajuan yang ditemukan</p>
            </div>

            {{-- Pagination --}}
            <div x-show="totalPages > 1" class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                <p class="text-sm text-gray-500">Halaman <span x-text="currentPage"></span> dari <span
                        x-text="totalPages"></span></p>
                <div class="flex gap-1">
                    <button @click="currentPage--; paginate()" :disabled="currentPage === 1"
                        class="px-3 py-1 rounded-lg text-sm border border-gray-300 disabled:opacity-50 hover:bg-gray-50">Prev</button>
                    <template x-for="page in totalPages" :key="page">
                        <button @click="currentPage = page; paginate()"
                            :class="currentPage === page ? 'bg-violet-600 text-white' :
                                'border border-gray-300 hover:bg-gray-50'"
                            class="px-3 py-1 rounded-lg text-sm" x-text="page"></button>
                    </template>
                    <button @click="currentPage++; paginate()" :disabled="currentPage === totalPages"
                        class="px-3 py-1 rounded-lg text-sm border border-gray-300 disabled:opacity-50 hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>

        {{-- Judul Populer --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Judul dengan Peminat Terbanyak</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach ($judulPopuler as $judul)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $judul->nama_judul }}</p>
                                <p class="text-sm text-gray-600">{{ $judul->dosen->name ?? '-' }} •
                                    {{ $judul->laboratorium->nama ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-violet-600">{{ $judul->total_peminat }}</p>
                                    <p class="text-xs text-gray-500">Peminat</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600">
                                        {{ $judul->pengajuan_ditetapkan_count }}</p>
                                    <p class="text-xs text-gray-500">Ditetapkan</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Statistik Per Lab --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Statistik Per Laboratorium</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Laboratorium</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Total Judul</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Tersedia</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Draft</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Total Peminat</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Ditetapkan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($statsPerLab as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $stat['nama'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-700">{{ $stat['total_judul'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-blue-600 font-semibold">{{ $stat['tersedia'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">{{ $stat['draft'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-violet-600 font-semibold">{{ $stat['total_peminat'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-green-600 font-semibold">{{ $stat['ditetapkan'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Statistik Per Dosen --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Top 10 Dosen (Berdasarkan Jumlah Judul)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Dosen</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Total Judul</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Tersedia</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Total Peminat</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Ditetapkan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($statsPerDosen as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">{{ $stat['nama'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $stat['nip'] }}</p>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">{{ $stat['total_judul'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-blue-600 font-semibold">{{ $stat['tersedia'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-violet-600 font-semibold">{{ $stat['total_peminat'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-green-600 font-semibold">{{ $stat['ditetapkan'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">

                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Detail Pengajuan</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Mahasiswa</p>
                            <p class="font-semibold text-gray-800" x-text="selectedItem.mahasiswa"></p>
                            <p class="text-xs text-gray-600" x-text="selectedItem.nim"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold inline-block"
                                :class="selectedItem.status_class" x-text="selectedItem.status_display"></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-xs text-blue-600 font-semibold mb-2">Pilihan 1</p>
                            <p class="font-bold text-gray-800 mb-1" x-text="selectedItem.judul1"></p>
                            <p class="text-sm text-gray-600" x-text="selectedItem.dosen1"></p>
                        </div>

                        <div x-show="selectedItem.judul2" class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-600 font-semibold mb-2">Pilihan 2</p>
                            <p class="font-bold text-gray-800 mb-1" x-text="selectedItem.judul2"></p>
                            <p class="text-sm text-gray-600" x-text="selectedItem.dosen2"></p>
                        </div>

                        <div x-show="selectedItem.judul3" class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-600 font-semibold mb-2">Pilihan 3</p>
                            <p class="font-bold text-gray-800 mb-1" x-text="selectedItem.judul3"></p>
                            <p class="text-sm text-gray-600" x-text="selectedItem.dosen3"></p>
                        </div>
                    </div>

                    <div x-show="selectedItem.judul_ditetapkan"
                        class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <p class="text-xs text-green-600 font-semibold mb-2">Judul Ditetapkan</p>
                        <p class="font-bold text-gray-800 mb-1" x-text="selectedItem.judul_ditetapkan"></p>
                        <p class="text-sm text-gray-600" x-text="selectedItem.dosen_ditetapkan"></p>
                    </div>

                    {{-- Info View-Only --}}
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="bg-violet-50 border border-violet-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <x-heroicon-o-information-circle
                                    class="w-5 h-5 text-violet-600 flex-shrink-0 mt-0.5" />
                                <div>
                                    <p class="font-semibold text-violet-800 text-sm">Mode Monitoring</p>
                                    <p class="text-xs text-violet-700 mt-1">
                                        Halaman ini hanya untuk monitoring. Approval dilakukan oleh Kepala Lab.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function monitoringPage() {
            return {
                allData: [],
                filteredData: [],
                paginatedData: [],
                search: '',
                filterStatus: 'all',
                showModal: false,
                selectedItem: {},
                currentPage: 1,
                perPage: 10,
                totalPages: 1,

                init() {
                    this.allData = [
                        @foreach ($pengajuanTerbaru as $p)
                            {
                                id: {{ $p->id }},
                                mahasiswa: '{{ addslashes($p->mahasiswa->name ?? '-') }}',
                                nim: '{{ $p->mahasiswa->nim ?? '-' }}',
                                judul1: '{{ addslashes($p->pilihan1->nama_judul ?? '-') }}',
                                dosen1: '{{ addslashes($p->pilihan1->dosen->name ?? '-') }}',
                                judul2: '{{ addslashes($p->pilihan2->nama_judul ?? '') }}',
                                dosen2: '{{ addslashes($p->pilihan2->dosen->name ?? '') }}',
                                judul3: '{{ addslashes($p->pilihan3->nama_judul ?? '') }}',
                                dosen3: '{{ addslashes($p->pilihan3->dosen->name ?? '') }}',
                                judul_ditetapkan: '{{ addslashes($p->judulDitetapkan->nama_judul ?? '') }}',
                                dosen_ditetapkan: '{{ addslashes($p->judulDitetapkan->dosen->name ?? '') }}',
                                status_display: '{{ $p->status_display }}',
                                status_class: 'bg-{{ $p->status_class }}-100 text-{{ $p->status_class }}-700',
                                status_raw: '{{ $p->status_kalab ?? 'pending' }}',
                                is_ditetapkan: {{ $p->status === 'ditetapkan' ? 'true' : 'false' }}
                            }
                            {{ $loop->last ? '' : ',' }}
                        @endforeach
                    ];
                    this.filterData();
                },

                filterData() {
                    var self = this;
                    var result = this.allData;

                    if (this.search) {
                        var q = this.search.toLowerCase();
                        result = result.filter(function(item) {
                            return item.mahasiswa.toLowerCase().includes(q) ||
                                item.nim.toLowerCase().includes(q) ||
                                item.judul1.toLowerCase().includes(q);
                        });
                    }

                    if (this.filterStatus === 'pending') {
                        result = result.filter(function(item) {
                            return item.status_raw === 'pending' || !item.status_raw;
                        });
                    } else if (this.filterStatus === 'disetujui') {
                        result = result.filter(function(item) {
                            return item.status_raw === 'disetujui';
                        });
                    } else if (this.filterStatus === 'ditolak') {
                        result = result.filter(function(item) {
                            return item.status_raw === 'ditolak';
                        });
                    } else if (this.filterStatus === 'ditetapkan') {
                        result = result.filter(function(item) {
                            return item.is_ditetapkan;
                        });
                    }

                    this.filteredData = result;
                    this.currentPage = 1;
                    this.paginate();
                },

                paginate() {
                    this.totalPages = Math.ceil(this.filteredData.length / this.perPage) || 1;
                    var start = (this.currentPage - 1) * this.perPage;
                    this.paginatedData = this.filteredData.slice(start, start + this.perPage);
                },

                openModal(item) {
                    this.selectedItem = item;
                    this.showModal = true;
                }
            }
        }
    </script>

</x-layout-prodi>
