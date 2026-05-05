<x-layout-kalab>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="validasiPage()" x-init="init()" class="space-y-6">

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

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-red-50 border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-exclamation-circle class="w-5 h-5" />
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false"><x-heroicon-o-x-mark class="w-5 h-5" /></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Validasi Judul</h2>
                    <p class="text-amber-100 text-sm">Validasi pengelompokan judul dari Koordinator Lab</p>
                </div>
                <div class="hidden md:flex gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $judulPending->count() }}</p>
                        <p class="text-xs text-amber-200">Pending</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $judulSelesai->count() }}</p>
                        <p class="text-xs text-amber-200">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-4">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <x-heroicon-o-magnifying-glass
                        class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input type="text" x-model="search" @input="filterData()"
                        placeholder="Cari judul, dosen, kode..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>
                <select x-model="filterLab" @change="filterData()"
                    class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="all">Semua Lab</option>
                    @foreach ($laboratorium as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                    @endforeach
                </select>
                <select x-model="filterStatus" @change="filterData()"
                    class="px-4 py-2.5 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    <option value="pending">Menunggu Validasi</option>
                    <option value="selesai">Sudah Divalidasi</option>
                    <option value="all">Semua</option>
                </select>
                <div class="flex items-center px-4 py-2.5 bg-amber-50 text-amber-700 rounded-xl text-sm font-medium">
                    <span x-text="filteredData.length"></span>&nbsp;judul
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Dosen</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Lab</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Catatan Koor</th>
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
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded"
                                        x-text="item.kode"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800 truncate max-w-[200px]"
                                        x-text="item.nama_judul"></p>
                                </td>
                                <td class="px-4 py-3 text-gray-600 truncate max-w-[120px]" x-text="item.dosen"></td>
                                <td class="px-4 py-3">
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold"
                                        x-text="item.lab"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span x-show="item.catatan_koor" class="text-xs text-orange-600 italic"
                                        x-text="item.catatan_koor"></span>
                                    <span x-show="!item.catatan_koor" class="text-xs text-gray-400">-</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                        :class="{
                                            'bg-yellow-100 text-yellow-700': item.status === 'pending_kalab',
                                            'bg-green-100 text-green-700': item.status === 'ditawarkan',
                                            'bg-red-100 text-red-700': item.status === 'ditolak_kalab'
                                        }"
                                        x-text="item.status_label"></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button @click="openModal(item)"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
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
                <p class="text-gray-500 font-medium">Tidak ada judul yang ditemukan</p>
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
                            :class="currentPage === page ? 'bg-amber-600 text-white' : 'border border-gray-300 hover:bg-gray-50'"
                            class="px-3 py-1 rounded-lg text-sm" x-text="page"></button>
                    </template>
                    <button @click="currentPage++; paginate()" :disabled="currentPage === totalPages"
                        class="px-3 py-1 rounded-lg text-sm border border-gray-300 disabled:opacity-50 hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Detail & Validasi Judul</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Kode</p>
                            <p class="font-mono font-semibold text-gray-800" x-text="selectedItem.kode"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Lab (dari Koor)</p>
                            <p class="font-semibold text-gray-800" x-text="selectedItem.lab"></p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-1">Judul</p>
                        <p class="font-bold text-gray-800" x-text="selectedItem.nama_judul"></p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                        <p class="text-sm text-gray-700" x-text="selectedItem.deskripsi"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Dosen</p>
                            <p class="text-sm font-medium text-gray-800" x-text="selectedItem.dosen"></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Skills</p>
                            <p class="text-sm text-gray-800" x-text="selectedItem.skills || '-'"></p>
                        </div>
                    </div>

                    <div x-show="selectedItem.catatan_koor" class="bg-blue-50 border-blue-200 rounded-xl p-4">
                        <p class="text-xs text-blue-600 font-semibold mb-1">Catatan Koordinator Lab:</p>
                        <p class="text-sm text-blue-800" x-text="selectedItem.catatan_koor"></p>
                    </div>

                    {{-- Actions (hanya untuk pending) --}}
                    <div x-show="selectedItem.status === 'pending_kalab'"
                        class="border-t border-gray-200 pt-4 mt-4 space-y-4">
                        <h4 class="font-semibold text-gray-800">Keputusan Validasi</h4>

                        {{-- Approve --}}
                        <form method="POST" :action="'/kepala-lab/validasi/' + selectedItem.id + '/approve'"
                            class="space-y-3">
                            @csrf
                            @method('PUT')
                            <textarea name="catatan_kalab" rows="2" placeholder="Catatan validasi (opsional)..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500"></textarea>
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all shadow-sm">
                                Validasi & Tawarkan ke Mahasiswa
                            </button>
                        </form>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center"><span
                                    class="bg-white px-3 text-xs text-gray-500">atau</span></div>
                        </div>

                        {{-- Reject --}}
                        <form method="POST" :action="'/kepala-lab/validasi/' + selectedItem.id + '/reject'"
                            class="space-y-3">
                            @csrf
                            @method('PUT')
                            <textarea name="catatan_kalab" required rows="2" placeholder="Alasan penolakan (wajib)..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500"></textarea>
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all shadow-sm">
                                Tolak & Kembalikan ke Koor Lab
                            </button>
                        </form>
                    </div>

                    {{-- Info jika sudah divalidasi --}}
                    <div x-show="selectedItem.status !== 'pending_kalab'" class="border-t border-gray-200 pt-4 mt-4">
                        <div class="rounded-xl p-4"
                            :class="selectedItem.status === 'ditawarkan' ? 'bg-green-50 border border-green-200' :
                                'bg-red-50 border border-red-200'">
                            <p class="text-sm font-medium"
                                :class="selectedItem.status === 'ditawarkan' ? 'text-green-700' : 'text-red-700'"
                                x-text="selectedItem.status === 'ditawarkan' ? 'Judul sudah divalidasi dan ditawarkan.' : 'Judul ditolak.'">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function validasiPage() {
                return {
                    allData: [],
                    filteredData: [],
                    paginatedData: [],
                    search: '',
                    filterLab: 'all',
                    filterStatus: 'pending',
                    showModal: false,
                    selectedItem: {},
                    currentPage: 1,
                    perPage: 10,
                    totalPages: 1,

                    init() {
                        this.allData = [
                            @foreach ($judulPending as $j)
                                {
                                    id: {{ $j->id }},
                                    kode: '{{ $j->kode }}',
                                    nama_judul: '{{ addslashes($j->nama_judul) }}',
                                    deskripsi: '{{ addslashes($j->deskripsi) }}',
                                    dosen: '{{ addslashes($j->dosen->name ?? '-') }}',
                                    lab: '{{ $j->laboratorium->nama ?? '-' }}',
                                    lab_id: {{ $j->laboratorium_id }},
                                    skills: '{{ addslashes($j->relevant_skills ?? '') }}',
                                    catatan_koor: '{{ addslashes($j->catatan_koor ?? '') }}',
                                    status: 'pending_kalab',
                                    status_label: 'Menunggu Validasi'
                                }
                                {{ $loop->last ? '' : ',' }}
                            @endforeach
                            @if ($judulPending->count() > 0 && $judulSelesai->count() > 0)
                                ,
                            @endif
                            @foreach ($judulSelesai as $j)
                                {
                                    id: {{ $j->id }},
                                    kode: '{{ $j->kode }}',
                                    nama_judul: '{{ addslashes($j->nama_judul) }}',
                                    deskripsi: '{{ addslashes($j->deskripsi) }}',
                                    dosen: '{{ addslashes($j->dosen->name ?? '-') }}',
                                    lab: '{{ $j->laboratorium->nama ?? '-' }}',
                                    lab_id: {{ $j->laboratorium_id }},
                                    skills: '{{ addslashes($j->relevant_skills ?? '') }}',
                                    catatan_koor: '{{ addslashes($j->catatan_koor ?? '') }}',
                                    status: '{{ $j->status_judul }}',
                                    status_label: '{{ $j->status_judul === 'ditawarkan' ? 'Divalidasi' : 'Ditolak' }}'
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
                                return item.nama_judul.toLowerCase().includes(q) ||
                                    item.dosen.toLowerCase().includes(q) ||
                                    item.kode.toLowerCase().includes(q);
                            });
                        }

                        if (this.filterLab !== 'all') {
                            result = result.filter(function(item) {
                                return item.lab_id == self.filterLab;
                            });
                        }

                        if (this.filterStatus === 'pending') {
                            result = result.filter(function(item) {
                                return item.status === 'pending_kalab';
                            });
                        } else if (this.filterStatus === 'selesai') {
                            result = result.filter(function(item) {
                                return item.status !== 'pending_kalab';
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

</x-layout-kalab>
