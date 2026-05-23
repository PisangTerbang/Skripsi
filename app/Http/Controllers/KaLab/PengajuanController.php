<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Halaman List Pengajuan yang perlu direview Ka Lab
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Validasi role
        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        // Filter status
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        // Query pengajuan yang perlu direview oleh Ka Lab
        $query = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
            'judulDitetapkan',
            'judulDitetapkan.dosen',
            'reviewerKalab'
        ]);

        // Filter by status
        if ($status === 'pending') {
            $query->where(function ($q) {
                $q->where('status_kalab', 'pending')
                    ->orWhereNull('status_kalab');
            });
        } elseif ($status === 'disetujui') {
            $query->where('status_kalab', 'disetujui');
        } elseif ($status === 'ditolak') {
            $query->where('status_kalab', 'ditolak');
        }

        // Search mahasiswa
        if ($search) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->latest()->paginate(10);

        // Stats
        $stats = [
            'total' => Pengajuan::count(),
            'pending' => Pengajuan::where(function ($q) {
                $q->where('status_kalab', 'pending')
                    ->orWhereNull('status_kalab');
            })->count(),
            'disetujui' => Pengajuan::where('status_kalab', 'disetujui')->count(),
            'ditolak' => Pengajuan::where('status_kalab', 'ditolak')->count(),
        ];

        return view('ka_lab.pengajuan.index', compact('pengajuan', 'stats', 'status', 'search'));
    }

    /**
     * Halaman Detail Pengajuan untuk review
     */
    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1',
            'pilihan1.dosen',
            'pilihan1.laboratorium',
            'pilihan2',
            'pilihan2.dosen',
            'pilihan2.laboratorium',
            'pilihan3',
            'pilihan3.dosen',
            'pilihan3.laboratorium',
            'judulDitetapkan',
            'judulDitetapkan.dosen',
            'reviewerKalab',
            'reviewerKoor',
            'reviewerKaprodi'
        ])->findOrFail($id);

        return view('ka_lab.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Approve pengajuan dan tetapkan judul
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'judul_terpilih' => 'required|in:pilihan_1,pilihan_2,pilihan_3,mandiri',
            'catatan_kalab' => 'nullable|string|max:1000',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = Auth::user();

        // Validasi apakah bisa direview
        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        DB::beginTransaction();
        try {
            $sumberJudul = $request->judul_terpilih;
            $judulId = null;

            // Tentukan judul yang dipilih
            if ($sumberJudul === 'pilihan_1') {
                $judulId = $pengajuan->pilihan_1_id;
            } elseif ($sumberJudul === 'pilihan_2') {
                $judulId = $pengajuan->pilihan_2_id;
            } elseif ($sumberJudul === 'pilihan_3') {
                $judulId = $pengajuan->pilihan_3_id;
            } elseif ($sumberJudul === 'mandiri') {
                // Buat judul baru dari judul mandiri
                $judulId = $this->createJudulMandiri($pengajuan);
            }

            // Approve menggunakan method di model
            $success = $pengajuan->approveByKalab(
                userId: $user->id,
                judulId: $judulId,
                sumberJudul: $sumberJudul,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menyetujui pengajuan.');
            }

            // Notifikasi ke mahasiswa
            DB::table('aktivitas')->insert([
                'user_id' => $pengajuan->mahasiswa_id,
                'tipe' => 'pengajuan_disetujui_kalab',
                'pesan' => 'Pengajuan Anda telah disetujui oleh Kepala Lab.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('ka-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kalab' => 'required|string|max:1000',
        ], [
            'catatan_kalab.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = Auth::user();

        // Validasi apakah bisa direview
        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        DB::beginTransaction();
        try {
            // Reject menggunakan method di model
            $success = $pengajuan->rejectByKalab(
                userId: $user->id,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menolak pengajuan.');
            }

            // Notifikasi ke mahasiswa
            DB::table('aktivitas')->insert([
                'user_id' => $pengajuan->mahasiswa_id,
                'tipe' => 'pengajuan_ditolak_kalab',
                'pesan' => 'Pengajuan Anda ditolak oleh Kepala Lab. Catatan: ' . $request->catatan_kalab,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('ka-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Buat judul baru dari judul mandiri mahasiswa
     */
    private function createJudulMandiri($pengajuan)
    {
        $judul = Judul::create([
            'nama_judul' => $pengajuan->judul_mandiri,
            'deskripsi' => $pengajuan->deskripsi_mandiri,
            'dosen_id' => null, // Belum ada dosen pembimbing
            'laboratorium_id' => null, // Akan ditentukan kemudian
            'status_judul' => 'ditawarkan',
            'aktif' => true,
            'is_locked' => false,
            'sumber' => 'mahasiswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log pembuatan judul mandiri
        DB::table('judul_logs')->insert([
            'judul_id' => $judul->id,
            'user_id' => Auth::id(),
            'aksi' => 'dibuat_dari_pengajuan',
            'dari_status' => null,
            'ke_status' => 'ditawarkan',
            'catatan' => 'Judul mandiri dari pengajuan mahasiswa: ' . $pengajuan->mahasiswa->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $judul->id;
    }
}
