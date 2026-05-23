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

        // ========== STATISTIK JUDUL ==========
        // Ka Lab tidak perlu filter per lab karena cuma 1 orang untuk semua lab
        $stats = [
            // Card 1: Judul baru dari dosen, belum direview
            'draft' => Judul::where('status_judul', 'draft')->count(),

            // Card 2: Judul pending validasi Ka Lab
            'pending_kalab' => Judul::where('status_judul', 'pending_kalab')->count(),

            // Card 3: Sudah disetujui Ka Lab, tersedia untuk mahasiswa
            'ditawarkan' => Judul::where('status_judul', 'ditawarkan')->count(),

            // Card 4: Ditolak Ka Lab, perlu revisi
            'ditolak_kalab' => Judul::where('status_judul', 'ditolak_kalab')->count(),
            'ditolak' => Judul::where('status_judul', 'ditolak_kalab')->count(),

            // Total semua judul
            'total_judul' => Judul::whereIn('status_judul', [
                'draft',
                'pending_kalab',
                'ditawarkan',
                'ditolak_kalab'
            ])->count(),
        ];

        // ========== STATISTIK PENGAJUAN MAHASISWA ==========
        $pengajuanStats = [
            // Total pengajuan mahasiswa
            'total_pengajuan' => Pengajuan::count(),

            // Pengajuan yang perlu direview Ka Lab
            'pending_review' => Pengajuan::where(function ($q) {
                $q->where('status_kalab', 'pending')
                    ->orWhereNull('status_kalab');
            })->count(),

            // Pengajuan yang sudah disetujui Ka Lab
            'disetujui' => Pengajuan::where('status_kalab', 'disetujui')->count(),

            // Pengajuan yang ditolak Ka Lab
            'ditolak' => Pengajuan::where('status_kalab', 'ditolak')->count(),

            // Pengajuan yang sudah ditetapkan
            'ditetapkan' => Pengajuan::where('status', 'ditetapkan')->count(),
        ];

        // ========== AKTIVITAS TERBARU (5 terakhir) ==========
        $recentActivities = DB::table('judul_logs')
            ->join('judul', 'judul_logs.judul_id', '=', 'judul.id')
            ->join('users', 'judul_logs.user_id', '=', 'users.id')
            ->select(
                'judul_logs.*',
                'judul.nama_judul',
                'judul.kode',
                'users.name as user_name'
            )
            ->orderBy('judul_logs.created_at', 'desc')
            ->limit(5)
            ->get();

        // ========== JUDUL YANG PERLU VALIDASI (5 terakhir) ==========
        $judulPerluValidasi = Judul::with(['dosen', 'laboratorium'])
            ->where('status_judul', 'pending_kalab')
            ->latest()
            ->limit(5)
            ->get();

        // ========== PENGAJUAN YANG PERLU DIREVIEW (5 terakhir) ==========
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
            'title'
        ));
    }
}
