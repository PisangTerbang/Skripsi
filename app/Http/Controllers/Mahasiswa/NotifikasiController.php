<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    /**
     * Halaman Notifikasi
     */
    public function index()
    {
        $userId = Auth::id();

        $aktivitas = DB::table('aktivitas')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(50); // Increase to 50 for better UX

        // Convert created_at to Carbon
        $aktivitas->getCollection()->transform(function ($item) {
            $item->created_at = Carbon::parse($item->created_at);
            $item->updated_at = Carbon::parse($item->updated_at);
            return $item;
        });

        return view('mahasiswa.notifikasi', [
            'aktivitas' => $aktivitas,
            'title' => 'Notifikasi'
        ]);
    }

    /**
     * API Data Notifikasi (untuk polling & refresh)
     */
    public function data()
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'data' => [],
                    'unread' => 0
                ], 401);
            }

            // Get latest 50 notifications
            $notif = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'pesan' => $item->pesan,
                        'link' => $item->link,
                        'tipe' => $item->tipe,
                        'is_read' => $item->is_read,
                        'waktu' => Carbon::parse($item->created_at)->diffForHumans(),
                    ];
                });

            $unread = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->where('is_read', '=', DB::raw('false'))
                ->count();

            return response()->json([
                'data' => $notif,
                'unread' => $unread,
                'success' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Notifikasi data error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'data' => [],
                'unread' => 0,
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Mark Single Notification as Read
     */
    public function markAsRead($id)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if notification exists and belongs to user
            $notification = DB::table('aktivitas')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }

            // Already read, no need to update
            if ($notification->is_read) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi sudah dibaca',
                    'already_read' => true
                ]);
            }

            // Mark as read
            $updated = DB::table('aktivitas')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->update(['is_read' => DB::raw('true')]);

            // Get new unread count
            $unreadCount = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->where('is_read', '=', DB::raw('false'))
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi telah ditandai sebagai dibaca',
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            Log::error('Mark as read error', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark All Notifications as Read
     */
    public function readAll()
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $updated = DB::table('aktivitas')
                ->where('user_id', $userId)
                ->where('is_read', '=', DB::raw('false'))
                ->update(['is_read' => DB::raw('true')]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menandai ' . $updated . ' notifikasi',
                'count' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('ReadAll error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
