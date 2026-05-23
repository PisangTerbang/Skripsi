<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use App\Models\Judul;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JudulController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $query = Judul::with(['dosen', 'laboratorium'])
            ->whereIn('status_judul', [
                'draft',
                'pending_kalab',
                'ditawarkan',
                'ditolak_kalab'
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_judul', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhereHas('dosen', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('lab')) {
            $query->where('laboratorium_id', $request->lab);
        }

        if ($request->filled('status')) {
            $query->where('status_judul', $request->status);
        }

        $juduls = $query->latest()->paginate($request->per_page ?? 10)->withQueryString();

        $stats = [
            'total' => Judul::whereIn('status_judul', ['draft', 'pending_kalab', 'ditawarkan', 'ditolak_kalab'])->count(),
            'draft' => Judul::where('status_judul', 'draft')->count(),
            'pending_kalab' => Judul::where('status_judul', 'pending_kalab')->count(),
            'ditawarkan' => Judul::where('status_judul', 'ditawarkan')->count(),
            'ditolak_kalab' => Judul::where('status_judul', 'ditolak_kalab')->count(),
        ];

        $judulList = Judul::with(['dosen', 'laboratorium'])
            ->withCount([
                'pengajuanPilihan1',
                'pengajuanPilihan2',
                'pengajuanPilihan3',
                'pengajuanDitetapkan',
            ])
            ->whereIn('status_judul', ['draft', 'pending_kalab', 'ditawarkan', 'ditolak_kalab'])
            ->get()
            ->map(function ($j) {
                $j->total_peminat = $j->pengajuan_pilihan1_count
                    + $j->pengajuan_pilihan2_count
                    + $j->pengajuan_pilihan3_count;
                $j->jumlah_ditetapkan = $j->pengajuan_ditetapkan_count;
                return $j;
            });

        $totalPeminat = $judulList->sum('total_peminat');
        $totalDitetapkan = $judulList->sum('jumlah_ditetapkan');

        $judulPerLab = $judulList->groupBy('laboratorium_id')->map(function ($items) {
            return [
                'total' => $items->count(),
                'tersedia' => $items->where('status_judul', 'ditawarkan')->count(),
                'peminat' => $items->sum('total_peminat'),
            ];
        });

        $laboratorium = Laboratorium::all();

        $judulsJson = $judulList->map(function ($j) {
            return [
                'id' => $j->id,
                'kode' => $j->kode ?? '-',
                'judul' => $j->nama_judul,
                'deskripsi' => $j->deskripsi ?? '',
                'dosen' => $j->dosen->name ?? '-',
                'lab' => $j->laboratorium->nama ?? '-',
                'lab_id' => $j->laboratorium_id,
                'status' => $j->status,
                'is_available' => $j->is_available,
                'status_judul' => $j->status_judul ?? 'draft',
                'status_judul_label' => match ($j->status_judul ?? 'draft') {
                    'draft' => 'Draft',
                    'pending_kalab' => 'Pending Validasi',
                    'ditawarkan' => 'Ditawarkan',
                    'ditolak_kalab' => 'Ditolak',
                    default => 'Draft',
                },
                'catatan_penolakan_kalab' => $j->catatan_kalab ?? '',
                'total_peminat' => $j->total_peminat ?? 0,
                'jumlah_ditetapkan' => $j->jumlah_ditetapkan ?? 0,
                'kuota_maksimal' => $j->kuota_maksimal,
            ];
        })->values();

        // ✅ Definisikan semua variable sebelum compact()
        $title = 'Monitoring Judul';
        $totalJudul = $stats['total'];
        $judulDraft = $stats['draft'];
        $pendingKalab = $stats['pending_kalab'];
        $ditawarkan = $stats['ditawarkan'];
        $ditolakKalab = $stats['ditolak_kalab'];

        return view('ka_lab.judul', compact(
            'juduls',
            'stats',
            'laboratorium',
            'judulsJson',
            'title',
            'totalJudul',
            'judulDraft',
            'pendingKalab',
            'ditawarkan',
            'ditolakKalab',
            'totalPeminat',
            'totalDitetapkan',
            'judulPerLab',
        ));
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'ka_lab') {
            abort(403, 'Anda tidak memiliki akses sebagai Kepala Lab');
        }

        $judul = Judul::with([
            'dosen',
            'laboratorium',
            'pengajuanPilihan1.mahasiswa',
            'pengajuanPilihan2.mahasiswa',
            'pengajuanPilihan3.mahasiswa',
            'pengajuanDitetapkan.mahasiswa'
        ])->findOrFail($id);

        return view('ka_lab.judul', compact('judul'));
    }
}
