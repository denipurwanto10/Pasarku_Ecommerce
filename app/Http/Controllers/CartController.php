<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->cartItems()->with(['product.seller', 'variant'])->get();
        $total = $items->sum(fn ($item) => $item->quantity * (int) $item->unit_price);

        return view('cart.index', compact('items', 'total'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);
        $qty = $request->input('quantity', 1);

        $variant = null;
        if ($request->filled('product_variant_id')) {
            $variant = $product->variants()->find($request->product_variant_id);
            if (! $variant) {
                return back()->with('error', 'Varian yang dipilih tidak valid.');
            }
        } elseif ($product->hasVariants()) {
            return back()->with('error', 'Pilih varian produk terlebih dahulu.');
        }

        $availableStock = $variant ? $variant->stock : $product->stock;
        if ($availableStock <= 0) {
            return back()->with('error', 'Stok produk ini sudah habis.');
        }

        $item = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);

        $item->quantity = min($availableStock, $item->exists ? $item->quantity + $qty : $qty);
        $item->save();

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwner($cartItem);

        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);
        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwner($cartItem);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    private function authorizeOwner(CartItem $cartItem): void
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
