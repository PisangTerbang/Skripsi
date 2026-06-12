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

        $periode = Periode::create([
            'nama' => $validated['nama'],
            'tanggal_buka' => $validated['tanggal_mulai'],
            'tanggal_tutup' => $validated['tanggal_selesai'],
            'is_active' => DB::raw($aktif ? 'true' : 'false'),
        ]);

        // Periode baru belum punya pengajuan → sinkronisasi membuka semua judul (mulai bersih).
        if ($aktif) {
            $this->sinkronkanKunciJudul($periode->id);
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

            // Mengaktifkan periode → sinkronkan kunci judul SESUAI data periode ini.
            // Periode baru: semua judul terbuka. Periode lama diaktifkan ulang:
            // judul yang dulu ditetapkan di periode itu terkunci kembali (bukan terbuka).
            if (!$wasActive) {
                $this->sinkronkanKunciJudul($periode->id);
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
     * Sinkronkan status kunci judul dengan SATU periode aktif.
     *
     * Kunci judul (is_locked) bersifat global, tetapi maknanya "judul ini sudah
     * ditetapkan ke mahasiswa pada periode aktif". Jadi saat periode aktif berganti,
     * kunci harus DIHITUNG ULANG dari data periode tsb — bukan asal dibuka semua:
     *  - Periode baru (belum ada pengajuan) → semua judul terbuka (mulai bersih).
     *  - Periode lama diaktifkan ulang → judul yang dulu ditetapkan-final di periode
     *    itu terkunci kembali, sehingga riwayat penetapannya tidak hilang.
     *
     * Judul dianggap terkunci bila ada pengajuan periode tsb yang final disetujui
     * (status='disetujui') dan menetapkan judul itu (judul_ditetapkan_id) — sama
     * persis kondisi saat is_locked di-set true oleh approveByKaprodi().
     * Data pengajuan periode mana pun TIDAK pernah dihapus.
     */
    private function sinkronkanKunciJudul(int $periodeAktifId): void
    {
        // Buka semua judul dulu.
        DB::table('judul')->update([
            'is_locked' => DB::raw('false'),
            'updated_at' => now(),
        ]);

        // Kunci kembali judul yang sudah ditetapkan-final pada periode aktif ini.
        $judulTerkunci = DB::table('pengajuan')
            ->where('periode_id', $periodeAktifId)
            ->where('status', 'disetujui')
            ->whereNotNull('judul_ditetapkan_id')
            ->pluck('judul_ditetapkan_id')
            ->unique()
            ->all();

        if (!empty($judulTerkunci)) {
            DB::table('judul')
                ->whereIn('id', $judulTerkunci)
                ->update([
                    'is_locked' => DB::raw('true'),
                    'updated_at' => now(),
                ]);
        }
    }
}
