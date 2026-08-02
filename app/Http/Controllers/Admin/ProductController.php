<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['seller', 'category'])
            ->when($request->filled('status'), fn ($q) => $q->where('moderation_status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function approve(Product $product)
    {
        $product->update(['moderation_status' => 'approved', 'moderation_note' => null]);

        return back()->with('success', 'Produk "'.$product->name.'" disetujui dan tampil kembali.');
    }

    public function reject(Request $request, Product $product)
    {
        $data = $request->validate([
            'note' => ['required', 'string', 'max:500'],
        ]);

        $product->update([
            'moderation_status' => 'rejected',
            'moderation_note' => $data['note'],
        ]);

        return back()->with('success', 'Produk "'.$product->name.'" disembunyikan dari publik.');
    }
}
