<x-layout-koor-ta title="Periode Pengajuan">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-indigo-200 bg-indigo-50">
                            <x-heroicon-o-calendar-days class="h-5 w-5 text-indigo-600" />
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">Periode Pengajuan</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Kelola periode buka/tutup pengajuan judul TA</p>
                        </div>
                    </div>
                    <a href="{{ route('koor-ta.periode.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                        <x-heroicon-o-plus class="h-3.5 w-3.5" />
                        Tambah Periode
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
                    Daftar Periode
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                {{-- Card Header --}}
                <div
                    class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-calendar-days class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Semua Periode</h2>
                            <p class="text-xs text-indigo-200">Hanya satu periode yang dapat aktif dalam satu waktu</p>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        {{ $periode->count() }} periode
                    </span>
                </div>

                @if ($periode->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="relative mb-6">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-blue-100 shadow-inner">
                                <x-heroicon-o-calendar-days class="h-12 w-12 text-indigo-300" />
                            </div>
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Belum ada periode</p>
                        <p class="mt-2 text-sm text-gray-400">Buat periode pertama untuk membuka pengajuan TA</p>
                        <a href="{{ route('koor-ta.periode.create') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-indigo-700">
                            <x-heroicon-o-plus class="h-4 w-4" />
                            Buat Periode Pertama
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Nama Periode</th>
                                    <th class="px-6 py-4">Tanggal Mulai</th>
                                    <th class="px-6 py-4">Tanggal Selesai</th>
                                    <th class="px-6 py-4">Total Pengajuan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($periode as $index => $item)
                                    <tr
                                        class="group transition-colors {{ $item->is_active ? 'bg-emerald-50/40 hover:bg-emerald-50/60' : 'hover:bg-indigo-50/30' }}">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full {{ $item->is_active ? 'bg-gradient-to-b from-emerald-400 to-green-500' : 'bg-gradient-to-b from-gray-300 to-gray-400' }}">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-indigo-300 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-all">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Nama --}}
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-800">{{ $item->nama }}</p>
                                            @if ($item->is_active)
                                                <span
                                                    class="mt-1 inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                                                    <span
                                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                                                    Sedang Berjalan
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Tanggal Mulai --}}
                                        <td class="px-6 py-4">
                                            <div
                                                class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2 text-center">
                                                <p class="text-sm font-black text-gray-700">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Tanggal Selesai --}}
                                        <td class="px-6 py-4">
                                            <div
                                                class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2 text-center">
                                                <p class="text-sm font-black text-gray-700">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Total Pengajuan --}}
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="rounded-full border-2 border-indigo-200 bg-indigo-50 px-3 py-1 text-sm font-black text-indigo-700">
                                                {{ $item->pengajuan_count }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            @if ($item->is_active)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-emerald-200 bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                                                    <span
                                                        class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border-2 border-gray-200 bg-gray-100 px-3 py-1.5 text-xs font-black text-gray-500">
                                                    <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">

                                                {{-- Toggle Active --}}
                                                <form method="POST"
                                                    action="{{ route('koor-ta.periode.toggle-active', $item) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-xl border-2 px-3 py-1.5 text-xs font-black shadow-sm transition hover:shadow-md
                                                            {{ $item->is_active
                                                                ? 'border-orange-300 bg-orange-500 text-white hover:bg-orange-600'
                                                                : 'border-emerald-300 bg-emerald-600 text-white hover:bg-emerald-700' }}">
                                                        @if ($item->is_active)
                                                            <x-heroicon-o-pause class="h-3.5 w-3.5" />
                                                            Nonaktifkan
                                                        @else
                                                            <x-heroicon-o-play class="h-3.5 w-3.5" />
                                                            Aktifkan
                                                        @endif
                                                    </button>
                                                </form>

                                                {{-- Edit --}}
                                                <a href="{{ route('koor-ta.periode.edit', $item) }}"
                                                    class="inline-flex items-center gap-1 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                                    <x-heroicon-o-pencil-square class="h-3.5 w-3.5" />
                                                    Edit
                                                </a>

                                                {{-- Hapus --}}
                                                @if ($item->pengajuan_count === 0)
                                                    <form method="POST"
                                                        action="{{ route('koor-ta.periode.destroy', $item) }}"
                                                        onsubmit="return confirm('Hapus periode {{ $item->nama }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-xl border-2 border-red-300 bg-red-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-red-700 hover:shadow-md">
                                                            <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-xl border-2 border-gray-200 bg-gray-100 px-3 py-1.5 text-xs font-black text-gray-400 cursor-not-allowed"
                                                        title="Tidak dapat dihapus karena sudah memiliki pengajuan">
                                                        <x-heroicon-o-lock-closed class="h-3.5 w-3.5" />
                                                        Terkunci
                                                    </span>
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
                            Total <span class="font-black text-gray-800">{{ $periode->count() }}</span> periode
                        </p>
                        <p class="text-xs font-semibold text-gray-500">
                            Aktif: <span
                                class="font-black text-emerald-600">{{ $periode->where('is_active', true)->count() }}</span>
                        </p>
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-layout-koor-ta>
