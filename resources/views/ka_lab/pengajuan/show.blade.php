<x-layout-kalab>
    <x-slot name="title">Detail Pengajuan</x-slot>

    <div x-data="{
        activeJudul: null,
        catatanApprove: '',
        showReject: false,
        catatanReject: ''
    }" class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-5">

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

            {{-- ===== 1. INFO MAHASISWA ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-sky-200 bg-gradient-to-r from-sky-700 to-blue-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-user class="h-5 w-5 text-white" />
                    </div>
                    <h2 class="font-extrabold text-white">Informasi Mahasiswa</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-xl font-black text-white shadow-md ring-2 ring-sky-200">
                            {{ strtoupper(substr($pengajuan->mahasiswa->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-base font-black text-gray-900">{{ $pengajuan->mahasiswa->name }}</p>
                            <p class="text-sm text-gray-500">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $pengajuan->mahasiswa->email }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span
                                class="rounded-lg border-2 border-violet-200 bg-violet-50 px-3 py-1.5 text-xs font-black text-violet-700">
                                {{ $pengajuan->periode->nama ?? '-' }}
                            </span>
                            <p class="mt-1 text-xs text-gray-400">{{ $pengajuan->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Progress --}}
                    @php $pct = $pengajuan->progress_percentage; @endphp
                    <div class="mt-4 rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Progress</span>
                            <span class="text-xs font-black text-gray-800">{{ round($pct) }}% —
                                {{ $pengajuan->current_step }}</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                            <div class="h-full rounded-full transition-all duration-700
                                {{ $pct >= 100 ? 'bg-gradient-to-r from-emerald-500 to-green-500' : ($pct >= 50 ? 'bg-gradient-to-r from-sky-500 to-blue-500' : 'bg-gradient-to-r from-yellow-400 to-orange-500') }}"
                                style="width: {{ $pct }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== 2. STATUS REVIEW ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-gray-200 bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-white" />
                    </div>
                    <h2 class="font-extrabold text-white">Status Review</h2>
                </div>
                <div class="grid grid-cols-1 divide-y-2 divide-gray-100 sm:grid-cols-2 sm:divide-x-2 sm:divide-y-0">

                    {{-- Ka Lab --}}
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black uppercase tracking-widest text-gray-400">Ka Lab</span>
                            @if ($pengajuan->status_kalab === 'disetujui')
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Disetujui
                                </span>
                            @elseif ($pengajuan->status_kalab === 'ditolak')
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Ditolak
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1 text-xs font-black text-yellow-700">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>Menunggu
                                </span>
                            @endif
                        </div>
                        @if ($pengajuan->reviewerKalab)
                            <p class="text-sm font-semibold text-gray-700">{{ $pengajuan->reviewerKalab->name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $pengajuan->tanggal_review_kalab?->format('d M Y, H:i') }} WIB</p>
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
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Disetujui
                                </span>
                            @elseif ($pengajuan->status_kaprodi === 'ditolak')
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-3 py-1 text-xs font-black text-red-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Ditolak
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-gray-200 bg-gray-100 px-3 py-1 text-xs font-black text-gray-500">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>Belum
                                </span>
                            @endif
                        </div>
                        @if ($pengajuan->reviewerKaprodi)
                            <p class="text-sm font-semibold text-gray-700">{{ $pengajuan->reviewerKaprodi->name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $pengajuan->tanggal_review_kaprodi?->format('d M Y, H:i') }} WIB</p>
                        @endif
                        @if ($pengajuan->catatan_kaprodi)
                            <div class="mt-2 rounded-lg border-2 border-gray-100 bg-gray-50 px-3 py-2">
                                <p class="text-xs italic text-gray-500">"{{ $pengajuan->catatan_kaprodi }}"</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
            {{-- ===== 3. PILIHAN JUDUL + KEPUTUSAN ===== --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">
                <div
                    class="flex items-center gap-3 border-b-4 border-blue-200 bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                        <x-heroicon-o-list-bullet class="h-5 w-5 text-white" />
                    </div>
                    <div class="flex-1">
                        <h2 class="font-extrabold text-white">Pilihan Judul Mahasiswa</h2>
                        @if ($pengajuan->canBeReviewedByKalab())
                            <p class="text-xs text-blue-200">Klik "Tetapkan" pada judul yang ingin disetujui</p>
                        @endif
                    </div>
                </div>

                <div class="p-5 space-y-4">

                    {{-- Judul Mandiri --}}
                    @if ($pengajuan->judul_mandiri)
                        @php $isMandiriDitetapkan = $pengajuan->sumber_judul === 'mandiri' && $pengajuan->status_kalab === 'disetujui'; @endphp
                        <div
                            class="rounded-xl border-2 {{ $isMandiriDitetapkan ? 'border-orange-300 bg-orange-50' : 'border-orange-200 bg-orange-50/50' }} overflow-hidden">
                            <div class="p-4">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-orange-500 text-xs font-black text-white">M</span>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="text-xs font-black text-orange-600 uppercase tracking-wide">Judul
                                                Mandiri</span>
                                            @if ($isMandiriDitetapkan)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-orange-500 px-2 py-0.5 text-[10px] font-black text-white">
                                                    <x-heroicon-o-check class="h-3 w-3" />
                                                    Ditetapkan
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed">
                                            {{ $pengajuan->judul_mandiri }}</p>
                                        @if ($pengajuan->deskripsi_mandiri)
                                            <p class="mt-1 text-xs text-gray-500 leading-relaxed">
                                                {{ $pengajuan->deskripsi_mandiri }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Tombol Tetapkan Mandiri --}}
                                @if ($pengajuan->canBeReviewedByKalab())
                                    <div class="mt-3 border-t-2 border-orange-100 pt-3">
                                        <button type="button"
                                            @click="activeJudul = (activeJudul === 'mandiri' ? null : 'mandiri'); catatanApprove = ''"
                                            x-bind:class="activeJudul === 'mandiri'
                                                ?
                                                'bg-orange-600 border-orange-300 text-white' :
                                                'bg-white border-orange-300 text-orange-600 hover:bg-orange-50'"
                                            class="inline-flex items-center gap-2 rounded-xl border-2 px-4 py-2 text-xs font-black transition">
                                            <x-heroicon-o-check-circle class="h-4 w-4" />
                                            <span
                                                x-text="activeJudul === 'mandiri' ? 'Batal' : 'Tetapkan Judul Ini'"></span>
                                        </button>
                                    </div>

                                    {{-- Form approve mandiri --}}
                                    <div x-show="activeJudul === 'mandiri'" x-transition class="mt-3">
                                        <form method="POST"
                                            action="{{ route('ka-lab.pengajuan.approve', $pengajuan->id) }}"
                                            class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="judul_terpilih" value="mandiri" />
                                            {{-- ✅ Dropdown pilih laboratorium --}}
                                            <div>
                                                <label class="mb-1 block text-xs font-bold text-gray-600">
                                                    Laboratorium <span class="text-red-500">*</span>
                                                </label>
                                                <select name="laboratorium_id" required
                                                    class="w-full rounded-xl border-2 border-gray-200 px-3 py-2 text-sm text-gray-800 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 transition">
                                                    <option value="">-- Pilih Laboratorium --</option>
                                                    @foreach ($laboratorium as $lab)
                                                        <option value="{{ $lab->id }}">{{ $lab->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-bold text-gray-600">Catatan <span
                                                        class="text-gray-400 font-normal">(opsional)</span></label>
                                                <textarea name="catatan_kalab" x-model="catatanApprove" rows="2" placeholder="Tambahkan catatan..."
                                                    class="w-full rounded-xl border-2 border-gray-200 px-3 py-2 text-sm text-gray-800 placeholder-gray-400 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 resize-none transition">
                                                </textarea>
                                            </div>
                                            <button type="submit"
                                                class="w-full rounded-xl border-2 border-orange-300 bg-orange-500 px-4 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-orange-600 hover:shadow-md">
                                                Konfirmasi — Tetapkan Judul Mandiri
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- 3 Pilihan Judul --}}
                    @foreach ([['value' => 'pilihan_1', 'label' => 'Pilihan 1', 'judul' => $pengajuan->pilihan1, 'alasan' => $pengajuan->alasan_1, 'color' => 'emerald'], ['value' => 'pilihan_2', 'label' => 'Pilihan 2', 'judul' => $pengajuan->pilihan2, 'alasan' => $pengajuan->alasan_2, 'color' => 'sky'], ['value' => 'pilihan_3', 'label' => 'Pilihan 3', 'judul' => $pengajuan->pilihan3, 'alasan' => $pengajuan->alasan_3, 'color' => 'violet']] as $p)
                        @if ($p['judul'])
                            @php
                                $isDitetapkan = $pengajuan->judul_ditetapkan_id === $p['judul']->id;
                                $statusPilihan = $pilihanStatus[$p['value']] ?? null;
                                $sudahDiambil = $statusPilihan && $statusPilihan['diambil'];
                            @endphp
                            <div
                                class="rounded-xl border-2 overflow-hidden
                                {{ $isDitetapkan
                                    ? 'border-' . $p['color'] . '-300 bg-' . $p['color'] . '-50'
                                    : ($sudahDiambil
                                        ? 'border-gray-300 bg-gray-100'
                                        : 'border-gray-200 bg-gray-50/50') }}">
                                <div class="p-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-black text-white
                                            {{ $sudahDiambil ? 'bg-gray-400' : 'bg-' . $p['color'] . '-600' }}">
                                            {{ substr($p['label'], -1) }}
                                        </span>
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <span
                                                    class="text-xs font-black uppercase tracking-wide
                                                    {{ $sudahDiambil ? 'text-gray-400' : 'text-gray-500' }}">
                                                    {{ $p['label'] }}
                                                </span>
                                                @if ($isDitetapkan)
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-{{ $p['color'] }}-600 px-2 py-0.5 text-[10px] font-black text-white">
                                                        <x-heroicon-o-check class="h-3 w-3" />
                                                        Ditetapkan
                                                    </span>
                                                @endif
                                                @if ($sudahDiambil)
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full border-2 border-red-200 bg-red-100 px-2 py-0.5 text-[10px] font-black text-red-700">
                                                        <x-heroicon-o-lock-closed class="h-3 w-3" />
                                                        Sudah Diambil
                                                    </span>
                                                @endif
                                            </div>

                                            <p
                                                class="text-sm font-bold leading-relaxed {{ $sudahDiambil ? 'text-gray-400 line-through' : 'text-gray-800' }}">
                                                {{ $p['judul']->nama_judul }}
                                            </p>
                                            <div
                                                class="mt-1.5 flex flex-wrap gap-3 text-xs {{ $sudahDiambil ? 'text-gray-400' : 'text-gray-500' }}">
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
                                                <div class="mt-2 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                                    <p
                                                        class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-0.5">
                                                        Alasan</p>
                                                    <p class="text-xs text-gray-600 leading-relaxed">
                                                        {{ $p['alasan'] }}</p>
                                                </div>
                                            @endif

                                            {{-- Info mahasiswa yang sudah mengambil --}}
                                            @if ($sudahDiambil)
                                                <div
                                                    class="mt-3 rounded-xl border-2 border-red-200 bg-red-50 px-3 py-3">
                                                    <p
                                                        class="text-xs font-black uppercase tracking-widest text-red-500 mb-2">
                                                        Sudah ditetapkan untuk:
                                                    </p>
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-red-400 to-rose-500 text-xs font-black text-white">
                                                            {{ strtoupper(substr($statusPilihan['nama'], 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-black text-gray-800">
                                                                {{ $statusPilihan['nama'] }}</p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $statusPilihan['nim'] }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="mt-1.5 text-xs text-red-600 font-semibold">
                                                        Pilih judul alternatif lainnya untuk mahasiswa ini.
                                                    </p>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    {{-- Tombol Tetapkan per Pilihan --}}
                                    @if ($pengajuan->canBeReviewedByKalab())
                                        <div
                                            class="mt-3 border-t-2 {{ $sudahDiambil ? 'border-gray-200' : 'border-gray-100' }} pt-3">
                                            @if ($sudahDiambil)
                                                {{-- Disabled — sudah diambil --}}
                                                <span
                                                    class="inline-flex items-center gap-2 rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-2 text-xs font-black text-gray-400 cursor-not-allowed">
                                                    <x-heroicon-o-lock-closed class="h-4 w-4" />
                                                    Tidak Tersedia
                                                </span>
                                            @else
                                                <button type="button"
                                                    @click="activeJudul = (activeJudul === '{{ $p['value'] }}' ? null : '{{ $p['value'] }}'); catatanApprove = ''"
                                                    x-bind:class="activeJudul === '{{ $p['value'] }}'
                                                        ?
                                                        'bg-{{ $p['color'] }}-600 border-{{ $p['color'] }}-300 text-white' :
                                                        'bg-white border-{{ $p['color'] }}-300 text-{{ $p['color'] }}-600 hover:bg-{{ $p['color'] }}-50'"
                                                    class="inline-flex items-center gap-2 rounded-xl border-2 px-4 py-2 text-xs font-black transition">
                                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                                    <span
                                                        x-text="activeJudul === '{{ $p['value'] }}' ? 'Batal' : 'Tetapkan Judul Ini'"></span>
                                                </button>
                                            @endif
                                        </div>

                                        {{-- Form approve per pilihan (hanya kalau tidak diambil) --}}
                                        @if (!$sudahDiambil)
                                            <div x-show="activeJudul === '{{ $p['value'] }}'" x-transition
                                                class="mt-3">
                                                <form method="POST"
                                                    action="{{ route('ka-lab.pengajuan.approve', $pengajuan->id) }}"
                                                    class="space-y-3">
                                                    @csrf
                                                    <input type="hidden" name="judul_terpilih"
                                                        value="{{ $p['value'] }}" />
                                                    <div>
                                                        <label class="mb-1 block text-xs font-bold text-gray-600">
                                                            Catatan <span
                                                                class="text-gray-400 font-normal">(opsional)</span>
                                                        </label>
                                                        <textarea name="catatan_kalab" x-model="catatanApprove" rows="2"
                                                            placeholder="Tambahkan catatan persetujuan..."
                                                            class="w-full rounded-xl border-2 border-gray-200 px-3 py-2 text-sm text-gray-800 placeholder-gray-400 focus:border-{{ $p['color'] }}-400 focus:outline-none focus:ring-2 focus:ring-{{ $p['color'] }}-100 resize-none transition">
                                                        </textarea>
                                                    </div>
                                                    <button type="submit"
                                                        class="w-full rounded-xl border-2 border-{{ $p['color'] }}-300 bg-{{ $p['color'] }}-600 px-4 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-{{ $p['color'] }}-700 hover:shadow-md">
                                                        Konfirmasi — Tetapkan {{ $p['label'] }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- ===== PERINGATAN: SEMUA PILIHAN SUDAH DIAMBIL ===== --}}
                    @if ($pengajuan->canBeReviewedByKalab() && $semuaPilihanDiambil)
                        <div class="rounded-xl border-2 border-orange-300 bg-orange-50 p-4">
                            <div class="flex items-start gap-3">
                                <x-heroicon-o-exclamation-triangle class="h-5 w-5 shrink-0 text-orange-500 mt-0.5" />
                                <div>
                                    <p class="text-sm font-black text-orange-700">Semua Pilihan Sudah Diambil</p>
                                    <p class="text-xs text-orange-600 mt-1 leading-relaxed">
                                        Semua judul yang dipilih mahasiswa ini sudah ditetapkan untuk mahasiswa lain.
                                        Pengajuan ini perlu ditolak agar mahasiswa dapat mengajukan ulang dengan pilihan
                                        judul yang berbeda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif


                    {{-- ===== TOMBOL TOLAK (terpisah di bawah semua pilihan) ===== --}}
                    @if ($pengajuan->canBeReviewedByKalab())
                        <div class="rounded-xl border-2 border-red-200 bg-red-50/50 overflow-hidden">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-black text-red-700">Tolak Semua Pilihan</p>
                                        <p class="text-xs text-red-500 mt-0.5">Pengajuan akan ditolak dan mahasiswa
                                            perlu mengajukan ulang</p>
                                    </div>
                                    <button type="button"
                                        @click="showReject = !showReject; catatanReject = ''; activeJudul = null"
                                        x-bind:class="showReject
                                            ?
                                            'bg-red-600 border-red-300 text-white' :
                                            'bg-white border-red-300 text-red-600 hover:bg-red-50'"
                                        class="inline-flex items-center gap-2 rounded-xl border-2 px-4 py-2 text-xs font-black transition shrink-0">
                                        <x-heroicon-o-x-circle class="h-4 w-4" />
                                        <span x-text="showReject ? 'Batal' : 'Tolak Pengajuan'"></span>
                                    </button>
                                </div>

                                {{-- Form Reject --}}
                                <div x-show="showReject" x-transition class="mt-4 border-t-2 border-red-100 pt-4">
                                    <form method="POST"
                                        action="{{ route('ka-lab.pengajuan.reject', $pengajuan->id) }}"
                                        class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="mb-1 block text-xs font-bold text-gray-600">
                                                Alasan Penolakan <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="catatan_kalab" x-model="catatanReject" rows="3"
                                                placeholder="Jelaskan alasan penolakan pengajuan ini..."
                                                class="w-full rounded-xl border-2 border-gray-200 px-3 py-2 text-sm text-gray-800 placeholder-gray-400 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 resize-none transition">
                                            </textarea>
                                            @error('catatan_kalab')
                                                <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" x-bind:disabled="catatanReject.trim() === ''"
                                            x-bind:class="catatanReject.trim() === '' ? 'opacity-50 cursor-not-allowed' :
                                                'hover:bg-red-700 hover:shadow-md'"
                                            class="w-full rounded-xl border-2 border-red-300 bg-red-600 px-4 py-2.5 text-sm font-black text-white shadow-sm transition focus:outline-none">
                                            Konfirmasi Penolakan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Sudah diputuskan — tampilkan hasil --}}
                    @if (!$pengajuan->canBeReviewedByKalab() && $pengajuan->status_kalab)
                        <div
                            class="rounded-xl border-2 {{ $pengajuan->status_kalab === 'disetujui' ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }} p-4">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="rounded-lg border-2 border-white bg-white px-3 py-2.5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Status
                                    </p>
                                    @if ($pengajuan->status_kalab === 'disetujui')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-2.5 py-1 text-xs font-black text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Disetujui
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border-2 border-red-200 bg-red-100 px-2.5 py-1 text-xs font-black text-red-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Ditolak
                                        </span>
                                    @endif
                                </div>
                                <div class="rounded-lg border-2 border-white bg-white px-3 py-2.5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Reviewer
                                    </p>
                                    <p class="text-sm font-black text-gray-800">
                                        {{ $pengajuan->reviewerKalab->name ?? '-' }}</p>
                                    @if ($pengajuan->tanggal_review_kalab)
                                        <p class="text-xs text-gray-400">
                                            {{ $pengajuan->tanggal_review_kalab->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                                @if ($pengajuan->judulDitetapkan && $pengajuan->status_kalab === 'disetujui')
                                    <div class="rounded-lg border-2 border-emerald-200 bg-white px-3 py-2.5">
                                        <p class="text-xs font-bold uppercase tracking-widest text-emerald-500 mb-1">
                                            Judul Ditetapkan</p>
                                        <p class="text-sm font-bold text-gray-800 leading-relaxed line-clamp-2">
                                            {{ $pengajuan->judulDitetapkan->nama_judul }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $pengajuan->judulDitetapkan->dosen->name ?? '-' }}</p>
                                    </div>
                                @endif
                            </div>
                            @if ($pengajuan->catatan_kalab_pengajuan)
                                <div class="mt-3 rounded-lg border-2 border-white bg-white px-3 py-2.5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Catatan
                                    </p>
                                    <p class="text-sm italic text-gray-600">
                                        "{{ $pengajuan->catatan_kalab_pengajuan }}"</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

</x-layout-kalab>
