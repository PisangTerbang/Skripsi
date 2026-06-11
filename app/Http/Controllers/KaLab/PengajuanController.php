<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.laboratorium',
            'pilihan1.dosen',
            'pilihan2.laboratorium',
            'pilihan2.dosen',
            'pilihan3.laboratorium',
            'pilihan3.dosen',
            'judulDitetapkan.dosen',
            'reviewerKalab',
        ]);

        if ($status === 'pending') {
            $query->where(function ($q) {
                $q->where('status_kalab', 'pending')->orWhereNull('status_kalab');
            });
        } elseif ($status === 'disetujui') {
            $query->where('status_kalab', 'disetujui');
        } elseif ($status === 'ditolak') {
            $query->where('status_kalab', 'ditolak');
        }

        if ($search) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->latest()->paginate(10);

        $stats = [
            'total' => Pengajuan::count(),
            'pending' => Pengajuan::where(function ($q) {
                $q->where('status_kalab', 'pending')->orWhereNull('status_kalab');
            })->count(),
            'disetujui' => Pengajuan::where('status_kalab', 'disetujui')->count(),
            'ditolak' => Pengajuan::where('status_kalab', 'ditolak')->count(),
        ];

        return view('ka_lab.pengajuan.index', compact('pengajuan', 'stats', 'status', 'search'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'periode',
            'pilihan1.dosen',
            'pilihan1.laboratorium',
            'pilihan2.dosen',
            'pilihan2.laboratorium',
            'pilihan3.dosen',
            'pilihan3.laboratorium',
            'judulDitetapkan.dosen',
            'reviewerKalab',
            'reviewerKaprodi',
        ])->findOrFail($id);

        $pilihanStatus = [];

        foreach ([
            'pilihan_1' => $pengajuan->pilihan_1_id,
            'pilihan_2' => $pengajuan->pilihan_2_id,
            'pilihan_3' => $pengajuan->pilihan_3_id,
        ] as $key => $judulId) {
            if (!$judulId) {
                $pilihanStatus[$key] = null;
                continue;
            }

            $sudahDiambil = Pengajuan::with('mahasiswa')
                ->where('judul_ditetapkan_id', $judulId)
                ->where('status_kalab', 'disetujui')
                ->where('id', '!=', $pengajuan->id)
                ->first();

            $pilihanStatus[$key] = $sudahDiambil ? [
                'diambil' => true,
                'nama' => $sudahDiambil->mahasiswa->name ?? '-',
                'nim' => $sudahDiambil->mahasiswa->nim ?? '-',
                'email' => $sudahDiambil->mahasiswa->email ?? '-',
            ] : ['diambil' => false];
        }

        $semuaPilihanDiambil = collect($pilihanStatus)
            ->filter(fn($s) => $s !== null)
            ->every(fn($s) => $s['diambil'] === true);

        // ✅ Daftar laboratorium untuk dropdown judul mandiri
        $laboratorium = Laboratorium::orderBy('nama')->get();

        return view('ka_lab.pengajuan.show', compact(
            'pengajuan',
            'pilihanStatus',
            'semuaPilihanDiambil',
            'laboratorium'
        ));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'judul_terpilih' => 'required|in:pilihan_1,pilihan_2,pilihan_3,mandiri',
            'catatan_kalab' => 'nullable|string|max:1000',
            // ✅ Wajib pilih lab kalau judul mandiri
            'laboratorium_id' => 'required_if:judul_terpilih,mandiri|nullable|exists:laboratorium,id',
        ], [
            'laboratorium_id.required_if' => 'Laboratorium wajib dipilih untuk judul mandiri.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = Auth::user();
        $sumberJudul = $request->judul_terpilih;
        $judulId = null;

        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        if (!$pengajuan->isPeriodeAktif()) {
            return back()->with('error', 'Pengajuan ini berada di periode yang sudah ditutup (arsip) dan tidak dapat diproses.');
        }

        if ($sumberJudul === 'pilihan_1') {
            $judulId = $pengajuan->pilihan_1_id;
        } elseif ($sumberJudul === 'pilihan_2') {
            $judulId = $pengajuan->pilihan_2_id;
        } elseif ($sumberJudul === 'pilihan_3') {
            $judulId = $pengajuan->pilihan_3_id;
        }

        // Cek apakah judul sudah diambil mahasiswa lain
        if ($judulId && $sumberJudul !== 'mandiri') {
            $sudahDiambil = Pengajuan::where('judul_ditetapkan_id', $judulId)
                ->where('status_kalab', 'disetujui')
                ->where('id', '!=', $pengajuan->id)
                ->exists();

            if ($sudahDiambil) {
                return back()->with('error', 'Judul ini sudah diambil oleh mahasiswa lain. Pilih judul alternatif lainnya.');
            }
        }

        DB::beginTransaction();
        try {
            if ($sumberJudul === 'mandiri') {
                // ✅ Pass laboratorium_id dari request
                $judulId = $this->createJudulMandiri($pengajuan, $request->laboratorium_id);
            }

            $success = $pengajuan->approveByKalab(
                userId: $user->id,
                judulId: $judulId,
                sumberJudul: $sumberJudul,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menyetujui pengajuan.');
            }

            DB::commit();

            return redirect()
                ->route('ka-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil disetujui! Mahasiswa akan mendapat notifikasi setelah pengumuman resmi dari Koordinator TA.');

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
            'catatan_kalab.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = Auth::user();

        if (!$pengajuan->canBeReviewedByKalab()) {
            return back()->with('error', 'Pengajuan ini tidak dapat direview saat ini.');
        }

        if (!$pengajuan->isPeriodeAktif()) {
            return back()->with('error', 'Pengajuan ini berada di periode yang sudah ditutup (arsip) dan tidak dapat diproses.');
        }

        DB::beginTransaction();
        try {
            $success = $pengajuan->rejectByKalab(
                userId: $user->id,
                catatan: $request->catatan_kalab
            );

            if (!$success) {
                throw new \Exception('Gagal menolak pengajuan.');
            }

            DB::commit();

            return redirect()
                ->route('ka-lab.pengajuan.index')
                ->with('success', 'Pengajuan berhasil ditolak. Mahasiswa akan mendapat notifikasi setelah pengumuman resmi dari Koordinator TA.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ✅ Terima laboratorium_id sebagai parameter
    private function createJudulMandiri($pengajuan, $laboratoriumId = null)
    {
        $judul = Judul::create([
            'nama_judul' => $pengajuan->judul_mandiri,
            'deskripsi' => $pengajuan->deskripsi_mandiri,
            // Dosen pembimbing yang dipilih mahasiswa untuk judul mandiri
            'dosen_id' => $pengajuan->dosen_pembimbing_id,
            'laboratorium_id' => $laboratoriumId,
            'kode' => Judul::generateKode($laboratoriumId),
            'status_judul' => 'ditawarkan',
            'aktif' => DB::raw('true'),
            'is_locked' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
