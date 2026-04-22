<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="pengajuanPage()" x-init="init()" class="space-y-6">

        {{-- ================= ALERT ================= --}}
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

        {{-- ================= HEADER CARD ================= --}}
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">Pengajuan Judul Skripsi</h2>
                    <p class="text-indigo-100 text-sm">Pilih judul dari dosen atau ajukan judul mandiri Anda sendiri</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                        <p class="text-3xl font-bold">{{ $jumlahPengajuan }}/2</p>
                        <p class="text-xs text-indigo-100 mt-1">Pengajuan Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= MY SUBMISSIONS ================= --}}
        @if ($mySubmissions->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pengajuan Anda
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($mySubmissions as $submission)
                        <div class="border-2 rounded-xl p-4 transition-all"
                            :class="{
                                'border-yellow-300 bg-yellow-50': '{{ $submission->status }}'
                                === 'pending',
                                'border-green-300 bg-green-50': '{{ $submission->status }}'
                                === 'disetujui'
                            }">

                            <div class="flex items-start justify-between mb-3">
                                <span class="px-3 py-1 text-xs font-bold rounded-full"
                                    :class="{
                                        'bg-yellow-200 text-yellow-800': '{{ $submission->status }}'
                                        === 'pending',
                                        'bg-green-200 text-green-800': '{{ $submission->status }}'
                                        === 'disetujui'
                                    }">
                                    Prioritas {{ $submission->prioritas }}
                                </span>
                                <span class="px-3 py-1 text-xs font-bold rounded-full"
                                    :class="{
                                        'bg-yellow-200 text-yellow-800': '{{ $submission->status }}'
                                        === 'pending',
                                        'bg-green-200 text-green-800': '{{ $submission->status }}'
                                        === 'disetujui'
                                    }">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>

                            <h4 class="font-bold text-gray-800 mb-2">
                                {{ $submission->jenis === 'mandiri' ? $submission->judul_mandiri : $submission->judul->nama_judul }}
                            </h4>

                            @if ($submission->jenis === 'pilih')
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Lab:</span>
                                    {{ $submission->judul->laboratorium->nama ?? '-' }}
                                </p>
                            @else
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Tipe:</span> Judul Mandiri
                                </p>
                            @endif

                            <p class="text-xs text-gray-500">
                                Diajukan {{ $submission->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================= TAB NAVIGATION ================= --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

            {{-- Tab Headers --}}
            <div class="flex border-b border-gray-200">
                <button @click="activeTab = 'pilih'"
                    :class="activeTab === 'pilih' ? 'border-indigo-600 text-indigo-600 bg-indigo-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Pilih Judul Dosen
                    </div>
                </button>

                <button @click="activeTab = 'mandiri'"
                    :class="activeTab === 'mandiri' ? 'border-purple-600 text-purple-600 bg-purple-50' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Judul Mandiri
                    </div>
                </button>
            </div>

            {{-- Tab Content --}}
            <div class="p-6">

                {{-- ================= TAB: PILIH JUDUL DOSEN ================= --}}
                <div x-show="activeTab === 'pilih'" x-transition>

                    {{-- Search & Filter --}}
                    <div class="mb-6 space-y-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            {{-- Search --}}
                            <div class="flex-1 relative">
                                <input type="text" x-model="searchQuery" @input="filterJudul()"
                                    placeholder="Cari judul atau dosen..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            {{-- Filter Lab --}}
                            <select x-model="selectedLab" @change="filterJudul()"
                                class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Semua Lab</option>
                                @foreach ($laboratorium as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                                @endforeach
                            </select>

                            {{-- Filter Status --}}
                            <select x-model="filterStatus" @change="filterJudul()"
                                class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="all">Semua Status</option>
                                <option value="available">Tersedia</option>
                                <option value="taken">Sudah Diambil</option>
                            </select>
                        </div>

                        {{-- Result Count --}}
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>Menampilkan <span class="font-semibold" x-text="filteredJudul.length"></span> dari
                                <span class="font-semibold">{{ $judul->count() }}</span> judul</span>
                            <button @click="resetFilters()" class="text-indigo-600 hover:text-indigo-700 font-medium">
                                Reset Filter
                            </button>
                        </div>
                    </div>

                    {{-- Judul Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="item in filteredJudul" :key="item.id">
                            <div
                                class="bg-white border-2 border-gray-200 rounded-xl p-5 hover:border-indigo-300 hover:shadow-lg transition-all group">

                                {{-- Header --}}
                                <div class="flex items-start justify-between mb-3">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded font-mono"
                                        x-text="item.kode"></span>
                                    <span class="text-xs px-2 py-1 rounded font-semibold"
                                        :class="item.is_taken ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                                        x-text="item.is_taken ? 'Diambil' : 'Tersedia'">
                                    </span>
                                </div>

                                {{-- Lab Badge --}}
                                <div class="mb-3">
                                    <span
                                        class="inline-flex items-center gap-1 text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span x-text="item.lab_name"></span>
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors"
                                    x-text="item.nama_judul"></h3>

                                {{-- Description --}}
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2" x-text="item.deskripsi"></p>

                                {{-- Dosen --}}
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="truncate" x-text="item.dosen_name"></span>
                                </div>

                                {{-- Peminat --}}
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span><span x-text="item.peminat"></span> peminat</span>
                                </div>

                                {{-- Taken Info --}}
                                <div x-show="item.is_taken"
                                    class="mb-4 p-2 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-xs text-red-700">
                                        <span class="font-semibold">Diambil oleh:</span> <span
                                            x-text="item.taken_by"></span>
                                    </p>
                                </div>

                                {{-- Action Button --}}
                                <button @click="openModal(item)"
                                    :disabled="item.is_taken || item.is_selected || {{ $jumlahPengajuan }} >= 2"
                                    class="w-full py-2.5 rounded-lg font-semibold transition-all"
                                    :class="{
                                        'bg-indigo-600 hover:bg-indigo-700 text-white': !item.is_taken && !item
                                            .is_selected && {{ $jumlahPengajuan }} < 2,
                                        'bg-green-500 text-white cursor-default': item.is_selected,
                                        'bg-gray-300 text-gray-500 cursor-not-allowed': item.is_taken ||
                                            {{ $jumlahPengajuan }} >= 2
                                    }">
                                    <span x-show="item.is_taken">Tidak Tersedia</span>
                                    <span x-show="item.is_selected && !item.is_taken">✓ Sudah Dipilih</span>
                                    <span
                                        x-show="!item.is_taken && !item.is_selected && {{ $jumlahPengajuan }} >= 2">Batas
                                        Tercapai</span>
                                    <span
                                        x-show="!item.is_taken && !item.is_selected && {{ $jumlahPengajuan }} < 2">Ajukan
                                        Judul</span>
                                </button>

                            </div>
                        </template>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="filteredJudul.length === 0" class="text-center py-16">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">Tidak ada judul yang sesuai</p>
                        <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                    </div>

                </div>

                {{-- ================= TAB: JUDUL MANDIRI ================= --}}
                <div x-show="activeTab === 'mandiri'" x-transition>

                    <div class="max-w-2xl mx-auto">
                        <div class="bg-purple-50 border-2 border-purple-200 rounded-2xl p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 bg-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-purple-900 mb-2">Tentang Judul Mandiri</h3>
                                    <p class="text-sm text-purple-700">
                                        Ajukan judul skripsi hasil ide Anda sendiri. Judul akan direview oleh dosen dan
                                        jika disetujui,
                                        dosen akan menentukan laboratorium yang sesuai.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="jenis" value="mandiri">

                            {{-- Judul --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Judul Skripsi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="judul_mandiri" required maxlength="255"
                                    placeholder="Masukkan judul skripsi Anda"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Maksimal 255 karakter</p>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Deskripsi & Latar Belakang <span class="text-red-500">*</span>
                                </label>
                                <textarea name="deskripsi_mandiri" required rows="6" maxlength="1000"
                                    placeholder="Jelaskan latar belakang, tujuan, dan ruang lingkup penelitian Anda..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
                            </div>

                            {{-- Prioritas --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Prioritas <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label
                                        class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-all">
                                        <input type="radio" name="prioritas" value="1" required
                                            class="sr-only peer">
                                        <div class="text-center peer-checked:text-purple-600">
                                            <div class="text-2xl font-bold mb-1">1</div>
                                            <div class="text-xs">Prioritas Utama</div>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-purple-500 transition-all">
                                        <input type="radio" name="prioritas" value="2" required
                                            class="sr-only peer">
                                        <div class="text-center peer-checked:text-purple-600">
                                            <div class="text-2xl font-bold mb-1">2</div>
                                            <div class="text-xs">Prioritas Kedua</div>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex gap-4">
                                <button type="submit" :disabled="{{ $jumlahPengajuan }} >= 2"
                                    class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 disabled:from-gray-400 disabled:to-gray-400 text-white py-3 rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl disabled:cursor-not-allowed">
                                    <span x-show="{{ $jumlahPengajuan }} < 2">Ajukan Judul Mandiri</span>
                                    <span x-show="{{ $jumlahPengajuan }} >= 2">Batas Pengajuan Tercapai</span>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= MODAL PENGAJUAN ================= --}}
    <div x-show="showModal" x-cloak @click.self="showModal = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">

        <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">Konfirmasi Pengajuan</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <form method="POST" action="{{ route('mahasiswa.pengajuan.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="jenis" value="pilih">
                    <input type="hidden" name="judul_id" x-model="selectedJudul.id">

                    {{-- Judul Info --}}
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                        <h4 class="font-bold text-indigo-900 mb-2" x-text="selectedJudul.nama_judul"></h4>
                        <p class="text-sm text-indigo-700 mb-2" x-text="selectedJudul.deskripsi"></p>
                        <div class="flex items-center gap-4 text-sm text-indigo-600">
                            <span>📚 <span x-text="selectedJudul.lab_name"></span></span>
                            <span>👨‍🏫 <span x-text="selectedJudul.dosen_name"></span></span>
                        </div>
                    </div>

                    {{-- Prioritas --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Pilih Prioritas <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-indigo-500 transition-all">
                                <input type="radio" name="prioritas" value="1" required class="sr-only peer">
                                <div class="text-center peer-checked:text-indigo-600">
                                    <div class="text-3xl font-bold mb-1">1</div>
                                    <div class="text-xs font-medium">Prioritas Utama</div>
                                </div>
                                <div
                                    class="absolute inset-0 border-2 border-indigo-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity">
                                </div>
                            </label>

                            <label
                                class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-indigo-500 transition-all">
                                <input type="radio" name="prioritas" value="2" required class="sr-only peer">
                                <div class="text-center peer-checked:text-indigo-600">
                                    <div class="text-3xl font-bold mb-1">2</div>
                                    <div class="text-xs font-medium">Prioritas Kedua</div>
                                </div>
                                <div
                                    class="absolute inset-0 border-2 border-indigo-600 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity">
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Alasan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alasan Memilih Judul (Opsional)
                        </label>
                        <textarea name="alasan" rows="4" maxlength="500"
                            placeholder="Jelaskan mengapa Anda tertarik dengan judul ini..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                            Ajukan Sekarang
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    {{-- ================= ALPINE SCRIPT ================= --}}
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

                init() {
                    // Load judul data
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
                                dosen_name: '{{ $j->dosen->name ?? '-' }}',
                                peminat: {{ $j->peminat ?? 0 }},
                                is_taken: {{ $isTaken ? 'true' : 'false' }},
                                taken_by: '{{ $takenBy }}',
                                is_selected: {{ $isSelected ? 'true' : 'false' }}
                            }
                            {{ $loop->last ? '' : ',' }}
                        @endforeach
                    ];

                    this.filteredJudul = this.allJudul;
                },

                filterJudul() {
                    let result = this.allJudul;

                    // Filter by search
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        result = result.filter(j =>
                            j.nama_judul.toLowerCase().includes(query) ||
                            j.dosen_name.toLowerCase().includes(query) ||
                            j.deskripsi.toLowerCase().includes(query)
                        );
                    }

                    // Filter by lab
                    if (this.selectedLab) {
                        result = result.filter(j => j.lab_id == this.selectedLab);
                    }

                    // Filter by status
                    if (this.filterStatus === 'available') {
                        result = result.filter(j => !j.is_taken);
                    } else if (this.filterStatus === 'taken') {
                        result = result.filter(j => j.is_taken);
                    }

                    this.filteredJudul = result;
                },

                resetFilters() {
                    this.searchQuery = '';
                    this.selectedLab = '';
                    this.filterStatus = 'all';
                    this.filteredJudul = this.allJudul;
                },

                openModal(judul) {
                    this.selectedJudul = judul;
                    this.showModal = true;
                }
            }
        }
    </script>

</x-layout>
