<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenJudulController extends Controller
{
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
                // Hitung total peminat dari semua pilihan
                $item->total_peminat = $item->pengajuan_pilihan1_count
                    + $item->pengajuan_pilihan2_count
                    + $item->pengajuan_pilihan3_count;

                // Hitung yang sudah ditetapkan
                $item->jumlah_ditetapkan = $item->pengajuan_ditetapkan_count;
                $item->lab_name = $item->laboratorium->nama ?? 'N/A';

                return $item;
            });

        // Hitung statistik untuk cards (SISTEM BARU)
        $totalJudul = $judul->count();
        $draft = $judul->where('status', 'draft')->count();
        $tersedia = $judul->where('status', 'available')->where('is_available', true)->count();
        $nonaktif = $judul->where('status', 'inactive')->count();
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
            'tersedia',
            'nonaktif',
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
            'status' => 'nullable|in:draft,available,inactive',
        ]);

        // Field form 'judul' dipetakan ke kolom asli 'nama_judul'
        $validated['nama_judul'] = $validated['judul'];
        unset($validated['judul']);

        $validated['dosen_id'] = auth()->id();

        // Default values untuk kolom baru
        $validated['status'] = $validated['status'] ?? 'draft'; // Default: draft
        // Kolom boolean PostgreSQL — wajib DB::raw agar tidak dikirim sebagai integer
        $validated['is_available'] = DB::raw($validated['status'] === 'available' ? 'true' : 'false');
        $validated['aktif'] = DB::raw('true');

        Judul::create($validated);

        $message = $validated['status'] === 'available'
            ? 'Judul berhasil ditambahkan dan langsung tersedia untuk mahasiswa'
            : 'Judul berhasil disimpan sebagai draft';

        return back()->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'required|string',
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'kuota_maksimal' => 'nullable|integer|min:1',
            'status' => 'nullable|in:draft,available,inactive',
        ]);

        // Field form 'judul' dipetakan ke kolom asli 'nama_judul'
        $validated['nama_judul'] = $validated['judul'];
        unset($validated['judul']);

        $judul->update($validated);

        // Kolom boolean PostgreSQL ditulis via query builder + DB::raw
        // (Eloquent update tidak mem-persist Expression pada kolom ber-cast)
        if (isset($validated['status'])) {
            DB::table('judul')->where('id', $judul->id)->update([
                'is_available' => DB::raw($validated['status'] === 'available' ? 'true' : 'false'),
            ]);
        }

        return back()->with('success', 'Judul berhasil diperbarui');
    }

    /**
     * Toggle status judul (draft <-> available)
     * Menggantikan toggleStatus lama yang pakai is_active
     */
    public function toggleStatus($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        // Toggle antara available dan inactive
        $newStatus = $judul->status === 'available' ? 'inactive' : 'available';

        DB::table('judul')->where('id', $judul->id)->update([
            'status' => $newStatus,
            'is_available' => DB::raw($newStatus === 'available' ? 'true' : 'false'),
            'updated_at' => now(),
        ]);

        $statusText = $newStatus === 'available' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Judul berhasil {$statusText}");
    }

    /**
     * Publish judul dari draft ke available
     */
    public function publish($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        if ($judul->status !== 'draft') {
            return back()->with('error', 'Hanya judul draft yang bisa dipublikasikan');
        }

        DB::table('judul')->where('id', $judul->id)->update([
            'status' => 'available',
            'is_available' => DB::raw('true'),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Judul berhasil dipublikasikan dan tersedia untuk mahasiswa');
    }

    /**
     * Kembalikan judul ke draft
     */
    public function unpublish($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        // Cek apakah ada mahasiswa yang sudah memilih
        $totalPeminat = $judul->pengajuanPilihan1()->count()
            + $judul->pengajuanPilihan2()->count()
            + $judul->pengajuanPilihan3()->count();

        if ($totalPeminat > 0) {
            return back()->with('error', 'Judul tidak dapat dikembalikan ke draft karena sudah dipilih oleh mahasiswa');
        }

        DB::table('judul')->where('id', $judul->id)->update([
            'status' => 'draft',
            'is_available' => DB::raw('false'),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Judul dikembalikan ke draft');
    }

    /**
     * Toggle ketersediaan judul (is_available)
     * Untuk sementara menutup/membuka judul tanpa mengubah status
     */
    public function toggleAvailability($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        if ($judul->status !== 'available') {
            return back()->with('error', 'Hanya judul dengan status "available" yang bisa di-toggle ketersediaannya');
        }

        $newAvailable = !$judul->is_available;

        DB::table('judul')->where('id', $judul->id)->update([
            'is_available' => DB::raw($newAvailable ? 'true' : 'false'),
            'updated_at' => now(),
        ]);

        $statusText = $newAvailable ? 'dibuka' : 'ditutup sementara';
        return back()->with('success', "Ketersediaan judul berhasil {$statusText}");
    }

    public function destroy($id)
    {
        $judul = Judul::where('dosen_id', auth()->id())->findOrFail($id);

        // Cek apakah judul sudah dipilih mahasiswa
        $totalPeminat = $judul->pengajuanPilihan1()->count()
            + $judul->pengajuanPilihan2()->count()
            + $judul->pengajuanPilihan3()->count();

        if ($totalPeminat > 0) {
            return back()->with('error', 'Judul tidak dapat dihapus karena sudah dipilih oleh mahasiswa');
        }

        // Cek apakah ada yang sudah ditetapkan
        $totalDitetapkan = $judul->pengajuanDitetapkan()->count();
        if ($totalDitetapkan > 0) {
            return back()->with('error', 'Judul tidak dapat dihapus karena sudah ditetapkan ke mahasiswa');
        }

        $judul->delete();

        return back()->with('success', 'Judul berhasil dihapus');
    }
}
