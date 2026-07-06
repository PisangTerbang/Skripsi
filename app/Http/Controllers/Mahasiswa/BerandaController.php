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

        // Progress & hero di-scope ke PERIODE AKTIF agar ikut reset saat ganti periode.
        $activePeriodeId = \App\Models\Periode::periodeAktif()?->id;

        // Periode yang pengumumannya sudah dikirim — hasil resmi baru boleh dibuka ke mahasiswa.
        $announcedPeriodes = DB::table('pengumuman')
            ->whereNotNull('dikirim_at')
            ->pluck('periode_id')
            ->all();

        // Semua statistik beranda di-scope ke PERIODE AKTIF (ikut reset tiap ganti periode).
        $total = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $activePeriodeId)
            ->count();

        // "Diproses" = sudah diajukan tapi periodenya belum diumumkan (hasil dirahasiakan s/d pengumuman).
        $pending = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $activePeriodeId)
            ->whereNotIn('periode_id', $announcedPeriodes)
            ->count();

        // Penolakan hanya boleh tampil setelah pengumuman resmi.
        $ditolak = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $activePeriodeId)
            ->whereIn('periode_id', $announcedPeriodes)
            ->where('status', 'ditolak')
            ->count();

        // Hanya pengajuan di periode aktif yang menentukan progress — pengajuan periode lama ada di Riwayat.
        $latestPengajuan = $activePeriodeId
            ? Pengajuan::where('mahasiswa_id', $mahasiswaId)
                ->where('periode_id', $activePeriodeId)
                ->latest()
                ->first()
            : null;

        $sudahDiumumkan = $latestPengajuan
            && in_array($latestPengajuan->periode_id, $announcedPeriodes);

        // Judul yang ditetapkan hanya dibuka SETELAH pengumuman resmi.
        $disetujui = $sudahDiumumkan
            ? Pengajuan::with('judulDitetapkan')
                ->where('mahasiswa_id', $mahasiswaId)
                ->where('periode_id', $activePeriodeId)
                ->where('status', 'disetujui')
                ->latest()
                ->first()
            : null;

        // Hero netral "sedang diproses" untuk pengajuan aktif yang belum diumumkan.
        $adaProsesBerjalan = $latestPengajuan && !$sudahDiumumkan;

        $riwayat = Pengajuan::with(['judulDitetapkan', 'pilihan1'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $activePeriodeId)
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

        $activePeriodeId = \App\Models\Periode::periodeAktif()?->id;

        // Progress di-scope ke periode aktif → ikut reset saat ganti periode.
        $latestPengajuan = $activePeriodeId
            ? Pengajuan::where('mahasiswa_id', $mahasiswaId)
                ->where('periode_id', $activePeriodeId)
                ->latest()
                ->first()
            : null;

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

        // Semua di-scope ke periode aktif (ikut reset tiap ganti periode).
        $jumlah = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $activePeriodeId)
            ->count();

        $riwayat = Pengajuan::with(['judulDitetapkan', 'pilihan1'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('periode_id', $activePeriodeId)
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
            ->where('periode_id', $activePeriodeId)
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
