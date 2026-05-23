<x-layout-koor-ta title="Detail Pengumuman">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- TOP BAR --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('koor-ta.pengumuman.index') }}"
                            class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-600">
                            <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
                        </a>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Detail Pengumuman</h1>
                            <p class="mt-0.5 text-xs text-gray-400">{{ $pengumuman->nama_periode }}</p>
                        </div>
                    </div>

                    {{-- Broadcast button --}}
                    @if (!$pengumuman->dikirim_at)
                        <form method="POST" action="{{ route('koor-ta.pengumuman.broadcast', $pengumuman->id) }}"
                            onsubmit="return confirm('Kirim pengumuman ini ke semua mahasiswa? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-4 py-2 text-xs font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                                <x-heroicon-o-paper-airplane class="h-3.5 w-3.5" />
                                Kirim ke Semua Mahasiswa
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                    class="flex items-center gap-3 rounded-2xl border-2 border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                    <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-green-500" />
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mx-auto max-w-3xl space-y-6">

                {{-- Detail Card --}}
                <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                    <div
                        class="border-b-4 {{ $pengumuman->dikirim_at ? 'border-emerald-200 bg-gradient-to-r from-emerald-600 to-green-700' : 'border-indigo-200 bg-gradient-to-r from-indigo-700 to-blue-700' }} px-6 py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                                    <x-heroicon-o-megaphone class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-white">{{ $pengumuman->judul }}</h2>
                                    <p class="text-xs text-white/70 mt-0.5">{{ $pengumuman->nama_periode }}</p>
                                </div>
                            </div>
                            @if ($pengumuman->dikirim_at)
                                <span
                                    class="shrink-0 inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                    Terkirim
                                </span>
                            @else
                                <span
                                    class="shrink-0 inline-flex items-center gap-1.5 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-xs font-black text-white">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-300"></span>
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Meta info --}}
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Periode</p>
                                <p class="mt-1 text-sm font-black text-gray-800">{{ $pengumuman->nama_periode }}</p>
                            </div>
                            <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Dibuat Oleh</p>
                                <p class="mt-1 text-sm font-black text-gray-800">{{ $pengumuman->nama_pembuat }}</p>
                            </div>
                            <div class="rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Dibuat</p>
                                <p class="mt-1 text-sm font-black text-gray-800">
                                    {{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($pengumuman->created_at)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        {{-- Status kirim --}}
                        @if ($pengumuman->dikirim_at)
                            <div class="rounded-xl border-2 border-emerald-200 bg-emerald-50 px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-check-circle class="h-5 w-5 text-emerald-500 shrink-0" />
                                    <div>
                                        <p class="text-sm font-black text-emerald-700">Pengumuman sudah dikirim</p>
                                        <p class="text-xs text-emerald-600 mt-0.5">
                                            Dikirim pada
                                            {{ \Carbon\Carbon::parse($pengumuman->dikirim_at)->format('d M Y, H:i') }}
                                            WIB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="rounded-xl border-2 border-yellow-200 bg-yellow-50 px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-clock class="h-5 w-5 text-yellow-500 shrink-0" />
                                    <div>
                                        <p class="text-sm font-black text-yellow-700">Belum dikirim (Draft)</p>
                                        <p class="text-xs text-yellow-600 mt-0.5">
                                            Klik tombol "Kirim ke Semua Mahasiswa" untuk broadcast pengumuman ini
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Divider --}}
                        <div class="flex items-center gap-3">
                            <div class="h-px flex-1 bg-gray-200"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Isi
                                Pengumuman</span>
                            <div class="h-px flex-1 bg-gray-200"></div>
                        </div>

                        {{-- Isi --}}
                        <div class="rounded-xl border-2 border-gray-200 bg-gray-50 px-5 py-4">
                            <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">{{ $pengumuman->isi }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between border-t-2 border-gray-100 pt-5">
                            <a href="{{ route('koor-ta.pengumuman.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border-2 border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 transition hover:bg-gray-50">
                                <x-heroicon-o-arrow-left class="h-4 w-4" />
                                Kembali
                            </a>

                            @if (!$pengumuman->dikirim_at)
                                <div class="flex items-center gap-3">
                                    {{-- Hapus --}}
                                    <form method="POST"
                                        action="{{ route('koor-ta.pengumuman.destroy', $pengumuman->id) }}"
                                        onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl border-2 border-red-300 bg-red-600 px-4 py-2 text-sm font-black text-white shadow-sm transition hover:bg-red-700">
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                            Hapus Draft
                                        </button>
                                    </form>

                                    {{-- Broadcast --}}
                                    <form method="POST"
                                        action="{{ route('koor-ta.pengumuman.broadcast', $pengumuman->id) }}"
                                        onsubmit="return confirm('Kirim pengumuman ini ke semua mahasiswa? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-4 py-2 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                                            <x-heroicon-o-paper-airplane class="h-4 w-4" />
                                            Kirim ke Semua Mahasiswa
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-layout-koor-ta>
