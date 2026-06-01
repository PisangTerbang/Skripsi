<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Laboratorium;
use App\Models\Judul;
use App\Models\User;

class DosenPengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosenId = $user->id;

        // ✅ Fix: hanya tampilkan pengajuan yang relevan untuk dosen ini
        // yaitu: pengajuan mandiri ATAU pengajuan pilih judul milik dosen ini
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'judul.laboratorium',
            'judul.dosen',
        ])
            ->where(function ($q) use ($dosenId) {
                // Pengajuan mandiri — semua dosen bisa lihat
                $q->where('jenis', 'mandiri')
                    // ATAU pengajuan pilih judul milik dosen ini
                    ->orWhereHas('judul', function ($q2) use ($dosenId) {
                    $q2->where('dosen_id', $dosenId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->jenis === 'mandiri'
                    ? 'mandiri_' . $item->id
                    : ($item->judul_id ?? 'no_judul_' . $item->id);
            });

        $laboratorium = Laboratorium::all();
        $totalPengajuan = $pengajuan->flatten()->count();
        $pending = $pengajuan->flatten()->where('status', 'pending')->count();
        $disetujui = $pengajuan->flatten()->where('status', 'disetujui')->count();
        $ditolak = $pengajuan->flatten()->where('status', 'ditolak')->count();

        $pengajuanJson = $pengajuan->map(function ($items) use ($dosenId) {
            $first = $items->first();
            $pemenang = $items->firstWhere('status', 'disetujui');

            $isOwner = false;
            if ($first->jenis === 'pilih' && $first->judul) {
                $isOwner = $first->judul->dosen_id === $dosenId;
            } elseif ($first->jenis === 'mandiri') {
                $isOwner = true;
            }

            return [
                'judul_id' => $first->jenis === 'pilih'
                    ? ($first->judul_id ?? $first->id)
                    : 'mandiri_' . $first->id,
                'judul' => $first->jenis === 'pilih'
                    ? ($first->judul->nama_judul ?? '-')
                    : ($first->judul_mandiri ?? '-'),
                'kode' => $first->jenis === 'pilih' ? ($first->judul->kode ?? '') : '',
                'deskripsi' => $first->jenis === 'mandiri' ? ($first->deskripsi_mandiri ?? '') : '',
                'jenis' => $first->jenis,
                'pemenang' => $pemenang ? ($pemenang->mahasiswa->name ?? '') : '',
                'is_owner' => $isOwner,
                'items' => $items->map(function ($p) use ($dosenId) {
                    $sudahPunyaJudul = Pengajuan::where('mahasiswa_id', $p->mahasiswa_id)
                        ->where('status', 'disetujui')
                        ->exists();

                    $itemIsOwner = false;
                    if ($p->jenis === 'pilih' && $p->judul) {
                        $itemIsOwner = $p->judul->dosen_id === $dosenId;
                    } elseif ($p->jenis === 'mandiri') {
                        $itemIsOwner = true;
                    }

                    return [
                        'id' => $p->id,
                        'mahasiswa' => $p->mahasiswa->name ?? '-',
                        'status' => $p->status,
                        'prioritas' => $p->prioritas ?? 1,
                        'jenis' => $p->jenis,
                        'judul_text' => $p->jenis === 'mandiri'
                            ? ($p->judul_mandiri ?? '-')
                            : ($p->judul->nama_judul ?? '-'),
                        'alasan' => $p->alasan ?? '',
                        'catatan_dosen' => $p->catatan_dosen ?? '',
                        'waktu' => $p->created_at->diffForHumans(),
                        'sudah_punya_judul' => $sudahPunyaJudul,
                        'is_owner' => $itemIsOwner,
                        'status_kaprodi' => $p->status_kaprodi ?? '',
                    ];
                })->values(),
            ];
        })->values();

        return view('dosen.pengajuan', [
            'pengajuan' => $pengajuan,
            'pengajuanJson' => $pengajuanJson,
            'laboratorium' => $laboratorium,
            'totalPengajuan' => $totalPengajuan,
            'pending' => $pending,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'dosenId' => $dosenId,
            'title' => 'Review Pengajuan Mahasiswa',
        ]);
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catan_dosen' => 'nullable|string|max:1000',
            'laboratorium_id' => 'nullable|exists:laboratorium,id'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $pengajuan = Pengajuan::with(['judul', 'mahasiswa'])->findOrFail($id);
                $statusBaru = $request->status;

                // CEK KEPEMILIKAN
                if ($pengajuan->jenis === 'pilih' && $pengajuan->judul) {
                    if ($pengajuan->judul->dosen_id !== auth()->id()) {
                        throw new \Exception('Anda tidak memiliki hak untuk menindaklanjuti pengajuan ini.');
                    }
                }

                $namaJudul = $pengajuan->jenis === 'mandiri'
                    ? $pengajuan->judul_mandiri
                    : ($pengajuan->judul->nama_judul ?? '-');

                // Cek mahasiswa sudah punya judul disetujui
                if ($statusBaru === 'disetujui') {
                    $sudahPunya = Pengajuan::where('mahasiswa_id', $pengajuan->mahasiswa_id)
                        ->where('status', 'disetujui')
                        ->exists();

                    if ($sudahPunya) {
                        throw new \Exception('Mahasiswa sudah memiliki judul yang disetujui');
                    }
                }

                // Update status dan catatan
                $pengajuan->status = $statusBaru;
                $pengajuan->catatan_dosen = $request->catatan_dosen;

                // MODE PILIH - DISETUJUI
                if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'pilih') {
                    if ($pengajuan->judul) {
                        DB::table('judul')->where('id', $pengajuan->judul_id)->update([
                            'is_locked' => DB::raw('true'),
                            'updated_at' => now(),
                        ]);
                    }

                    // Tolak pengajuan lain untuk judul ini
                    $otherSubmissions = Pengajuan::where('judul_id', $pengajuan->judul_id)
                        ->where('id', '!=', $pengajuan->id)
                        ->where('status', 'pending')
                        ->get();

                    foreach ($otherSubmissions as $other) {
                        $other->update([
                            'status' => 'ditolak',
                            'catatan_dosen' => 'Judul sudah diambil oleh ' . $pengajuan->mahasiswa->name
                        ]);

                        DB::table('aktivitas')->insert([
                            'user_id' => $other->mahasiswa_id,
                            'tipe' => 'penolakan',
                            'pesan' => 'Pengajuan judul "' . $namaJudul . '" ditolak. Judul sudah diambil mahasiswa lain.',
                            'is_read' => DB::raw('false'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // MODE MANDIRI - DISETUJUI
                if ($statusBaru === 'disetujui' && $pengajuan->jenis === 'mandiri') {
                    if (!$request->laboratorium_id) {
                        throw new \Exception('Laboratorium wajib dipilih untuk judul mandiri');
                    }

                    $lab = Laboratorium::find($request->laboratorium_id);
                    $prefix = strtoupper($lab->nama);
                    $count = Judul::where('laboratorium_id', $request->laboratorium_id)->count();
                    $kode = $prefix . '-' . ($count + 1);

                    DB::table('judul')->insert([
                        'kode' => $kode,
                        'nama_judul' => $pengajuan->judul_mandiri,
                        'deskripsi' => $pengajuan->deskripsi_mandiri,
                        'laboratorium_id' => $request->laboratorium_id,
                        'dosen_id' => auth()->id(),
                        'aktif' => DB::raw('true'),
                        'is_locked' => DB::raw('true'),
                        'status_judul' => 'ditawarkan',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $judulBaruId = DB::table('judul')->where('kode', $kode)->value('id');
                    $pengajuan->judul_id = $judulBaruId;
                }

                // SET STATUS KAPRODI = PENDING (jika disetujui dosen)
                if ($statusBaru === 'disetujui') {
                    $pengajuan->status_kaprodi = 'pending';
                }

                $pengajuan->save();

                // NOTIFIKASI KE MAHASISWA
                if ($statusBaru === 'disetujui') {
                    $pesan = 'Pengajuan judul "' . $namaJudul . '" telah disetujui dosen! Menunggu persetujuan final Kaprodi.';
                    $tipe = 'persetujuan';
                } else {
                    $catatan = $request->catatan_dosen ? ' Catatan: ' . $request->catatan_dosen : '';
                    $pesan = 'Pengajuan judul "' . $namaJudul . '" ditolak.' . $catatan;
                    $tipe = 'penolakan';
                }

                DB::table('aktivitas')->insert([
                    'user_id' => $pengajuan->mahasiswa_id,
                    'tipe' => $tipe,
                    'pesan' => $pesan,
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // NOTIFIKASI KE DOSEN (SELF)
                DB::table('aktivitas')->insert([
                    'user_id' => auth()->id(),
                    'tipe' => $tipe,
                    'pesan' => 'Anda telah ' . ($statusBaru === 'disetujui' ? 'menyetujui' : 'menolak') . ' pengajuan "' . $namaJudul . '" dari ' . $pengajuan->mahasiswa->name,
                    'is_read' => DB::raw('false'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // NOTIFIKASI KE KAPRODI (jika disetujui)
                if ($statusBaru === 'disetujui') {
                    $kaprodi = User::where('role', 'kaprodi')->first();
                    if ($kaprodi) {
                        DB::table('aktivitas')->insert([
                            'user_id' => $kaprodi->id,
                            'tipe' => 'pending_approval',
                            'pesan' => 'Pengajuan judul "' . $namaJudul . '" oleh ' . $pengajuan->mahasiswa->name . ' telah disetujui dosen dan menunggu persetujuan final Anda.',
                            'is_read' => DB::raw('false'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });

            return back()->with('success', 'Pengajuan berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak') . '!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
