<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductCompareController extends Controller
{
    /**
     * Tampilkan halaman perbandingan produk.
     * Daftar produk dikelola di sisi klien (localStorage) lewat tombol "Bandingkan"
     * pada kartu produk, lalu dikirim ke sini sebagai daftar ID yang dipisah koma.
     */
    public function index(Request $request)
    {
        $ids = collect(explode(',', (string) $request->get('ids')))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->take(4)
            ->values();

        $products = Product::active()
            ->whereIn('id', $ids)
            ->with(['seller', 'category'])
            ->get()
            ->sortBy(fn ($product) => $ids->search($product->id))
            ->values();

        return view('products.compare', compact('products'));
    }
}
