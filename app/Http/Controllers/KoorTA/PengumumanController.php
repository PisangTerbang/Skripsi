<?php

namespace App\Http\Controllers\KoorTA;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = DB::table('pengumuman')
            ->join('users', 'pengumuman.dibuat_oleh', '=', 'users.id')
            ->join('periode', 'pengumuman.periode_id', '=', 'periode.id')
            ->select(
                'pengumuman.*',
                'users.name as nama_pembuat',
                'periode.nama as nama_periode'
            )
            ->orderBy('pengumuman.created_at', 'desc')
            ->get();

        $periode = Periode::orderBy('created_at', 'desc')->get();

        return view('koor-ta.pengumuman.index', compact('pengumuman', 'periode'));
    }

    public function create()
    {
        $periode = Periode::orderBy('created_at', 'desc')->get();
        return view('koor-ta.pengumuman.create', compact('periode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periode,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        DB::table('pengumuman')->insert([
            'periode_id' => $validated['periode_id'],
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'dibuat_oleh' => auth()->id(),
            'dikirim_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('koor-ta.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    public function show($id)
    {
        $pengumuman = DB::table('pengumuman')
            ->join('users', 'pengumuman.dibuat_oleh', '=', 'users.id')
            ->join('periode', 'pengumuman.periode_id', '=', 'periode.id')
            ->select(
                'pengumuman.*',
                'users.name as nama_pembuat',
                'periode.nama as nama_periode'
            )
            ->where('pengumuman.id', $id)
            ->firstOrFail();

        return view('koor-ta.pengumuman.show', compact('pengumuman'));
    }

    public function broadcast($id)
    {
        $pengumuman = DB::table('pengumuman')->where('id', $id)->firstOrFail();

        // Cek sudah pernah broadcast
        if ($pengumuman->dikirim_at) {
            return back()->with('error', 'Pengumuman ini sudah pernah dikirim');
        }

        DB::beginTransaction();
        try {
            // Ambil semua mahasiswa
            $mahasiswa = User::where('role', 'mahasiswa')->get();

            // Insert notifikasi ke semua mahasiswa
            $notifikasi = $mahasiswa->map(fn($m) => [
                'user_id' => $m->id,
                'tipe' => 'pengumuman',
                'pesan' => "[Pengumuman] {$pengumuman->judul}: {$pengumuman->isi}",
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            if (!empty($notifikasi)) {
                DB::table('aktivitas')->insert($notifikasi);
            }

            // Update dikirim_at
            DB::table('pengumuman')->where('id', $id)->update([
                'dikirim_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', "Pengumuman berhasil dikirim ke {$mahasiswa->count()} mahasiswa");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pengumuman = DB::table('pengumuman')->where('id', $id)->firstOrFail();

        if ($pengumuman->dikirim_at) {
            return back()->with('error', 'Pengumuman yang sudah dikirim tidak dapat dihapus');
        }

        DB::table('pengumuman')->where('id', $id)->delete();

        return redirect()->route('koor-ta.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
