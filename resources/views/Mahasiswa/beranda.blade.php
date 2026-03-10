<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    {{-- Periode --}}
    <div class="mb-6">
        <p class="text-sm text-slate-500">
            Periode Aktif
            <span class="font-semibold text-slate-700">
                • Semester {{ ucfirst($periodeAktif->semester) }}
                {{ $periodeAktif->tahun_ajaran }}
            </span>
        </p>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl shadow-sm border p-4">
            <p class="text-sm text-slate-500">Total Pengajuan</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">
                {{ $totalPengajuan }}
            </p>
        </div>

    </div>

    {{-- Dashboard Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Update --}}
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <h3 class="text-base font-semibold text-slate-700 mb-4">
                Update Terbaru
            </h3>

            <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1">

                @forelse($aktivitas as $a)
                    <div class="flex items-start gap-3 border-b pb-2">

                        {{-- Icon --}}
                        <div class="mt-1">

                            @if (str_contains($a->pesan, 'disetujui'))
                                <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                                    <x-heroicon-o-check-circle class="w-4 h-4" />
                                </div>
                            @elseif(str_contains($a->pesan, 'ditolak'))
                                <div class="bg-red-100 text-red-600 p-2 rounded-lg">
                                    <x-heroicon-o-x-circle class="w-4 h-4" />
                                </div>
                            @else
                                <div class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                                    <x-heroicon-o-clock class="w-4 h-4" />
                                </div>
                            @endif

                        </div>

                        {{-- Text --}}
                        <div>
                            <p class="text-sm text-slate-700">
                                {{ $a->pesan }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $a->created_at->format('d M Y') }}
                            </p>
                        </div>

                    </div>
                @empty
                    <p class="text-sm text-slate-400">
                        Belum ada aktivitas.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- Lab Paling Diminati --}}
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <h3 class="text-base font-semibold text-slate-700 mb-4">
                Lab Paling Diminati
            </h3>

            <div class="space-y-3">

                @foreach ($labPopuler as $lab)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600">
                                {{ $lab->nama }}
                            </span>
                            <span class="font-semibold text-indigo-600">
                                {{ $lab->persentase }}%
                            </span>
                        </div>

                        <div class="w-full bg-slate-200 h-2 rounded-full">
                            <div class="bg-indigo-500 h-2 rounded-full transition-all"
                                style="width: {{ $lab->persentase }}%">
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>

    {{-- Judul Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border p-5 mt-6">
        <h3 class="text-base font-semibold text-slate-700 mb-4">
            Judul Terbaru
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            @foreach ($judulTerbaru as $j)
                <div class="border rounded-xl p-3 hover:bg-slate-50 transition cursor-pointer">
                    <p class="text-sm text-slate-700">
                        {{ $j->nama_judul }}
                    </p>
                </div>
            @endforeach

        </div>
    </div>

</x-layout>
