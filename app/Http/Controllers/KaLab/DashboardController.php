<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Validasi: user harus punya role ka_lab
        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        // Ka Lab hanya menangani laboratoriumnya sendiri.
        $myLab = $user->laboratorium_id;

        // ========== STATISTIK JUDUL (scope lab) ==========
        // Satu query agregat (FILTER) menggantikan 6 count terpisah → hemat latency DB remote.
        $j = Judul::where('laboratorium_id', $myLab)->selectRaw("
            count(*) filter (where status_judul = 'draft')         as draft,
            count(*) filter (where status_judul = 'pending_kalab') as pending_kalab,
            count(*) filter (where status_judul = 'ditawarkan')    as ditawarkan,
            count(*) filter (where status_judul = 'ditolak_kalab') as ditolak_kalab,
            count(*) filter (where status_judul in ('draft','pending_kalab','ditawarkan','ditolak_kalab')) as total_judul
        ")->first();
        $stats = [
            'draft'         => (int) $j->draft,
            'pending_kalab' => (int) $j->pending_kalab,
            'ditawarkan'    => (int) $j->ditawarkan,
            'ditolak_kalab' => (int) $j->ditolak_kalab,
            'ditolak'       => (int) $j->ditolak_kalab,
            'total_judul'   => (int) $j->total_judul,
        ];

        // ========== STATISTIK PENGAJUAN MAHASISWA ==========
        // Statistik pengajuan di-scope ke PERIODE AKTIF (ikut reset tiap ganti periode).
        // Satu query agregat menggantikan 5 count terpisah.
        $pid = \App\Models\Periode::periodeAktif()?->id;
        $p = Pengajuan::where('periode_id', $pid)->where('lab_aktif_id', $myLab)->selectRaw("
            count(*)                                                as total_pengajuan,
            count(*) filter (where status_kalab is null)            as pending_review,
            count(*) filter (where status_kalab = 'disetujui')      as disetujui,
            count(*) filter (where status_kalab = 'ditolak')        as ditolak,
            count(*) filter (where judul_ditetapkan_id is not null) as ditetapkan
        ")->first();
        $pengajuanStats = [
            'total_pengajuan' => (int) $p->total_pengajuan,
            'pending_review'  => (int) $p->pending_review,
            'disetujui'       => (int) $p->disetujui,
            'ditolak'         => (int) $p->ditolak,
            'ditetapkan'      => (int) $p->ditetapkan,
        ];

        // ========== DATA GRAFIK (lintas periode, scope lab) ==========
        // Tren pengajuan masuk per periode. Filter lab diletakkan di kondisi JOIN
        // agar periode tanpa pengajuan tetap tampil (leftJoin).
        $trenPengajuan = \App\Models\Periode::select(
            'periode.nama',
            DB::raw('count(pengajuan.id) as total')
        )
            ->leftJoin('pengajuan', function ($j) use ($myLab) {
                $j->on('periode.id', '=', 'pengajuan.periode_id')
                    ->where('pengajuan.lab_aktif_id', '=', $myLab);
            })
            ->groupBy('periode.id', 'periode.nama')
            ->orderBy('periode.id')
            ->get();

        // Tren keputusan validasi Ka Lab per periode (berdasarkan status_kalab).
        $trenKeputusan = \App\Models\Periode::select(
            'periode.nama',
            DB::raw("count(case when pengajuan.status_kalab = 'disetujui' then 1 end) as disetujui"),
            DB::raw("count(case when pengajuan.status_kalab = 'ditolak' then 1 end) as ditolak")
        )
            ->leftJoin('pengajuan', function ($j) use ($myLab) {
                $j->on('periode.id', '=', 'pengajuan.periode_id')
                    ->where('pengajuan.lab_aktif_id', '=', $myLab);
            })
            ->groupBy('periode.id', 'periode.nama')
            ->orderBy('periode.id')
            ->get();

        // ========== AKTIVITAS TERBARU (5 terakhir, scope lab) ==========
        $recentActivities = DB::table('judul_logs')
            ->join('judul', 'judul_logs.judul_id', '=', 'judul.id')
            ->join('users', 'judul_logs.user_id', '=', 'users.id')
            ->where('judul.laboratorium_id', $myLab)
            ->select(
                'judul_logs.*',
                'judul.nama_judul',
                'judul.kode',
                'users.name as user_name'
            )
            ->orderBy('judul_logs.created_at', 'desc')
            ->limit(5)
            ->get();

        // ========== JUDUL YANG PERLU VALIDASI (5 terakhir, scope lab) ==========
        $judulPerluValidasi = Judul::with(['dosen', 'laboratorium'])
            ->where('laboratorium_id', $myLab)
            ->where('status_judul', 'pending_kalab')
            ->latest()
            ->limit(5)
            ->get();

        // ========== PENGAJUAN YANG PERLU DIREVIEW (5 terakhir, scope lab) ==========
        $pengajuanPerluReview = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen'
        ])
            ->where('periode_id', $pid) // hanya periode aktif → pengajuan periode lama tak muncul
            ->where('lab_aktif_id', $myLab) // hanya pengajuan yang saat ini ditangani lab ini
            ->where('status', 'pending') // sudah difinalkan (ditolak/disetujui) tidak perlu direview lagi
            ->where(function ($q) {
                $q->where('status_kalab', 'pending')
                    ->orWhereNull('status_kalab');
            })
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        $title = 'Dashboard Kepala Lab';

        return view('ka_lab.dashboard', compact(
            'stats',
            'pengajuanStats',
            'recentActivities',
            'judulPerluValidasi',
            'pengajuanPerluReview',
            'trenPengajuan',
            'trenKeputusan',
            'title'
        ));
    }
}
