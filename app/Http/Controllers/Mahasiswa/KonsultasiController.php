<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Judul;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KonsultasiController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::id();

        $conversations = Conversation::with(['dosen', 'lastMessage'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($conv) use ($mahasiswaId) {
                $conv->unread = $conv->unreadCountFor($mahasiswaId);
                return $conv;
            });

        $dosenList = User::where('role', 'dosen')->orderBy('name')->get();

        return view('mahasiswa.konsultasi.index', compact('conversations', 'dosenList'));
    }

    public function show($dosenId)
    {
        $mahasiswaId = Auth::id();

        $dosen = User::where('role', 'dosen')->findOrFail($dosenId);

        $conversation = Conversation::firstOrCreate(
            ['mahasiswa_id' => $mahasiswaId, 'dosen_id' => $dosenId],
            ['last_message_at' => now()]
        );

        // ✅ DB::raw untuk PostgreSQL boolean
        DB::table('messages')
            ->where('conversation_id', $conversation->id)
            ->where('sender_id', $dosenId)
            ->where('is_read', DB::raw('false'))
            ->update(['is_read' => DB::raw('true')]);

        $judulDosen = Judul::where('dosen_id', $dosenId)
            ->where('status_judul', 'ditawarkan')
            ->whereRaw('is_locked = false')
            ->get();

        return view('mahasiswa.konsultasi.show', compact('conversation', 'dosen', 'judulDosen'));
    }

    public function send(Request $request, $conversationId)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $mahasiswaId = Auth::id();
        $conversation = Conversation::where('id', $conversationId)
            ->where('mahasiswa_id', $mahasiswaId)
            ->firstOrFail();

        DB::table('messages')->insert([
            'conversation_id' => $conversation->id,
            'sender_id' => $mahasiswaId,
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
            'user_id' => $conversation->dosen_id,
            'tipe' => 'pesan_baru',
            'pesan' => Auth::user()->name . ' mengirim pesan konsultasi kepada Anda',
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }

    public function sendJudulCard(Request $request, $conversationId)
    {
        $request->validate(['judul_id' => 'required|exists:judul,id']);

        $mahasiswaId = Auth::id();
        $conversation = Conversation::where('id', $conversationId)
            ->where('mahasiswa_id', $mahasiswaId)
            ->firstOrFail();

        $judul = Judul::where('id', $request->judul_id)
            ->where('dosen_id', $conversation->dosen_id)
            ->firstOrFail();

        DB::table('messages')->insert([
            'conversation_id' => $conversation->id,
            'sender_id' => $mahasiswaId,
            'body' => 'Saya ingin menanyakan judul ini.',
            'tipe' => 'judul_card',
            'judul_id' => $judul->id,
            'judul_snapshot' => json_encode([
                'nama_judul' => $judul->nama_judul,
                'kode' => $judul->kode,
                'deskripsi' => $judul->deskripsi,
                'lab' => $judul->laboratorium->nama ?? '-',
            ]),
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('conversations')
            ->where('id', $conversation->id)
            ->update(['last_message_at' => now()]);

        DB::table('aktivitas')->insert([
            'user_id' => $conversation->dosen_id,
            'tipe' => 'pesan_baru',
            'pesan' => Auth::user()->name . ' mengirim pertanyaan tentang judul: ' . $judul->nama_judul,
            'is_read' => DB::raw('false'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }

    public function poll($conversationId)
    {
        $mahasiswaId = Auth::id();
        $conversation = Conversation::where('id', $conversationId)
            ->where('mahasiswa_id', $mahasiswaId)
            ->firstOrFail();

        // ✅ DB::raw untuk PostgreSQL boolean
        DB::table('messages')
            ->where('conversation_id', $conversation->id)
            ->where('sender_id', $conversation->dosen_id)
            ->where('is_read', DB::raw('false'))
            ->update(['is_read' => DB::raw('true')]);

        $messages = DB::table('messages')
            ->where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($mahasiswaId) {
                $snapshot = $msg->judul_snapshot ? json_decode($msg->judul_snapshot, true) : null;
                return [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'tipe' => $msg->tipe ?? 'text',
                    'is_mine' => $msg->sender_id === $mahasiswaId,
                    'time' => \Carbon\Carbon::parse($msg->created_at)->format('H:i'),
                    'date' => \Carbon\Carbon::parse($msg->created_at)->format('d M Y'),
                    'timestamp' => \Carbon\Carbon::parse($msg->created_at)->timestamp,
                    'judul_snapshot' => $snapshot,
                ];
            });

        return response()->json(['messages' => $messages, 'success' => true]);
    }
}
