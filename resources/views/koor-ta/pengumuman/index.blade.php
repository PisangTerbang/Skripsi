<x-layout-koor-ta title="Pengumuman">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-indigo-200 bg-indigo-50">
                            <x-heroicon-o-megaphone class="h-5 w-5 text-indigo-600" />
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Pengumuman</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Broadcast hasil pengajuan ke semua mahasiswa</p>
                        </div>
                    </div>
                    <a href="{{ route('koor-ta.pengumuman.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                        <x-heroicon-o-plus class="h-3.5 w-3.5" />
                        Buat Pengumuman
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

            {{-- Section Label --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-table-cells class="h-3 w-3" />
                    Daftar Pengumuman
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Filter Jenis --}}
            @php
                $tabs = [
                    'all' => ['label' => 'Semua', 'count' => $jumlah['all']],
                    'penetapan' => ['label' => 'Penetapan Judul', 'count' => $jumlah['penetapan']],
                    'biasa' => ['label' => 'Info Umum', 'count' => $jumlah['biasa']],
                ];
            @endphp
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($tabs as $key => $tab)
                    <a href="{{ route('koor-ta.pengumuman.index', $key === 'all' ? [] : ['jenis' => $key]) }}"
                        class="inline-flex items-center gap-2 rounded-xl border-2 px-4 py-2 text-xs font-black transition
                            {{ $jenis === $key
                                ? 'border-indigo-300 bg-indigo-600 text-white shadow-sm'
                                : 'border-gray-200 bg-white text-gray-600 hover:border-indigo-200 hover:bg-indigo-50' }}">
                        {{ $tab['label'] }}
                        <span class="rounded-full px-2 py-0.5 text-[10px] {{ $jenis === $key ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-500' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                {{-- Card Header --}}
                <div
                    class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-megaphone class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Semua Pengumuman</h2>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        {{ $pengumuman->count() }} pengumuman
                    </span>
                </div>

                @if ($pengumuman->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="relative mb-6">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-blue-100 shadow-inner">
                                <x-heroicon-o-megaphone class="h-12 w-12 text-indigo-300" />
                            </div>
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Belum ada pengumuman</p>
                        <p class="mt-2 text-sm text-gray-400">Buat pengumuman untuk dikirim ke semua mahasiswa</p>
                        <a href="{{ route('koor-ta.pengumuman.create') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-indigo-700">
                            <x-heroicon-o-plus class="h-4 w-4" />
                            Buat Pengumuman Pertama
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Judul</th>
                                    <th class="px-6 py-4">Periode</th>
                                    <th class="px-6 py-4">Dibuat Oleh</th>
                                    <th class="px-6 py-4">Dibuat</th>
                                    <th class="px-6 py-4">Status Kirim</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($pengumuman as $index => $item)
                                    <tr
                                        class="group transition-colors {{ $item->dikirim_at ? 'hover:bg-emerald-50/30' : 'hover:bg-indigo-50/30' }}">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full {{ $item->dikirim_at ? 'bg-gradient-to-b from-emerald-400 to-green-500' : 'bg-gradient-to-b from-indigo-400 to-blue-500' }}">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-indigo-300 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-all">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Judul --}}
                                        <td class="max-w-[240px] px-6 py-4">
                                            <div class="mb-1">
                                                @if ($item->tampilkan_hasil)
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-blue-200 bg-blue-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-wide text-blue-700">
                                                        <x-heroicon-o-clipboard-document-check class="h-3 w-3" /> Penetapan Judul
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-gray-200 bg-gray-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-wide text-gray-500">
                                                        <x-heroicon-o-megaphone class="h-3 w-3" /> Info Umum
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="font-bold text-gray-800 line-clamp-1">{{ $item->judul }}</p>
                                            <p class="mt-0.5 text-xs text-gray-400 line-clamp-1">{{ $item->isi }}</p>
                                        </td>

                                        {{-- Periode --}}
                                        <td class="px-6 py-4">
                                            <span
                                                class="rounded-lg border-2 border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-black text-indigo-700">
                                                {{ $item->nama_periode }}
                                            </span>
                                        </td>

                                        {{-- Dibuat Oleh --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-xs font-black text-white">
                                                    {{ strtoupper(substr($item->nama_pembuat, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="text-sm font-semibold text-gray-700">{{ $item->nama_pembuat }}</span>
                                            </div>
                                        </td>

                                        {{-- Dibuat --}}
                                        <td class="px-6 py-4">
                                            <div
                                                class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2 text-center">
                                                <p class="text-sm font-black text-gray-700">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Status Kirim --}}
                                        <td class="px-6 py-4">
                                            @if ($item->dikirim_at)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                    Terkirim
                                                </span>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($item->dikirim_at)->format('d M Y, H:i') }}
                                                </p>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-yellow-200 bg-yellow-100 px-3 py-1.5 text-xs font-black text-yellow-700">
                                                    <span
                                                        class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                                                    Draft
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">

                                                {{-- Detail --}}
                                                <a href="{{ route('koor-ta.pengumuman.show', $item->id) }}"
                                                    class="inline-flex items-center gap-1 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                                    <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                                    Detail
                                                </a>

                                                {{-- Broadcast --}}
                                                @if (!$item->dikirim_at)
                                                    <form method="POST"
                                                        action="{{ route('koor-ta.pengumuman.broadcast', $item->id) }}"
                                                        onsubmit="return confirm('Kirim pengumuman ini ke semua mahasiswa? Tindakan ini tidak dapat dibatalkan.')">
                                                        @csrf
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-xl border-2 border-emerald-300 bg-emerald-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                                                            <x-heroicon-o-paper-airplane class="h-3.5 w-3.5" />
                                                            Kirim
                                                        </button>
                                                    </form>

                                                    {{-- Hapus --}}
                                                    <form method="POST"
                                                        action="{{ route('koor-ta.pengumuman.destroy', $item->id) }}"
                                                        onsubmit="return confirm('Hapus pengumuman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-xl border-2 border-red-300 bg-red-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                                                            <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                        <p class="text-xs font-semibold text-gray-500">
                            Total <span class="font-black text-gray-800">{{ $pengumuman->count() }}</span> pengumuman
                        </p>
                        <div class="flex items-center gap-4 text-xs">
                            <span class="flex items-center gap-1.5 font-bold text-emerald-600">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                {{ $pengumuman->whereNotNull('dikirim_at')->count() }} terkirim
                            </span>
                            <div class="h-4 w-px bg-gray-300"></div>
                            <span class="flex items-center gap-1.5 font-bold text-yellow-600">
                                <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                {{ $pengumuman->whereNull('dikirim_at')->count() }} draft
                            </span>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-layout-koor-ta>
