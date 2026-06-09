<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        // ========== JUDUL PENDING VALIDASI ==========
        $judulPending = Judul::with(['dosen', 'laboratorium'])
            ->withCount([
                'pengajuanPilihan1',
                'pengajuanPilihan2',
                'pengajuanPilihan3',
                'pengajuanDitetapkan'
            ])
            ->where('status_judul', 'pending_kalab')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                $item->total_peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;
                $item->jumlah_ditetapkan = $item->pengajuan_ditetapkan_count;
                return $item;
            });

        // ========== JUDUL SUDAH DIVALIDASI ==========
        $judulSelesai = Judul::with(['dosen', 'laboratorium', 'reviewerKalab'])
            ->withCount([
                'pengajuanPilihan1',
                'pengajuanPilihan2',
                'pengajuanPilihan3',
                'pengajuanDitetapkan'
            ])
            ->whereIn('status_judul', ['ditawarkan', 'ditolak_kalab'])
            ->whereNotNull('reviewed_at_kalab')
            ->orderBy('reviewed_at_kalab', 'desc')
            ->get()
            ->map(function ($item) {
                $item->total_peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;
                $item->jumlah_ditetapkan = $item->pengajuan_ditetapkan_count;
                return $item;
            });

        // ========== STATISTIK ==========
        $totalJudul = Judul::count();

        $divalidasi = Judul::where('status_judul', 'ditawarkan')
            ->whereNotNull('reviewed_at_kalab')
            ->count();

        $ditolak = Judul::where('status_judul', 'ditolak_kalab')
            ->whereNotNull('reviewed_at_kalab')
            ->count();

        $totalPeminat = DB::table('pengajuan')
            ->join('judul', function ($join) {
                $join->on('pengajuan.pilihan_1_id', '=', 'judul.id')
                    ->orOn('pengajuan.pilihan_2_id', '=', 'judul.id')
                    ->orOn('pengajuan.pilihan_3_id', '=', 'judul.id');
            })
            ->count();

        $totalDitetapkan = DB::table('pengajuan')
            ->where('status', 'ditetapkan')
            ->count();

        // ========== JSON DATA UNTUK ALPINE.JS ==========
        $judulPendingJson = $judulPending->map(function ($j) {
            return [
                'id' => $j->id,
                'kode' => $j->kode ?? '',
                'nama_judul' => $j->nama_judul ?? '',
                'deskripsi' => $j->deskripsi ?? '',
                'dosen' => $j->dosen->name ?? '-',
                'lab' => $j->laboratorium->nama ?? '-',
                'lab_id' => $j->laboratorium_id,
                'skills' => $j->relevant_skills ?? '',
                'catatan_kalab' => $j->catatan_kalab ?? '',
                'status' => 'pending_kalab',
                'status_label' => 'Menunggu Validasi',
                'total_peminat' => $j->total_peminat ?? 0,
                'jumlah_ditetapkan' => $j->jumlah_ditetapkan ?? 0,
                'kuota_maksimal' => $j->kuota_maksimal,
                'mahasiswa_ditetapkan' => null,
            ];
        })->values();

        $judulSelesaiJson = $judulSelesai->map(function ($j) {
            // ✅ Cari mahasiswa yang judulnya ditetapkan
            $pengajuanDitetapkan = Pengajuan::with('mahasiswa')
                ->where('judul_ditetapkan_id', $j->id)
                ->where('status_kalab', 'disetujui')
                ->first();

            return [
                'id' => $j->id,
                'kode' => $j->kode ?? '',
                'nama_judul' => $j->nama_judul ?? '',
                'deskripsi' => $j->deskripsi ?? '',
                'dosen' => $j->dosen->name ?? '-',
                'lab' => $j->laboratorium->nama ?? '-',
                'lab_id' => $j->laboratorium_id,
                'skills' => $j->relevant_skills ?? '',
                'catatan_kalab' => $j->catatan_kalab ?? '',
                'status' => $j->status_judul,
                'status_label' => $j->status_judul === 'ditawarkan' ? 'Divalidasi' : 'Ditolak',
                'total_peminat' => $j->total_peminat ?? 0,
                'jumlah_ditetapkan' => $j->jumlah_ditetapkan ?? 0,
                'kuota_maksimal' => $j->kuota_maksimal,
                // ✅ Data mahasiswa yang ditetapkan
                'mahasiswa_ditetapkan' => $pengajuanDitetapkan ? [
                    'nama' => $pengajuanDitetapkan->mahasiswa->name ?? '-',
                    'nim' => $pengajuanDitetapkan->mahasiswa->nim ?? '-',
                    'email' => $pengajuanDitetapkan->mahasiswa->email ?? '-',
                    'status' => $pengajuanDitetapkan->status_kaprodi ?? 'pending',
                ] : null,
            ];
        })->values();

        return view('ka_lab.validasi', [
            'title' => 'Validasi Judul',
            'judulPending' => $judulPending,
            'judulSelesai' => $judulSelesai,
            'judulPendingJson' => $judulPendingJson,
            'judulSelesaiJson' => $judulSelesaiJson,
            'totalJudul' => $totalJudul,
            'divalidasi' => $divalidasi,
            'ditolak' => $ditolak,
            'totalPeminat' => $totalPeminat,
            'totalDitetapkan' => $totalDitetapkan,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'catatan_kalab' => 'nullable|string|max:1000',
        ]);

        $judul = Judul::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            return back()->with('error', 'Anda tidak memiliki akses sebagai Kepala Lab.');
        }

        if ($judul->status_judul !== 'pending_kalab') {
            return back()->with('error', 'Judul tidak dalam status pending validasi.');
        }

        DB::beginTransaction();
        try {
            $judul->update([
                'status_judul' => 'ditawarkan',
                'catatan_kalab' => $request->catatan_kalab,
                'reviewed_by_kalab' => $user->id,
                'reviewed_at_kalab' => now(),
            ]);

            DB::table('judul_logs')->insert([
                'judul_id' => $judul->id,
                'user_id' => $user->id,
                'aksi' => 'divalidasi_kalab',
                'dari_status' => 'pending_kalab',
                'ke_status' => 'ditawarkan',
                'catatan' => $request->catatan_kalab,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($judul->dosen_id) {
                DB::table('aktivitas')->insert([
                    'user_id' => $judul->dosen_id,
                    'tipe' => 'judul_divalidasi_kalab',
                    'pesan' => "Judul '{$judul->nama_judul}' telah divalidasi oleh Kepala Lab dan siap ditawarkan ke mahasiswa.",
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Judul berhasil divalidasi dan ditawarkan ke mahasiswa!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kalab' => 'required|string|max:1000',
        ], [
            'catatan_kalab.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $judul = Judul::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            return back()->with('error', 'Anda tidak memiliki akses sebagai Kepala Lab.');
        }

        if ($judul->status_judul !== 'pending_kalab') {
            return back()->with('error', 'Judul tidak dalam status pending validasi.');
        }

        DB::beginTransaction();
        try {
            $judul->update([
                'status_judul' => 'ditolak_kalab',
                'catatan_kalab' => $request->catatan_kalab,
                'reviewed_by_kalab' => $user->id,
                'reviewed_at_kalab' => now(),
            ]);

            DB::table('judul_logs')->insert([
                'judul_id' => $judul->id,
                'user_id' => $user->id,
                'aksi' => 'ditolak_kalab',
                'dari_status' => 'pending_kalab',
                'ke_status' => 'ditolak_kalab',
                'catatan' => $request->catatan_kalab,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($judul->dosen_id) {
                DB::table('aktivitas')->insert([
                    'user_id' => $judul->dosen_id,
                    'tipe' => 'judul_ditolak_kalab',
                    'pesan' => "Judul '{$judul->nama_judul}' ditolak oleh Kepala Lab. Catatan: {$request->catatan_kalab}",
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Judul ditolak dan perlu revisi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
