<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->products()
            ->with('category')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data = collect($data)->except(['variant_name', 'variant_price_adjustment', 'variant_stock', 'variant_sku'])->all();

        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);
        $data['seller_id'] = $request->user()->id;
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        \App\Models\StockHistory::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'type' => 'restock',
            'quantity_change' => $product->stock,
            'stock_after' => $product->stock,
            'note' => 'Stok awal saat produk dibuat',
        ]);

        $this->storeGalleryImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $this->authorizeOwner($product);
        $categories = Category::orderBy('name')->get();
        $product->load(['images', 'variants']);

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeOwner($product);

        $data = $this->validateProduct($request);
        $data = collect($data)->except(['variant_name', 'variant_price_adjustment', 'variant_stock', 'variant_sku'])->all();
        $data['is_active'] = $request->boolean('is_active', true);

        // Simpan harga efektif sebelum diubah, untuk deteksi turun harga setelah update.
        $oldFinalPrice = (float) $product->final_price;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $newStock = $data['stock'];
        unset($data['stock']);

        $product->update($data);

        $stockDiff = $newStock - $product->stock;
        if ($stockDiff !== 0) {
            $product->applyStockChange($stockDiff, 'adjustment', $request->user()->id, 'Perubahan stok dari halaman edit produk');
        }

        // Beri tahu pembeli yang sudah menyimpan produk ini ke wishlist kalau harganya baru saja turun.
        $newFinalPrice = (float) $product->fresh()->final_price;
        if ($newFinalPrice < $oldFinalPrice) {
            $watchers = \App\Models\Wishlist::where('product_id', $product->id)->with('user')->get();
            foreach ($watchers as $watcher) {
                if ($watcher->user) {
                    $watcher->user->notify(new \App\Notifications\PriceDropNotification($product, $oldFinalPrice));
                }
            }
        }

        if ($request->filled('delete_images')) {
            $toDelete = ProductImage::where('product_id', $product->id)
                ->whereIn('id', $request->input('delete_images'))
                ->get();

            foreach ($toDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        $this->storeGalleryImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function reorderImages(Request $request, Product $product)
    {
        $this->authorizeOwner($product);

        $data = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer'],
        ]);

        $imageIds = ProductImage::where('product_id', $product->id)->pluck('id')->toArray();

        foreach ($data['order'] as $index => $imageId) {
            if (in_array((int) $imageId, $imageIds, true)) {
                ProductImage::where('id', $imageId)->where('product_id', $product->id)->update(['sort_order' => $index]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('success', 'Urutan foto berhasil disimpan.');
    }

    private function storeGalleryImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $nextOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $i => $file) {
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'sort_order' => $nextOrder + $i,
            ]);
        }
    }

    /**
     * Simpan/perbarui daftar varian produk (misal ukuran/warna) dari form seller.
     * Baris tanpa nama diabaikan; baris dengan variant_id yang sudah tidak dikirim ulang akan dihapus.
     */
    private function syncVariants(Request $request, Product $product): void
    {
        if (! $request->has('variants_present')) {
            return;
        }

        $names = $request->input('variant_name', []);
        $ids = $request->input('variant_id', []);
        $prices = $request->input('variant_price_adjustment', []);
        $stocks = $request->input('variant_stock', []);
        $skus = $request->input('variant_sku', []);

        $keptIds = [];

        foreach ($names as $i => $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }

            $payload = [
                'product_id' => $product->id,
                'name' => $name,
                'sku' => isset($skus[$i]) && trim($skus[$i]) !== '' ? trim($skus[$i]) : null,
                'price_adjustment' => (int) ($prices[$i] ?? 0),
                'stock' => max(0, (int) ($stocks[$i] ?? 0)),
                'sort_order' => $i,
            ];

            $variantId = $ids[$i] ?? null;

            if ($variantId && $existing = ProductVariant::where('id', $variantId)->where('product_id', $product->id)->first()) {
                $existing->update($payload);
                $keptIds[] = $existing->id;
            } else {
                $created = ProductVariant::create($payload);
                $keptIds[] = $created->id;
            }
        }

        ProductVariant::where('product_id', $product->id)->whereNotIn('id', $keptIds)->delete();
    }

    public function destroy(Product $product)
    {
        $this->authorizeOwner($product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'discount_starts_at' => ['nullable', 'date'],
            'discount_ends_at' => ['nullable', 'date', 'after_or_equal:discount_starts_at'],
            'flash_sale_stock' => ['nullable', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'max:2048'],
            'variant_name.*' => ['nullable', 'string', 'max:100'],
            'variant_price_adjustment.*' => ['nullable', 'integer'],
            'variant_stock.*' => ['nullable', 'integer', 'min:0'],
            'variant_sku.*' => ['nullable', 'string', 'max:60'],
        ]);
    }

    private function authorizeOwner(Product $product): void
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }
    }
}
