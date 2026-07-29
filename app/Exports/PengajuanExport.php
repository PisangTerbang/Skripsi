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
    protected $jenis;
    protected $periode;

    public function __construct($periodeId = null, $status = null, $jenis = 'lengkap')
    {
        $this->periodeId = $periodeId;
        $this->status = $status;
        $this->jenis = $jenis === 'ringkas' ? 'ringkas' : 'lengkap';
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
        if ($this->jenis === 'ringkas') {
            // Log dipadatkan: satu kolom Status Akhir + satu kolom Log Ringkas.
            return [
                'No',
                'NIM',
                'Nama Mahasiswa',
                'Periode',
                'Judul Ditetapkan',
                'Dosen Pembimbing',
                'Laboratorium',
                'Status Akhir',
                'Log Ringkas',
                'Tanggal Pengajuan',
            ];
        }

        // Lengkap: log penuh per tingkat (Ka Lab & Kaprodi).
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

        $judul = $row->judulDitetapkan->nama_judul ?? $row->judulDitetapkan->judul ?? '-';
        $dosen = $row->judulDitetapkan->dosen->name ?? '-';
        $lab = $row->judulDitetapkan->laboratorium->nama ?? '-';

        if ($this->jenis === 'ringkas') {
            return [
                $no,
                $row->mahasiswa->nim ?? '-',
                $row->mahasiswa->name ?? '-',
                $row->periode->nama ?? '-',
                $judul,
                $dosen,
                $lab,
                $this->statusAkhir($row),
                $this->logRingkas($row),
                $row->created_at->format('d/m/Y H:i'),
            ];
        }

        return [
            $no,
            $row->mahasiswa->nim ?? '-',
            $row->mahasiswa->name ?? '-',
            $row->periode->nama ?? '-',
            $judul,
            $dosen,
            $lab,
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

    /**
     * Jejak review dipadatkan menjadi satu kolom, mis.:
     *   "Ka Lab: Disetujui (05/08/28 09:40) → Prodi: Disetujui (07/08/28 13:05)".
     * Bila Ka Lab menolak, alur berhenti di Ka Lab (tidak diteruskan ke Prodi).
     */
    private function logRingkas($row): string
    {
        $kalab = 'Ka Lab: ' . $this->formatStatus($row->status_kalab);
        if ($row->tanggal_review_kalab) {
            $kalab .= ' (' . $row->tanggal_review_kalab->format('d/m/y H:i') . ')';
        }
        $parts = [$kalab];

        if ($row->status_kalab !== 'ditolak') {
            $prodi = 'Prodi: ' . $this->formatStatus($row->status_kaprodi);
            if ($row->tanggal_review_kaprodi) {
                $prodi .= ' (' . $row->tanggal_review_kaprodi->format('d/m/y H:i') . ')';
            }
            $parts[] = $prodi;
        }

        return implode(' → ', $parts);
    }
}
