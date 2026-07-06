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
        // Urutan kronologis (tanggal buka, fallback created_at) — terbaru di atas.
        $periode = Periode::withCount('pengajuan')
            ->urutKronologis()
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
            $this->tolakPengajuanPendingPeriodeLain($periode->id);
            $this->sinkronkanKunciJudul($periode->id);
            $this->bersihkanNotifikasi();
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
                $this->tolakPengajuanPendingPeriodeLain($periode->id);
                $this->sinkronkanKunciJudul($periode->id);
                $this->bersihkanNotifikasi();
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
     * Sinkronkan status kunci judul dengan SATU periode aktif ("reset agresif").
     *
     * Kunci judul (is_locked) bersifat global, tetapi maknanya "judul ini sudah
     * ditetapkan ke mahasiswa pada periode aktif". Prinsipnya: ganti periode = SEMUA
     * judul terbuka kembali, sehingga judul yang dulu terpakai bisa diambil lagi.
     *
     * Setelah membuka semua, kunci hanya dipasang kembali untuk melindungi periode
     * yang MASIH BERJALAN dari tabrakan judul:
     *  - Periode arsip (sudah diumumkan resmi ATAU ditutup) → dibiarkan terbuka semua.
     *    Inilah yang membuat judul "nyangkut" dari siklus lampau bisa diambil lagi.
     *  - Periode berjalan (belum diumumkan) → judul yang sudah ditetapkan-final di
     *    periode itu dikunci lagi agar tidak diberikan ke dua mahasiswa sekaligus.
     *
     * Judul dianggap terkunci bila ada pengajuan periode tsb yang final disetujui
     * (status='disetujui') dan menetapkan judul itu (judul_ditetapkan_id) — sama
     * persis kondisi saat is_locked di-set true oleh approveByKaprodi().
     * Data pengajuan periode mana pun TIDAK pernah dihapus.
     */
    /**
     * Otomatis menolak pengajuan yang masih 'pending' di periode SELAIN yang baru
     * diaktifkan. Prinsipnya: begitu periode berganti, pengajuan lama yang belum
     * diputus dianggap kedaluwarsa (tak akan direview lagi) → status difinalkan
     * menjadi 'ditolak'. Data pengajuan TIDAK dihapus, hanya statusnya diubah.
     */
    private function tolakPengajuanPendingPeriodeLain(int $periodeAktifId): void
    {
        DB::table('pengajuan')
            ->where('periode_id', '!=', $periodeAktifId)
            ->where('status', 'pending')
            ->update([
                'status' => 'ditolak',
                // Finalkan juga tahap yang belum diputus agar rekam data konsisten
                // (tidak ada "ditolak tapi status_kalab masih kosong"). Keputusan yang
                // sudah ada (mis. kalab 'disetujui') tetap dipertahankan.
                'status_kalab' => DB::raw("CASE WHEN status_kalab IS NULL OR status_kalab = '' THEN 'ditolak' ELSE status_kalab END"),
                'catatan_kaprodi' => DB::raw("COALESCE(NULLIF(catatan_kaprodi, ''), 'Otomatis ditolak: periode berganti tanpa keputusan final.')"),
                'updated_at' => now(),
            ]);
    }

    private function sinkronkanKunciJudul(int $periodeAktifId): void
    {
        // 1) Buka SEMUA judul dulu (reset agresif — mulai bersih tiap ganti periode).
        DB::table('judul')->update([
            'is_locked' => DB::raw('false'),
            'updated_at' => now(),
        ]);

        // 2) Periode arsip → cukup terbuka semua, tidak perlu dikunci ulang.
        $periode = DB::table('periode')->where('id', $periodeAktifId)->first();
        $sudahDiumumkan = DB::table('pengumuman')
            ->where('periode_id', $periodeAktifId)
            ->whereNotNull('dikirim_at')
            ->exists();

        if (($periode && $periode->ditutup) || $sudahDiumumkan) {
            return;
        }

        // 3) Periode berjalan → kunci kembali judul yang sudah ditetapkan-final di sini.
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

    /**
     * Aksi manual: sinkronkan ulang kunci judul terhadap periode aktif sekarang.
     *
     * Jaring pengaman bila ada judul yang "nyangkut" (terkunci padahal seharusnya
     * terbuka). Koordinator TA bisa menjalankannya kapan saja tanpa mengubah periode.
     */
    public function sinkronKunci()
    {
        $aktif = Periode::periodeAktif();

        if (!$aktif) {
            // Tidak ada periode aktif → buka semua judul saja.
            DB::table('judul')->update([
                'is_locked' => DB::raw('false'),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Tidak ada periode aktif — semua judul dibuka kembali.');
        }

        $this->sinkronkanKunciJudul($aktif->id);

        return back()->with('success', "Kunci judul disinkronkan ulang dengan periode aktif ({$aktif->nama}).");
    }

    /**
     * Bersihkan notifikasi saat ganti periode aktif.
     *
     * Notifikasi (aktivitas) bersifat transient — milik aktivitas periode yang
     * baru berakhir — jadi ikut "reset" agar lonceng semua pengguna mulai bersih
     * di periode baru. Riwayat resmi tetap aman di pengajuan/pengumuman/judul_logs,
     * dan data lama bisa ditelusuri lewat filter periode (monitoring koor_ta,
     * riwayat dosen).
     */
    private function bersihkanNotifikasi(): void
    {
        DB::table('aktivitas')->delete();
    }
}
