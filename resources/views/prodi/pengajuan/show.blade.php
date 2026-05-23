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

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- ===== KOLOM KIRI (2/3) ===== --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- Info Mahasiswa --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 border-b-4 border-violet-200 bg-gradient-to-r from-violet-700 to-purple-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-user class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Informasi Mahasiswa</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-xl font-black text-white shadow-md ring-2 ring-violet-200">
                                    {{ strtoupper(substr($pengajuan->mahasiswa->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg font-black text-gray-900">{{ $pengajuan->mahasiswa->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                                    <p class="text-sm text-gray-400">{{ $pengajuan->mahasiswa->email }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="rounded-lg border-2 border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-black text-violet-700">
                                        {{ $pengajuan->periode->nama ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Judul Ditetapkan --}}
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
                            <div class="p-6">
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
                                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Dosen
                                                </p>
                                                <p class="text-sm font-bold text-gray-800">
                                                    {{ $pengajuan->judulDitetapkan->dosen->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-100">
                                                <x-heroicon-o-building-office class="h-4 w-4 text-violet-600" />
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Lab
                                                </p>
                                                <p class="text-sm font-bold text-gray-800">
                                                    {{ $pengajuan->judulDitetapkan->laboratorium->nama ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Semua Pilihan Judul --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 border-b-4 border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-list-bullet class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Semua Pilihan Judul</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach ([['label' => 'Pilihan 1', 'judul' => $pengajuan->pilihan1, 'alasan' => $pengajuan->alasan_1], ['label' => 'Pilihan 2', 'judul' => $pengajuan->pilihan2, 'alasan' => $pengajuan->alasan_2], ['label' => 'Pilihan 3', 'judul' => $pengajuan->pilihan3, 'alasan' => $pengajuan->alasan_3]] as $pilihan)
                                @if ($pilihan['judul'])
                                    @php
                                        $isDitetapkan = $pengajuan->judul_ditetapkan_id === $pilihan['judul']->id;
                                    @endphp
                                    <div
                                        class="rounded-xl border-2 p-4 {{ $isDitetapkan ? 'border-violet-300 bg-violet-50' : 'border-gray-200 bg-gray-50' }}">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span
                                                        class="rounded-lg px-2.5 py-1 text-xs font-black {{ $isDitetapkan ? 'bg-violet-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                        {{ $pilihan['label'] }}
                                                    </span>
                                                    @if ($isDitetapkan)
                                                        <span
                                                            class="flex items-center gap-1 text-xs font-black text-violet-600">
                                                            <x-heroicon-o-check-circle class="h-3.5 w-3.5" />
                                                            Ditetapkan
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm font-bold text-gray-800 leading-relaxed">
                                                    {{ $pilihan['judul']->nama_judul ?? $pilihan['judul']->judul }}
                                                </p>
                                                <div class="mt-2 flex flex-wrap gap-3 text-xs text-gray-500">
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
                                                    <div
                                                        class="mt-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                                        <p
                                                            class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">
                                                            Alasan</p>
                                                        <p class="text-xs text-gray-600 leading-relaxed">
                                                            {{ $pilihan['alasan'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- ===== KOLOM KANAN (1/3) ===== --}}
                <div class="space-y-6">

                    {{-- Progress --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-600 to-blue-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-chart-bar class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Progress</h2>
                        </div>
                        <div class="p-5 space-y-4">

                            {{-- Progress Bar --}}
                            @php $pct = $pengajuan->progress_percentage; @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-500">Kemajuan</span>
                                    <span class="text-sm font-black text-gray-800">{{ round($pct) }}%</span>
                                </div>
                                <div class="h-3 w-full overflow-hidden rounded-full bg-gray-200">
                                    <div class="h-full rounded-full transition-all duration-700
                                        {{ $pct >= 100
                                            ? 'bg-gradient-to-r from-emerald-500 to-green-500'
                                            : ($pct >= 50
                                                ? 'bg-gradient-to-r from-blue-500 to-indigo-500'
                                                : 'bg-gradient-to-r from-yellow-400 to-orange-500') }}"
                                        style="width: {{ $pct }}%">
                                    </div>
                                </div>
                                <p class="mt-2 text-xs font-semibold text-gray-500">{{ $pengajuan->current_step }}</p>
                            </div>

                            {{-- Steps --}}
                            <div class="space-y-3">
                                @php
                                    $steps = [
                                        ['label' => 'Pengajuan Masuk', 'done' => true],
                                        [
                                            'label' => 'Review Ka Lab',
                                            'done' => $pengajuan->status_kalab === 'disetujui',
                                        ],
                                        [
                                            'label' => 'Review Kaprodi',
                                            'done' => $pengajuan->status_kaprodi === 'disetujui',
                                        ],
                                    ];
                                @endphp
                                @foreach ($steps as $step)
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2
                                            {{ $step['done'] ? 'border-emerald-400 bg-emerald-100' : 'border-gray-300 bg-gray-100' }}">
                                            @if ($step['done'])
                                                <x-heroicon-o-check class="h-3.5 w-3.5 text-emerald-600" />
                                            @else
                                                <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                            @endif
                                        </div>
                                        <span
                                            class="text-sm {{ $step['done'] ? 'font-bold text-gray-800' : 'font-medium text-gray-400' }}">
                                            {{ $step['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    {{-- Riwayat Review --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-2 border-b-4 border-gray-200 bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Riwayat Review</h2>
                        </div>
                        <div class="divide-y-2 divide-gray-100">

                            {{-- Ka Lab --}}
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Ka
                                        Lab</span>
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
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ $pengajuan->reviewerKalab->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $pengajuan->tanggal_review_kalab?->format('d M Y, H:i') }} WIB
                                    </p>
                                @endif
                                @if ($pengajuan->catatan_kalab_pengajuan)
                                    <div class="mt-2 rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                        <p class="text-xs italic text-gray-500">
                                            "{{ $pengajuan->catatan_kalab_pengajuan }}"</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- Form Review Kaprodi --}}
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

                                {{-- Pilih Aksi --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" @click="action = 'approve'"
                                        :class="action === 'approve'
                                            ?
                                            'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-300' :
                                            'bg-white text-gray-600 border-gray-200 hover:border-emerald-400'"
                                        class="flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 text-sm font-black transition-all">
                                        <x-heroicon-o-check-circle class="h-4 w-4" />
                                        Setujui
                                    </button>
                                    <button type="button" @click="action = 'reject'"
                                        :class="action === 'reject'
                                            ?
                                            'bg-red-600 text-white border-red-600 shadow-md ring-2 ring-red-300' :
                                            'bg-white text-gray-600 border-gray-200 hover:border-red-400'"
                                        class="flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 text-sm font-black transition-all">
                                        <x-heroicon-o-x-circle class="h-4 w-4" />
                                        Tolak
                                    </button>
                                </div>

                                {{-- Form --}}
                                <div x-show="action !== null" x-transition>
                                    <form
                                        :action="action === 'approve'
                                            ?
                                            '{{ route('prodi.pengajuan.approve', $pengajuan->id) }}' :
                                            '{{ route('prodi.pengajuan.reject', $pengajuan->id) }}'"
                                        method="POST" class="space-y-4">
                                        @csrf

                                        <div>
                                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                                Catatan
                                                <span x-show="action === 'reject'" class="text-red-500">*</span>
                                                <span x-show="action === 'approve'"
                                                    class="text-gray-400 font-normal">(opsional)</span>
                                            </label>
                                            <textarea name="catatan_kaprodi" x-model="catatan" rows="4"
                                                :placeholder="action === 'approve'
                                                    ?
                                                    'Tambahkan catatan persetujuan (opsional)...' :
                                                    'Jelaskan alasan penolakan...'"
                                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-100 resize-none transition">
                                            </textarea>
                                            @error('catatan_kaprodi')
                                                <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                            :disabled="action === 'reject' && catatan.trim() === ''"
                                            :class="{
                                                'bg-emerald-600 hover:bg-emerald-700 border-emerald-300': action === 'approve',
                                                'bg-red-600 hover:bg-red-700 border-red-300': action === 'reject',
                                                'opacity-50 cursor-not-allowed': action === 'reject' && catatan
                                                .trim() === ''
                                            }"
                                            class="w-full rounded-xl border-2 px-4 py-3 text-sm font-black text-white shadow-sm transition hover:shadow-md focus:outline-none">
                                            <span
                                                x-text="action === 'approve' ? 'Konfirmasi Persetujuan' : 'Konfirmasi Penolakan'"></span>
                                        </button>

                                    </form>
                                </div>

                                {{-- Placeholder --}}
                                <div x-show="action === null"
                                    class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-400">
                                    Pilih tindakan di atas untuk melanjutkan
                                </div>

                            </div>
                        </div>
                    @else
                        {{-- Sudah diputuskan --}}
                        <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                            <div
                                class="flex items-center gap-2 border-b-4
                                {{ $pengajuan->status_kaprodi === 'disetujui' ? 'border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700' : 'border-red-200 bg-gradient-to-r from-red-600 to-rose-700' }}
                                px-6 py-4">
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
                                        <span
                                            class="text-sm font-black text-gray-800">{{ $pengajuan->reviewerKaprodi->name }}</span>
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
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">
                                            Catatan</p>
                                        <p class="text-sm italic text-gray-600">"{{ $pengajuan->catatan_kaprodi }}"
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

</x-layout-prodi>
