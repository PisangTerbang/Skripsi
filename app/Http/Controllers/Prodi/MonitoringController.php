<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\User;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    /**
     * VIEW-ONLY: Monitoring semua pengajuan dan judul (tanpa approval)
     */
    public function index()
    {
        $user = Auth::user();

        // Validasi role
        if ($user->role !== 'prodi') {
            abort(403, 'Anda tidak memiliki akses sebagai Program Studi');
        }

        // ========== STATISTIK PENGAJUAN ==========
        $totalPengajuan = Pengajuan::count();
        $menungguDosen = Pengajuan::where('status_kalab', 'pending')->orWhereNull('status_kalab')->count();
        $disetujuiDosen = Pengajuan::where('status_kalab', 'disetujui')->count();
        $ditolakDosen = Pengajuan::where('status_kalab', 'ditolak')->count();
        $ditetapkan = Pengajuan::where('status', 'ditetapkan')->count();

        // ========== STATISTIK JUDUL ==========
        $totalJudul = Judul::count();
        $judulDraft = Judul::where('status_judul', 'draft')->count();
        $judulTersedia = Judul::where('status_judul', 'ditawarkan')->count();
        $judulNonaktif = Judul::where('status_judul', 'ditolak_kalab')->count();

        // ========== PENGAJUAN TERBARU ==========
        $pengajuanTerbaru = Pengajuan::with([
            'mahasiswa',
            'pilihan1.dosen',
            'pilihan2.dosen',
            'pilihan3.dosen',
            'judulDitetapkan.dosen',
        ])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($item) {
                // Tentukan status display
                if ($item->status === 'ditetapkan') {
                    $item->status_display = 'Ditetapkan';
                    $item->status_class = 'success';
                } elseif ($item->status_kalab === 'disetujui') {
                    $item->status_display = 'Disetujui Ka Lab';
                    $item->status_class = 'info';
                } elseif ($item->status_kalab === 'ditolak') {
                    $item->status_display = 'Ditolak Ka Lab';
                    $item->status_class = 'danger';
                } else {
                    $item->status_display = 'Menunggu Ka Lab';
                    $item->status_class = 'warning';
                }

                return $item;
            });

        // ========== JUDUL DENGAN PEMINAT TERBANYAK ==========
        $judulPopuler = Judul::with(['dosen', 'laboratorium'])
            ->withCount([
                'pengajuanPilihan1',
                'pengajuanPilihan2',
                'pengajuanPilihan3',
                'pengajuanDitetapkan'
            ])
            ->get()
            ->map(function ($item) {
                $item->total_peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;
                return $item;
            })
            ->sortByDesc('total_peminat')
            ->take(10);

        // ========== STATISTIK PER LABORATORIUM ==========
        $statsPerLab = Laboratorium::with(['judul'])
            ->get()
            ->map(function ($lab) {
                $judul = $lab->judul;

                return [
                    'nama' => $lab->nama,
                    'total_judul' => $judul->count(),
                    'tersedia' => $judul->where('status_judul', 'ditawarkan')->count(),
                    'draft' => $judul->where('status_judul', 'draft')->count(),
                    'nonaktif' => $judul->where('status_judul', 'ditolak_kalab')->count(),
                    'total_peminat' => $judul->sum(function ($j) {
                        return $j->pengajuanPilihan1()->count()
                            + $j->pengajuanPilihan2()->count()
                            + $j->pengajuanPilihan3()->count();
                    }),
                    'ditetapkan' => $judul->sum(function ($j) {
                        return $j->pengajuanDitetapkan()->count();
                    }),
                ];
            });

        // ========== STATISTIK PER DOSEN (TOP 10) ==========
        $statsPerDosen = User::where('role', 'dosen')
            ->with(['judulDosen'])
            ->get()
            ->map(function ($dosen) {
                $judul = $dosen->judulDosen;

                return [
                    'nama' => $dosen->name,
                    'nip' => $dosen->nip,
                    'total_judul' => $judul->count(),
                    'tersedia' => $judul->where('status_judul', 'ditawarkan')->count(),
                    'total_peminat' => $judul->sum(function ($j) {
                        return $j->pengajuanPilihan1()->count()
                            + $j->pengajuanPilihan2()->count()
                            + $j->pengajuanPilihan3()->count();
                    }),
                    'ditetapkan' => $judul->sum(function ($j) {
                        return $j->pengajuanDitetapkan()->count();
                    }),
                ];
            })
            ->sortByDesc('total_judul')
            ->take(10);

        // ========== MAHASISWA YANG BELUM MENGAJUKAN ==========
        $mahasiswaBelumMengajukan = User::where('role', 'mahasiswa')
            ->whereDoesntHave('pengajuanMahasiswa')
            ->count();

        return view('prodi.monitoring', [
            'title' => 'Monitoring Sistem',

            // Statistik Pengajuan
            'totalPengajuan' => $totalPengajuan,
            'menungguDosen' => $menungguDosen,
            'disetujuiDosen' => $disetujuiDosen,
            'ditolakDosen' => $ditolakDosen,
            'ditetapkan' => $ditetapkan,

            // Statistik Judul
            'totalJudul' => $totalJudul,
            'judulDraft' => $judulDraft,
            'judulTersedia' => $judulTersedia,
            'judulNonaktif' => $judulNonaktif,

            // Data Detail
            'pengajuanTerbaru' => $pengajuanTerbaru,
            'judulPopuler' => $judulPopuler,
            'statsPerLab' => $statsPerLab,
            'statsPerDosen' => $statsPerDosen,
            'mahasiswaBelumMengajukan' => $mahasiswaBelumMengajukan,
        ]);
    }
}
