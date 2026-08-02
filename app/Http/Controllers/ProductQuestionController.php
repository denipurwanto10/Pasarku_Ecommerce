<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQuestion;
use App\Notifications\NewProductQuestionNotification;
use App\Notifications\ProductQuestionAnsweredNotification;
use Illuminate\Http\Request;

class ProductQuestionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = $request->user();

        if ($product->seller_id === $user->id) {
            return back()->with('error', 'Kamu tidak bisa bertanya pada produkmu sendiri.');
        }

        $data = $request->validate([
            'question' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $question = ProductQuestion::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'question' => $data['question'],
        ]);

        if ($product->seller) {
            $product->seller->notify(new NewProductQuestionNotification($question));
        }

        return redirect(url()->previous().'#tanya-jawab')->with('success', 'Pertanyaanmu berhasil dikirim. Penjual akan segera menjawab.');
    }

    public function answer(Request $request, ProductQuestion $question)
    {
        $user = $request->user();

        if ($question->product->seller_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'answer' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $question->update([
            'answer' => $data['answer'],
            'answered_at' => now(),
        ]);

        $question->user->notify(new ProductQuestionAnsweredNotification($question));

        return redirect(url()->previous().'#tanya-jawab')->with('success', 'Jawaban berhasil dikirim.');
    }

    public function destroy(Request $request, ProductQuestion $question)
    {
        $user = $request->user();

        if ($question->user_id !== $user->id) {
            abort(403);
        }

        if ($question->isAnswered()) {
            return back()->with('error', 'Pertanyaan yang sudah dijawab tidak bisa dihapus.');
        }

        $question->delete();

        return redirect(url()->previous().'#tanya-jawab')->with('success', 'Pertanyaan dihapus.');
    }
}
