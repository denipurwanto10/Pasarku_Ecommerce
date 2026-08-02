<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\QuickReply;
use Illuminate\Http\Request;

class QuickReplyController extends Controller
{
    public function index(Request $request)
    {
        $quickReplies = $request->user()->quickReplies()->orderBy('sort_order')->get();

        return view('seller.quick-replies.index', compact('quickReplies'));
    }

    public function create()
    {
        return view('seller.quick-replies.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateQuickReply($request);
        $data['seller_id'] = $request->user()->id;
        $data['sort_order'] = (int) ($request->user()->quickReplies()->max('sort_order') + 1);

        QuickReply::create($data);

        return redirect()->route('seller.quick-replies.index')->with('success', 'Template balasan cepat berhasil ditambahkan.');
    }

    public function edit(QuickReply $quickReply)
    {
        $this->authorizeOwner($quickReply);

        return view('seller.quick-replies.edit', compact('quickReply'));
    }

    public function update(Request $request, QuickReply $quickReply)
    {
        $this->authorizeOwner($quickReply);

        $quickReply->update($this->validateQuickReply($request));

        return redirect()->route('seller.quick-replies.index')->with('success', 'Template balasan cepat berhasil diperbarui.');
    }

    public function destroy(QuickReply $quickReply)
    {
        $this->authorizeOwner($quickReply);
        $quickReply->delete();

        return back()->with('success', 'Template balasan cepat berhasil dihapus.');
    }

    private function validateQuickReply(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:1000'],
        ]);
    }

    private function authorizeOwner(QuickReply $quickReply): void
    {
        if ($quickReply->seller_id !== auth()->id()) {
            abort(403);
        }
    }
}
