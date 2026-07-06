<?php

namespace App\Exports;

use App\Http\Controllers\KaLab\ExportController;
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

/**
 * Ekspor Excel pengajuan untuk Kepala Lab.
 *
 * Dua mode (jenis):
 *  - informasi : ringkas — identitas mahasiswa, pembimbing, lab, dan SATU kolom
 *                status akhir (Diterima/Ditolak/Diproses). Tanpa log.
 *  - lengkap   : seluruh detail termasuk jejak review (log) Ka Lab & Prodi.
 */
class KaLabPengajuanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected ?int $periodeId;
    protected string $status;
    protected string $jenis;
    protected ?Periode $periode;

    public function __construct(?int $periodeId = null, string $status = 'all', string $jenis = 'informasi')
    {
        $this->periodeId = $periodeId;
        $this->status = $status;
        $this->jenis = $jenis === 'lengkap' ? 'lengkap' : 'informasi';
        $this->periode = $periodeId ? Periode::find($periodeId) : null;
    }

    public function collection()
    {
        return ExportController::query($this->periodeId, $this->status)->latest()->get();
    }

    public function headings(): array
    {
        if ($this->jenis === 'lengkap') {
            return [
                'No',
                'NIM',
                'Nama Mahasiswa',
                'Periode',
                'Judul Ditetapkan',
                'Sumber Judul',
                'Dosen Pembimbing',
                'Laboratorium',
                'Pilihan 1',
                'Pilihan 2',
                'Pilihan 3',
                'Status Ka Lab',
                'Reviewer Ka Lab',
                'Tgl Review Ka Lab',
                'Catatan Ka Lab',
                'Status Prodi',
                'Reviewer Prodi',
                'Tgl Review Prodi',
                'Catatan Prodi',
                'Status Akhir',
                'Tgl Pengajuan',
            ];
        }

        // Mode informasi (ringkas) — tanpa log.
        return [
            'No',
            'NIM',
            'Nama Mahasiswa',
            'Judul Ditetapkan',
            'Dosen Pembimbing',
            'Laboratorium',
            'Status',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        if ($this->jenis === 'lengkap') {
            return [
                $no,
                $row->mahasiswa->nim ?? '-',
                $row->mahasiswa->name ?? '-',
                $row->periode->nama ?? '-',
                $row->judulDitetapkan->nama_judul ?? $row->judulDitetapkan->judul ?? '-',
                $this->sumberLabel($row->sumber_judul),
                $row->judulDitetapkan->dosen->name ?? '-',
                $row->judulDitetapkan->laboratorium->nama ?? '-',
                $row->pilihan1->nama_judul ?? '-',
                $row->pilihan2->nama_judul ?? '-',
                $row->pilihan3->nama_judul ?? '-',
                $this->tingkatLabel($row->status_kalab),
                $row->reviewerKalab->name ?? '-',
                $row->tanggal_review_kalab ? $row->tanggal_review_kalab->format('d/m/Y H:i') : '-',
                $row->catatan_kalab_pengajuan ?? '-',
                $this->tingkatLabel($row->status_kaprodi),
                $row->reviewerKaprodi->name ?? '-',
                $row->tanggal_review_kaprodi ? $row->tanggal_review_kaprodi->format('d/m/Y H:i') : '-',
                $row->catatan_kaprodi ?? '-',
                $this->statusAkhir($row),
                $row->created_at->format('d/m/Y H:i'),
            ];
        }

        return [
            $no,
            $row->mahasiswa->nim ?? '-',
            $row->mahasiswa->name ?? '-',
            $row->judulDitetapkan->nama_judul ?? $row->judulDitetapkan->judul ?? '-',
            $row->judulDitetapkan->dosen->name ?? '-',
            $row->judulDitetapkan->laboratorium->nama ?? '-',
            $this->statusAkhir($row),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0284C7'], // sky-600 (tema Ka Lab)
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
        return $this->jenis === 'lengkap' ? 'Laporan Lengkap' : 'Laporan Informasi';
    }

    // ==================== HELPERS ====================

    /** Status akhir yang diringkas menjadi satu: Diterima / Ditolak / Diproses. */
    private function statusAkhir($row): string
    {
        if ($row->status_kaprodi === 'disetujui') {
            return 'Diterima';
        }
        if ($row->status_kalab === 'ditolak' || $row->status_kaprodi === 'ditolak') {
            return 'Ditolak';
        }
        return 'Diproses';
    }

    private function tingkatLabel($status): string
    {
        return match ($status) {
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            'pending'   => 'Pending',
            null        => 'Belum Diproses',
            default     => ucfirst((string) $status),
        };
    }

    private function sumberLabel($sumber): string
    {
        return match ($sumber) {
            'pilihan_1' => 'Pilihan 1',
            'pilihan_2' => 'Pilihan 2',
            'pilihan_3' => 'Pilihan 3',
            'mandiri', 'usulan' => 'Usulan Mandiri',
            default => '-',
        };
    }
}
