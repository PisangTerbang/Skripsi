<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\User;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Periode aktif
        $periodeAktif = Periode::where('is_active', DB::raw('true'))->first();
        $pid = $periodeAktif?->id;

        // Stats utama — total mahasiswa/dosen/judul bersifat katalog/identitas (global).
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $totalJudul = Judul::count();

        // Pengajuan & status di-scope ke periode aktif (aktivitas berjalan).
        // Histori lintas periode tersedia di chart per-periode & menu Monitoring.
        // Satu query agregat (FILTER) menggantikan 5 count terpisah → hemat latency DB remote.
        $p = Pengajuan::where('periode_id', $pid)->selectRaw("
            count(*)                                                                as total,
            count(*) filter (where status_kalab is null)                            as pending,
            count(*) filter (where status_kalab = 'disetujui' and status_kaprodi is null) as proses,
            count(*) filter (where status_kaprodi = 'disetujui')                    as selesai,
            count(*) filter (where status_kalab = 'ditolak' or status_kaprodi = 'ditolak') as ditolak
        ")->first();
        $totalPengajuan = (int) $p->total;
        $pengajuanPending = (int) $p->pending;
        $pengajuanProses = (int) $p->proses;
        $pengajuanSelesai = (int) $p->selesai;
        $pengajuanDitolak = (int) $p->ditolak;

        // Pengajuan per periode (untuk chart)
        $pengajuanPerPeriode = Periode::withCount('pengajuan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ========== DATA GRAFIK ==========
        // Donut: distribusi keputusan pengajuan PERIODE AKTIF (ikut reset tiap ganti periode).
        $distribusiKeputusan = [
            'selesai' => $pengajuanSelesai,
            'proses' => $pengajuanProses,
            'pending' => $pengajuanPending,
            'ditolak' => $pengajuanDitolak,
        ];

        // Line: tren pengajuan masuk per periode (lintas periode, kronologis).
        $trenPengajuan = Periode::select(
            'periode.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('periode.id', 'periode.nama')
            ->orderByRaw('COALESCE(periode.tanggal_buka, periode.created_at::date) asc')
            ->get();

        // Bar: tren keputusan final per periode (disetujui Kaprodi vs ditolak Ka Lab/Prodi).
        $trenKeputusan = Periode::select(
            'periode.nama',
            DB::raw("count(case when pengajuan.status_kaprodi = 'disetujui' then 1 end) as disetujui"),
            DB::raw("count(case when pengajuan.status_kalab = 'ditolak' or pengajuan.status_kaprodi = 'ditolak' then 1 end) as ditolak")
        )
            ->leftJoin('pengajuan', 'periode.id', '=', 'pengajuan.periode_id')
            ->groupBy('periode.id', 'periode.nama')
            ->orderByRaw('COALESCE(periode.tanggal_buka, periode.created_at::date) asc')
            ->get();

        // User terbaru
        $userTerbaru = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->latest()
            ->take(5)
            ->get();

        // Pengajuan terbaru
        $pengajuanTerbaru = Pengajuan::with(['mahasiswa', 'periode', 'judulDitetapkan'])
            ->latest()
            ->take(5)
            ->get();

        return view('koor-ta.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalPengajuan',
            'totalJudul',
            'pengajuanPending',
            'pengajuanProses',
            'pengajuanSelesai',
            'pengajuanDitolak',
            'periodeAktif',
            'pengajuanPerPeriode',
            'userTerbaru',
            'pengajuanTerbaru',
            'distribusiKeputusan',
            'trenPengajuan',
            'trenKeputusan',
        ));
    }
}
