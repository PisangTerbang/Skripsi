<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengajuanDosenPage()" x-init="init()" class="space-y-6">

        {{-- =============== ALERTS ================= --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 0 11-18 0 9 9 0 0118 0z" />
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
                class="bg-red-50 border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 0 0118 0z" />
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
                    <h2 class="text-2xl font-bold mb-2">Review Pengajuan Mahasiswa</h2>
                    <p class="text-emerald-100 text-sm">Tinjau dan berikan keputusan untuk pengajuan judul skripsi</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold">{{ $pending }}</p>
                        <p class="text-xs text-emerald-100 mt-1">Perlu Review</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= STATS CARDS ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-6">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-document-text class="w-6 h-6 text-indigo-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Pengajuan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-clock class="w-6 h-6 text-yellow-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Perlu Review</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pending }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Disetujui</p>
                <p class="text-3xl font-bold text-gray-800">{{ $disetujui }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-6">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Ditolak</p>
                <p class="text-3xl font-bold text-gray-800">{{ $ditolak }}</p>
            </div>

        </div>



        {{-- ================= FILTER & SEARCH ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" x-model="searchQuery" @input="applyFilter()"
                        placeholder="Cari mahasiswa atau judul..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <x-heroicon-o-magnifying-glass
                        class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />

                </div>
                <select x-model="filterStatus" @change="applyFilter()"
                    class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="all">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
                <select x-model="filterJenis" @change="applyFilter()"
                    class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="all">Semua Jenis</option>
                    <option value="pilih">Pilih Judul</option>
                    <option value="mandiri">Judul Mandiri</option>
                </select>
                <div class="flex items-center px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl font-medium text-sm">
                    <span x-text="filteredData.length"></span>&nbsp;hasil
                </div>
            </div>
        </div>

        {{-- ================= PENGAJUAN LIST ================= --}}
        <div class="space-y-6">
            <template x-for="group in filteredData" :key="group.judul_id">
                <div class="bg-white rounded-2xl shadow-lg border-gray-100 overflow-hidden">

                    {{-- Group Header --}}
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span x-show="group.jenis === 'mandiri'"
                                        class="px-3 py-1 text-xs font-bold bg-purple-100 text-purple-700 rounded-full">Judul
                                        Mandiri</span>
                                    <span x-show="group.jenis === 'pilih'"
                                        class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded-full">Pilih
                                        Judul</span>
                                    <span x-show="!group.is_owner"
                                        class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-full">Judul
                                        Dosen Lain</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="group.judul"></h3>
                                <p x-show="group.kode" class="text-sm text-gray-500">Kode: <span class="font-mono"
                                        x-text="group.kode"></span></p>
                                <p x-show="group.deskripsi" class="text-sm text-gray-600 mt-2"
                                    x-text="group.deskripsi"></p>
                            </div>
                            <div x-show="group.pemenang" class="ml-4">
                                <div class="bg-green-100 border-green-200 rounded-xl px-4 py-2">
                                    <p class="text-xs text-green-600 font-semibold mb-1">Sudah Diambil</p>
                                    <p class="text-sm text-green-800 font-medium" x-text="group.pemenang"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submissions List --}}
                    <div class="p-6 space-y-4">
                        <template x-for="item in group.items" :key="item.id">
                            <div class="border-2 rounded-xl p-5 transition-all"
                                :class="{
                                    'border-yellow-200 bg-yellow-50/50': item.status === 'pending',
                                    'border-green-200 bg-green-50/50': item.status === 'disetujui',
                                    'border-red-200 bg-red-50/50': item.status === 'ditolak'
                                }">

                                {{-- Item Header --}}
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h4 class="font-bold text-gray-800" x-text="item.mahasiswa"></h4>
                                            <span class="px-3 py-1 text-xs font-bold rounded-full"
                                                :class="{
                                                    'bg-yellow-200 text-yellow-800': item.status === 'pending',
                                                    'bg-green-200 text-green-800': item.status === 'disetujui',
                                                    'bg-red-200 text-red-800': item.status === 'ditolak'
                                                }"
                                                x-text="item.status === 'pending' ? 'Pending' : item.status === 'disetujui' ? 'Disetujui' : 'Ditolak'">
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Prioritas <span class="font-semibold" x-text="item.prioritas"></span>
                                            <span x-show="item.prioritas === 1"
                                                class="text-blue-600 font-semibold ml-1">(Utama)</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="item.waktu"></p>
                                    </div>
                                </div>

                                {{-- Warning: Mahasiswa sudah punya judul --}}
                                <div x-show="item.sudah_punya_judul && item.status === 'pending'"
                                    class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <p class="text-sm text-orange-700">Mahasiswa sudah memiliki judul yang disetujui
                                    </p>
                                </div>

                                {{-- Alasan Mahasiswa --}}
                                <div x-show="item.alasan" class="mb-4">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <p class="text-xs text-blue-600 font-semibold mb-2">Alasan Mahasiswa:</p>
                                        <p class="text-sm text-blue-900" x-text="item.alasan"></p>
                                    </div>
                                </div>

                                {{-- Catatann Dosen --}}
                                <div x-show="item.catatan_dosen" class="mb-4">
                                    <div class="rounded-lg p-4"
                                        :class="{
                                            'bg-green-50 border border-green-200': item.status === 'disetujui',
                                            'bg-red-50 border border-red-200': item.status === 'ditolak'
                                        }">
                                        <p class="text-xs font-semibold mb-2"
                                            :class="{
                                                'text-green-700': item.status === 'disetujui',
                                                'text-red-700': item.status === 'ditolak'
                                            }">
                                            Catatan Dosen:</p>
                                        <p class="text-sm"
                                            :class="{
                                                'text-green-800': item.status === 'disetujui',
                                                'text-red-800': item.status === 'ditolak'
                                            }"
                                            x-text="item.catatan_dosen"></p>
                                    </div>
                                </div>

                                {{-- Status Kaprodi (jika sudah di-approve dosen) --}}
                                <div x-show="item.status === 'disetujui'" class="mb-4">
                                    <div class="flex items-center gap-2 p-3 rounded-lg"
                                        :class="{
                                            'bg-purple-50 border border-purple-200': item
                                                .status_kaprodi === 'pending',
                                            'bg-green-50 border border-green-200': item.status_kaprodi === 'disetujui',
                                            'bg-red-50 border border-red-200': item.status_kaprodi === 'ditolak'
                                        }">
                                        <x-heroicon-o-academic-cap class="w-5 h-5 text-purple-600" />
                                        <div>
                                            <p class="text-xs font-semibold"
                                                :class="{
                                                    'text-purple-700': item.status_kaprodi === 'pending',
                                                    'text-green-700': item.status_kaprodi === 'disetujui',
                                                    'text-red-700': item.status_kaprodi === 'ditolak'
                                                }">
                                                Status Kaprodi:
                                                <span
                                                    x-text="item.status_kaprodi === 'pending' ? 'Menunggu Persetujuan Final' : (item.status_kaprodi === 'disetujui' ? 'Disetujui - Mahasiswa Boleh Mulai' : 'Ditolak')"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                {{-- Action Buttons --}}
                                <div x-show="item.status === 'pending'">
                                    <template x-if="!item.is_owner">
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-center">
                                            <p class="text-sm text-gray-500">Hanya dosen pemilik judul yang dapat
                                                menindaklanjuti pengajuan ini</p>
                                        </div>
                                    </template>
                                    <template x-if="item.is_owner">
                                        <div class="flex gap-3">
                                            <button @click="openReviewModal(item, 'disetujui')"
                                                :disabled="item.sudah_punya_judul"
                                                class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-xl font-semibold transition-all shadow-sm hover:shadow-md">
                                                Setujui
                                            </button>
                                            <button @click="openReviewModal(item, 'ditolak')"
                                                class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-all shadow-sm hover:shadow-md">
                                                Tolak
                                            </button>
                                        </div>
                                    </template>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="filteredData.length === 0">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                    <p class="text-gray-500 text-lg font-medium">Tidak ada pengajuan</p>
                    <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                </div>
            </template>
        </div>


        {{-- =============== REVIEW MODAL (DI DALAM x-data) ================= --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">
                        <span x-show="reviewAction === 'disetujui'" class="text-green-600">Setujui
                            Pengajuan</span>
                        <span x-show="reviewAction === 'ditolak'" class="text-red-600">Tolak
                            Pengajuan</span>
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-600 mb-1">Mahasiswa:</p>
                        <p class="text-lg font-bold text-gray-800" x-text="selectedItem.mahasiswa"></p>
                        <p class="text-sm text-gray-600 mt-2 mb-1">Judul:</p>
                        <p class="font-semibold text-gray-800" x-text="selectedItem.judul_text"></p>
                    </div>
                    <form method="POST" :action="'/dosen/pengajuan/' + selectedItem.id" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" :value="reviewAction">
                        <template x-if="selectedItem.jenis === 'mandiri' && reviewAction === 'disetujui'">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih
                                    Laboratorium <span class="text-red-500">*</span></label>
                                <select name="laboratorium_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="">-- Pilih Laboratorium --</option>
                                    @foreach ($laboratorium as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </template>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan untuk
                                Mahasiswa</label>
                            <textarea name="catatan_dosen" rows="4" placeholder="Berikan catatan, saran, atau alasan keputusan Anda..."
                                class="w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
                        </div>
                        <div x-show="reviewAction === 'disetujui'"
                            class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">Perhatian:</span> Setelah disetujui, judul akan
                                terkunci dan pengajuan lain akan otomatis ditolak.
                            </p>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="showModal = false"
                                class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl text-white"
                                :class="reviewAction === 'disetujui' ? 'bg-green-600 hover:bg-green-700' :
                                    'bg-red-600 hover:bg-red-700'">
                                <span
                                    x-text="reviewAction === 'disetujui' ? 'Setujui Pengajuan' : 'Tolak Pengajuan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function pengajuanDosenPage() {
            return {
                searchQuery: '',
                filterStatus: 'all',
                filterJenis: 'all',
                showModal: false,
                reviewAction: '',
                selectedItem: {
                    id: null,
                    mahasiswa: '',
                    judul_text: '',
                    jenis: ''
                },
                allData: [],
                filteredData: [],

                init() {
                    this.allData = [
                        @foreach ($pengajuan as $judulId => $items)
                            @php
                                $first = $items->first();
                                $pemenang = $items->firstWhere('status', 'disetujui');
                                $isOwner = false;
                                if ($first->jenis === 'pilih' && $first->judul) {
                                    $isOwner = $first->judul->dosen_id === $dosenId;
                                } elseif ($first->jenis === 'mandiri') {
                                    $isOwner = true;
                                }
                            @endphp {
                                judul_id: '{{ $judulId }}',
                                judul: '{{ $first->jenis === 'pilih' ? addslashes($first->judul->nama_judul ?? '-') : addslashes($first->judul_mandiri) }}',
                                kode: '{{ $first->jenis === 'pilih' ? $first->judul->kode ?? '' : '' }}',
                                deskripsi: '{{ $first->jenis === 'mandiri' ? addslashes($first->deskripsi_mandiri) : '' }}',
                                jenis: '{{ $first->jenis }}',
                                pemenang: '{{ $pemenang ? addslashes($pemenang->mahasiswa->name) : '' }}',
                                is_owner: {{ $isOwner ? 'true' : 'false' }},
                                items: [
                                    @foreach ($items as $p)
                                        @php
                                            $sudahPunyaJudul = \App\Models\Pengajuan::where('mahasiswa_id', $p->mahasiswa_id)->where('status', 'disetujui')->exists();
                                            $itemIsOwner = false;
                                            if ($p->jenis === 'pilih' && $p->judul) {
                                                $itemIsOwner = $p->judul->dosen_id === $dosenId;
                                            } elseif ($p->jenis === 'mandiri') {
                                                $itemIsOwner = true;
                                            }
                                        @endphp {
                                            id: {{ $p->id }},
                                            mahasiswa: '{{ addslashes($p->mahasiswa->name) }}',
                                            status: '{{ $p->status }}',
                                            prioritas: {{ $p->prioritas }},
                                            jenis: '{{ $p->jenis }}',
                                            judul_text: '{{ $p->jenis === 'mandiri' ? addslashes($p->judul_mandiri) : addslashes($p->judul->nama_judul ?? '-') }}',
                                            alasan: '{{ addslashes($p->alasan ?? '') }}',
                                            catatan_dosen: '{{ addslashes($p->catatan_dosen ?? '') }}',
                                            waktu: '{{ $p->created_at->diffForHumans() }}',
                                            sudah_punya_judul: {{ $sudahPunyaJudul ? 'true' : 'false' }},
                                            is_owner: {{ $itemIsOwner ? 'true' : 'false' }},
                                            status_kaprodi: '{{ $p->status_kaprodi ?? "" }}',

                                        }
                                        {{ $loop->last ? '' : ',' }}
                                    @endforeach
                                ]
                            }
                            {{ $loop->last ? '' : ',' }}
                        @endforeach
                    ];

                    this.applyFilter();
                },

                applyFilter() {
                    var self = this;
                    var result = this.allData;

                    if (this.searchQuery) {
                        var query = this.searchQuery.toLowerCase();
                        result = result.map(function(group) {
                            var filtered = group.items.filter(function(item) {
                                return item.mahasiswa.toLowerCase().includes(query) ||
                                    item.judul_text.toLowerCase().includes(query);
                            });
                            return filtered.length > 0 ? Object.assign({}, group, {
                                items: filtered
                            }) : null;
                        }).filter(function(g) {
                            return g !== null;
                        });
                    }

                    if (self.filterStatus !== 'all') {
                        result = result.map(function(group) {
                            var filtered = group.items.filter(function(item) {
                                return item.status === self.filterStatus;
                            });
                            return filtered.length > 0 ? Object.assign({}, group, {
                                items: filtered
                            }) : null;
                        }).filter(function(g) {
                            return g !== null;
                        });
                    }

                    if (self.filterJenis !== 'all') {
                        result = result.filter(function(group) {
                            return group.jenis === self.filterJenis;
                        });
                    }

                    this.filteredData = result;
                },

                openReviewModal(item, action) {
                    this.selectedItem = item;
                    this.reviewAction = action;
                    this.showModal = true;
                }
            }
        }
    </script>

</x-layout-dosen>
