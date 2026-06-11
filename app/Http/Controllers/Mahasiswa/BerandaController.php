<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::id();

        // Periode yang pengumumannya sudah dikirim — hasil resmi baru boleh dibuka ke mahasiswa.
        $announcedPeriodes = DB::table('pengumuman')
            ->whereNotNull('dikirim_at')
            ->pluck('periode_id')
            ->all();

        $total = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        // "Diproses" = sudah diajukan tapi periodenya belum diumumkan (hasil dirahasiakan s/d pengumuman).
        $pending = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereNotIn('periode_id', $announcedPeriodes)
            ->count();

        // Penolakan hanya boleh tampil setelah pengumuman resmi.
        $ditolak = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('periode_id', $announcedPeriodes)
            ->where('status', 'ditolak')
            ->count();

        $totalSemua = Pengajuan::count();
        $disetujuiSemua = Pengajuan::where('status', 'disetujui')->count();

        $latestPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->first();

        $sudahDiumumkan = $latestPengajuan
            && in_array($latestPengajuan->periode_id, $announcedPeriodes);

        // Judul yang ditetapkan hanya dibuka SETELAH pengumuman resmi.
        $disetujui = $sudahDiumumkan
            ? Pengajuan::with('judulDitetapkan')
                ->where('mahasiswa_id', $mahasiswaId)
                ->where('status', 'disetujui')
                ->latest()
                ->first()
            : null;

        // Hero netral "sedang diproses" untuk pengajuan aktif yang belum diumumkan.
        $adaProsesBerjalan = $latestPengajuan && !$sudahDiumumkan;

        $riwayat = Pengajuan::with(['judulDitetapkan', 'pilihan1'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) use ($announcedPeriodes) {
                $announced = in_array($r->periode_id, $announcedPeriodes);
                return [
                    // Sebelum diumumkan, jangan bocorkan judul yang ditetapkan Ka Lab — tampilkan pilihan mahasiswa.
                    'judul' => $announced
                        ? ($r->judulDitetapkan->nama_judul
                            ?? $r->judulDitetapkan->judul
                            ?? $r->pilihan1->nama_judul
                            ?? '-')
                        : ($r->pilihan1->nama_judul ?? $r->judul_mandiri ?? '-'),
                    // Hasil (disetujui/ditolak) dirahasiakan sampai pengumuman resmi.
                    'status' => $announced ? $r->status : 'pending',
                    'waktu' => $r->created_at->diffForHumans(),
                    'isNew' => false,
                ];
            });

        // Progress mahasiswa: none -> diproses -> diumumkan (hasil dirahasiakan s/d pengumuman).
        if ($sudahDiumumkan) {
            $statusProgress = 'diumumkan';
        } elseif ($latestPengajuan) {
            $statusProgress = 'diproses';
        } else {
            $statusProgress = 'none';
        }

        return view('mahasiswa.beranda', compact(
            'total',
            'pending',
            'ditolak',
            'totalSemua',
            'disetujuiSemua',
            'disetujui',
            'riwayat',
            'statusProgress',
            'sudahDiumumkan',
            'adaProsesBerjalan',
        ))->with('title', 'Beranda');
    }

    public function data()
    {
        $mahasiswaId = Auth::id();

        $announcedPeriodes = DB::table('pengumuman')
            ->whereNotNull('dikirim_at')
            ->pluck('periode_id')
            ->all();

        $latestPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->first();

        $sudahDiumumkan = $latestPengajuan
            && in_array($latestPengajuan->periode_id, $announcedPeriodes);

        // Hasil dirahasiakan sampai pengumuman resmi: none -> diproses -> diumumkan.
        if ($sudahDiumumkan) {
            $status = 'diumumkan';
        } elseif ($latestPengajuan) {
            $status = 'diproses';
        } else {
            $status = 'none';
        }

        $jumlah = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        $riwayat = Pengajuan::with(['judulDitetapkan', 'pilihan1'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) use ($announcedPeriodes) {
                $announced = in_array($r->periode_id, $announcedPeriodes);
                return [
                    'judul' => $announced
                        ? ($r->judulDitetapkan->nama_judul
                            ?? $r->judulDitetapkan->judul
                            ?? $r->pilihan1->nama_judul
                            ?? '-')
                        : ($r->pilihan1->nama_judul ?? $r->judul_mandiri ?? '-'),
                    'status' => $announced ? $r->status : 'pending',
                    'waktu' => $r->created_at->diffForHumans(),
                ];
            });

        $notif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereNotIn('periode_id', $announcedPeriodes)
            ->count();

        return response()->json([
            'status' => $status,
            'jumlah' => $jumlah,
            'riwayat' => $riwayat,
            'notif' => $notif,
        ]);
    }
}
