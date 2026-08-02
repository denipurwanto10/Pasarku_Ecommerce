<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Daftar percakapan milik user yang login (buyer atau seller).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::query()
            ->where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['buyer', 'seller', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('chat.index', compact('conversations'));
    }

    /**
     * Buyer memulai (atau melanjutkan) percakapan dengan penjual, opsional terkait produk tertentu.
     */
    public function start(Request $request, User $seller)
    {
        $user = $request->user();

        if ($seller->role !== 'seller') {
            abort(404);
        }

        if ($user->role !== 'buyer') {
            abort(403, 'Hanya pembeli yang bisa memulai chat dengan toko.');
        }

        $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        $conversation = Conversation::firstOrCreate(
            ['buyer_id' => $user->id, 'seller_id' => $seller->id],
            ['product_id' => $request->input('product_id')]
        );

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Tampilkan satu thread percakapan dan tandai pesan lawan bicara sebagai sudah dibaca.
     */
    public function show(Request $request, Conversation $conversation)
    {
        $this->authorizeParticipant($request, $conversation);

        $user = $request->user();

        $conversation->load(['buyer', 'seller', 'product']);

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherUser = $conversation->otherParticipant($user);

        $quickReplies = $user->role === 'seller'
            ? $user->quickReplies()->orderBy('sort_order')->get()
            : collect();

        return view('chat.show', compact('conversation', 'messages', 'otherUser', 'quickReplies'));
    }

    /**
     * Kirim pesan baru dalam percakapan.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeParticipant($request, $conversation);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_id' => $message->sender_id,
                    'created_at' => $message->created_at->format('H:i'),
                ],
            ]);
        }

        return back();
    }

    /**
     * Endpoint polling ringan: ambil pesan baru + status online lawan bicara.
     * Dipanggil berkala lewat JavaScript di halaman chat.
     */
    public function poll(Request $request, Conversation $conversation)
    {
        $this->authorizeParticipant($request, $conversation);

        $user = $request->user();
        $otherUser = $conversation->otherParticipant($user);

        $afterId = (int) $request->query('after', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->orderBy('created_at')
            ->get();

        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(fn (Message $m) => [
                'id' => $m->id,
                'body' => $m->body,
                'sender_id' => $m->sender_id,
                'created_at' => $m->created_at->format('H:i'),
            ]),
            'other_online' => $otherUser->fresh()->isOnline(),
        ]);
    }

    protected function authorizeParticipant(Request $request, Conversation $conversation): void
    {
        $userId = $request->user()->id;

        if ($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId) {
            abort(403);
        }
    }
}
