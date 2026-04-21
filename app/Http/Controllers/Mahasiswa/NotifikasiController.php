<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // ================= HALAMAN NOTIF =================
    public function index()
    {
        $userId = Auth::id();

        $aktivitas = Aktivitas::where('user_id', $userId)
            ->latest()
            ->get();

        return view('Mahasiswa.notifikasi', [
            'aktivitas' => $aktivitas,
            'title' => 'Notifikasi'
        ]);
    }

    // ================= API REALTIME =================
    public function data()
    {
        $userId = Auth::id();

        $notif = Aktivitas::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'pesan' => $item->pesan,
                    'tipe' => $item->tipe,
                    'is_read' => $item->is_read,
                    'waktu' => $item->created_at->diffForHumans(),
                ];
            });

        $unread = Aktivitas::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'data' => $notif,
            'unread' => $unread
        ]);
    }

    // ================= MARK AS READ =================
    public function readAll()
    {
        $userId = Auth::id();

        Aktivitas::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true
        ]);
    }
}