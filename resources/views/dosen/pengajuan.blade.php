<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengajuanDosenPage()" x-init="init()" class="space-y-6">

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

            {{-- Total --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Pengajuan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }}</p>
            </div>

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
                <p class="text-gray-500 text-sm font-medium mb-1">Perlu Review</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pending }}</p>
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
                <p class="text-3xl font-bold text-gray-800">{{ $disetujui }}</p>
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
                <p class="text-3xl font-bold text-gray-800">{{ $ditolak }}</p>
            </div>

        </div>

        {{-- ================= FILTER & SEARCH ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row gap-4">

                {{-- Search --}}
                <div class="flex-1 relative">
                    <input type="text" x-model="searchQuery" @input="applyFilter()"
                        placeholder="Cari mahasiswa atau judul..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                {{-- Filter Status --}}
                <select x-model="filterStatus" @change="applyFilter()"
                    class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="all">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>

                {{-- Filter Jenis --}}
                <select x-model="filterJenis" @change="applyFilter()"
                    class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="all">Semua Jenis</option>
                    <option value="pilih">Pilih Judul</option>
                    <option value="mandiri">Judul Mandiri</option>
                </select>

                {{-- Result Count --}}
                <div class="flex items-center px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl font-medium text-sm">
                    <span x-text="filteredData.length"></span> hasil
                </div>

            </div>
        </div>

        {{-- ================= PENGAJUAN LIST ================= --}}
        <div class="space-y-6">

            <template x-for="group in filteredData" :key="group.judul_id">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                    {{-- Group Header --}}
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span x-show="group.jenis === 'mandiri'"
                                        class="px-3 py-1 text-xs font-bold bg-purple-100 text-purple-700 rounded-full">
                                        Judul Mandiri
                                    </span>
                                    <span x-show="group.jenis === 'pilih'"
                                        class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded-full">
                                        Pilih Judul
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1" x-text="group.judul"></h3>
                                <p x-show="group.kode" class="text-sm text-gray-500">
                                    Kode: <span class="font-mono" x-text="group.kode"></span>
                                </p>
                                <p x-show="group.deskripsi" class="text-sm text-gray-600 mt-2"
                                    x-text="group.deskripsi"></p>
                            </div>
                            <div x-show="group.pemenang" class="ml-4">
                                <div class="bg-green-100 border border-green-200 rounded-xl px-4 py-2">
                                    <p class="text-xs text-green-600 font-semibold mb-1">✓ Sudah Diambil</p>
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

                                {{-- Header --}}
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

                                {{-- Warning --}}
                                <div x-show="item.sudah_punya_judul && item.status === 'pending'"
                                    class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <p class="text-sm text-orange-700 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Mahasiswa sudah memiliki judul yang disetujui
                                    </p>
                                </div>

                                {{-- Alasan --}}
                                <div x-show="item.alasan" class="mb-4">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <p class="text-xs text-blue-600 font-semibold mb-2">Alasan Mahasiswa:</p>
                                        <p class="text-sm text-blue-900" x-text="item.alasan"></p>
                                    </div>
                                </div>

                                {{-- Catatan Dosen (if exists) --}}
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
                                            Catatan Anda:
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

                                {{-- Action Buttons --}}
                                <div x-show="item.status === 'pending'" class="flex gap-3">
                                    <button @click="openReviewModal(item, 'disetujui')"
                                        :disabled="item.sudah_punya_judul"
                                        class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-xl font-semibold transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Setujui
                                    </button>

                                    <button @click="openReviewModal(item, 'ditolak')"
                                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>

                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="filteredData.length === 0">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">Tidak ada pengajuan</p>
                    <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                </div>
            </template>

        </div>

    

    {{-- ================= REVIEW MODAL ================= --}}
    <div x-show="showModal" x-cloak @click.self="showModal = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">

        <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">
                    <template x-if="reviewAction === 'disetujui'">
                        <span class="text-green-600">Setujui Pengajuan</span>
                    </template>
                    <template x-if="reviewAction === 'ditolak'">
                        <span class="text-red-600">Tolak Pengajuan</span>
                    </template>
                </h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-6">

                {{-- Student Info --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-600 mb-1">Mahasiswa:</p>
                    <p class="text-lg font-bold text-gray-800" x-text="selectedItem.mahasiswa"></p>
                    <p class="text-sm text-gray-600 mt-2 mb-1">Judul:</p>
                    <p class="font-semibold text-gray-800" x-text="selectedItem.judul_text"></p>
                </div>

                {{-- Form --}}
                <form method="POST" :action="'/dosen/pengajuan/' + selectedItem.id" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="status" :value="reviewAction">

                    {{-- Lab Selection (for mandiri + disetujui) --}}
                    <template x-if="selectedItem.jenis === 'mandiri' && reviewAction === 'disetujui'">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Laboratorium <span class="text-red-500">*</span>
                            </label>
                            <select name="laboratorium_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="">-- Pilih Laboratorium --</option>
                                @foreach ($laboratorium as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    {{-- Catan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan untuk Mahasiswa
                        </label>
                        <textarea name="catatan_dosen" rows="4" placeholder="Berikan catatan, saran, atau alasan keputusan Anda..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
                    </div>

                    {{-- Warning for Approve --}}
                    <template x-if="reviewAction === 'disetujui'">
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">Perhatian:</span> Setelah disetujui, judul akan terkunci
                                dan pengajuan lain untuk judul ini akan otomatis ditolak.
                            </p>
                        </div>
                    </template>

                    {{-- Actions --}}
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


    {{-- ================= ALPINE SCRIPT ================= --}}
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
                            @endphp {
                                judul_id: '{{ $judulId }}',
                                judul: '{{ $first->jenis === 'pilih' ? addslashes($first->judul->nama_judul ?? '-') : addslashes($first->judul_mandiri) }}',
                                kode: '{{ $first->jenis === 'pilih' ? $first->judul->kode ?? '' : '' }}',
                                deskripsi: '{{ $first->jenis === 'mandiri' ? addslashes($first->deskripsi_mandiri) : '' }}',
                                jenis: '{{ $first->jenis }}',
                                pemenang: '{{ $pemenang ? addslashes($pemenang->mahasiswa->name) : '' }}',
                                items: [
                                    @foreach ($items as $p)
                                        @php
                                            $sudahPunyaJudul = \App\Models\Pengajuan::where('mahasiswa_id', $p->mahasiswa_id)->where('status', 'disetujui')->exists();
                                        @endphp {
                                            id: {{ $p->id }},
                                            mahasiswa: '{{ addslashes($p->mahasiswa->name) }}',
                                            status: '{{ $p->status }}',
                                            prioritas: {{ $p->prioritas }},
                                            jenis: '{{ $p->jenis }}',
                                            judul_text: '{{ $p->jenis === 'mandiri' ? addslashes($p->judul_mandiri) : addslashes($p->judul->nama_judul ?? '-') }}',
                                            alasan: '{{ addslashes($p->alasan ?? '') }}',
                                            catan_dosen: '{{ addslashes($p->catatan_dosen ?? '') }}',
                                            waktu: '{{ $p->created_at->diffForHumans() }}',
                                            sudah_punya_judul: {{ $sudahPunyaJudul ? 'true' : 'false' }}
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
                            var filteredItems = group.items.filter(function(item) {
                                return item.mahasiswa.toLowerCase().includes(query) ||
                                    item.judul_text.toLowerCase().includes(query);
                            });
                            if (filteredItems.length > 0) {
                                return Object.assign({}, group, {
                                    items: filteredItems
                                });
                            }
                            return null;
                        }).filter(function(g) {
                            return g !== null;
                        });
                    }

                    if (self.filterStatus !== 'all') {
                        result = result.map(function(group) {
                            var filteredItems = group.items.filter(function(item) {
                                return item.status === self.filterStatus;
                            });
                            if (filteredItems.length > 0) {
                                return Object.assign({}, group, {
                                    items: filteredItems
                                });
                            }
                            return null;
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
