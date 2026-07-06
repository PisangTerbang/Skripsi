<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengajuan TA — Kepala Lab</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1f2937; }

        .header { background: #0284c7; color: white; padding: 16px 20px; margin-bottom: 16px; }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .header p { font-size: 10px; opacity: 0.85; }

        .meta {
            display: flex; gap: 20px; margin-bottom: 16px; padding: 10px 16px;
            background: #f0f9ff; border: 1px solid #e0f2fe; border-radius: 6px;
        }
        .meta-item { flex: 1; }
        .meta-item .label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #6b7280; margin-bottom: 2px; }
        .meta-item .value { font-size: 11px; font-weight: bold; color: #1f2937; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #0284c7; color: white; }
        thead th { padding: 8px 6px; text-align: left; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 7px 6px; border-bottom: 1px solid #e5e7eb; font-size: 9px; vertical-align: top; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 8px; font-weight: bold; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef3c7; color: #92400e; }

        .footer {
            margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb;
            display: flex; justify-content: space-between; font-size: 9px; color: #6b7280;
        }
        .no-data { text-align: center; padding: 40px; color: #9ca3af; font-size: 12px; }
    </style>
</head>

<body>

    @php
        $lengkap = ($jenis ?? 'informasi') === 'lengkap';

        $statusAkhir = function ($p) {
            if ($p->status_kaprodi === 'disetujui') return ['Diterima', 'badge-green'];
            if ($p->status_kalab === 'ditolak' || $p->status_kaprodi === 'ditolak') return ['Ditolak', 'badge-red'];
            return ['Diproses', 'badge-yellow'];
        };
        $sumberLabel = fn($s) => match ($s) {
            'pilihan_1' => 'Pilihan 1', 'pilihan_2' => 'Pilihan 2', 'pilihan_3' => 'Pilihan 3',
            'mandiri', 'usulan' => 'Usulan Mandiri', default => '-',
        };
        $tingkat = fn($s) => match ($s) {
            'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'pending' => 'Pending',
            null => 'Belum', default => ucfirst((string) $s),
        };
    @endphp

    {{-- Header --}}
    <div class="header">
        <h1>Laporan {{ $lengkap ? 'Lengkap' : 'Informasi' }} Pengajuan Judul Tugas Akhir</h1>
        <p>Panel Kepala Laboratorium — Program Studi Informatika</p>
    </div>

    {{-- Meta --}}
    <div class="meta">
        <div class="meta-item">
            <div class="label">Periode</div>
            <div class="value">{{ $periode ? $periode->nama : 'Semua Periode' }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Status</div>
            <div class="value">{{ $statusLabel }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Total Data</div>
            <div class="value">{{ $pengajuan->count() }} pengajuan</div>
        </div>
        <div class="meta-item">
            <div class="label">Dicetak</div>
            <div class="value">{{ now()->format('d M Y, H:i') }} WIB</div>
        </div>
        <div class="meta-item">
            <div class="label">Oleh</div>
            <div class="value">{{ auth()->user()->name }}</div>
        </div>
    </div>

    {{-- Table --}}
    @if ($pengajuan->isEmpty())
        <div class="no-data">Tidak ada data pengajuan</div>
    @elseif ($lengkap)
        <table>
            <thead>
                <tr>
                    <th style="width:22px">No</th>
                    <th style="width:60px">NIM</th>
                    <th style="width:90px">Nama</th>
                    <th style="width:90px">Judul Ditetapkan</th>
                    <th style="width:55px">Sumber</th>
                    <th style="width:80px">Pembimbing</th>
                    <th style="width:55px">Lab</th>
                    <th style="width:50px">Ka Lab</th>
                    <th style="width:60px">Catatan Ka Lab</th>
                    <th style="width:50px">Prodi</th>
                    <th style="width:60px">Catatan Prodi</th>
                    <th style="width:50px">Status</th>
                    <th style="width:50px">Tgl</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan as $i => $p)
                    @php [$sLabel, $sClass] = $statusAkhir($p); @endphp
                    <tr>
                        <td style="text-align:center">{{ $i + 1 }}</td>
                        <td>{{ $p->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $p->mahasiswa->name ?? '-' }}</td>
                        <td>{{ $p->judulDitetapkan->nama_judul ?? ($p->judulDitetapkan->judul ?? '-') }}</td>
                        <td>{{ $sumberLabel($p->sumber_judul) }}</td>
                        <td>{{ $p->judulDitetapkan->dosen->name ?? '-' }}</td>
                        <td>{{ $p->judulDitetapkan->laboratorium->nama ?? '-' }}</td>
                        <td>{{ $tingkat($p->status_kalab) }}</td>
                        <td>{{ $p->catatan_kalab_pengajuan ?? '-' }}</td>
                        <td>{{ $tingkat($p->status_kaprodi) }}</td>
                        <td>{{ $p->catatan_kaprodi ?? '-' }}</td>
                        <td><span class="badge {{ $sClass }}">{{ $sLabel }}</span></td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:30px">No</th>
                    <th style="width:90px">NIM</th>
                    <th style="width:150px">Nama Mahasiswa</th>
                    <th>Judul Ditetapkan</th>
                    <th style="width:130px">Dosen Pembimbing</th>
                    <th style="width:90px">Laboratorium</th>
                    <th style="width:70px">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan as $i => $p)
                    @php [$sLabel, $sClass] = $statusAkhir($p); @endphp
                    <tr>
                        <td style="text-align:center">{{ $i + 1 }}</td>
                        <td>{{ $p->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $p->mahasiswa->name ?? '-' }}</td>
                        <td>{{ $p->judulDitetapkan->nama_judul ?? ($p->judulDitetapkan->judul ?? '-') }}</td>
                        <td>{{ $p->judulDitetapkan->dosen->name ?? '-' }}</td>
                        <td>{{ $p->judulDitetapkan->laboratorium->nama ?? '-' }}</td>
                        <td><span class="badge {{ $sClass }}">{{ $sLabel }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <span>Sistem Informasi Pengelolaan Judul TA — Panel Kepala Lab</span>
        <span>Dicetak: {{ now()->format('d M Y H:i') }} WIB</span>
    </div>

</body>

</html>
