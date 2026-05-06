<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DosenJudulController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $judul = Judul::where('dosen_id', $user->id)
            ->with('laboratorium')
            ->withCount([
                'pengajuan as total_peminat',
                'pengajuan as total_disetujui' => function ($query) {
                    $query->where('status', 'disetujui');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->can_edit = !$item->is_locked && $item->total_disetujui == 0;
                $item->can_delete = !$item->is_locked && $item->total_disetujui == 0;
                $item->can_toggle = !$item->is_locked;
                $item->lab_name = $item->laboratorium ? $item->laboratorium->nama : 'N/A';
                return $item;
            });

        $totalJudul = $judul->count();
        $aktif = $judul->where('aktif', true)->where('is_locked', false)->count();
        $terkunci = $judul->where('is_locked', true)->count();
        $pendingKoor = $judul->where('status_judul', 'pending_koor')->count();
        $pendingKalab = $judul->where('status_judul', 'pending_kalab')->count();
        $ditawarkan = $judul->where('status_judul', 'ditawarkan')->count();

        $laboratorium = Laboratorium::orderBy('nama')->get();

        return view('dosen.judul', [
            'title' => 'Manajemen Judul',
            'judul' => $judul,
            'laboratorium' => $laboratorium,
            'totalJudul' => $totalJudul,
            'aktif' => $aktif,
            'terkunci' => $terkunci,
            'pendingKoor' => $pendingKoor,
            'pendingKalab' => $pendingKalab,
            'ditawarkan' => $ditawarkan,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'nama_judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
        ], [
            'laboratorium_id.required' => 'Laboratorium harus dipilih',
            'laboratorium_id.exists' => 'Laboratorium tidak valid',
            'nama_judul.required' => 'Nama judul harus diisi',
            'nama_judul.max' => 'Nama judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        $user = Auth::user();

        // Generate kode berdasarkan nama lab + nomor urut
        $lab = Laboratorium::find($validated['laboratorium_id']);
        $prefix = strtoupper($lab->nama);
        $lastNumber = Judul::where('laboratorium_id', $validated['laboratorium_id'])->count();
        $kode = $prefix . '-' . ($lastNumber + 1);

        while (Judul::where('kode', $kode)->exists()) {
            $lastNumber++;
            $kode = $prefix . '-' . ($lastNumber + 1);
        }

        // Status awal: pending_koor (masuk ke workflow koor lab)
        DB::table('judul')->insert([
            'kode' => $kode,
            'nama_judul' => $validated['nama_judul'],
            'deskripsi' => $validated['deskripsi'],
            'dosen_id' => $user->id,
            'laboratorium_id' => $validated['laboratorium_id'],
            'aktif' => DB::raw('false'),
            'is_locked' => DB::raw('false'),
            'status_judul' => 'pending_koor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log
        $judulId = DB::table('judul')->where('kode', $kode)->value('id');
        DB::table('judul_logs')->insert([
            'judul_id' => $judulId,
            'user_id' => $user->id,
            'aksi' => 'diajukan',
            'dari_status' => null,
            'ke_status' => 'pending_koor',
            'catatan' => 'Judul diajukan oleh dosen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dosen.judul.index')
            ->with('success', 'Judul berhasil diajukan! Menunggu review Koordinator Lab.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'laboratorium_id' => 'required|exists:laboratorium,id',
            'nama_judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
        ], [
            'laboratorium_id.required' => 'Laboratorium harus dipilih',
            'laboratorium_id.exists' => 'Laboratorium tidak valid',
            'nama_judul.required' => 'Nama judul harus disi',
            'nama_judul.max' => 'Nama judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi harus disi',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        $user = Auth::user();
        $judul = Judul::where('id', $id)
            ->where('dosen_id', $user->id)
            ->withCount([
                'pengajuan as total_disetujui' => function ($query) {
                    $query->where('status', 'disetujui');
                }
            ])
            ->firstOrFail();

        if ($judul->is_locked) {
            return redirect()->route('dosen.judul.index')
                ->with('error', 'Judul terkunci tidak dapat diubah!');
        }

        if ($judul->total_disetujui > 0) {
            return redirect()->route('dosen.judul.index')
                ->with('error', 'Judul tidak dapat diubah karena sudah ada mahasiswa yang disetujui!');
        }

        $judul->update([
            'nama_judul' => $validated['nama_judul'],
            'deskripsi' => $validated['deskripsi'],
            'laboratorium_id' => $validated['laboratorium_id'],
        ]);

        return redirect()->route('dosen.judul.index')
            ->with('success', 'Judul berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $user = Auth::user();
        $judul = Judul::where('id', $id)
            ->where('dosen_id', $user->id)
            ->firstOrFail();

        if ($judul->is_locked) {
            return redirect()->route('dosen.judul.index')
                ->with('error', 'Judul terkunci tidak dapat diubah statusnya!');
        }

        DB::statement("UPDATE judul SET aktif = NOT aktif, updated_at = NOW() WHERE id = ?", [$id]);

        $judul->refresh();
        $statusText = $judul->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('dosen.judul.index')
            ->with('success', "Judul berhasil {$statusText}!");
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $judul = Judul::where('id', $id)
            ->where('dosen_id', $user->id)
            ->withCount([
                'pengajuan as total_disetujui' => function ($query) {
                    $query->where('status', 'disetujui');
                }
            ])
            ->firstOrFail();

        if ($judul->is_locked) {
            return redirect()->route('dosen.judul.index')
                ->with('error', 'Judul terkunci tidak dapat dihapus!');
        }

        if ($judul->total_disetujui > 0) {
            return redirect()->route('dosen.judul.index')
                ->with('error', 'Judul tidak dapat dihapus karena sudah ada mahasiswa yang disetujui!');
        }

        $judul->delete();

        // NOTIFIKASI KE KOOR LAB (sesuai lab yang dipilih)
        $koorLab = User::where('role', 'koor_lab')
            ->where('laboratorium_id', $validated['laboratorium_id'])
            ->first();

        if ($koorLab) {
            DB::table('aktivitas')->insert([
                'user_id' => $koorLab->id,
                'tipe' => 'judul_baru',
                'pesan' => auth()->user()->name . ' mengajukan judul baru: ' . $validated['nama_judul'],
                'is_read' => DB::raw('false'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return redirect()->route('dosen.judul.index')
            ->with('success', 'Judul berhasil dihapus!');
    }
}
