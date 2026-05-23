<x-layout-prodi>
    <x-slot:title>Management Periode</x-slot>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3"
            role="alert">
            <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3"
            role="alert">
            <x-heroicon-o-x-circle class="w-5 h-5 flex-shrink-0" />
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Actions --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Periode TA</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola periode tugas akhir dan jadwal pengajuan</p>
        </div>
        <a href="{{ route('prodi.periode.create') }}"
            class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition shadow-sm hover:shadow-md">
            <x-heroicon-o-plus class="w-5 h-5" />
            <span class="font-medium">Tambah Periode</span>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tahun Akademik
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jadwal Pengajuan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pengajuan
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($periode as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $p->nama ?? '-' }}</div>
                                @if ($p->keterangan)
                                    <div class="text-xs text-gray-500 mt-1">{{ $p->keterangan }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $p->tahun_akademik ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->tanggal_buka && $p->tanggal_tutup)
                                    <div class="text-sm text-gray-900">
                                        {{ $p->tanggal_buka->format('d M Y') }} -
                                        {{ $p->tanggal_tutup->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $p->tanggal_buka->diffInDays($p->tanggal_tutup) }} hari
                                    </div>
                                @else
                                    <div class="text-sm text-gray-400 italic">Belum diatur</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($p->status_badge_color === 'green') bg-green-100 text-green-800
                                @elseif($p->status_badge_color === 'blue') bg-blue-100 text-blue-800
                                @elseif($p->status_badge_color === 'red') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                    {{ $p->status_periode }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-document-text class="w-4 h-4 text-gray-400" />
                                    <span class="text-sm text-gray-900 font-medium">{{ $p->pengajuan->count() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Toggle Active --}}
                                    <form action="{{ route('prodi.periode.toggle-active', $p) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="p-2 rounded-lg transition
                                        @if ($p->is_active) bg-green-100 text-green-600 hover:bg-green-200
                                        @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif"
                                            title="{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if ($p->is_active)
                                                <x-heroicon-o-check-circle class="w-5 h-5" />
                                            @else
                                                <x-heroicon-o-x-circle class="w-5 h-5" />
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('prodi.periode.edit', $p) }}"
                                        class="p-2 bg-yellow-100 text-yellow-600 hover:bg-yellow-200 rounded-lg transition"
                                        title="Edit">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('prodi.periode.destroy', $p) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus periode ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Hapus" @if ($p->pengajuan->count() > 0) disabled @endif>
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <x-heroicon-o-calendar class="mx-auto h-16 w-16 text-gray-300" />
                                <p class="mt-4 text-gray-500 font-medium">Belum ada periode yang dibuat</p>
                                <p class="mt-1 text-sm text-gray-400">Klik tombol "Tambah Periode" untuk membuat periode
                                    baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layout-prodi>
