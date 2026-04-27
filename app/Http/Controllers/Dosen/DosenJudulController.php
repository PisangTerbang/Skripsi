<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $laboratorium = Laboratorium::orderBy('nama')->get();

        return view('dosen.judul', [
            'title' => 'Manajemen Judul',
            'judul' => $judul,
            'laboratorium' => $laboratorium,
            'totalJudul' => $totalJudul,
            'aktif' => $aktif,
            'terkunci' => $terkunci,
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

        // Pastikan kode unik
        while (Judul::where('kode', $kode)->exists()) {
            $lastNumber++;
            $kode = $prefix . '-' . ($lastNumber + 1);
        }

        // Insert dengan raw SQL untuk PostgreSQL boolean compatibility
        DB::table('judul')->insert([
            'kode' => $kode,
            'nama_judul' => $validated['nama_judul'],
            'deskripsi' => $validated['deskripsi'],
            'dosen_id' => $user->id,
            'laboratorium_id' => $validated['laboratorium_id'],
            'aktif' => DB::raw('true'),
            'is_locked' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dosen.judul.index')
            ->with('success', 'Judul berhasil ditambahkan!');
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
            'deskripsi.required' => 'Deskripsi harus diisi',
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

        return redirect()->route('dosen.judul.index')
            ->with('success', 'Judul berhasil dihapus!');
    }
}
