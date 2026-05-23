<x-layout-koor-ta title="User Management">

    <div class="min-h-screen bg-slate-100">
        <div class="px-6 py-6 space-y-6">

            {{-- ===== TOP BAR ===== --}}
            <div class="sticky top-0 z-10 border-b-2 border-indigo-100 bg-white px-6 py-4 shadow-sm -mx-6 -mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-indigo-200 bg-indigo-50">
                            <x-heroicon-o-users class="h-5 w-5 text-indigo-600" />
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div>
                            <h1 class="text-lg font-extrabold text-gray-900">User Management</h1>
                            <p class="mt-0.5 text-xs text-gray-400">Kelola semua akun pengguna sistem</p>
                        </div>
                    </div>
                    <a href="{{ route('koor-ta.users.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                        <x-heroicon-o-plus class="h-3.5 w-3.5" />
                        Tambah User
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

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ([['label' => 'Total', 'value' => $stats['total'], 'color' => 'indigo'], ['label' => 'Mahasiswa', 'value' => $stats['mahasiswa'], 'color' => 'blue'], ['label' => 'Dosen', 'value' => $stats['dosen'], 'color' => 'violet'], ['label' => 'Ka Lab', 'value' => $stats['ka_lab'], 'color' => 'emerald'], ['label' => 'Prodi', 'value' => $stats['prodi'], 'color' => 'orange'], ['label' => 'Koor TA', 'value' => $stats['koordinator_ta'], 'color' => 'red']] as $stat)
                    <div
                        class="relative overflow-hidden rounded-2xl border-2 border-{{ $stat['color'] }}-200 bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-black text-gray-900">{{ $stat['value'] }}</p>
                        <div class="mt-2 h-1 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full bg-{{ $stat['color'] }}-500"
                                style="width: {{ $stats['total'] > 0 ? ($stat['value'] / $stats['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===== FILTER + TABLE ===== --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
                <span
                    class="flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-gray-400 shadow-sm">
                    <x-heroicon-o-table-cells class="h-3 w-3" />
                    Daftar User
                </span>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
            </div>

            {{-- Filter & Search --}}
            <form method="GET" action="{{ route('koor-ta.users.index') }}" class="flex flex-wrap items-center gap-3">

                {{-- Role Filter --}}
                <div class="flex items-center gap-1 rounded-2xl border-2 border-gray-200 bg-white p-1.5 shadow-sm">
                    @foreach ([
        'all' => 'Semua',
        'mahasiswa' => 'Mahasiswa',
        'dosen' => 'Dosen',
        'ka_lab' => 'Ka Lab',
        'prodi' => 'Prodi',
        'koordinator_ta' => 'Koor TA',
    ] as $val => $label)
                        <button type="submit" name="role" value="{{ $val }}"
                            class="rounded-xl px-3 py-1.5 text-xs font-bold transition-all
                                {{ $role === $val
                                    ? 'bg-indigo-600 text-white shadow-sm'
                                    : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            {{ $label }}
                        </button>
                        @if (!$loop->last)
                            <div class="h-5 w-px bg-gray-200"></div>
                        @endif
                    @endforeach
                </div>

                {{-- Search --}}
                <div
                    class="flex flex-1 items-center gap-2 rounded-2xl border-2 border-gray-200 bg-white px-4 py-2 shadow-sm min-w-[200px]">
                    <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0 text-gray-400" />
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Cari nama, email, atau NIM..."
                        class="flex-1 bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none" />
                    @if ($search)
                        <a href="{{ route('koor-ta.users.index', ['role' => $role]) }}"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <x-heroicon-o-x-mark class="h-4 w-4" />
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    <x-heroicon-o-magnifying-glass class="h-3.5 w-3.5" />
                    Cari
                </button>
            </form>

            {{-- Table Card --}}
            <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

                {{-- Card Header --}}
                <div
                    class="flex items-center justify-between border-b-4 border-indigo-200 bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border-2 border-white/30 bg-white/20">
                            <x-heroicon-o-users class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-white">Daftar Pengguna</h2>
                            <p class="text-xs text-indigo-200">
                                {{ $role !== 'all' ? ucfirst(str_replace('_', ' ', $role)) : 'Semua role' }}
                                {{ $search ? "— pencarian: \"{$search}\"" : '' }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs font-black text-white">
                        {{ $users->total() }} user
                    </span>
                </div>

                @if ($users->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="relative mb-6">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-3xl border-2 border-indigo-100 bg-gradient-to-br from-indigo-50 to-blue-100 shadow-inner">
                                <x-heroicon-o-users class="h-12 w-12 text-indigo-300" />
                            </div>
                        </div>
                        <p class="text-lg font-extrabold text-gray-800">Tidak ada user</p>
                        <p class="mt-2 text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr
                                    class="border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-black uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Nama</th>
                                    <th class="px-6 py-4">Email</th>
                                    <th class="px-6 py-4">NIM</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Bergabung</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-gray-100">
                                @foreach ($users as $index => $user)
                                    <tr class="group transition-colors hover:bg-indigo-50/30">

                                        {{-- No --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-9 w-1.5 rounded-full bg-gradient-to-b from-indigo-400 to-blue-500">
                                                </div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50 text-xs font-black text-gray-500 group-hover:border-indigo-300 group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-all">
                                                    {{ $users->firstItem() + $index }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Nama --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-sm font-black text-white shadow-md ring-2 ring-indigo-200">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                                    @if ($user->id === auth()->id())
                                                        <span class="text-xs text-indigo-500 font-semibold">—
                                                            Anda</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        </td>

                                        {{-- NIM --}}
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $user->nim ?? '—' }}</span>
                                        </td>

                                        {{-- Role --}}
                                        <td class="px-6 py-4">
                                            @php
                                                $roleConfig = match ($user->role) {
                                                    'mahasiswa' => [
                                                        'bg-blue-100 text-blue-700 border-blue-200',
                                                        'Mahasiswa',
                                                    ],
                                                    'dosen' => [
                                                        'bg-violet-100 text-violet-700 border-violet-200',
                                                        'Dosen',
                                                    ],
                                                    'ka_lab' => [
                                                        'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                        'Ka Lab',
                                                    ],
                                                    'prodi' => [
                                                        'bg-orange-100 text-orange-700 border-orange-200',
                                                        'Prodi',
                                                    ],
                                                    'koordinator_ta' => [
                                                        'bg-red-100 text-red-700 border-red-200',
                                                        'Koor TA',
                                                    ],
                                                    default => [
                                                        'bg-gray-100 text-gray-600 border-gray-200',
                                                        $user->role,
                                                    ],
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center rounded-full border-2 px-3 py-1 text-xs font-black {{ $roleConfig[0] }}">
                                                {{ $roleConfig[1] }}
                                            </span>
                                        </td>

                                        {{-- Bergabung --}}
                                        <td class="px-6 py-4">
                                            <div
                                                class="rounded-xl border-2 border-gray-100 bg-gray-50 px-3 py-2 text-center">
                                                <p class="text-sm font-black text-gray-700">
                                                    {{ $user->created_at->format('d M Y') }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $user->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">

                                                {{-- Edit --}}
                                                <a href="{{ route('koor-ta.users.edit', $user) }}"
                                                    class="inline-flex items-center gap-1 rounded-xl border-2 border-indigo-300 bg-indigo-600 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md">
                                                    <x-heroicon-o-pencil-square class="h-3.5 w-3.5" />
                                                    Edit
                                                </a>

                                                {{-- Reset Password --}}
                                                <form method="POST"
                                                    action="{{ route('koor-ta.users.reset-password', $user) }}"
                                                    onsubmit="return confirm('Reset password {{ $user->name }}?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-xl border-2 border-yellow-300 bg-yellow-500 px-3 py-1.5 text-xs font-black text-white shadow-sm transition hover:bg-yellow-600 hover:shadow-md">
                                                        <x-heroicon-o-key class="h-3.5 w-3.5" />
                                                        Reset
                                                    </button>
                                                </form>

                                                {{-- Hapus --}}
                                                @if ($user->id !== auth()->id())
                                                    <form method="POST"
                                                        action="{{ route('koor-ta.users.destroy', $user) }}"
                                                        onsubmit="return confirm('Hapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
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

                    {{-- Pagination --}}
                    @if ($users->hasPages())
                        <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Menampilkan <span
                                    class="font-black text-gray-800">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
                                dari <span class="font-black text-gray-800">{{ $users->total() }}</span> user
                            </p>
                            {{ $users->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="flex items-center justify-between border-t-2 border-gray-200 bg-gray-50 px-6 py-4">
                            <p class="text-xs font-semibold text-gray-500">
                                Total <span class="font-black text-gray-800">{{ $users->total() }}</span> user
                            </p>
                        </div>
                    @endif
                @endif

            </div>

        </div>
    </div>

</x-layout-koor-ta>
