<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    {{-- Periode --}}
    <p class="text-sm text-slate-500 mb-4">
        Periode:
        <span class="font-semibold">
            Semester {{ ucfirst($periodeAktif->semester) }}
            {{ $periodeAktif->tahun_ajaran }}
        </span>
    </p>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <div class="bg-white p-4 rounded shadow">
            <h3>Total</h3>
            <p class="text-2xl font-bold">{{ $total }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3>Disetujui</h3>
            <p class="text-2xl font-bold text-green-600">{{ $disetujui }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3>Ditolak</h3>
            <p class="text-2xl font-bold text-red-600">{{ $ditolak }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3>Pending</h3>
            <p class="text-2xl font-bold text-yellow-500">{{ $pending }}</p>
        </div>

    </div>

    {{-- CHART ROW 1 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        {{-- Donut --}}
        <div class="bg-white p-6 rounded shadow h-[320px]">
            <h3 class="font-semibold mb-2">
                Distribusi Keputusan
            </h3>
            <div class="relative h-[240px]">
                <canvas id="donutChart"></canvas>
            </div>
        </div>

        {{-- Tren Pengajuan --}}
        <div class="bg-white p-6 rounded shadow h-[320px]">
            <h3 class="font-semibold mb-2">Tren Pengajuan</h3>
            <div class="relative h-[240px]">
                <canvas id="trenChart"></canvas>
            </div>
        </div>

    </div>

    {{-- CHART ROW 2 --}}
    <div class="mt-6">
        <div class="bg-white p-6 rounded shadow h-[320px]">
            <h3 class="font-semibold mb-2">Tren Keputusan</h3>
            <div class="relative h-[240px]">
                <canvas id="keputusanChart"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded shadow mt-6">
        <h3 class="font-semibold mb-3">
            Lab dengan Persetujuan Terbanyak
        </h3>

        @forelse($labDisetujui as $lab)
            <div class="mb-2">
                <div class="flex justify-between text-sm">
                    <span>{{ $lab->nama }}</span>
                    <span class="font-semibold">{{ $lab->total }}</span>
                </div>
                <div class="w-full bg-slate-200 h-2 rounded mt-1">
                    <div class="bg-indigo-500 h-2 rounded" style="width: {{ $lab->total * 10 }}%">
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400">Belum ada data</p>
        @endforelse
    </div>
    <div class="bg-white p-6 rounded shadow mt-6">
        <h3 class="font-semibold mb-3 text-red-500">
            Lab dengan Penolakan Tinggi
        </h3>

        @forelse($labDitolak as $lab)
            <div class="mb-2">
                <div class="flex justify-between text-sm">
                    <span>{{ $lab->nama }}</span>
                    <span class="font-semibold">{{ $lab->total }}</span>
                </div>
                <div class="w-full bg-slate-200 h-2 rounded mt-1">
                    <div class="bg-red-500 h-2 rounded" style="width: {{ $lab->total * 10 }}%">
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400">Belum ada data</p>
        @endforelse
    </div>
    <div class="bg-white p-6 rounded shadow mt-6">
        <h3 class="font-semibold mb-3">
            Rasio Persetujuan per Lab
        </h3>

        @foreach ($rasioLab as $lab)
            @php
                $total = $lab->disetujui + $lab->ditolak;
                $rasio = $total > 0 ? round(($lab->disetujui / $total) * 100) : 0;
            @endphp

            <div class="mb-3">
                <div class="flex justify-between text-sm">
                    <span>{{ $lab->nama }}</span>
                    <span class="font-semibold">{{ $rasio }}%</span>
                </div>

                <div class="w-full bg-slate-200 h-2 rounded mt-1">
                    <div class="bg-green-500 h-2 rounded" style="width: {{ $rasio }}%">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-layout-dosen>





<script>
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Disetujui', 'Ditolak'],
            datasets: [{
                data: [{{ $pending }}, {{ $disetujui }}, {{ $ditolak }}],
                backgroundColor: ['#facc15', '#22c55e', '#ef4444']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<script>
    new Chart(document.getElementById('trenChart'), {
        type: 'line',
        data: {
            labels: [
                @foreach ($trenPengajuan as $t)
                    "{{ $t->semester }} {{ $t->tahun_ajaran }}",
                @endforeach
            ],
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: [
                    @foreach ($trenPengajuan as $t)
                        {{ $t->total }},
                    @endforeach
                ],
                borderColor: '#6366f1',
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false
        }
    });
</script>
<script>
    new Chart(document.getElementById('keputusanChart'), {
        type: 'bar',
        data: {
            labels: [
                @foreach ($trenKeputusan as $t)
                    "{{ $t->semester }} {{ $t->tahun_ajaran }}",
                @endforeach
            ],
            datasets: [{
                    label: 'Disetujui',
                    data: [
                        @foreach ($trenKeputusan as $t)
                            {{ $t->disetujui }},
                        @endforeach
                    ]
                },
                {
                    label: 'Ditolak',
                    data: [
                        @foreach ($trenKeputusan as $t)
                            {{ $t->ditolak }},
                        @endforeach
                    ]
                }
            ]
        },
        options: {
            maintainAspectRatio: false
        }
    });
</script>
