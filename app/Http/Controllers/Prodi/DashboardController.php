<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Validasi role
        if ($user->role !== 'prodi') {
            abort(403, 'Anda tidak memiliki akses sebagai Program Studi');
        }

        // Pengajuan yang menunggu keputusan Prodi pada PERIODE AKTIF (sudah disetujui Ka Lab, belum diputus Prodi).
        // status_kaprodi bernilai null saat menunggu (bukan 'pending'), jadi pakai whereNull.
        $pid = Periode::periodeAktif()?->id;

        $totalJudul = Judul::count();
        $judulDitawarkan = Judul::where('status_judul', 'ditawarkan')->count();
        $pendingFinal = Pengajuan::where('periode_id', $pid)
            ->where('status_kalab', 'disetujui')
            ->whereNull('status_kaprodi')
            ->count();
        $totalPengajuan = Pengajuan::where('periode_id', $pid)->count();

        // ========== DONUT: keputusan final Prodi (periode aktif) ==========
        $finalDisetujui = Pengajuan::where('periode_id', $pid)->where('status_kaprodi', 'disetujui')->count();
        $finalDitolak = Pengajuan::where('periode_id', $pid)->where('status_kaprodi', 'ditolak')->count();
        $finalMenunggu = $pendingFinal; // sudah disetujui Ka Lab, belum diputus Prodi

        // ========== DATA GRAFIK (lintas periode) ==========
        // Tren pengajuan masuk per periode.
        $trenPengajuan = Periode::select(
            'periode.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('periode.id', 'periode.nama')
            ->orderBy('periode.id')
            ->get();

        // Tren keputusan final Prodi per periode (berdasarkan status_kaprodi).
        $trenKeputusan = Periode::select(
            'periode.nama',
            DB::raw("count(case when pengajuan.status_kaprodi = 'disetujui' then 1 end) as disetujui"),
            DB::raw("count(case when pengajuan.status_kaprodi = 'ditolak' then 1 end) as ditolak")
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('periode.id', 'periode.nama')
            ->orderBy('periode.id')
            ->get();

        return view('prodi.dashboard', [
            'title' => 'Dashboard Program Studi',
            'totalJudul' => $totalJudul,
            'judulDitawarkan' => $judulDitawarkan,
            'pendingFinal' => $pendingFinal,
            'totalPengajuan' => $totalPengajuan,
            'finalDisetujui' => $finalDisetujui,
            'finalDitolak' => $finalDitolak,
            'finalMenunggu' => $finalMenunggu,
            'trenPengajuan' => $trenPengajuan,
            'trenKeputusan' => $trenKeputusan,
        ]);
    }
}
