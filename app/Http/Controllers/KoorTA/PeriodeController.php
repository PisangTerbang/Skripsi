<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{
    public function index()
    {
        $periode = Periode::withCount('pengajuan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('koor-ta.periode.index', compact('periode'));
    }

    public function create()
    {
        return view('koor-ta.periode.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ], [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
        ]);

        $aktif = $request->boolean('is_active');

        // Jika set aktif, nonaktifkan periode lain dulu
        if ($aktif) {
            DB::table('periode')->update(['is_active' => DB::raw('false')]);
        }

        Periode::create([
            'nama' => $validated['nama'],
            'tanggal_buka' => $validated['tanggal_mulai'],
            'tanggal_tutup' => $validated['tanggal_selesai'],
            'is_active' => DB::raw($aktif ? 'true' : 'false'),
        ]);

        // Mengaktifkan periode baru = mulai bersih: reset aktivitas (buka kembali semua judul).
        if ($aktif) {
            $this->resetAktivitasJudul();
        }

        return redirect()->route('koor-ta.periode.index')
            ->with('success', 'Periode berhasil ditambahkan');
    }

    public function edit(Periode $periode)
    {
        // ✅ fix: loadCount supaya view bisa akses pengajuan_count
        $periode->loadCount('pengajuan');

        return view('koor-ta.periode.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ], [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
        ]);

        $periode->update([
            'nama' => $validated['nama'],
            'tanggal_buka' => $validated['tanggal_mulai'],
            'tanggal_tutup' => $validated['tanggal_selesai'],
        ]);

        return redirect()->route('koor-ta.periode.index')
            ->with('success', 'Periode berhasil diperbarui');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->pengajuan()->count() > 0) {
            return back()->with('error', 'Periode tidak dapat dihapus karena sudah memiliki pengajuan');
        }

        $periode->delete();

        return redirect()->route('koor-ta.periode.index')
            ->with('success', 'Periode berhasil dihapus');
    }

    public function toggleActive(Periode $periode)
    {
        DB::beginTransaction();
        try {
            // ✅ fix: simpan nilai lama sebelum update
            $wasActive = $periode->is_active;

            if (!$wasActive) {
                // Aktifkan — nonaktifkan semua periode lain dulu
                DB::table('periode')->update(['is_active' => DB::raw('false')]);
            }

            $periode->update([
                'is_active' => DB::raw($wasActive ? 'false' : 'true'),
            ]);

            // Mengaktifkan periode = ganti periode aktif → reset aktivitas (judul dibuka kembali).
            // Data pengajuan periode lama tetap utuh sebagai riwayat.
            if (!$wasActive) {
                $this->resetAktivitasJudul();
            }

            DB::commit();

            // ✅ fix: pakai $wasActive bukan $periode->is_active
            $status = $wasActive ? 'dinonaktifkan' : 'diaktifkan';

            return back()->with('success', "Periode berhasil {$status}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reset aktivitas saat ganti periode aktif:
     * buka kembali semua judul yang sempat terkunci di periode sebelumnya
     * sehingga mahasiswa bisa memilih/mengajukan lagi di periode baru.
     * Data pengajuan periode lama TIDAK dihapus — tetap tersimpan sebagai riwayat.
     */
    private function resetAktivitasJudul(): void
    {
        DB::table('judul')->update([
            'is_locked' => DB::raw('false'),
            'updated_at' => now(),
        ]);
    }
}
