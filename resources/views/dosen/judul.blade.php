<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div x-data="judulPage()" x-init="init()">
        <div class="min-h-screen bg-slate-100">
            <div class="px-6 py-6 space-y-6">

                {{-- ===== TOP BAR ===== --}}
                <div class="sticky top-0 z-10 border-b-2 border-emerald-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-emerald-200 bg-emerald-50">
                                <x-heroicon-o-book-open class="h-5 w-5 text-emerald-600" />
                            </div>
                            <div class="h-8 w-px bg-gray-200"></div>
                            <div>
                                <h1 class="text-lg font-extrabold text-gray-900">Manajemen Judul TA</h1>
                                <p class="mt-0.5 text-xs text-gray-400">Kelola judul yang Anda tawarkan kepada mahasiswa
                                </p>
                            </div>
                        </div>
                        <button @click="openCreateModal()"
                            class="inline-flex items-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                            <x-heroicon-o-plus class="h-3.5 w-3.5" />
                            Tambah Judul
                        </button>
                    </div>
                </div>

                {{-- Alert --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                        class="flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                        <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-green-500" />
                        <span class="font-semibold">{{ session('success') }}</span>
                        <button @click="show = false"
                            class="ml-auto rounded-lg p-1 text-green-400 hover:bg-green-100 transition">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                        class="flex items-center gap-3 rounded-2xl border-2 border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 shadow-sm">
                        <x-heroicon-o-x-circle class="h-5 w-5 shrink-0 text-red-500" />
                        <span class="font-semibold">{{ session('error') }}</span>
                        <button @click="show = false"
                            class="ml-auto rounded-lg p-1 text-red-400 hover:bg-red-100 transition">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </button>
                    </div>
                @endif

                {{-- ===== STATS ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-chart-bar class="h-3 w-3" />
                        Ringkasan
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

                    {{-- Total --}}
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-indigo-300 bg-gradient-to-br from-indigo-600 to-blue-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document-text class="h-6 w-6 text-indigo-200 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">Total</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $totalJudul }}</p>
                        </div>
                    </div>

                    {{-- Draft --}}
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-gray-300 bg-gradient-to-br from-gray-500 to-gray-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-document class="h-6 w-6 text-gray-200 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-200">Draft</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $draft }}</p>
                        </div>
                    </div>

                    {{-- Menunggu Validasi --}}
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-amber-300 bg-gradient-to-br from-amber-500 to-orange-600 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-clock class="h-6 w-6 text-amber-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-amber-100">Menunggu Validasi</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $pending }}</p>
                        </div>
                    </div>

                    {{-- Ditawarkan --}}
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-500 to-green-600 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-emerald-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-100">Ditawarkan</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $ditawarkan }}</p>
                        </div>
                    </div>

                    {{-- Total Peminat --}}
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-violet-300 bg-gradient-to-br from-violet-500 to-purple-700 p-4 shadow-lg transition hover:-translate-y-0.5">
                        <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-white/10"></div>
                        <div class="relative">
                            <x-heroicon-o-users class="h-6 w-6 text-violet-100 mb-2" />
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-100">Peminat</p>
                            <p class="mt-1 text-3xl font-black text-white">{{ $totalPeminat }}</p>
                        </div>
                    </div>

                </div>

                {{-- ===== FILTER ===== --}}
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                    <span
                        class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                        <x-heroicon-o-table-cells class="h-3 w-3" />
                        Daftar Judul
                    </span>
                    <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
                </div>

                <div class="flex flex-wrap items-center gap-3">

                    {{-- Status Filter Pills --}}
                    <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                        @foreach ([
        'all' => 'Semua',
        'draft' => 'Draft',
        'pending_kalab' => 'Menunggu',
        'ditawarkan' => 'Ditawarkan',
        'ditolak_kalab' => 'Ditolak',
    ] as $val => $label)
                            <button type="button" @click="filterStatus = '{{ $val }}'; applyFilter()"
                                x-bind:class="filterStatus === '{{ $val }}'
                                    ?
                                    '{{ $val === 'draft' ? 'bg-gray-600' : ($val === 'pending_kalab' ? 'bg-amber-500' : ($val === 'ditawarkan' ? 'bg-emerald-600' : ($val === 'ditolak_kalab' ? 'bg-red-600' : 'bg-indigo-600'))) }} text-white shadow-sm' :
                                    'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                                class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all">
                                {{ $label }}
                            </button>
                            @if (!$loop->last)
                                <div class="h-5 w-px bg-gray-200"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Lab Filter --}}
                    <select x-model="filterLab" @change="applyFilter()"
                        class="rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:border-emerald-400 focus:outline-none transition">
                        <option value="all">Semua Lab</option>
                        @foreach ($laboratorium as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                        @endforeach
                    </select>

                    {{-- Search --}}
                    <div
                        class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                        <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                        <input type="text" x-model="searchQuery" @input="applyFilter()"
                            placeholder="Cari judul, kode..."
                            class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                        <template x-if="searchQuery !== ''">
                            <button @click="searchQuery = ''; applyFilter()"
                                class="text-gray-400 hover:text-gray-600 transition">
                                <x-heroicon-o-x-mark class="h-4 w-4" />
                            </button>
                        </template>
                    </div>

                    {{-- Count --}}
                    <div
                        class="flex items-center gap-1.5 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm text-xs font-bold text-gray-600">
                        <span x-text="filteredData.length"></span>
                        <span>judul</span>
                    </div>

                </div>

                {{-- ===== JUDUL GRID ===== --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">

                    <template x-for="item in filteredData" :key="item.id">
                        <div class="group relative overflow-hidden rounded-2xl border-2 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                            x-bind:class="{
                                'border-gray-200 hover:border-gray-300': item.status_judul === 'draft',
                                'border-amber-200 hover:border-amber-300': item.status_judul === 'pending_kalab',
                                'border-emerald-200 hover:border-emerald-300': item.status_judul === 'ditawarkan',
                                'border-red-200 hover:border-red-300': item.status_judul === 'ditolak_kalab'
                            }">

                            {{-- Color bar top --}}
                            <div class="h-1.5 w-full"
                                x-bind:class="{
                                    'bg-gradient-to-r from-gray-300 to-gray-400': item.status_judul === 'draft',
                                    'bg-gradient-to-r from-amber-400 to-orange-400': item.status_judul === 'pending_kalab',
                                    'bg-gradient-to-r from-emerald-500 to-green-500': item.status_judul === 'ditawarkan',
                                    'bg-gradient-to-r from-red-500 to-rose-500': item.status_judul === 'ditolak_kalab'
                                }">
                            </div>

                            <div class="p-5">

                                {{-- Header --}}
                                <div class="flex items-start justify-between mb-3">
                                    <span
                                        class="rounded-lg border-2 border-gray-200 bg-gray-50 px-2.5 py-1 font-mono text-xs font-black text-gray-600"
                                        x-text="item.kode">
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <span x-show="item.status_judul === 'draft'"
                                            class="inline-flex items-center gap-1 rounded-full border-2 border-gray-200 bg-gray-100 px-2 py-0.5 text-[10px] font-black text-gray-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Draft
                                        </span>
                                        <span x-show="item.status_judul === 'pending_kalab'"
                                            class="inline-flex items-center gap-1 rounded-full border-2 border-amber-200 bg-amber-100 px-2 py-0.5 text-[10px] font-black text-amber-700">
                                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                                            Menunggu Validasi
                                        </span>
                                        <span x-show="item.status_judul === 'ditawarkan'"
                                            class="inline-flex items-center gap-1 rounded-full border-2 border-emerald-200 bg-emerald-100 px-2 py-0.5 text-[10px] font-black text-emerald-700">
                                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                                            Ditawarkan
                                        </span>
                                        <span x-show="item.status_judul === 'ditolak_kalab'"
                                            class="inline-flex items-center gap-1 rounded-full border-2 border-red-200 bg-red-100 px-2 py-0.5 text-[10px] font-black text-red-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Ditolak Ka Lab
                                        </span>
                                        <span x-show="item.is_locked"
                                            class="inline-flex items-center gap-1 rounded-full border-2 border-red-200 bg-red-100 px-2 py-0.5 text-[10px] font-black text-red-700">
                                            <x-heroicon-o-lock-closed class="h-3 w-3" />
                                            Terkunci
                                        </span>
                                    </div>
                                </div>

                                {{-- Lab Badge --}}
                                <div class="mb-3">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-lg border-2 border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-black text-indigo-700">
                                        <x-heroicon-o-building-office class="h-3 w-3" />
                                        <span x-text="item.lab_name"></span>
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h3 class="mb-2 line-clamp-2 text-sm font-black text-gray-800 leading-relaxed group-hover:text-emerald-700 transition-colors"
                                    x-text="item.nama_judul">
                                </h3>

                                {{-- Description --}}
                                <p class="mb-4 line-clamp-2 text-xs text-gray-500 leading-relaxed"
                                    x-text="item.deskripsi">
                                </p>

                                {{-- Stats --}}
                                <div class="mb-4 flex items-center gap-3 border-t-2 border-gray-100 pt-3">
                                    <span class="flex items-center gap-1 text-xs font-semibold text-gray-500">
                                        <x-heroicon-o-users class="h-3.5 w-3.5" />
                                        <span x-text="item.total_peminat"></span> peminat
                                    </span>
                                    <span x-show="item.jumlah_ditetapkan > 0"
                                        class="flex items-center gap-1 text-xs font-black text-emerald-600">
                                        <x-heroicon-o-check class="h-3.5 w-3.5" />
                                        <span x-text="item.jumlah_ditetapkan"></span> ditetapkan
                                    </span>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center justify-end gap-1.5 border-t-2 border-gray-100 pt-3">

                                    {{-- Ajukan ke Ka Lab (draft / ditolak) --}}
                                    <form x-show="item.status_judul === 'draft' || item.status_judul === 'ditolak_kalab'"
                                        method="POST" x-bind:action="'/dosen/judul/' + item.id + '/ajukan'">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-xl border-2 border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-black text-emerald-700 transition hover:bg-emerald-100">
                                            <x-heroicon-o-paper-airplane class="h-3.5 w-3.5" />
                                            <span x-text="item.status_judul === 'ditolak_kalab' ? 'Ajukan Ulang' : 'Ajukan'"></span>
                                        </button>
                                    </form>

                                    {{-- Tarik kembali (menunggu validasi) --}}
                                    <form x-show="item.status_judul === 'pending_kalab'"
                                        method="POST" x-bind:action="'/dosen/judul/' + item.id + '/tarik'">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-xl border-2 border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-black text-amber-700 transition hover:bg-amber-100">
                                            <x-heroicon-o-arrow-uturn-left class="h-3.5 w-3.5" />
                                            Tarik
                                        </button>
                                    </form>

                                    {{-- Edit (draft / ditolak) --}}
                                    <button x-show="item.status_judul === 'draft' || item.status_judul === 'ditolak_kalab'"
                                        @click="openEditModal(item)"
                                        class="inline-flex items-center gap-1 rounded-xl border-2 border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-black text-blue-700 transition hover:bg-blue-100">
                                        <x-heroicon-o-pencil-square class="h-3.5 w-3.5" />
                                        Edit
                                    </button>

                                    {{-- Hapus (selama belum ditawarkan & belum dikunci) --}}
                                    <button x-show="item.status_judul !== 'ditawarkan' && !item.is_locked"
                                        @click="confirmDelete(item.id)"
                                        class="inline-flex items-center gap-1 rounded-xl border-2 border-red-200 bg-red-50 px-3 py-1.5 text-xs font-black text-red-700 transition hover:bg-red-100">
                                        <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                    </button>

                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <template x-if="filteredData.length === 0">
                        <div class="col-span-full flex flex-col items-center justify-center py-24 text-center">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-emerald-100 bg-gradient-to-br from-emerald-50 to-teal-100 shadow-inner mb-6">
                                <x-heroicon-o-book-open class="h-12 w-12 text-emerald-300" />
                            </div>
                            <p class="text-lg font-extrabold text-gray-800">
                                <span x-show="searchQuery || filterLab !== 'all' || filterStatus !== 'all'">Tidak ada
                                    judul yang sesuai filter</span>
                                <span x-show="!searchQuery && filterLab === 'all' && filterStatus === 'all'">Belum ada
                                    judul</span>
                            </p>
                            <p class="mt-2 text-sm text-gray-400">
                                <span x-show="searchQuery || filterLab !== 'all' || filterStatus !== 'all'">Coba ubah
                                    filter atau kata kunci pencarian</span>
                                <span x-show="!searchQuery && filterLab === 'all' && filterStatus === 'all'">Mulai
                                    tambahkan judul TA untuk mahasiswa</span>
                            </p>
                            <button @click="openCreateModal()"
                                x-show="!searchQuery && filterLab === 'all' && filterStatus === 'all'"
                                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-emerald-700">
                                <x-heroicon-o-plus class="h-4 w-4" />
                                Tambah Judul Pertama
                            </button>
                        </div>
                    </template>

                </div>

            </div>
        </div>

        {{-- ===== CREATE/EDIT MODAL ===== --}}
        <div x-show="showModal" x-cloak @click.self="showModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl border-2 border-gray-200 bg-white shadow-2xl">

                <div
                    class="sticky top-0 z-10 flex items-center justify-between border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <template x-if="!editMode">
                                <x-heroicon-o-plus class="h-5 w-5 text-white" />
                            </template>
                            <template x-if="editMode">
                                <x-heroicon-o-pencil-square class="h-5 w-5 text-white" />
                            </template>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-white">
                                <span x-show="!editMode">Tambah Judul Baru</span>
                                <span x-show="editMode">Edit Judul</span>
                            </h3>
                            <p class="text-xs text-emerald-200">Isi semua field yang diperlukan</p>
                        </div>
                    </div>
                    <button @click="showModal = false"
                        class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20 text-white transition hover:bg-white/30">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                <form method="POST"
                    x-bind:action="editMode ? '/dosen/judul/' + selectedItem.id : '{{ route('dosen.judul.store') }}'"
                    class="p-6 space-y-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT" />
                    </template>

                    {{-- Laboratorium --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            Laboratorium <span class="text-red-500">*</span>
                        </label>
                        <select name="laboratorium_id" required x-model="formData.laboratorium_id"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach ($laboratorium as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ✅ fix: field name 'judul' sesuai controller --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            Nama Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" required maxlength="500" x-model="formData.judul"
                            placeholder="Contoh: Sistem Informasi Manajemen Perpustakaan"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                        <p class="mt-1 text-xs text-gray-400">Maksimal 500 karakter</p>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi" required rows="5" maxlength="1000" x-model="formData.deskripsi"
                            placeholder="Jelaskan latar belakang, tujuan, dan ruang lingkup penelitian..."
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 resize-none transition">
                    </textarea>
                        <p class="mt-1 text-xs text-gray-400">Maksimal 1000 karakter</p>
                    </div>

                    {{-- Aksi awal (hanya saat create) --}}
                    <div x-show="!editMode">
                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                            Simpan sebagai
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="flex items-center gap-3 rounded-xl border-2 border-gray-200 p-3 cursor-pointer transition hover:border-gray-300"
                                x-bind:class="formData.aksi === 'draft' ? 'border-gray-400 bg-gray-50' : ''">
                                <input type="radio" name="aksi" value="draft" x-model="formData.aksi"
                                    class="h-4 w-4 text-gray-600 border-gray-300 focus:ring-gray-500" />
                                <div>
                                    <p class="text-sm font-bold text-gray-700">Draft</p>
                                    <p class="text-xs text-gray-400">Simpan dulu, belum diajukan</p>
                                </div>
                            </label>
                            <label
                                class="flex items-center gap-3 rounded-xl border-2 border-gray-200 p-3 cursor-pointer transition hover:border-emerald-300"
                                x-bind:class="formData.aksi === 'ajukan' ? 'border-emerald-400 bg-emerald-50' : ''">
                                <input type="radio" name="aksi" value="ajukan" x-model="formData.aksi"
                                    class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" />
                                <div>
                                    <p class="text-sm font-bold text-emerald-700">Ajukan ke Ka Lab</p>
                                    <p class="text-xs text-gray-400">Dikirim untuk divalidasi</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 border-t-2 border-gray-100 pt-4">
                        <button type="button" @click="showModal = false"
                            class="flex-1 rounded-xl border-2 border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                            <template x-if="!editMode">
                                <x-heroicon-o-plus class="h-4 w-4" />
                            </template>
                            <template x-if="editMode">
                                <x-heroicon-o-check class="h-4 w-4" />
                            </template>
                            <span x-show="!editMode">Tambah Judul</span>
                            <span x-show="editMode">Simpan Perubahan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ===== DELETE MODAL ===== --}}
        <div x-show="showDeleteModal" x-cloak @click.self="showDeleteModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="w-full max-w-md rounded-2xl border-2 border-gray-200 bg-white shadow-2xl overflow-hidden">

                <div
                    class="flex items-center gap-3 border-b-4 border-red-200 bg-gradient-to-r from-red-600 to-rose-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-trash class="h-5 w-5 text-white" />
                    </div>
                    <h3 class="font-extrabold text-white">Hapus Judul?</h3>
                </div>

                <div class="p-6 space-y-5">
                    <div class="flex items-start gap-3 rounded-xl border-2 border-red-200 bg-red-50 p-4">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 shrink-0 text-red-500 mt-0.5" />
                        <p class="text-sm font-semibold text-red-700 leading-relaxed">
                            Apakah Anda yakin ingin menghapus judul ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="showDeleteModal = false"
                            class="flex-1 rounded-xl border-2 border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                            Batal
                        </button>
                        <form method="POST" x-bind:action="'/dosen/judul/' + deleteId" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                                <x-heroicon-o-trash class="h-4 w-4" />
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- ✅ closing x-data --}}

    <script>
        function judulPage() {
            return {
                allData: @json($judul),
                filteredData: [],
                searchQuery: '',
                filterLab: 'all',
                filterStatus: 'all',
                showModal: false,
                showDeleteModal: false,
                editMode: false,
                selectedItem: null,
                deleteId: null,
                formData: {
                    laboratorium_id: '',
                    judul: '',
                    deskripsi: '',
                    aksi: 'draft',
                },

                init() {
                    this.allData = this.allData.map(item => ({
                        id: item.id,
                        kode: item.kode ?? '-',
                        nama_judul: item.nama_judul ?? item.judul ?? '-',
                        deskripsi: item.deskripsi ?? '',
                        status_judul: item.status_judul ?? 'draft',
                        is_locked: item.is_locked ?? false,
                        lab_id: item.laboratorium_id,
                        lab_name: item.lab_name ?? 'N/A',
                        total_peminat: item.total_peminat ?? 0,
                        jumlah_ditetapkan: item.jumlah_ditetapkan ?? 0,
                    }));
                    this.applyFilter();
                },

                applyFilter() {
                    let result = this.allData;

                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        result = result.filter(item =>
                            item.nama_judul.toLowerCase().includes(q) ||
                            item.deskripsi.toLowerCase().includes(q) ||
                            (item.kode && item.kode.toLowerCase().includes(q))
                        );
                    }

                    if (this.filterLab !== 'all') {
                        result = result.filter(item => item.lab_id == this.filterLab);
                    }

                    if (this.filterStatus !== 'all') {
                        result = result.filter(item => item.status_judul === this.filterStatus);
                    }

                    this.filteredData = result;
                },

                openCreateModal() {
                    this.editMode = false;
                    this.selectedItem = null;
                    this.formData = {
                        laboratorium_id: '',
                        judul: '',
                        deskripsi: '',
                        aksi: 'draft',
                    };
                    this.showModal = true;
                },

                openEditModal(item) {
                    this.editMode = true;
                    this.selectedItem = item;
                    this.formData = {
                        laboratorium_id: item.lab_id,
                        judul: item.nama_judul,
                        deskripsi: item.deskripsi,
                        aksi: 'draft',
                    };
                    this.showModal = true;
                },

                confirmDelete(id) {
                    this.deleteId = id;
                    this.showDeleteModal = true;
                }
            }
        }
    </script>

</x-layout-dosen>
