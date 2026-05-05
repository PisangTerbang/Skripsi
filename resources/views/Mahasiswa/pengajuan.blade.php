<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengajuanPage()" x-init="init()" class="space-y-6">

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
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-exclamation-circle class="w-5 h-5" />
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false"><x-heroicon-o-x-mark class="w-5 h-5" /></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Pengajuan Judul Skripsi</h2>
                    <p class="text-indigo-100 text-sm">Pilih judul dari dosen atau ajukan judul mandiri</p>
                </div>
                <div class="hidden md:flex gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $jumlahPengajuan }}/2</p>
                        <p class="text-xs text-indigo-200">Pengajuan Aktif</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
                        <p class="text-2xl font-bold">{{ $judul->count() }}</p>
                        <p class="text-xs text-indigo-200">Judul Tersedia</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- My Submissions --}}
        @if ($mySubmissions->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-indigo-600" />
                    Pengajuan Anda
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($mySubmissions as $submission)
                        <div
                            class="border-2 rounded-xl p-4 {{ $submission->status === 'pending' ? 'border-yellow-300 bg-yellow-50' : 'border-green-300 bg-green-50' }}">
                            <div class="flex items-start justify-between mb-2">
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full {{ $submission->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                    Prioritas {{ $submission->prioritas }}
                                </span>
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full {{ $submission->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>
                            <h4 class="font-bold text-gray-800 mb-1">
                                {{ $submission->jenis === 'mandiri' ? $submission->judul_mandiri : $submission->judul->nama_judul }}
                            </h4>
                            <p class="text-xs text-gray-500">Diajukan {{ $submission->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tab Navigation --}}
        <div class="bg-white rounded-2xl shadow-lg border-gray-100 overflow-hidden">
            <div class="flex border-b border-gray-200">
                <button @click="activeTab = 'pilih'"
                    :class="activeTab === 'pilih' ? 'border-indigo-600 text-indigo-600 bg-indigo-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 px-6 py-4 text-sm font-semibold border-b-2 transition-all flex items-center justify-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5" />
                    Pilih Judul Dosen
                </button>
                <button @click="activeTab = 'mandiri'"
                    :class="activeTab === 'mandiri' ? 'border-purple-600 text-purple-600 bg-purple-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 px-6 py-4 text-sm font-semibold border-b-2 transition-all flex items-center justify-center gap-2">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                    Judul Mandiri
                </button>
            </div>

            <div class="p-6">
                {{-- TAB: PILIH JUDUL --}}
                <div x-show="activeTab === 'pilih'" x-transition>

                    {{-- Filter --}}
                    <div class="mb-6 flex flex-col md:flex-row gap-3">
                        <div class="flex-1 relative">
                            <x-heroicon-o-magnifying-glass
                                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input type="text" x-model="searchQuery" @input="filterJudul()"
                                placeholder="Cari judul, dosen, kode..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <select x-model="selectedLab" @change="filterJudul()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua Lab</option>
                            @foreach ($laboratorium as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                            @endforeach
                        </select>
                        <select x-model="filterStatus" @change="filterJudul()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="all">Semua</option>
                            <option value="available">Tersedia</option>
                            <option value="taken">Sudah Diambil</option>
                        </select>
                        <div
                            class="flex items-center px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-sm font-medium">
                            <span x-text="filteredJudul.length"></span>&nbsp;judul
                        </div>
                    </div>

                    {{-- Tabel --}}
                    <div class="overflow-x-auto rounded-xl border-gray-200">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Dosen</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Lab</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Peminat</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(item, index) in paginatedJudul" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-gray-500"
                                            x-text="(currentPage - 1) * perPage + index + 1"></td>
                                        <td class="px-4 py-3"><span
                                                class="font-mono text-xs bg-gray-100 px-2 py-1 rounded"
                                                x-text="item.kode"></span></td>
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-gray-800 truncate max-w-[200px]"
                                                x-text="item.nama_judul"></p>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 truncate max-w-[120px]"
                                            x-text="item.dosen_name"></td>
                                        <td class="px-4 py-3"><span
                                                class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold"
                                                x-text="item.lab_name"></span></td>
                                        <td class="px-4 py-3 text-gray-600" x-text="item.peminat"></td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                                :class="item.is_taken ? 'bg-red-100 text-red-700' : (item.is_selected ?
                                                    'bg-green-100 text-green-700' :
                                                    'bg-emerald-100 text-emerald-700')"
                                                x-text="item.is_taken ? 'Diambil' : (item.is_selected ? 'Dipilih' : 'Tersedia')"></span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button @click="openModal(item)"
                                                :disabled="item.is_taken || item.is_selected || {{ $jumlahPengajuan }} >= 2"
                                                class="p-2 rounded-lg transition-colors"
                                                :class="item.is_taken || item.is_selected || {{ $jumlahPengajuan }} >= 2 ?
                                                    'text-gray-300 cursor-not-allowed' :
                                                    'text-indigo-600 hover:bg-indigo-50'">
                                                <x-heroicon-o-paper-airplane class="w-5 h-5" />
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Empty --}}
                    <div x-show="filteredJudul.length === 0" class="p-12 text-center">
                        <x-heroicon-o-document-magnifying-glass class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <p class="text-gray-500 font-medium">Tidak ada judul yang ditemukan</p>
                    </div>

                    {{-- Pagination --}}
                    <div x-show="totalPages > 1" class="mt-4 flex items-center justify-between">
                        <p class="text-sm text-gray-500">Halaman <span x-text="currentPage"></span> dari <span
                                x-text="totalPages"></span></p>
                        <div class="flex gap-1">
                            <button @click="currentPage--; paginate()" :disabled="currentPage === 1"
                                class="px-3 py-1 rounded-lg text-sm border-gray-300 disabled:opacity-50 hover:bg-gray-50">Prev</button>
                            <template x-for="page in totalPages" :key="page">
                                <button @click="currentPage = page; paginate()"
                                    :class="currentPage === page ? 'bg-indigo-600 text-white' :
                                        'border border-gray-300 hover:bg-gray-50'"
                                    class="px-3 py-1 rounded-lg text-sm" x-text="page"></button>
                            </template>
                            <button @click="currentPage++; paginate()" :disabled="currentPage === totalPages"
                                class="px-3 py-1 rounded-lg text-sm border border-gray-300 disabled:opacity-50 hover:bg-gray-50">Next</button>
                        </div>
                    </div>
                </div>

                {{-- TAB: JUDUL MANDIRI --}}
                <div x-show="activeTab === 'mandiri'" x-transition>
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-purple-50 border-2 border-purple-200 rounded-2xl p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <x-heroicon-o-information-circle class="w-6 h-6 text-purple-700" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-purple-900 mb-2">Tentang Judul Mandiri</h3>
                                    <p class="text-sm text-purple-700">Ajukan judul skripsi hasil ide Anda sendiri.
                                        Judul akan direview oleh dosen dan jika disetujui, akan masuk ke proses
                                        laboratorium.</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="jenis" value="mandiri">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Skripsi <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="judul_mandiri" required maxlength="255"
                                    placeholder="Masukkan judul skripsi Anda"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span
                                        class="text-red-500">*</span></label>
                                <textarea name="deskripsi_mandiri" required rows="5" maxlength="1000"
                                    placeholder="Jelaskan latar belakang dan tujuan penelitian..."
                                    class="w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Prioritas <span
                                        class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label
                                        class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-all">
                                        <input type="radio" name="prioritas" value="1" required
                                            class="sr-only peer">
                                        <div class="text-center peer-checked:text-purple-600">
                                            <div class="text-2xl font-bold mb-1">1</div>
                                            <div class="text-xs">Utama</div>
                                            <div
                                                class="absolute inset-0 border-2 border-purple-600 rounded-xl opacity-0 peer-checked:opacity-100">
                                            </div>
                                        </div>
                                    </label>
                                    <label
                                        class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-all">
                                        <input type="radio" name="prioritas" value="2" required
                                            class="sr-only peer">
                                        <div class="text-center peer-checked:text-purple-600">
                                            <div class="text-2xl font-bold mb-1">2</div>
                                            <div class="text-xs">Kedua</div>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-xl opacity-0 peer-checked:opacity-100">
                                        </div>

                                    </label>

                                </div>
                            </div>


                            <button type="submit" :disabled="{{ $jumlahPengajuan }} >= 2"
                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 disabled:from-gray-400 disabled:to-gray-400 text-white py-3 rounded-xl font-semibold transition-all shadow-lg disabled:cursor-not-allowed">
                                <span x-show="{{ $jumlahPengajuan }} < 2">Ajukan Judul Mandiri</span>
                                <span x-show="{{ $jumlahPengajuan }} >= 2">Batas Pengajuan Tercapai</span>
                            </button>
                        </form>
                    </div>
                </div>



                {{-- MODAL --}}
                <div x-show="showModal" x-cloak @click.self="showModal = false"
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

                        <div
                            class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800">Konfirmasi Pengajuan</h3>
                            <button @click="showModal = false"><x-heroicon-o-x-mark
                                    class="w-6 h-6 text-gray-400 hover:text-gray-600" /></button>
                        </div>

                        <div class="p-6">
                            <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}"
                                class="space-y-6">
                                @csrf
                                <input type="hidden" name="jenis" value="pilih">
                                <input type="hidden" name="judul_id" :value="selectedJudul.id">

                                {{-- Info Judul --}}
                                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 space-y-2">
                                    <h4 class="font-bold text-indigo-900" x-text="selectedJudul.nama_judul"></h4>
                                    <p class="text-sm text-indigo-700" x-text="selectedJudul.deskripsi"></p>
                                    <div class="flex gap-4 text-xs text-indigo-600">
                                        <span x-text="'Lab: ' + selectedJudul.lab_name"></span>
                                        <span x-text="'Dosen: ' + selectedJudul.dosen_name"></span>
                                    </div>
                                </div>

                                {{-- Prioritas --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Prioritas <span
                                            class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label
                                            class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-indigo-500 transition-all">
                                            <input type="radio" name="prioritas" value="1" required
                                                class="sr-only peer">
                                            <div class="text-center peer-checked:text-indigo-600">
                                                <div class="text-3xl font-bold mb-1">1</div>
                                                <div class="text-xs font-medium">Utama</div>
                                            </div>
                                            <div
                                                class="absolute inset-0 border-2 border-indigo-600 rounded-xl opacity-0 peer-checked:opacity-100">
                                            </div>
                                        </label>
                                        <label
                                            class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-indigo-500 transition-all">
                                            <input type="radio" name="prioritas" value="2" required
                                                class="sr-only peer">
                                            <div class="text-center peer-checked:text-indigo-600">
                                                <div class="text-3xl font-bold mb-1">2</div>
                                                <div class="text-xs font-medium">Kedua</div>
                                            </div>
                                            <div
                                                class="absolute inset-0 border-2 border-indigo-600 rounded-xl opacity-0 peer-checked:opacity-100">
                                            </div>
                                        </label>
                                    </div>
                                </div>


                                {{-- Alasan --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan
                                        (Opsional)</label>
                                    <textarea name="alasan" rows="3" maxlength="500" placeholder="Jelaskan mengapa Anda tertarik..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                                </div>

                                {{-- Actions --}}
                                <div class="flex gap-4">
                                    <button type="button" @click="showModal = false"
                                        class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50">Batal</button>
                                    <button type="submit"
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg">Ajukan
                                        Sekarang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <script>
                function pengajuanPage() {
                    return {
                        activeTab: 'pilih',
                        searchQuery: '',
                        selectedLab: '',
                        filterStatus: 'all',
                        showModal: false,
                        selectedJudul: {},
                        allJudul: [],
                        filteredJudul: [],
                        paginatedJudul: [],
                        currentPage: 1,
                        perPage: 10,
                        totalPages: 1,

                        init() {
                            this.allJudul = [
                                @foreach ($judul as $j)
                                    @php
                                        $approved = $j->pengajuan->where('status', 'disetujui')->first();
                                        $isTaken = $approved !== null;
                                        $takenBy = $approved ? $approved->mahasiswa->name : null;
                                        $isSelected = in_array($j->id, $pengajuanSaya);
                                    @endphp {
                                        id: {{ $j->id }},
                                        kode: '{{ $j->kode ?? '-' }}',
                                        nama_judul: '{{ addslashes($j->nama_judul) }}',
                                        deskripsi: '{{ addslashes($j->deskripsi) }}',
                                        lab_id: {{ $j->laboratorium_id }},
                                        lab_name: '{{ $j->laboratorium->nama ?? '-' }}',
                                        dosen_name: '{{ addslashes($j->dosen->name ?? '-') }}',
                                        peminat: {{ $j->peminat ?? 0 }},
                                        is_taken: {{ $isTaken ? 'true' : 'false' }},
                                        taken_by: '{{ addslashes($takenBy ?? '') }}',
                                        is_selected: {{ $isSelected ? 'true' : 'false' }}
                                    }
                                    {{ $loop->last ? '' : ',' }}
                                @endforeach
                            ];
                            this.filteredJudul = this.allJudul;
                            this.paginate();
                        },

                        filterJudul() {
                            var self = this;
                            var result = this.allJudul;

                            if (this.searchQuery) {
                                var q = this.searchQuery.toLowerCase();
                                result = result.filter(function(j) {
                                    return j.nama_judul.toLowerCase().includes(q) ||
                                        j.dosen_name.toLowerCase().includes(q) ||
                                        j.kode.toLowerCase().includes(q);
                                });
                            }

                            if (this.selectedLab) {
                                result = result.filter(function(j) {
                                    return j.lab_id == self.selectedLab;
                                });
                            }

                            if (this.filterStatus === 'available') {
                                result = result.filter(function(j) {
                                    return !j.is_taken;
                                });
                            } else if (this.filterStatus === 'taken') {
                                result = result.filter(function(j) {
                                    return j.is_taken;
                                });
                            }

                            this.filteredJudul = result;
                            this.currentPage = 1;
                            this.paginate();
                        },

                        paginate() {
                            this.totalPages = Math.ceil(this.filteredJudul.length / this.perPage) || 1;
                            var start = (this.currentPage - 1) * this.perPage;
                            this.paginatedJudul = this.filteredJudul.slice(start, start + this.perPage);
                        },

                        resetFilters() {
                            this.searchQuery = '';
                            this.selectedLab = '';
                            this.filterStatus = 'all';
                            this.filteredJudul = this.allJudul;
                            this.currentPage = 1;
                            this.paginate();
                        },

                        openModal(judul) {
                            this.selectedJudul = judul;
                            this.showModal = true;
                        }
                    }
                }
            </script>

</x-layout>
