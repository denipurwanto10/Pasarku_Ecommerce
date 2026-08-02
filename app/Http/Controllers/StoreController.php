<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Request $request, User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $isOwner = auth()->check() && auth()->id() === $seller->id;

        if ($seller->store_status !== 'approved' && ! $isOwner) {
            abort(404);
        }

        $query = $seller->products()->active();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $request->category));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int) $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) $request->price_max);
        }

        if ($request->filled('rating')) {
            $query->where('rating_avg', '>=', (int) $request->rating);
        }

        if ($request->get('stock') === 'tersedia') {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('diskon')) {
            $query->whereNotNull('discount_price')
                ->whereColumn('discount_price', '<', 'price');
        }

        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'termurah' => $query->orderBy('price', 'asc'),
            'termahal' => $query->orderBy('price', 'desc'),
            'terlaris' => $query->orderBy('sold_count', 'desc'),
            'rating' => $query->orderByDesc('rating_avg'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $productCount = $seller->products()->active()->count();
        $totalSold = $seller->products()->sum('sold_count');
        $ratingAvg = round((float) $seller->products()->where('rating_count', '>', 0)->avg('rating_avg'), 2);
        $ratingCount = (int) $seller->products()->sum('rating_count');

        $categories = Category::whereHas('products', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id)->active();
        })->withCount(['products' => function ($q) use ($seller) {
            $q->where('seller_id', $seller->id)->active();
        }])->get();

        $wishlistIds = auth()->check() && auth()->user()->role === 'buyer'
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        $followerCount = $seller->followers()->count();
        $isFollowing = auth()->check() && auth()->user()->role === 'buyer'
            ? auth()->user()->isFollowing($seller)
            : false;

        return view('store.show', compact(
            'seller', 'products', 'sort', 'wishlistIds', 'categories',
            'productCount', 'totalSold', 'ratingAvg', 'ratingCount',
            'followerCount', 'isFollowing', 'isOwner'
        ));
    }
}
