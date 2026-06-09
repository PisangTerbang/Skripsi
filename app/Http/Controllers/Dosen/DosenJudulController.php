<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenJudulController extends Controller
{
    /**
     * Siklus judul (status_judul):
     * draft -> pending_kalab -> ditawarkan / ditolak_kalab
     * Dosen mengajukan; Ka Lab memvalidasi; mahasiswa hanya melihat 'ditawarkan'.
     */
    public function index()
    {
        $judul = Judul::where('dosen_id', auth()->id())
            ->with('laboratorium')
            ->withCount([
                'pengajuanPilihan1',
                'pengajuanPilihan2',
                'pengajuanPilihan3',
                'pengajuanDitetapkan'
            ])
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->total_peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;
                $item->jumlah_ditetapkan = $item->pengajuan_ditetapkan_count;
                $item->lab_name = $item->laboratorium->nama ?? 'N/A';
                return $item;
            });

        $totalJudul = $judul->count();
        $draft = $judul->where('status_judul', 'draft')->count();
        $pending = $judul->where('status_judul', 'pending_kalab')->count();
        $ditawarkan = $judul->where('status_judul', 'ditawarkan')->count();
        $ditolak = $judul->where('status_judul', 'ditolak_kalab')->count();
        $totalPeminat = $judul->sum('total_peminat');
        $totalDitetapkan = $judul->sum('jumlah_ditetapkan');

        $title = 'Manajemen Judul';
        $laboratorium = Laboratorium::all();

        return view('dosen.judul', compact(
            'judul',
            'title',
            'laboratorium',
            'totalJudul',
            'draft',
            'pending',
            'ditawarkan',
            'ditolak',
            'totalPeminat',
            'totalDitetapkan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'kuota_maksimal' => 'nullable|integer|min:1',
            'aksi' => 'nullable|in:draft,ajukan',
        ]);

        // 'ajukan' = langsung kirim ke Ka Lab untuk validasi; default draft
        $ajukan = ($validated['aksi'] ?? 'draft') === 'ajukan';

        $judul = Judul::create([
            'dosen_id' => auth()->id(),
            'laboratorium_id' => $validated['laboratorium_id'],
            'nama_judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'kuota_maksimal' => $validated['kuota_maksimal'] ?? null,
            'status_judul' => $ajukan ? 'pending_kalab' : 'draft',
            'aktif' => DB::raw('true'),
            'is_available' => DB::raw('false'),
            'is_locked' => DB::raw('false'),
        ]);

        if ($ajukan) {
            DB::table('judul')->where('id', $judul->id)->update([
                'submitted_to_kalab_at' => now(),
                'submitted_to_kalab_by' => auth()->id(),
            ]);
        }

        return back()->with(
            'success',
            $ajukan
                ? 'Judul diajukan ke Ka Lab untuk divalidasi.'
                : 'Judul disimpan sebagai draft.'
        );
    }

    public function update(Request $request, $id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        // Hanya draft / ditolak yang boleh diedit (yang sudah ditawarkan dikunci alur)
        if (!in_array($judul->status_judul, ['draft', 'ditolak_kalab'])) {
            return back()->with('error', 'Judul yang sedang/selesai divalidasi tidak dapat diedit.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'kuota_maksimal' => 'nullable|integer|min:1',
        ]);

        $judul->update([
            'nama_judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'laboratorium_id' => $validated['laboratorium_id'],
            'kuota_maksimal' => $validated['kuota_maksimal'] ?? null,
        ]);

        return back()->with('success', 'Judul berhasil diperbarui');
    }

    /**
     * Ajukan judul ke Ka Lab untuk validasi (draft / ditolak -> pending_kalab).
     */
    public function ajukan($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        if (!in_array($judul->status_judul, ['draft', 'ditolak_kalab'])) {
            return back()->with('error', 'Judul ini tidak dapat diajukan.');
        }

        DB::table('judul')->where('id', $judul->id)->update([
            'status_judul' => 'pending_kalab',
            'catatan_kalab' => null,
            'submitted_to_kalab_at' => now(),
            'submitted_to_kalab_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Judul diajukan ke Ka Lab untuk divalidasi.');
    }

    /**
     * Tarik kembali pengajuan validasi (pending_kalab -> draft).
     */
    public function tarik($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        if ($judul->status_judul !== 'pending_kalab') {
            return back()->with('error', 'Hanya judul yang sedang menunggu validasi yang bisa ditarik.');
        }

        DB::table('judul')->where('id', $judul->id)->update([
            'status_judul' => 'draft',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan validasi ditarik. Judul kembali ke draft.');
    }

    public function destroy($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        $totalPeminat = $judul->pengajuanPilihan1()->count()
            + $judul->pengajuanPilihan2()->count()
            + $judul->pengajuanPilihan3()->count();

        if ($totalPeminat > 0) {
            return back()->with('error', 'Judul tidak dapat dihapus karena sudah dipilih oleh mahasiswa');
        }

        if ($judul->pengajuanDitetapkan()->count() > 0) {
            return back()->with('error', 'Judul tidak dapat dihapus karena sudah ditetapkan ke mahasiswa');
        }

        $judul->delete();

        return back()->with('success', 'Judul berhasil dihapus');
    }
}
