<x-layout-kaprodi>
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
                    <h2 class="text-2xl font-bold mb-1">Monitoring & Final Approval</h2>
                    <p class="text-violet-100 text-sm">Pantau seluruh proses dan berikan persetujuan final</p>
                </div>
                <div class="hidden md:flex gap-3">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $pendingFinal->count() }}</p>
                        <p class="text-xs text-violet-200">Pending Final</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $semuaPengajuan->count() }}</p>
                <p class="text-xs text-violet-200">Total</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                <input type="text" x-model="search" @input="filterData()"
                placeholder="Cari mahasiswa, judul..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
                <select x-model="filterStatus" @change="filterData()"
                    class="px-4 py-2.5 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-violet-500">
                    <option value="pending">Menunggu Approval</option>
                    <option value="all">Semua Pengajuan</option>
                    <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                </select>
                <div class="flex items-center px-4 py-2.5 bg-violet-50 text-violet-700 rounded-xl text-sm font-medium">
                    <span x-text="filteredData.length"></span>&nbsp;pengajuan
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Mahasiswa</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Lab</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status Dosen</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status Final</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in paginatedData" :key="item.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-500" x-text="(currentPage - 1) * perPage + index + 1"></td>
                                <td class="px-4 py-3 font-medium text-gray-800" x-text="item.mahasiswa"></td>
                                <td class="px-4 py-3">
                                    <p class="text-gray-800 truncate max-w-[200px]" x-text="item.judul"></p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold" x-text="item.lab"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                        :class="{
                                            'bg-green-100 text-green-700': item.status_dosen === 'disetujui',
                                            'bg-yellow-100 text-yellow-700': item.status_dosen === 'pending',
                                            'bg-red-100 text-red-700': item.status_dosen === 'ditolak'
                                        }"
                                x-text="item.status_dosen_label"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                        :class="{
                                            'bg-purple-100 text-purple-700': item.status_kaprodi === 'disetujui',
                                            'bg-yellow-100 text-yellow-700': item.status_kaprodi === 'pending',
                                'bg-red-100 text-red-700': item.status_kaprodi === 'ditolak',
                                            'bg-gray-100 text-gray-500': !item.status_kaprodi
                                        }"
                                x-text="item.status_kaprodi_label"></span>
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
                <p class="text-sm text-gray-500">Halaman <span x-text="currentPage"></span> dari <span x-text="totalPages"></span></p>
                <div class="flex gap-1">
                    <button @click="currentPage--; paginate()" :disabled="currentPage === 1"
                        class="px-3 py-1 rounded-lg text-sm border border-gray-300 disabled:opacity-50 hover:bg-gray-50">Prev</button>
                    <template x-for="page in totalPages" :key="page">
                        <button @click="currentPage = page; paginate()"
                :class="currentPage === page ? 'bg-violet-600 text-white' : 'border border-gray-300 hover:bg-gray-50'"
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
            <div @click.away="showModal = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
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
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1">Lab</p>
                            <p class="font-semibold text-gray-800" x-text="selectedItem.lab"></p>
                        </div>
                </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-1">Judul</p>
                        <p class="font-bold text-gray-800" x-text="selectedItem.judul"></p>
                </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-1">Dosen</p>
                        <p class="text-sm text-gray-800" x-text="selectedItem.dosen"></p>
                    </div>

                    <div x-show="selectedItem.catatan_dosen" class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-xs text-blue-600 font-semibold mb-1">Catatan Dosen:</p>
                        <p class="text-sm text-blue-800" x-text="selectedItem.catatan_dosen"></p>
                    </div>

                    {{-- Actions (hanya untuk pending) --}}
                <div x-show="selectedItem.status_kaprodi === 'pending'" class="border-t border-gray-200 pt-4 mt-4 space-y-4">
                        <h4 class="font-semibold text-gray-800">Keputusan Final</h4>

                        <form method="POST" :action="'/kaprodi/monitoring/' + selectedItem.id + '/approve'" class="space-y-3">
                            @csrf
                @method('PUT')
                <textarea name="catatan_kaprodi" rows="2" placeholder="Catatan (opsional)..."
                                class="w-full px-4 py-2.5 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-500"></textarea>
                            <button type="submit" class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all shadow-sm">
                                Setujui - Mahasiswa Boleh Mulai Pengerjaan
                            </button>
                        </form>

                        <div class="relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div>
                            <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-gray-500">atau</span></div>
                        </div>

                        <form method="POST" :action="'/kaprodi/monitoring/' + selectedItem.id + '/reject'" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <textarea name="catatan_kaprodi" required rows="2" placeholder="Alasan penolakan (wajib)..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500"></textarea>
                            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all shadow-sm">
                                Tolak & Kembalikan
                            </button>
                        </form>
                    </div>

                    <div x-show="selectedItem.status_kaprodi && selectedItem.status_kaprodi !== 'pending'" class="border-t border-gray-200 pt-4 mt-4">
                        <div class="rounded-xl p-4"
                            :class="selectedItem.status_kaprodi === 'disetujui' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                            <p class="text-sm font-medium"
                                :class="selectedItem.status_kaprodi === 'disetujui' ? 'text-green-700' : 'text-red-700'"
                                x-text="selectedItem.status_kaprodi === 'disetujui' ? 'Pengajuan sudah disetujui. Mahasiswa dapat memulai pengerjaan.' : 'Pengajuan ditolak.'"></p>
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
                filterStatus: 'pending',
                showModal: false,
                selectedItem: {},
                currentPage: 1,
                perPage: 10,
                totalPages: 1,

                init() {
                    this.allData = [
                        @foreach($pendingFinal as $p)
                        {
                            id: {{ $p->id }},
                            mahasiswa: '{{ addslashes($p->mahasiswa->name ?? "-") }}',
                            judul: '{{ addslashes($p->judul->nama_judul ?? $p->judul_mandiri ?? "-") }}',
                            lab: '{{ $p->judul->laboratorium->nama ?? "-" }}',
                            dosen: '{{ addslashes($p->judul->dosen->name ?? "-") }}',
                            catatan_dosen: '{{ addslashes($p->catatan_dosen ?? "") }}',
                status_dosen: '{{ $p->status }}',
                            status_dosen_label: '{{ ucfirst($p->status) }}',
                            status_kaprodi: 'pending',
                            status_kaprodi_label: 'Menunggu'
                        }{{ $loop->last ? '' : ',' }}
                        @endforeach
                @if($pendingFinal->count() > 0 && $semuaPengajuan->count() > 0),@endif
                        @foreach($semuaPengajuan->where('status_kaprodi', '!=', 'pending') as $p)
                        {
                id: {{ $p->id }},
                            mahasiswa: '{{ addslashes($p->mahasiswa->name ?? "-") }}',
                            judul: '{{ addslashes($p->judul->nama_judul ?? $p->judul_mandiri ?? "-") }}',
                lab: '{{ $p->judul->laboratorium->nama ?? "-" }}',
                            dosen: '{{ addslashes($p->judul->dosen->name ?? "-") }}',
                catatan_dosen: '{{ addslashes($p->catatan_dosen ?? "") }}',
                            status_dosen: '{{ $p->status }}',
                            status_dosen_label: '{{ ucfirst($p->status) }}',
                status_kaprodi: '{{ $p->status_kaprodi ?? "" }}',
                            status_kaprodi_label: '{{ $p->status_kaprodi ? ucfirst($p->status_kaprodi) : "-" }}'
                        }{{ $loop->last ? '' : ',' }}
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
                                item.judul.toLowerCase().includes(q);
                        });
                    }

                    if (this.filterStatus === 'pending') {
                        result = result.filter(function(item) { return item.status_kaprodi === 'pending'; });
                    } else if (this.filterStatus === 'disetujui') {
                        result = result.filter(function(item) { return item.status_kaprodi === 'disetujui'; });
                    } else if (this.filterStatus === 'ditolak') {
                        result = result.filter(function(item) { return item.status_kaprodi === 'ditolak'; });
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

</x-layout-kaprodi>
