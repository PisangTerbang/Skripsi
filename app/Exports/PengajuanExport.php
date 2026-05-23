<?php

namespace App\Exports;

use App\Models\Pengajuan;
use App\Models\Periode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PengajuanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected $periodeId;
    protected $status;
    protected $periode;

    public function __construct($periodeId = null, $status = null)
    {
        $this->periodeId = $periodeId;
        $this->status = $status;
        $this->periode = $periodeId ? Periode::find($periodeId) : null;
    }

    public function collection()
    {
        $query = Pengajuan::with([
            'mahasiswa',
            'periode',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'reviewerKalab',
            'reviewerKaprodi',
        ]);

        if ($this->periodeId) {
            $query->where('periode_id', $this->periodeId);
        }

        if ($this->status && $this->status !== 'all') {
            if ($this->status === 'disetujui') {
                $query->where('status_kaprodi', 'disetujui');
            } elseif ($this->status === 'ditolak') {
                $query->where(function ($q) {
                    $q->where('status_kalab', 'ditolak')
                        ->orWhere('status_kaprodi', 'ditolak');
                });
            } elseif ($this->status === 'pending') {
                $query->whereNull('status_kalab');
            } elseif ($this->status === 'proses') {
                $query->where('status_kalab', 'disetujui')
                    ->whereNull('status_kaprodi');
            }
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIM',
            'Nama Mahasiswa',
            'Periode',
            'Judul Ditetapkan',
            'Dosen Pembimbing',
            'Laboratorium',
            'Status Ka Lab',
            'Reviewer Ka Lab',
            'Tanggal Review Ka Lab',
            'Status Kaprodi',
            'Reviewer Kaprodi',
            'Tanggal Review Kaprodi',
            'Catatan Ka Lab',
            'Catatan Kaprodi',
            'Tanggal Pengajuan',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->mahasiswa->nim ?? '-',
            $row->mahasiswa->name ?? '-',
            $row->periode->nama ?? '-',
            $row->judulDitetapkan->nama_judul ?? $row->judulDitetapkan->judul ?? '-',
            $row->judulDitetapkan->dosen->name ?? '-',
            $row->judulDitetapkan->laboratorium->nama ?? '-',
            $this->formatStatus($row->status_kalab),
            $row->reviewerKalab->name ?? '-',
            $row->tanggal_review_kalab ? $row->tanggal_review_kalab->format('d/m/Y H:i') : '-',
            $this->formatStatus($row->status_kaprodi),
            $row->reviewerKaprodi->name ?? '-',
            $row->tanggal_review_kaprodi ? $row->tanggal_review_kaprodi->format('d/m/Y H:i') : '-',
            $row->catatan_kalab_pengajuan ?? '-',
            $row->catatan_kaprodi ?? '-',
            $row->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Pengajuan';
    }

    private function formatStatus($status): string
    {
        return match ($status) {
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'pending' => 'Pending',
            null => 'Belum Diproses',
            default => ucfirst($status),
        };
    }
}
