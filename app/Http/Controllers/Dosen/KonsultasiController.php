<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KonsultasiController extends Controller
{
    public function index()
    {
        $dosenId = Auth::id();

        $conversations = Conversation::with(['mahasiswa', 'lastMessage'])
            ->where('dosen_id', $dosenId)
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($conv) use ($dosenId) {
                $conv->unread = $conv->unreadCountFor($dosenId);
                return $conv;
            });

        return view('dosen.konsultasi.index', compact('conversations'));
    }

    public function show($conversationId)
    {
        $dosenId = Auth::id();
        $conversation = Conversation::with(['mahasiswa'])
            ->where('id', $conversationId)
            ->where('dosen_id', $dosenId)
            ->firstOrFail();

        // ✅ DB::raw untuk PostgreSQL boolean
        DB::table('messages')
            ->where('conversation_id', $conversation->id)
            ->where('sender_id', $conversation->mahasiswa_id)
            ->where('is_read', DB::raw('false'))
            ->update(['is_read' => DB::raw('true')]);

        $mahasiswa = $conversation->mahasiswa;

        return view('dosen.konsultasi.show', compact('conversation', 'mahasiswa'));
    }

    public function send(Request $request, $conversationId)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $dosenId = Auth::id();
        $conversation = Conversation::where('id', $conversationId)
            ->where('dosen_id', $dosenId)
            ->firstOrFail();

        DB::table('messages')->insert([
            'conversation_id' => $conversation->id,
            'sender_id' => $dosenId,
            'body' => $request->body,
            'tipe' => 'text',
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('conversations')
            ->where('id', $conversation->id)
            ->update(['last_message_at' => now()]);

        DB::table('aktivitas')->insert([
            'user_id' => $conversation->mahasiswa_id,
            'tipe' => 'pesan_baru',
            'pesan' => Auth::user()->name . ' membalas pesan konsultasi Anda',
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }

    public function poll($conversationId)
    {
        $dosenId = Auth::id();
        $conversation = Conversation::where('id', $conversationId)
            ->where('dosen_id', $dosenId)
            ->firstOrFail();

        // ✅ DB::raw untuk PostgreSQL boolean
        DB::table('messages')
            ->where('conversation_id', $conversation->id)
            ->where('sender_id', $conversation->mahasiswa_id)
            ->where('is_read', DB::raw('false'))
            ->update(['is_read' => DB::raw('true')]);

        $messages = DB::table('messages')
            ->where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($dosenId) {
                $snapshot = $msg->judul_snapshot ? json_decode($msg->judul_snapshot, true) : null;
                return [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'tipe' => $msg->tipe ?? 'text',
                    'is_mine' => $msg->sender_id === $dosenId,
                    'time' => \Carbon\Carbon::parse($msg->created_at)->format('H:i'),
                    'date' => \Carbon\Carbon::parse($msg->created_at)->format('d M Y'),
                    'timestamp' => \Carbon\Carbon::parse($msg->created_at)->timestamp,
                    'judul_snapshot' => $snapshot,
                ];
            });

        return response()->json(['messages' => $messages, 'success' => true]);
    }
}
