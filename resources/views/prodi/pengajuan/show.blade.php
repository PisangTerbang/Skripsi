<x-layout-prodi title="Detail Pengajuan">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-violet-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('prodi.pengajuan.index') }}"
                            class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-violet-400 hover:bg-violet-50 hover:text-violet-600">
                            <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                        </a>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Detail Pengajuan</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Review dan tentukan keputusan Kaprodi</p>
                        </div>
                    </div>
                    <a href="{{ route('prodi.pengajuan.riwayat') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700">
                        <x-heroicon-o-clock class="h-3.5 w-3.5" />
                        Lihat Riwayat
                    </a>
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

            <div class="space-y-6">

                {{-- Info Mahasiswa --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-violet-200 bg-gradient-to-r from-violet-700 to-purple-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-user class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="font-extrabold text-white">Informasi Mahasiswa</h2>
                            <p class="text-xs text-violet-200">Detail pengajuan dan progress saat ini</p>
                        </div>
                    </div>
                    <div class="p-5 space-y-5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-xl font-black text-white shadow-md ring-2 ring-violet-200">
                                {{ strtoupper(substr($pengajuan->mahasiswa->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-lg font-black text-gray-900">{{ $pengajuan->mahasiswa->name }}</p>
                                <p class="text-sm text-gray-500">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                                <p class="text-sm text-gray-400">{{ $pengajuan->mahasiswa->email }}</p>
                            </div>
                            <div class="flex flex-col gap-2 text-right">
                                <span
                                    class="rounded-lg border-2 border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-black text-violet-700">
                                    {{ $pengajuan->periode->nama ?? '-' }}
                                </span>
                                <p class="text-xs text-gray-400">{{ $pengajuan->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @php $pct = $pengajuan->progress_percentage; @endphp
                        <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Progress</span>
                                <span class="text-xs font-black text-gray-800">{{ round($pct) }}% — {{ $pengajuan->current_step }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                <div class="h-full rounded-full transition-all duration-700
                                    {{ $pct >= 100 ? 'bg-gradient-to-r from-emerald-500 to-green-500' : ($pct >= 50 ? 'bg-gradient-to-r from-sky-500 to-blue-500' : 'bg-gradient-to-r from-yellow-400 to-orange-500') }}"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Review --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-3 border-b-4 border-gray-200 bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="font-extrabold text-white">Status Review</h2>
                            <p class="text-xs text-gray-200">Status review oleh Ka Lab dan Kaprodi</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 divide-y-2 divide-gray-100 sm:grid-cols-2 sm:divide-x-2 sm:divide-y-0">

                        {{-- Ka Lab --}}
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-black uppercase tracking-widest text-gray-400">Ka Lab</span>
                                @if ($pengajuan->status_kalab === 'disetujui')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @elseif ($pengajuan->status_kalab === 'ditolak')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1 text-xs font-black text-yellow-700">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                        Menunggu
                                    </span>
                                @endif
                            </div>
                            @if ($pengajuan->reviewerKalab)
                                <p class="text-sm font-semibold text-gray-700">{{ $pengajuan->reviewerKalab->name }}</p>
                                <p class="text-xs text-gray-400">{{ $pengajuan->tanggal_review_kalab?->format('d M Y, H:i') }} WIB</p>
                            @endif
                            @if ($pengajuan->catatan_kalab_pengajuan)
                                <div class="mt-2 rounded-lg border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                    <p class="text-xs italic text-gray-500">"{{ $pengajuan->catatan_kalab_pengajuan }}"</p>
                                </div>
                            @endif
                        </div>

                        {{-- Kaprodi --}}
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-black uppercase tracking-widest text-gray-400">Kaprodi</span>
                                @if ($pengajuan->status_kaprodi === 'disetujui')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @elseif ($pengajuan->status_kaprodi === 'ditolak')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-gray-200 bg-gray-100 px-3 py-1 text-xs font-black text-gray-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Belum
                                    </span>
                                @endif
                            </div>
                            @if ($pengajuan->reviewerKaprodi)
                                <p class="text-sm font-semibold text-gray-700">{{ $pengajuan->reviewerKaprodi->name }}</p>
                                <p class="text-xs text-gray-400">{{ $pengajuan->tanggal_review_kaprodi?->format('d M Y, H:i') }} WIB</p>
                            @endif
                            @if ($pengajuan->catatan_kaprodi)
                                <div class="mt-2 rounded-lg border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                    <p class="text-xs italic text-gray-500">"{{ $pengajuan->catatan_kaprodi }}"</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                @if ($pengajuan->judulDitetapkan)
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 border-b-4 border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-check-badge class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Judul yang Ditetapkan</h2>
                        </div>
                        <div class="p-5">
                            <div class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-5">
                                <p class="font-black text-gray-900 leading-relaxed text-base">
                                    {{ $pengajuan->judulDitetapkan->nama_judul ?? $pengajuan->judulDitetapkan->judul }}
                                </p>
                                <div class="mt-4 flex flex-wrap gap-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-black text-blue-600">
                                            {{ strtoupper(substr($pengajuan->judulDitetapkan->dosen->name ?? 'D', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Dosen</p>
                                            <p class="text-sm font-bold text-gray-800">{{ $pengajuan->judulDitetapkan->dosen->name ?? '-' }}</p>
                                            @if ($pengajuan->judulDitetapkan->dosen)
                                                <p class="text-xs font-semibold text-gray-500">
                                                    Mahasiswa dibimbing:
                                                    <span class="font-black text-emerald-600">{{ $pengajuan->judulDitetapkan->dosen->jumlahBimbingan() }}</span> mahasiswa
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-100">
                                            <x-heroicon-o-building-office class="h-4 w-4 text-violet-600" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Lab</p>
                                            <p class="text-sm font-bold text-gray-800">{{ $pengajuan->judulDitetapkan->laboratorium->nama ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                    <div
                        class="flex items-center gap-2 border-b-4 border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-list-bullet class="h-5 w-5 text-white" />
                        </div>
                        <div class="flex-1">
                            <h2 class="font-extrabold text-white">Pilihan Judul Mahasiswa</h2>
                            @if ($pengajuan->canBeReviewedByKaprodi())
                                <p class="text-xs text-blue-200">Lihat dan konfirmasi keputusan Kaprodi di bawah</p>
                            @endif
                        </div>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach ([['label' => 'Pilihan 1', 'judul' => $pengajuan->pilihan1, 'alasan' => $pengajuan->alasan_1], ['label' => 'Pilihan 2', 'judul' => $pengajuan->pilihan2, 'alasan' => $pengajuan->alasan_2], ['label' => 'Pilihan 3', 'judul' => $pengajuan->pilihan3, 'alasan' => $pengajuan->alasan_3]] as $pilihan)
                            @if ($pilihan['judul'])
                                @php
                                    $isDitetapkan = $pengajuan->judul_ditetapkan_id === $pilihan['judul']->id;
                                @endphp
                                <div
                                    class="rounded-xl border-2 overflow-hidden {{ $isDitetapkan ? 'border-violet-300 bg-violet-50' : 'border-gray-200 bg-gray-50' }}">
                                    <div class="p-4">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-1">
                                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                                    <span
                                                        class="text-xs font-black uppercase tracking-wide {{ $isDitetapkan ? 'text-violet-600' : 'text-gray-500' }}">
                                                        {{ $pilihan['label'] }}
                                                    </span>
                                                    @if ($isDitetapkan)
                                                        <span
                                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-2.5 py-0.5 text-[10px] font-black text-white">
                                                            <x-heroicon-o-check-badge class="h-3.5 w-3.5" />
                                                            Judul disetujui Ka Lab
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm font-bold leading-relaxed {{ $isDitetapkan ? 'text-gray-700' : 'text-gray-800' }}">
                                                    {{ $pilihan['judul']->nama_judul ?? $pilihan['judul']->judul }}
                                                </p>
                                                <div class="mt-1.5 flex flex-wrap gap-3 text-xs text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <x-heroicon-o-academic-cap class="h-3.5 w-3.5" />
                                                        {{ $pilihan['judul']->dosen->name ?? '-' }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <x-heroicon-o-building-office class="h-3.5 w-3.5" />
                                                        {{ $pilihan['judul']->laboratorium->nama ?? '-' }}
                                                    </span>
                                                </div>
                                                @if ($pilihan['alasan'])
                                                    <div class="mt-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Alasan</p>
                                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $pilihan['alasan'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- Usulan mandiri: tak punya pilihan 1/2/3, tampilkan judul mandiri + lab tujuan --}}
                        @if ($pengajuan->jenis === 'mandiri')
                            <div class="overflow-hidden rounded-xl border-2 border-orange-200 bg-orange-50">
                                <div class="p-4">
                                    <div class="mb-2 flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-orange-500 px-2 py-0.5 text-[10px] font-black text-white">
                                            Usulan Mandiri
                                        </span>
                                        @if ($pengajuan->judul_ditetapkan_id)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-violet-600 px-2 py-0.5 text-[10px] font-black text-white">
                                                <x-heroicon-o-check class="h-3 w-3" /> Ditetapkan
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-bold leading-relaxed text-gray-800">
                                        {{ $pengajuan->judul_mandiri ?? '-' }}
                                    </p>
                                    <div class="mt-1.5 flex flex-wrap gap-3 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <x-heroicon-o-academic-cap class="h-3.5 w-3.5" />
                                            {{ $pengajuan->dosenPembimbing->name ?? '-' }} (pembimbing)
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <x-heroicon-o-building-office class="h-3.5 w-3.5" />
                                            {{ $pengajuan->labAktif->nama ?? 'Lab belum ditentukan' }}
                                        </span>
                                    </div>
                                    @if ($pengajuan->deskripsi_mandiri)
                                        <div class="mt-3 rounded-lg border border-orange-200 bg-white px-3 py-2">
                                            <p class="mb-1 text-xs font-bold uppercase tracking-wide text-orange-400">Deskripsi</p>
                                            <p class="text-xs leading-relaxed text-gray-600">{{ $pengajuan->deskripsi_mandiri }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($pengajuan->canBeReviewedByKaprodi())
                    <div x-data="{ action: null, catatan: '' }"
                        class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 border-b-4 border-violet-200 bg-gradient-to-r from-violet-700 to-purple-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-pencil-square class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Keputusan Kaprodi</h2>
                        </div>
                        <div class="p-5 space-y-4">

                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" @click="action = 'approve'"
                                    :class="action === 'approve' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-300' : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-400'"
                                    class="flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 text-sm font-black transition-all">
                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                    Setujui
                                </button>
                                <button type="button" @click="action = 'reject'"
                                    :class="action === 'reject' ? 'bg-red-600 text-white border-red-600 shadow-md ring-2 ring-red-300' : 'bg-white text-gray-600 border-gray-200 hover:border-red-400'"
                                    class="flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 text-sm font-black transition-all">
                                    <x-heroicon-o-x-circle class="h-4 w-4" />
                                    Tolak
                                </button>
                            </div>

                            <div x-show="action !== null" x-transition>
                                <form
                                    :action="action === 'approve' ? '{{ route('prodi.pengajuan.approve', $pengajuan->id) }}' : '{{ route('prodi.pengajuan.reject', $pengajuan->id) }}'"
                                    method="POST" class="space-y-4">
                                    @csrf

                                    <div>
                                        <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                            Catatan
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="catatan_kaprodi" x-model="catatan" rows="4"
                                            :placeholder="action === 'approve' ? 'Tambahkan catatan persetujuan (wajib)...' : 'Jelaskan alasan penolakan (wajib)...'"
                                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 resize-none transition"></textarea>
                                        @error('catatan_kaprodi')
                                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        :disabled="catatan.trim() === ''"
                                        :class="{
                                            'bg-emerald-600 hover:bg-emerald-700 border-emerald-300': action === 'approve',
                                            'bg-red-600 hover:bg-red-700 border-red-300': action === 'reject',
                                            'opacity-50 cursor-not-allowed': catatan.trim() === ''
                                        }"
                                        class="w-full rounded-xl border-2 px-4 py-3 text-sm font-black text-white shadow-sm transition hover:shadow-md focus:outline-none">
                                        <span x-text="action === 'approve' ? 'Konfirmasi Persetujuan' : 'Konfirmasi Penolakan'"></span>
                                    </button>
                                </form>
                            </div>

                            <div x-show="action === null"
                                class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-400">
                                Pilih tindakan di atas untuk melanjutkan
                            </div>
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 {{ $pengajuan->status_kaprodi === 'disetujui' ? 'border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700' : 'border-red-200 bg-gradient-to-r from-red-600 to-rose-700' }} px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                @if ($pengajuan->status_kaprodi === 'disetujui')
                                    <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                                @else
                                    <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                                @endif
                            </div>
                            <h2 class="font-extrabold text-white">Keputusan Kaprodi</h2>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-500">Status</span>
                                @if ($pengajuan->status_kaprodi === 'disetujui')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1.5 text-xs font-black text-red-700">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                            @if ($pengajuan->reviewerKaprodi)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-500">Reviewer</span>
                                    <span class="text-sm font-black text-gray-800">{{ $pengajuan->reviewerKaprodi->name }}</span>
                                </div>
                            @endif
                            @if ($pengajuan->tanggal_review_kaprodi)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-500">Tanggal</span>
                                    <div
                                        class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-1.5 text-center">
                                        <p class="text-sm font-black text-gray-700">
                                            {{ $pengajuan->tanggal_review_kaprodi->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $pengajuan->tanggal_review_kaprodi->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if ($pengajuan->catatan_kaprodi)
                                <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Catatan</p>
                                    <p class="text-sm italic text-gray-600">"{{ $pengajuan->catatan_kaprodi }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-layout-prodi>
