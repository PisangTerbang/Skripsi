<?php

namespace App\Http\Controllers\KaLab;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $aktivitas = DB::table('aktivitas')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $aktivitas->getCollection()->transform(function ($item) {
            $item->created_at = Carbon::parse($item->created_at);
            $item->updated_at = Carbon::parse($item->updated_at);
            return $item;
        });

        return view('ka_lab.notifikasi', [
            'aktivitas' => $aktivitas,
            'title' => 'Notifikasi',
        ]);
    }

    public function data()
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json(['data' => [], 'unread' => 0], 401);
            }

            $notif = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'pesan' => $item->pesan,
                        'tipe' => $item->tipe,
                        'is_read' => $item->is_read,
                        'waktu' => Carbon::parse($item->created_at)->diffForHumans(),
                    ];
                });

            $unread = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->where('is_read', DB::raw('false'))
                ->count();

            return response()->json([
                'data' => $notif,
                'unread' => $unread,
                'success' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('KaLab Notifikasi error', ['error' => $e->getMessage()]);
            return response()->json(['data' => [], 'unread' => 0, 'success' => false], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $userId = Auth::id();

            $notification = DB::table('aktivitas')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
            }

            if ($notification->is_read) {
                return response()->json(['success' => true, 'already_read' => true]);
            }

            DB::table('aktivitas')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->update(['is_read' => DB::raw('true')]);

            $unreadCount = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->where('is_read', DB::raw('false'))
                ->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
            ]);

        } catch (\Exception $e) {
            Log::error('KaLab Mark as read error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function readAll()
    {
        try {
            $userId = Auth::id();

            $updated = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->where('is_read', DB::raw('false'))
                ->update(['is_read' => DB::raw('true')]);

            return response()->json([
                'success' => true,
                'count' => $updated,
            ]);

        } catch (\Exception $e) {
            Log::error('KaLab ReadAll error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
