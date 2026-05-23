<x-layout-kalab>
    <x-slot name="title">Detail Pengajuan</x-slot>

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-sky-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('ka-lab.pengajuan.index') }}"
                            class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-sky-400 hover:bg-sky-50 hover:text-sky-600">
                            <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                        </a>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Detail Pengajuan</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Review dan tetapkan judul TA mahasiswa</p>
                        </div>
                    </div>
                    {{-- Status badge --}}
                    @if ($pengajuan->status_kalab === 'disetujui')
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-4 py-1.5 text-xs font-black text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Sudah Disetujui
                        </span>
                    @elseif ($pengajuan->status_kalab === 'ditolak')
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-4 py-1.5 text-xs font-black text-red-700">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            Ditolak
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-4 py-1.5 text-xs font-black text-yellow-700">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                            Menunggu Review
                        </span>
                    @endif
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
                            class="flex items-center gap-3 border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 to-blue-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-user class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Informasi Mahasiswa</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-xl font-black text-white shadow-md ring-2 ring-sky-200">
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
                                    <p class="mt-1 text-xs text-gray-400">
                                        Diajukan {{ $pengajuan->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Progress --}}
                            @php $pct = $pengajuan->progress_percentage; @endphp
                            <div class="mt-5 rounded-xl border-2 border-gray-100 bg-gray-50 p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Progress
                                        Pengajuan</span>
                                    <span class="text-sm font-black text-gray-800">{{ round($pct) }}%</span>
                                </div>
                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200">
                                    <div class="h-full rounded-full transition-all duration-700
                                        {{ $pct >= 100
                                            ? 'bg-gradient-to-r from-emerald-500 to-green-500'
                                            : ($pct >= 50
                                                ? 'bg-gradient-to-r from-sky-500 to-blue-500'
                                                : 'bg-gradient-to-r from-yellow-400 to-orange-500') }}"
                                        style="width: {{ $pct }}%">
                                    </div>
                                </div>
                                <p class="mt-2 text-xs font-semibold text-gray-500">{{ $pengajuan->current_step }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Pilihan Judul --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-3 border-b-4 border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-list-bullet class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Pilihan Judul Mahasiswa</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach ([['label' => 'Pilihan 1', 'key' => 'pilihan_1', 'judul' => $pengajuan->pilihan1, 'alasan' => $pengajuan->alasan_1, 'color' => 'emerald'], ['label' => 'Pilihan 2', 'key' => 'pilihan_2', 'judul' => $pengajuan->pilihan2, 'alasan' => $pengajuan->alasan_2, 'color' => 'sky'], ['label' => 'Pilihan 3', 'key' => 'pilihan_3', 'judul' => $pengajuan->pilihan3, 'alasan' => $pengajuan->alasan_3, 'color' => 'violet']] as $p)
                                @if ($p['judul'])
                                    @php
                                        $isDitetapkan = $pengajuan->judul_ditetapkan_id === $p['judul']->id;
                                    @endphp
                                    <div
                                        class="rounded-xl border-2 p-4 {{ $isDitetapkan ? 'border-' . $p['color'] . '-300 bg-' . $p['color'] . '-50' : 'border-gray-200 bg-gray-50' }}">
                                        <div class="flex items-start gap-3">
                                            <span
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-{{ $p['color'] }}-600 text-xs font-black text-white shadow-sm">
                                                {{ substr($p['label'], -1) }}
                                            </span>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span
                                                        class="text-xs font-black text-gray-500 uppercase tracking-wide">{{ $p['label'] }}</span>
                                                    @if ($isDitetapkan)
                                                        <span
                                                            class="flex items-center gap-1 rounded-full bg-{{ $p['color'] }}-600 px-2 py-0.5 text-[10px] font-black text-white">
                                                            <x-heroicon-o-check class="h-3 w-3" />
                                                            Ditetapkan
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm font-bold text-gray-800 leading-relaxed">
                                                    {{ $p['judul']->nama_judul }}
                                                </p>
                                                <div class="mt-2 flex flex-wrap gap-3 text-xs text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <x-heroicon-o-academic-cap class="h-3.5 w-3.5" />
                                                        {{ $p['judul']->dosen->name ?? '-' }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <x-heroicon-o-building-office class="h-3.5 w-3.5" />
                                                        {{ $p['judul']->laboratorium->nama ?? '-' }}
                                                    </span>
                                                </div>
                                                @if ($p['alasan'])
                                                    <div
                                                        class="mt-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                                        <p
                                                            class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">
                                                            Alasan</p>
                                                        <p class="text-xs text-gray-600 leading-relaxed">
                                                            {{ $p['alasan'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Judul Mandiri --}}
                            @if ($pengajuan->judul_mandiri)
                                <div class="rounded-xl border-2 border-orange-200 bg-orange-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-orange-500 text-xs font-black text-white shadow-sm">
                                            M
                                        </span>
                                        <div class="flex-1">
                                            <span class="text-xs font-black text-gray-500 uppercase tracking-wide">Judul
                                                Mandiri</span>
                                            <p class="mt-1 text-sm font-bold text-gray-800 leading-relaxed">
                                                {{ $pengajuan->judul_mandiri }}
                                            </p>
                                            @if ($pengajuan->deskripsi_mandiri)
                                                <p class="mt-2 text-xs text-gray-600 leading-relaxed">
                                                    {{ $pengajuan->deskripsi_mandiri }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
                {{-- ===== KOLOM KANAN (1/3) ===== --}}
                <div class="space-y-6">

                    {{-- Status Review --}}
                    <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                        <div
                            class="flex items-center gap-3 border-b-4 border-gray-200 bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-white" />
                            </div>
                            <h2 class="font-extrabold text-white">Status Review</h2>
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

                            {{-- Kaprodi --}}
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-xs font-black uppercase tracking-widest text-gray-400">Kaprodi</span>
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
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ $pengajuan->reviewerKaprodi->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $pengajuan->tanggal_review_kaprodi?->format('d M Y, H:i') }} WIB
                                    </p>
                                @endif
                                @if ($pengajuan->catatan_kaprodi)
                                    <div class="mt-2 rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                        <p class="text-xs italic text-gray-500">"{{ $pengajuan->catatan_kaprodi }}"
                                        </p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- Form Review Ka Lab --}}
                    @if ($pengajuan->canBeReviewedByKalab())
                        <div x-data="{ action: null, judulTerpilih: '', catatan: '' }"
                            class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                            <div
                                class="flex items-center gap-3 border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 to-blue-700 px-6 py-4">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                    <x-heroicon-o-pencil-square class="h-5 w-5 text-white" />
                                </div>
                                <h2 class="font-extrabold text-white">Keputusan Ka Lab</h2>
                            </div>
                            <div class="p-5 space-y-4">

                                {{-- Pilih Aksi --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" @click="action = 'approve'"
                                        x-bind:class="action === 'approve'
                                            ?
                                            'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-300' :
                                            'bg-white text-gray-600 border-gray-200 hover:border-emerald-400'"
                                        class="flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 text-sm font-black transition-all">
                                        <x-heroicon-o-check-circle class="h-4 w-4" />
                                        Setujui
                                    </button>
                                    <button type="button" @click="action = 'reject'"
                                        x-bind:class="action === 'reject'
                                            ?
                                            'bg-red-600 text-white border-red-600 shadow-md ring-2 ring-red-300' :
                                            'bg-white text-gray-600 border-gray-200 hover:border-red-400'"
                                        class="flex items-center justify-center gap-2 rounded-xl border-2 px-4 py-3 text-sm font-black transition-all">
                                        <x-heroicon-o-x-circle class="h-4 w-4" />
                                        Tolak
                                    </button>
                                </div>

                                {{-- Form Approve --}}
                                <div x-show="action === 'approve'" x-transition>
                                    <form method="POST"
                                        action="{{ route('ka-lab.pengajuan.approve', $pengajuan->id) }}"
                                        class="space-y-4">
                                        @csrf

                                        {{-- Pilih Judul --}}
                                        <div>
                                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                                Pilih Judul yang Ditetapkan <span class="text-red-500">*</span>
                                            </label>
                                            <div class="space-y-2">
                                                @foreach ([['value' => 'pilihan_1', 'label' => 'Pilihan 1', 'judul' => $pengajuan->pilihan1, 'color' => 'emerald'], ['value' => 'pilihan_2', 'label' => 'Pilihan 2', 'judul' => $pengajuan->pilihan2, 'color' => 'sky'], ['value' => 'pilihan_3', 'label' => 'Pilihan 3', 'judul' => $pengajuan->pilihan3, 'color' => 'violet']] as $opt)
                                                    @if ($opt['judul'])
                                                        <label
                                                            class="flex items-start gap-3 rounded-xl border-2 p-3 cursor-pointer transition-all
                                                            {{ old('judul_terpilih') === $opt['value'] ? 'border-' . $opt['color'] . '-400 bg-' . $opt['color'] . '-50' : 'border-gray-200 hover:border-' . $opt['color'] . '-300 hover:bg-gray-50' }}">
                                                            <input type="radio" name="judul_terpilih"
                                                                value="{{ $opt['value'] }}" x-model="judulTerpilih"
                                                                class="mt-0.5 h-4 w-4 text-{{ $opt['color'] }}-600 border-gray-300 focus:ring-{{ $opt['color'] }}-500" />
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2 mb-0.5">
                                                                    <span
                                                                        class="rounded-lg bg-{{ $opt['color'] }}-600 px-2 py-0.5 text-[10px] font-black text-white">
                                                                        {{ $opt['label'] }}
                                                                    </span>
                                                                </div>
                                                                <p
                                                                    class="text-xs font-semibold text-gray-800 leading-relaxed">
                                                                    {{ $opt['judul']->nama_judul }}
                                                                </p>
                                                                <p class="text-xs text-gray-400 mt-0.5">
                                                                    {{ $opt['judul']->dosen->name ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </label>
                                                    @endif
                                                @endforeach

                                                {{-- Judul Mandiri --}}
                                                @if ($pengajuan->judul_mandiri)
                                                    <label
                                                        class="flex items-start gap-3 rounded-xl border-2 border-gray-200 p-3 cursor-pointer transition-all hover:border-orange-300 hover:bg-orange-50">
                                                        <input type="radio" name="judul_terpilih" value="mandiri"
                                                            x-model="judulTerpilih"
                                                            class="mt-0.5 h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500" />
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2 mb-0.5">
                                                                <span
                                                                    class="rounded-lg bg-orange-500 px-2 py-0.5 text-[10px] font-black text-white">
                                                                    Mandiri
                                                                </span>
                                                            </div>
                                                            <p
                                                                class="text-xs font-semibold text-gray-800 leading-relaxed">
                                                                {{ $pengajuan->judul_mandiri }}
                                                            </p>
                                                        </div>
                                                    </label>
                                                @endif
                                            </div>
                                            @error('judul_terpilih')
                                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        {{-- Catatan --}}
                                        <div>
                                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                                Catatan
                                                <span class="text-gray-400 font-normal">(opsional)</span>
                                            </label>
                                            <textarea name="catatan_kalab" x-model="catatan" rows="3" placeholder="Tambahkan catatan persetujuan..."
                                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100 resize-none transition">
                                            </textarea>
                                        </div>

                                        <button type="submit" x-bind:disabled="judulTerpilih === ''"
                                            x-bind:class="judulTerpilih === '' ? 'opacity-50 cursor-not-allowed' :
                                                'hover:bg-emerald-700 hover:shadow-md'"
                                            class="w-full rounded-xl border-2 border-emerald-300 bg-emerald-600 px-4 py-3 text-sm font-black text-white shadow-sm transition focus:outline-none">
                                            Konfirmasi Persetujuan
                                        </button>

                                    </form>
                                </div>

                                {{-- Form Reject --}}
                                <div x-show="action === 'reject'" x-transition>
                                    <form method="POST"
                                        action="{{ route('ka-lab.pengajuan.reject', $pengajuan->id) }}"
                                        class="space-y-4">
                                        @csrf

                                        <div>
                                            <label class="mb-1.5 block text-sm font-bold text-gray-700">
                                                Alasan Penolakan <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="catatan_kalab" x-model="catatan" rows="4"
                                                placeholder="Jelaskan alasan penolakan pengajuan ini..."
                                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 resize-none transition">
                                            </textarea>
                                            @error('catatan_kalab')
                                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <button type="submit" x-bind:disabled="catatan.trim() === ''"
                                            x-bind:class="catatan.trim() === '' ? 'opacity-50 cursor-not-allowed' :
                                                'hover:bg-red-700 hover:shadow-md'"
                                            class="w-full rounded-xl border-2 border-red-300 bg-red-600 px-4 py-3 text-sm font-black text-white shadow-sm transition focus:outline-none">
                                            Konfirmasi Penolakan
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
                                class="flex items-center gap-3 border-b-4
                                {{ $pengajuan->status_kalab === 'disetujui'
                                    ? 'border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700'
                                    : 'border-red-200 bg-gradient-to-r from-red-600 to-rose-700' }}
                                px-6 py-4">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                    @if ($pengajuan->status_kalab === 'disetujui')
                                        <x-heroicon-o-check-circle class="h-5 w-5 text-white" />
                                    @else
                                        <x-heroicon-o-x-circle class="h-5 w-5 text-white" />
                                    @endif
                                </div>
                                <h2 class="font-extrabold text-white">Keputusan Ka Lab</h2>
                            </div>
                            <div class="p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-500">Status</span>
                                    @if ($pengajuan->status_kalab === 'disetujui')
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
                                @if ($pengajuan->reviewerKalab)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-500">Reviewer</span>
                                        <span
                                            class="text-sm font-black text-gray-800">{{ $pengajuan->reviewerKalab->name }}</span>
                                    </div>
                                @endif
                                @if ($pengajuan->tanggal_review_kalab)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-500">Tanggal</span>
                                        <div
                                            class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-1.5 text-center">
                                            <p class="text-sm font-black text-gray-700">
                                                {{ $pengajuan->tanggal_review_kalab->format('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $pengajuan->tanggal_review_kalab->format('H:i') }} WIB
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                @if ($pengajuan->judulDitetapkan && $pengajuan->status_kalab === 'disetujui')
                                    <div class="rounded-xl border-2 border-emerald-200 bg-emerald-50 p-3">
                                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-wide mb-1">
                                            Judul Ditetapkan</p>
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed">
                                            {{ $pengajuan->judulDitetapkan->nama_judul }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $pengajuan->judulDitetapkan->dosen->name ?? '-' }}
                                        </p>
                                    </div>
                                @endif
                                @if ($pengajuan->catatan_kalab_pengajuan)
                                    <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">
                                            Catatan</p>
                                        <p class="text-sm italic text-gray-600">
                                            "{{ $pengajuan->catatan_kalab_pengajuan }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-layout-kalab>
