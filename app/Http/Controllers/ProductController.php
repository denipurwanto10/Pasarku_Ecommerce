<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['seller', 'category', 'images']);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $isWishlisted = auth()->check() && auth()->user()->role === 'buyer'
            ? auth()->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;

        $reviews = $product->reviews()->with('user')->paginate(6, ['*'], 'ulasan');
        $questions = $product->questions()->with('user')->paginate(10, ['*'], 'tanya');

        $canReview = false;
        $alreadyReviewed = false;

        if (auth()->check() && auth()->user()->role === 'buyer') {
            $alreadyReviewed = Review::where('product_id', $product->id)
                ->where('user_id', auth()->id())
                ->exists();

            $canReview = ! $alreadyReviewed && OrderItem::where('product_id', $product->id)
                ->whereHas('order', fn ($q) => $q->where('buyer_id', auth()->id())->where('status', 'completed'))
                ->exists();
        }

        $hasStockAlert = auth()->check() && auth()->user()->role === 'buyer'
            ? auth()->user()->hasStockAlertFor($product)
            : false;

        $recentEntry = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => 'Rp'.number_format($product->final_price, 0, ',', '.'),
            'image' => $product->image_url,
            'url' => route('products.show', $product),
        ];

        return view('products.show', compact(
            'product', 'related', 'isWishlisted', 'reviews', 'questions', 'canReview', 'alreadyReviewed', 'hasStockAlert', 'recentEntry'
        ));
    }
}
