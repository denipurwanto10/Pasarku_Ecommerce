<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->products()
            ->select('id', 'name', 'stock', 'image', 'is_active')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('filter') && $request->filter === 'low', fn ($q) => $q->where('stock', '>', 0)->where('stock', '<=', Product::LOW_STOCK_THRESHOLD))
            ->when($request->filled('filter') && $request->filter === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->orderBy('stock')
            ->paginate(12)
            ->withQueryString();

        return view('seller.stock.index', compact('products'));
    }

    public function history(Product $product)
    {
        $this->authorizeOwner($product);

        $histories = $product->stockHistories()->with('user')->paginate(20);

        return view('seller.stock.history', compact('product', 'histories'));
    }

    public function adjust(Request $request, Product $product)
    {
        $this->authorizeOwner($product);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'not_in:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $product->applyStockChange(
            (int) $data['quantity'],
            'restock',
            $request->user()->id,
            $data['note'] ?? ($data['quantity'] > 0 ? 'Penambahan stok manual' : 'Pengurangan stok manual')
        );

        return back()->with('success', 'Stok "'.$product->name.'" berhasil diperbarui menjadi '.$product->stock.'.');
    }

    private function authorizeOwner(Product $product): void
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }
    }
}
