<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Exports\KaLabPengajuanExport;
use App\Models\Pengajuan;
use App\Models\Periode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Ekspor data pengajuan untuk Kepala Lab.
 *
 * Berbeda dari ekspor Koordinator TA: laporan Ka Lab TIDAK menyertakan audit log
 * judul, dan status diterima diringkas menjadi SATU kolom (Diterima / Ditolak /
 * Diproses) — bukan status per Ka Lab & Prodi terpisah.
 *
 * Dua jenis laporan:
 *  - informasi : ringkas (identitas mahasiswa, pembimbing, lab, status akhir).
 *  - lengkap   : seluruh detail termasuk jejak review (log) Ka Lab & Prodi.
 */
class ExportController extends Controller
{
    /** Status filter yang diperbolehkan. */
    private const STATUS = ['all', 'diterima', 'ditolak', 'proses'];

    /** Jenis laporan yang diperbolehkan. */
    private const JENIS = ['informasi', 'lengkap'];

    public function index()
    {
        $periode = Periode::urutKronologis()->get();

        return view('ka_lab.export.index', compact('periode'));
    }

    public function exportExcel(Request $request)
    {
        [$periodeId, $status, $jenis, $periode] = $this->params($request);

        return Excel::download(
            new KaLabPengajuanExport($periodeId, $status, $jenis),
            $this->filename($periode, $status, $jenis, 'xlsx')
        );
    }

    public function exportPdf(Request $request)
    {
        [$periodeId, $status, $jenis, $periode] = $this->params($request);

        $pengajuan = $this->query($periodeId, $status)->latest()->get();

        $pdf = Pdf::loadView('ka_lab.export.pengajuan-pdf', [
            'pengajuan'   => $pengajuan,
            'periode'     => $periode,
            'statusLabel' => $this->statusLabel($status),
            'jenis'       => $jenis,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($this->filename($periode, $status, $jenis, 'pdf'));
    }

    // ==================== HELPERS ====================

    /** Validasi & normalisasi input, kembalikan [periodeId, status, jenis, periode]. */
    private function params(Request $request): array
    {
        $request->validate([
            'periode_id' => 'nullable|exists:periode,id',
            'status'     => 'nullable|string',
            'jenis'      => 'nullable|string',
        ]);

        $periodeId = $request->periode_id;
        $status = in_array($request->status, self::STATUS, true) ? $request->status : 'all';
        $jenis = in_array($request->jenis, self::JENIS, true) ? $request->jenis : 'informasi';
        $periode = $periodeId ? Periode::find($periodeId) : null;

        return [$periodeId, $status, $jenis, $periode];
    }

    /**
     * Query pengajuan + eager load. Status di sini adalah "status akhir" hasil
     * penetapan (Diterima/Ditolak/Diproses), bukan status per tingkat.
     */
    public static function query(?int $periodeId, string $status)
    {
        $query = Pengajuan::with([
            'mahasiswa',
            'periode',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'pilihan1.dosen',
            'pilihan2.dosen',
            'pilihan3.dosen',
            'reviewerKalab',
            'reviewerKaprodi',
        ]);

        // Ka Lab hanya mengekspor pengajuan yang (pernah) ditangani laboratoriumnya.
        $myLab = \Illuminate\Support\Facades\Auth::user()?->laboratorium_id;
        if ($myLab) {
            $query->where('lab_aktif_id', $myLab);
        }

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        if ($status === 'diterima') {
            $query->where('status_kaprodi', 'disetujui');
        } elseif ($status === 'ditolak') {
            $query->where(function ($q) {
                $q->where('status_kalab', 'ditolak')
                    ->orWhere('status_kaprodi', 'ditolak');
            });
        } elseif ($status === 'proses') {
            $query->whereNull('status_kaprodi')
                ->where(function ($q) {
                    $q->whereNull('status_kalab')
                        ->orWhere('status_kalab', '!=', 'ditolak');
                });
        }

        return $query;
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
            'proses'   => 'Masih Diproses',
            default    => 'Semua Status',
        };
    }

    private function filename(?Periode $periode, string $status, string $jenis, string $ext): string
    {
        $name = 'laporan-pengajuan-' . $jenis;
        if ($periode) {
            $name .= '-' . str_replace(['/', '\\', ' '], '-', strtolower($periode->nama));
        }
        if ($status !== 'all') {
            $name .= '-' . $status;
        }
        return $name . '-' . now()->format('Ymd-His') . '.' . $ext;
    }
}
