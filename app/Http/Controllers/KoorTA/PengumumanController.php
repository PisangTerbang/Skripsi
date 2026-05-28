<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = DB::table('pengumuman')
            ->join('users', 'pengumuman.dibuat_oleh', '=', 'users.id')
            ->join('periode', 'pengumuman.periode_id', '=', 'periode.id')
            ->select(
                'pengumuman.*',
                'users.name as nama_pembuat',
                'periode.nama as nama_periode'
            )
            ->orderBy('pengumuman.created_at', 'desc')
            ->get();

        $periode = Periode::orderBy('created_at', 'desc')->get();

        return view('koor-ta.pengumuman.index', compact('pengumuman', 'periode'));
    }

    public function create()
    {
        $periode = Periode::orderBy('created_at', 'desc')->get();
        return view('koor-ta.pengumuman.create', compact('periode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periode,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        DB::table('pengumuman')->insert([
            'periode_id' => $validated['periode_id'],
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'dibuat_oleh' => auth()->id(),
            'dikirim_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('koor-ta.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    public function show($id)
    {
        $pengumuman = DB::table('pengumuman')
            ->join('users', 'pengumuman.dibuat_oleh', '=', 'users.id')
            ->join('periode', 'pengumuman.periode_id', '=', 'periode.id')
            ->select(
                'pengumuman.*',
                'users.name as nama_pembuat',
                'periode.nama as nama_periode'
            )
            ->where('pengumuman.id', $id)
            ->firstOrFail();

        return view('koor-ta.pengumuman.show', compact('pengumuman'));
    }

    public function broadcast($id)
    {
        $pengumuman = DB::table('pengumuman')->where('id', $id)->firstOrFail();

        if ($pengumuman->dikirim_at) {
            return back()->with('error', 'Pengumuman ini sudah pernah dikirim');
        }

        DB::beginTransaction();
        try {
            // ✅ Ambil semua pengajuan di periode ini yang sudah diproses Kaprodi
            $pengajuanList = Pengajuan::with([
                'mahasiswa',
                'judulDitetapkan',
            ])
                ->where('periode_id', $pengumuman->periode_id)
                ->whereNotNull('status_kaprodi') // sudah diproses Kaprodi
                ->get();

            $notifikasi = [];

            foreach ($pengajuanList as $p) {
                if ($p->status_kaprodi === 'disetujui') {
                    // ✅ Notifikasi disetujui — dengan info judul
                    $judulText = $p->judulDitetapkan->nama_judul
                        ?? $p->judulDitetapkan->judul
                        ?? 'judul TA Anda';

                    $notifikasi[] = [
                        'user_id' => $p->mahasiswa_id,
                        'tipe' => 'pengumuman_disetujui',
                        'pesan' => "[{$pengumuman->judul}] Selamat! Pengajuan judul TA Anda telah disetujui. Judul yang ditetapkan: {$judulText}",
                        'is_read' => DB::raw('false'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } elseif ($p->status_kaprodi === 'ditolak') {
                    // ✅ Notifikasi ditolak
                    $notifikasi[] = [
                        'user_id' => $p->mahasiswa_id,
                        'tipe' => 'pengumuman_ditolak',
                        'pesan' => "[{$pengumuman->judul}] Pengajuan judul TA Anda tidak disetujui. Silakan hubungi koordinator untuk informasi lebih lanjut.",
                        'is_read' => DB::raw('false'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // ✅ Juga kirim pengumuman umum ke semua mahasiswa di periode ini
            // (mahasiswa yang belum diproses juga perlu tahu ada pengumuman)
            $mahasiswaSudahDapat = collect($notifikasi)->pluck('user_id')->toArray();

            $mahasiswaBelumDapat = Pengajuan::where('periode_id', $pengumuman->periode_id)
                ->whereNotIn('mahasiswa_id', $mahasiswaSudahDapat)
                ->pluck('mahasiswa_id')
                ->unique();

            foreach ($mahasiswaBelumDapat as $mahasiswaId) {
                $notifikasi[] = [
                    'user_id' => $mahasiswaId,
                    'tipe' => 'pengumuman',
                    'pesan' => "[{$pengumuman->judul}] {$pengumuman->isi}",
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($notifikasi)) {
                DB::table('aktivitas')->insert($notifikasi);
            }

            // Update dikirim_at
            DB::table('pengumuman')->where('id', $id)->update([
                'dikirim_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $totalPenerima = count($notifikasi);
            return back()->with('success', "Pengumuman berhasil dikirim ke {$totalPenerima} mahasiswa");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pengumuman = DB::table('pengumuman')->where('id', $id)->firstOrFail();

        if ($pengumuman->dikirim_at) {
            return back()->with('error', 'Pengumuman yang sudah dikirim tidak dapat dihapus');
        }

        DB::table('pengumuman')->where('id', $id)->delete();

        return redirect()->route('koor-ta.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
