<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::active()->ordered()->get();

        $query = Product::query()->active()
            ->whereHas('seller', fn ($s) => $s->where('store_status', 'approved'))
            ->with(['seller', 'category']);

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

        // Toko terpercaya/toko pilihan: sama seperti badge yang muncul di halaman toko & produk,
        // dihitung dari rata-rata rating ulasan asli dan jumlah penjualan/ulasan toko tersebut.
        $trustedSellerIds = User::trustedSellerIds();

        if ($request->boolean('trusted')) {
            $query->whereIn('seller_id', $trustedSellerIds);
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
        $categories = Category::withCount('products')->get();

        $wishlistIds = auth()->check() && auth()->user()->role === 'buyer'
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        // Flash sale: produk dengan diskon berbatas waktu yang masih aktif.
        // Hanya tampil di beranda default (bukan hasil pencarian/filter kategori)
        // supaya tidak mengalihkan fokus dari maksud pengguna.
        $flashSaleProducts = collect();
        $flashSaleEndsAt = null;

        if (! $request->filled('q') && ! $request->filled('category') && ! $request->filled('page')) {
            $flashSaleProducts = Product::active()
                ->whereHas('seller', fn ($s) => $s->where('store_status', 'approved'))
                ->whereNotNull('discount_price')
                ->whereNotNull('discount_ends_at')
                ->where('discount_ends_at', '>', now())
                ->where(function ($q) {
                    $q->whereNull('discount_starts_at')->orWhere('discount_starts_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('flash_sale_stock')->orWhereColumn('flash_sale_sold', '<', 'flash_sale_stock');
                })
                ->where('stock', '>', 0)
                ->with('seller')
                ->orderBy('discount_ends_at')
                ->limit(10)
                ->get();

            $flashSaleEndsAt = $flashSaleProducts->min('discount_ends_at');
        }

        return view('home', compact('products', 'categories', 'sort', 'wishlistIds', 'banners', 'flashSaleProducts', 'flashSaleEndsAt', 'trustedSellerIds'));
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $products = Product::query()
            ->active()
            ->where('name', 'like', '%'.$q.'%')
            ->orderByDesc('sold_count')
            ->limit(6)
            ->get(['id', 'name', 'slug', 'price', 'image']);

        return response()->json($products->map(fn ($p) => [
            'name' => $p->name,
            'price' => 'Rp'.number_format($p->price, 0, ',', '.'),
            'image' => $p->image_url,
            'url' => route('products.show', $p),
        ]));
    }
}
