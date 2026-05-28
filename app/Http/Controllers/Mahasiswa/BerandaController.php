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

        // ================= USER STATS =================
        $total = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        $pending = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'pending')
            ->count();

        $ditolak = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'ditolak')
            ->count();

        // ================= GLOBAL STATS =================
        $totalSemua = Pengajuan::count();
        $disetujuiSemua = Pengajuan::where('status', 'disetujui')->count();

        // ================= JUDUL DISETUJUI (HERO) =================
        $disetujui = Pengajuan::with('judulDitetapkan')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'disetujui')
            ->latest()
            ->first();

        // ================= RIWAYAT TERAKHIR =================
        $riwayat = Pengajuan::with('judulDitetapkan')
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'judul' => $r->judulDitetapkan->nama_judul
                        ?? $r->judulDitetapkan->judul
                        ?? $r->judul_mandiri
                        ?? '-',
                    'status' => $r->status,
                    'waktu' => $r->created_at->diffForHumans(),
                    'isNew' => false,
                ];
            });

        // ================= STATUS UNTUK PROGRESS BAR =================
        // ✅ Cek apakah sudah diumumkan KoorTA
        $sudahDiumumkan = DB::table('aktivitas')
            ->where('user_id', $mahasiswaId)
            ->whereIn('tipe', ['pengumuman_disetujui', 'pengumuman_ditolak'])
            ->exists();

        // ✅ Tentukan status untuk progress bar
        $latestPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->first();

        if ($sudahDiumumkan) {
            $statusProgress = 'diumumkan';
        } elseif ($latestPengajuan?->status_kaprodi === 'disetujui') {
            $statusProgress = 'disetujui'; // kaprodi approve, belum diumumkan
        } elseif ($latestPengajuan?->status_kalab === 'disetujui') {
            $statusProgress = 'review'; // kalab approve, menunggu kaprodi
        } elseif ($latestPengajuan) {
            $statusProgress = 'pending';
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
        ))->with('title', 'Beranda');
    }

    public function data()
    {
        $mahasiswaId = Auth::id();

        $latestPengajuan = Pengajuan::with('judulDitetapkan')
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->first();

        // ✅ Cek apakah sudah diumumkan KoorTA
        $sudahDiumumkan = DB::table('aktivitas')
            ->where('user_id', $mahasiswaId)
            ->whereIn('tipe', ['pengumuman_disetujui', 'pengumuman_ditolak'])
            ->exists();

        // ✅ Tentukan status untuk progress bar
        if ($sudahDiumumkan) {
            $status = 'diumumkan';
        } elseif ($latestPengajuan?->status_kaprodi === 'disetujui') {
            $status = 'disetujui';
        } elseif ($latestPengajuan?->status_kalab === 'disetujui') {
            $status = 'review';
        } elseif ($latestPengajuan) {
            $status = 'pending';
        } else {
            $status = 'none';
        }

        $jumlah = Pengajuan::where('mahasiswa_id', $mahasiswaId)->count();

        $riwayat = Pengajuan::with('judulDitetapkan')
            ->where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'judul' => $r->judulDitetapkan->nama_judul
                        ?? $r->judulDitetapkan->judul
                        ?? $r->judul_mandiri
                        ?? '-',
                    'status' => $r->status,
                    'waktu' => $r->created_at->diffForHumans(),
                ];
            });

        $notif = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['pending', 'review'])
            ->count();

        return response()->json([
            'status' => $status,
            'jumlah' => $jumlah,
            'riwayat' => $riwayat,
            'notif' => $notif,
        ]);
    }
}
