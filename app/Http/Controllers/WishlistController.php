<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->wishlists()->with(['product.seller', 'product.category'])->latest()->get();

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request, Product $product)
    {
        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ]);
            $status = 'added';
            $message = 'Ditambahkan ke wishlist.';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'count' => $request->user()->wishlists()->count(),
            ]);
        }

        return back()->with('success', $message);
    }
}
