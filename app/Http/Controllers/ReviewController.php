<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = $request->user();

        if ($user->role !== 'buyer') {
            abort(403);
        }

        $alreadyReviewed = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Kamu sudah memberi ulasan untuk produk ini.');
        }

        $eligibleOrderItem = OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn ($q) => $q->where('buyer_id', $user->id)->where('status', 'completed'))
            ->first();

        if (! $eligibleOrderItem) {
            return back()->with('error', 'Kamu hanya bisa memberi ulasan untuk produk yang sudah selesai kamu beli.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_item_id' => $eligibleOrderItem->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        $product->refreshRatingCache();

        if ($product->seller) {
            $product->seller->notify(new NewReviewNotification($review));
        }

        return redirect()->route('products.show', $product)->with('success', 'Terima kasih! Ulasanmu berhasil dikirim.');
    }
}
