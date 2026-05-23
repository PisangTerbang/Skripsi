<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Export Pengajuan TA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        .header {
            background: #4f46e5;
            color: white;
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            opacity: 0.85;
        }

        .meta {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
            padding: 10px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        .meta-item {
            flex: 1;
        }

        .meta-item .label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .meta-item .value {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        thead tr {
            background: #4f46e5;
            color: white;
        }

        thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #eff6ff;
        }

        tbody td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #374151;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #6b7280;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-size: 12px;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>Laporan Pengajuan Judul Tugas Akhir</h1>
        <p>Program Studi Informatika — Fakultas Teknologi Industri — Universitas Islam Indonesia</p>
    </div>

    {{-- Meta Info --}}
    <div class="meta">
        <div class="meta-item">
            <div class="label">Periode</div>
            <div class="value">{{ $periode ? $periode->nama : 'Semua Periode' }}</div>
        </div>
        <div class="meta-item">
            <div class="label">Status Filter</div>
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
            <div class="label">Dicetak Oleh</div>
            <div class="value">{{ auth()->user()->name }}</div>
        </div>
    </div>

    {{-- Table --}}
    @if ($pengajuan->isEmpty())
        <div class="no-data">Tidak ada data pengajuan</div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:25px">No</th>
                    <th style="width:70px">NIM</th>
                    <th style="width:100px">Nama Mahasiswa</th>
                    <th style="width:80px">Judul Ditetapkan</th>
                    <th style="width:80px">Dosen</th>
                    <th style="width:60px">Lab</th>
                    <th style="width:55px">Status Ka Lab</th>
                    <th style="width:55px">Status Kaprodi</th>
                    <th style="width:60px">Tgl Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan as $index => $item)
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                        <td>{{ $item->judulDitetapkan->nama_judul ?? ($item->judulDitetapkan->judul ?? '-') }}</td>
                        <td>{{ $item->judulDitetapkan->dosen->name ?? '-' }}</td>
                        <td>{{ $item->judulDitetapkan->laboratorium->nama ?? '-' }}</td>
                        <td>
                            @php
                                $kalabClass = match ($item->status_kalab) {
                                    'disetujui' => 'badge-green',
                                    'ditolak' => 'badge-red',
                                    default => 'badge-yellow',
                                };
                                $kalabLabel = match ($item->status_kalab) {
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    default => 'Pending',
                                };
                            @endphp
                            <span class="badge {{ $kalabClass }}">{{ $kalabLabel }}</span>
                        </td>
                        <td>
                            @php
                                $kaprodiClass = match ($item->status_kaprodi) {
                                    'disetujui' => 'badge-green',
                                    'ditolak' => 'badge-red',
                                    default => 'badge-gray',
                                };
                                $kaprodiLabel = match ($item->status_kaprodi) {
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    null => 'Belum',
                                    default => ucfirst($item->status_kaprodi),
                                };
                            @endphp
                            <span class="badge {{ $kaprodiClass }}">{{ $kaprodiLabel }}</span>
                        </td>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <span>Sistem Pengajuan Judul TA — Program Studi Informatika UII</span>
        <span>Dicetak: {{ now()->format('d M Y H:i') }} WIB</span>
    </div>

</body>

</html>
