<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;

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

        $validated['dosen_id'] = auth()->id();

        // Default values untuk kolom baru
        $validated['status'] = $validated['status'] ?? 'draft'; // Default: draft
        $validated['is_available'] = ($validated['status'] === 'available'); // Auto-set berdasarkan status
        $validated['is_active'] = true; // Backward compatibility

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

        // Update is_available sesuai status
        if (isset($validated['status'])) {
            $validated['is_available'] = ($validated['status'] === 'available');
        }

        $judul->update($validated);

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
        $newIsAvailable = ($newStatus === 'available');

        $judul->update([
            'status' => $newStatus,
            'is_available' => $newIsAvailable,
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

        $judul->update([
            'status' => 'available',
            'is_available' => true,
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

        $judul->update([
            'status' => 'draft',
            'is_available' => false,
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

        $judul->update([
            'is_available' => !$judul->is_available,
        ]);

        $statusText = $judul->is_available ? 'dibuka' : 'ditutup sementara';
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
