<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Exports\PengajuanExport;
use App\Models\Pengajuan;
use App\Models\Periode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $periode = Periode::urutKronologis()->get();

        return view('koor-ta.export.index', compact('periode'));
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'periode_id' => 'nullable|exists:periode,id',
            'status' => 'nullable|string',
        ]);

        $periodeId = $request->periode_id;
        $status = $request->status ?? 'all';
        $periode = $periodeId ? Periode::find($periodeId) : null;

        // ✅ fix: hapus karakter / \ dan spasi dari nama file
        $filename = 'pengajuan-ta';
        if ($periode) {
            $filename .= '-' . str_replace(['/', '\\', ' '], '-', strtolower($periode->nama));
        }
        if ($status !== 'all') {
            $filename .= '-' . $status;
        }
        $filename .= '-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new PengajuanExport($periodeId, $status),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'periode_id' => 'nullable|exists:periode,id',
            'status' => 'nullable|string',
        ]);

        $periodeId = $request->periode_id;
        $status = $request->status ?? 'all';
        $periode = $periodeId ? Periode::find($periodeId) : null;
        $statusLabel = match ($status) {
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'pending' => 'Pending',
            'proses' => 'Dalam Proses',
            default => 'Semua Status',
        };

        $query = Pengajuan::with([
            'mahasiswa',
            'periode',
            'judulDitetapkan.dosen',
            'judulDitetapkan.laboratorium',
            'reviewerKalab',
            'reviewerKaprodi',
        ]);

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        if ($status && $status !== 'all') {
            if ($status === 'disetujui') {
                $query->where('status_kaprodi', 'disetujui');
            } elseif ($status === 'ditolak') {
                $query->where(function ($q) {
                    $q->where('status_kalab', 'ditolak')
                        ->orWhere('status_kaprodi', 'ditolak');
                });
            } elseif ($status === 'pending') {
                $query->whereNull('status_kalab');
            } elseif ($status === 'proses') {
                $query->where('status_kalab', 'disetujui')
                    ->whereNull('status_kaprodi');
            }
        }

        $pengajuan = $query->latest()->get();

        $pdf = Pdf::loadView('koor-ta.export.pengajuan-pdf', compact(
            'pengajuan',
            'periode',
            'statusLabel',
        ))->setPaper('a4', 'landscape');

        // ✅ fix: hapus karakter / \ dan spasi dari nama file
        $filename = 'pengajuan-ta';
        if ($periode) {
            $filename .= '-' . str_replace(['/', '\\', ' '], '-', strtolower($periode->nama));
        }
        if ($status !== 'all') {
            $filename .= '-' . $status;
        }
        $filename .= '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
