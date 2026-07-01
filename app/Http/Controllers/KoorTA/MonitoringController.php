<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\Periode;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $periode = Periode::withCount('pengajuan')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_pengajuan' => Pengajuan::count(),
            'pending_kalab' => Pengajuan::whereNull('status_kalab')->count(),
            'disetujui_kalab' => Pengajuan::where('status_kalab', 'disetujui')->count(),
            'ditolak_kalab' => Pengajuan::where('status_kalab', 'ditolak')->count(),
            'disetujui_kaprodi' => Pengajuan::where('status_kaprodi', 'disetujui')->count(),
            'ditolak_kaprodi' => Pengajuan::where('status_kaprodi', 'ditolak')->count(),
            'total_judul' => Judul::count(),
            'judul_tersedia' => Judul::where('status_judul', 'ditawarkan')->count(),
        ];

        return view('koor-ta.monitoring.index', compact('periode', 'stats'));
    }


    public function pengajuan(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $status = $request->get('status', 'all');

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

        if ($status === 'pending') {
            $query->whereNull('status_kalab');
        } elseif ($status === 'proses') {
            $query->where('status_kalab', 'disetujui')->whereNull('status_kaprodi');
        } elseif ($status === 'selesai') {
            $query->where('status_kaprodi', 'disetujui');
        } elseif ($status === 'ditolak') {
            $query->where(function ($q) {
                $q->where('status_kalab', 'ditolak')
                    ->orWhere('status_kaprodi', 'ditolak');
            });
        }

        $pengajuan = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $periode = Periode::urutKronologis()->get();

        return view('koor-ta.monitoring.pengajuan', compact('pengajuan', 'periode', 'periodeId', 'status'));
    }

    public function judul(Request $request)
    {
        $labId = $request->get('lab_id');
        $status = $request->get('status', 'all');

        $query = Judul::with(['dosen', 'laboratorium'])
            ->withCount(['pengajuanPilihan1', 'pengajuanPilihan2', 'pengajuanPilihan3', 'pengajuanDitetapkan']);

        if ($labId) {
            $query->where('laboratorium_id', $labId);
        }

        if ($status !== 'all') {
            $query->where('status_judul', $status);
        }

        $judul = $query->orderBy('laboratorium_id')->orderBy('kode')->paginate(20)->withQueryString();

        $labs = \App\Models\Laboratorium::orderBy('nama')->get();

        return view('koor-ta.monitoring.judul', compact('judul', 'labs', 'labId', 'status'));
    }
}
