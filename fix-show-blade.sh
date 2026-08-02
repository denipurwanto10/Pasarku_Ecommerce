#!/usr/bin/env bash
# Jalankan skrip ini dari root project Laravel kamu (folder yang ada file artisan-nya)
# Contoh: cd /c/laragon/www/ecommerce  lalu  bash fix-show-blade.sh
set -e

if [ ! -f "artisan" ]; then
    echo "ERROR: File 'artisan' tidak ditemukan di folder ini."
    echo "Pastikan kamu menjalankan skrip ini dari root project Laravel, contoh:"
    echo "  cd /c/laragon/www/ecommerce"
    echo "  bash fix-show-blade.sh"
    exit 1
fi

T1="resources/views/products/show.blade.php"
T2="app/Http/Controllers/ProductController.php"
T3="resources/views/seller/products/edit.blade.php"
mkdir -p "$(dirname "$T1")" "$(dirname "$T2")" "$(dirname "$T3")"

cat > "$T1" << 'BLADE_EOF'
@extends('layouts.app')
@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->description) ?: $product->name, 155))
@section('meta_image', $product->image_url)
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-32 lg:pb-10">
    <nav class="text-xs text-ink-400 mb-6 flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-clay-600">Beranda</a>
        <span>/</span>
        @if($product->category)
        <a href="{{ route('home', ['category' => $product->category->slug]) }}" class="hover:text-clay-600">{{ $product->category->name }}</a>
        <span>/</span>
        @endif
        <span class="text-ink-600">{{ Str::limit($product->name, 40) }}</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-10">
        <div class="lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-3xl overflow-hidden bg-white p-2 aspect-square ring-1 ring-ink-100 shadow-card">
                <img id="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-2xl">
            </div>
            @if($product->images->isNotEmpty())
            <div class="mt-3 grid grid-cols-5 gap-2">
                <button type="button" onclick="document.getElementById('main-image').src = '{{ $product->image_url }}'"
                    class="rounded-xl overflow-hidden aspect-square ring-2 ring-clay-400 bg-ink-50">
                    <img src="{{ $product->image_url }}" class="w-full h-full object-cover" alt="Cover">
                </button>
                @foreach($product->images as $img)
                <button type="button" onclick="document.getElementById('main-image').src = '{{ $img->url }}'"
                    class="rounded-xl overflow-hidden aspect-square ring-1 ring-ink-100 hover:ring-clay-300 bg-ink-50 transition">
                    <img src="{{ $img->url }}" class="w-full h-full object-cover" alt="Foto {{ $loop->iteration }}">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <div>
            @if($product->category)
            <span class="inline-block text-xs font-bold uppercase tracking-wide text-forest-700 bg-forest-100 px-3 py-1 rounded-full">{{ $product->category->name }}</span>
            @endif
            <div class="mt-4 flex items-start justify-between gap-3">
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 leading-tight">{{ $product->name }}</h1>
                @auth
                    @if(auth()->user()->role === 'buyer')
                    <button type="button" onclick="toggleWishlist(this, {{ $product->id }})" data-wishlisted="{{ $isWishlisted ? '1' : '0' }}"
                        class="wishlist-btn shrink-0 h-11 w-11 rounded-full border border-ink-200 flex items-center justify-center transition-colors active:scale-90 {{ $isWishlisted ? 'text-rose-500 border-rose-200 bg-rose-50' : 'text-ink-400 hover:text-rose-500 hover:border-rose-200' }}"
                        title="Simpan ke wishlist">
                        <svg class="h-5 w-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                    </button>
                    @endif
                @endauth
            </div>
            <div class="mt-2">
                <a href="#ulasan" class="inline-flex">
                    @include('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count, 'showEmpty' => true])
                </a>
            </div>
            <div class="mt-3 flex items-center gap-3 text-sm text-ink-400 flex-wrap">
                <span>{{ $product->sold_count }} terjual</span>
                <span>·</span>
                <span>Stok {{ $product->stock }}</span>
                @if($product->stockBadgeLabel())
                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $product->isOutOfStock() ? 'bg-ink-100 text-ink-500' : 'bg-rose-50 text-rose-600' }}">{{ $product->stockBadgeLabel() }}</span>
                @endif
            </div>
            <div class="mt-5">
                @if($product->isOnDiscount())
                <div class="flex items-center gap-3 flex-wrap">
                    <p class="font-serif text-3xl sm:text-4xl font-bold bg-gradient-to-r from-clay-600 to-clay-800 bg-clip-text text-transparent">Rp{{ number_format($product->final_price, 0, ',', '.') }}</p>
                    <p class="text-base text-ink-400 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-rose-100 text-rose-600">Hemat {{ $product->discount_percent }}%</span>
                </div>
                @else
                <p class="font-serif text-3xl sm:text-4xl font-bold bg-gradient-to-r from-clay-600 to-clay-800 bg-clip-text text-transparent">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                @endif
            </div>

            <div class="mt-6 rounded-2xl border border-ink-100 bg-white shadow-soft p-4 flex items-center gap-3 hover:border-forest-300 hover:shadow-card transition-all">
                <a href="{{ $product->seller->store_slug ? route('store.show', $product->seller) : '#' }}" class="flex items-center gap-3 min-w-0 flex-1">
                    <span class="h-10 w-10 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-sm font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($product->seller->store_name ?? $product->seller->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <p class="text-sm font-semibold text-ink-800 truncate">{{ $product->seller->store_name ?? $product->seller->name }}</p>
                            @php($sellerBadges = $product->seller->storeBadges())
                            @if(!empty($sellerBadges))
                            <span class="shrink-0 inline-flex items-center gap-0.5 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-{{ $sellerBadges[0]['color'] }}-100 text-{{ $sellerBadges[0]['color'] }}-700">{{ $sellerBadges[0]['icon'] }} {{ $sellerBadges[0]['label'] }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-ink-400 truncate">{{ $product->seller->address ? Str::limit($product->seller->address, 40) : 'Penjual terpercaya' }}</p>
                    </div>
                </a>
                @auth
                    @if(auth()->user()->role === 'buyer')
                    <form action="{{ route('chat.start', $product->seller) }}" method="POST" class="shrink-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn-tactile h-9 w-9 rounded-full border border-ink-200 text-ink-600 flex items-center justify-center hover:border-clay-300 hover:text-clay-600 transition-colors" title="Chat Penjual">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                        </button>
                    </form>
                    @endif
                @endauth
                <a href="{{ $product->seller->store_slug ? route('store.show', $product->seller) : '#' }}" class="shrink-0">
                    <svg class="h-4 w-4 text-ink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-semibold text-ink-700 mb-2">Deskripsi Produk</h3>
                <p class="text-sm text-ink-500 leading-relaxed whitespace-pre-line">{{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>
            </div>

            @auth
                @if(auth()->user()->role === 'buyer')
                <form action="{{ route('cart.store', $product) }}" method="POST" class="mt-8 hidden lg:flex items-center gap-3">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                        class="w-20 rounded-full border border-ink-200 px-4 py-3 text-sm text-center shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400">
                    <button type="submit" {{ $product->stock == 0 ? 'disabled' : '' }}
                        class="btn-tactile flex-1 rounded-full gradient-brand text-white font-semibold py-3.5 text-sm shadow-glow hover:brightness-110 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                    </button>
                </form>
                @if($product->stock == 0)
                <button type="button" onclick="toggleStockAlert(this, {{ $product->id }})" data-alerted="{{ $hasStockAlert ? '1' : '0' }}"
                    class="stock-alert-btn mt-3 hidden lg:flex w-full items-center justify-center gap-2 rounded-full border font-semibold py-3 text-sm transition-colors {{ $hasStockAlert ? 'bg-forest-50 border-forest-200 text-forest-700' : 'border-ink-200 text-ink-600 hover:border-forest-300 hover:text-forest-700' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    <span class="stock-alert-label">{{ $hasStockAlert ? 'Kami akan beri tahu kamu' : 'Beri Tahu Saya Saat Tersedia' }}</span>
                </button>
                @endif
                <!-- Mobile sticky buy bar -->
                <form action="{{ route('cart.store', $product) }}" method="POST" data-fixed-escape class="lg:hidden fixed bottom-16 left-0 right-0 z-30 glass border-t border-ink-100 px-4 py-3 flex items-center gap-3 shadow-[0_-4px_16px_rgba(76,29,149,0.08)]">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                        class="w-16 rounded-full border border-ink-200 bg-white px-2 py-3 text-sm text-center focus:outline-none focus:ring-2 focus:ring-clay-400">
                    @if($product->stock == 0)
                    <button type="button" onclick="toggleStockAlert(this, {{ $product->id }})" data-alerted="{{ $hasStockAlert ? '1' : '0' }}"
                        class="stock-alert-btn flex-1 rounded-full border font-semibold py-3.5 text-sm transition-colors {{ $hasStockAlert ? 'bg-forest-50 border-forest-200 text-forest-700' : 'border-ink-300 text-ink-600' }}">
                        <span class="stock-alert-label">{{ $hasStockAlert ? 'Kami akan beri tahu kamu' : 'Beri Tahu Saya' }}</span>
                    </button>
                    @else
                    <button type="submit"
                        class="btn-tactile flex-1 rounded-full gradient-brand text-white font-semibold py-3.5 text-sm shadow-glow transition">
                        Tambah ke Keranjang
                    </button>
                    @endif
                </form>
                @else
                <div class="mt-8 rounded-xl bg-ink-50 px-4 py-3 text-sm text-ink-500">Masuk sebagai pembeli untuk membeli produk ini.</div>
                @endif
            @else
                <a href="{{ route('login') }}" class="mt-8 hidden lg:block text-center rounded-full gradient-brand text-white font-semibold py-3.5 text-sm shadow-glow hover:brightness-110 transition">Masuk untuk Membeli</a>
                <a href="{{ route('login') }}" data-fixed-escape class="lg:hidden fixed bottom-16 left-0 right-0 z-30 border-t border-clay-800 px-4 py-3.5 text-center font-semibold text-sm text-white gradient-brand shadow-[0_-4px_16px_rgba(76,29,149,0.25)]">Masuk untuk Membeli</a>
            @endauth
        </div>
    </div>

    <div id="ulasan" class="mt-16 scroll-mt-24">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif text-2xl font-bold text-ink-900">Ulasan Pembeli</h2>
            <div class="flex items-center gap-2">
                @include('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count, 'size' => 'h-5 w-5'])
            </div>
        </div>

        @auth
            @if(auth()->user()->role === 'buyer')
                @if($canReview)
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="rounded-2xl border border-ink-100 bg-white shadow-soft p-5 mb-8">
                    @csrf
                    <p class="text-sm font-semibold text-ink-800 mb-3">Bagikan pengalamanmu dengan produk ini</p>
                    <div class="flex items-center gap-1 mb-3" id="rating-picker">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" {{ $i == 5 ? 'checked' : '' }}>
                            <svg class="h-7 w-7 text-ink-200 peer-checked:text-amber-400 hover:text-amber-300 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 21.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </label>
                        @endfor
                    </div>
                    <textarea name="comment" rows="3" maxlength="500" placeholder="Ceritakan kualitas produk, pengiriman, dsb. (opsional)"
                        class="w-full rounded-2xl border border-ink-200 px-4 py-3 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400"></textarea>
                    <button type="submit" class="btn-tactile mt-3 rounded-full gradient-brand text-white font-semibold px-6 py-2.5 text-sm shadow-glow hover:brightness-110 transition">Kirim Ulasan</button>
                </form>
                @elseif($alreadyReviewed)
                <div class="rounded-2xl bg-forest-50 border border-forest-100 px-4 py-3 text-sm text-forest-700 mb-8">Terima kasih, kamu sudah memberi ulasan untuk produk ini.</div>
                @else
                <div class="rounded-2xl bg-ink-50 px-4 py-3 text-sm text-ink-500 mb-8">Ulasan hanya bisa ditulis oleh pembeli yang pesanannya sudah selesai untuk produk ini.</div>
                @endif
            @endif
        @endauth

        @if($reviews->isEmpty())
        <div class="rounded-2xl border border-dashed border-ink-200 px-6 py-10 text-center text-sm text-ink-400">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</div>
        @else
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="rounded-2xl border border-ink-100 bg-white shadow-soft p-4">
                <div class="flex items-center gap-3">
                    <span class="h-9 w-9 rounded-full bg-gradient-to-br from-clay-400 to-clay-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-ink-800 truncate">{{ $review->user->name }}</p>
                        <div class="flex items-center gap-2">
                            @include('partials.rating-stars', ['rating' => $review->rating, 'count' => 0])
                            <span class="text-xs text-ink-400">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                @if($review->comment)
                <p class="mt-3 text-sm text-ink-600 leading-relaxed">{{ $review->comment }}</p>
                @endif
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $reviews->links() }}</div>
        @endif
    </div>

    @if($related->isNotEmpty())
    <div class="mt-16">
        <h2 class="font-serif text-2xl font-bold text-ink-900 mb-6">Produk Serupa</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
            @foreach($related as $r)
            <a href="{{ route('products.show', $r) }}" class="group block rounded-2xl bg-white p-2 shadow-soft transition-all duration-300 hover:-translate-y-1 hover:shadow-card">
                <div class="rounded-xl overflow-hidden bg-ink-50 aspect-square ring-1 ring-ink-100 group-hover:ring-clay-300 transition-colors">
                    <img src="{{ $r->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $r->name }}" loading="lazy">
                </div>
                <p class="mt-3 px-1 text-sm font-semibold text-ink-800 line-clamp-2 group-hover:text-clay-600 transition-colors">{{ $r->name }}</p>
                <div class="px-1 mt-1">@include('partials.rating-stars', ['rating' => $r->rating_avg, 'count' => $r->rating_count])</div>
                <p class="mt-1 px-1 pb-1 font-serif text-base font-bold text-ink-900">Rp{{ number_format($r->price, 0, ',', '.') }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</section>

<script>
(function () {
    try {
        const KEY = 'pasarku_recently_viewed';
        const entry = @json($recentEntry);
        let list = JSON.parse(localStorage.getItem(KEY) || '[]');
        list = list.filter(item => item.id !== entry.id);
        list.unshift(entry);
        list = list.slice(0, 10);
        localStorage.setItem(KEY, JSON.stringify(list));
    } catch (e) {}
})();
</script>
@endsection
BLADE_EOF
echo "File ditimpa: $(pwd)/$T1"

cat > "$T2" << 'CONTROLLER_EOF'
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
            'product', 'related', 'isWishlisted', 'reviews', 'canReview', 'alreadyReviewed', 'hasStockAlert', 'recentEntry'
        ));
    }
}
CONTROLLER_EOF
echo "File ditimpa: $(pwd)/$T2"

cat > "$T3" << 'EDIT_EOF'
@extends('layouts.app')
@section('title', 'Ubah Produk')
@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('seller.products.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Ubah Produk</h1>

    @if($product->moderation_status === 'rejected')
    <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <p class="font-semibold">Produk ini disembunyikan oleh admin.</p>
        @if($product->moderation_note)<p class="mt-1">Alasan: {{ $product->moderation_note }}</p>@endif
    </div>
    @endif

    @if($errors->any())
    <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-ink-100 bg-white shadow-card p-5 sm:p-8 space-y-5">
        @csrf @method('PUT')
        <div class="flex items-center gap-4">
            <img src="{{ $product->image_url }}" class="h-20 w-20 rounded-xl object-cover border border-ink-100 shadow-soft" alt="{{ $product->name }}">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Ganti Foto Cover (opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:gradient-brand file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5 file:shadow-glow">
            </div>
        </div>

        @if($product->images->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-semibold text-ink-700">Galeri Foto Saat Ini</label>
                <span id="reorder-status" class="text-xs text-ink-400"></span>
            </div>
            <div id="gallery-sortable" class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                @foreach($product->images as $img)
                <div class="gallery-item relative group cursor-grab active:cursor-grabbing" draggable="true" data-image-id="{{ $img->id }}">
                    <img src="{{ $img->url }}" class="w-full aspect-square object-cover rounded-xl border border-ink-100 pointer-events-none group-has-[:checked]:opacity-40 group-has-[:checked]:ring-2 group-has-[:checked]:ring-rose-400 transition">
                    <span class="absolute top-1 left-1 h-5 w-5 rounded-full bg-white/90 border border-ink-200 flex items-center justify-center text-ink-400" title="Geser untuk urutkan">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
                    </span>
                    <label class="absolute inset-0 cursor-pointer">
                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="sr-only peer">
                        <span class="absolute top-1 right-1 h-5 w-5 rounded-full bg-white/90 border border-ink-200 flex items-center justify-center text-[10px] text-rose-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition">✕</span>
                    </label>
                </div>
                @endforeach
            </div>
            <p class="mt-1.5 text-xs text-ink-400">Seret foto untuk mengubah urutan (di HP: tekan-tahan sebentar lalu geser), tersimpan otomatis. Klik tanda ✕ untuk menandai foto yang akan dihapus saat kamu menyimpan perubahan.</p>
        </div>
        @endif

        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Tambah Foto Galeri (opsional, maks 6)</label>
            <input type="file" name="images[]" accept="image/*" multiple class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-ink-800 file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kategori</label>
                <select name="category_id" class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id)==$cat->id?'selected':'' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            <p class="mt-1.5 text-xs text-ink-400">Perubahan stok di sini otomatis tercatat di <a href="{{ route('seller.stock.history', $product) }}" class="underline hover:text-clay-600">riwayat stok</a>.</p>
        </div>
        <div class="rounded-2xl border border-dashed border-rose-200 bg-rose-50/40 p-4 space-y-4">
            <p class="text-sm font-semibold text-rose-700">Diskon Produk (opsional, harga coret — terpisah dari kupon)</p>
            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Harga Diskon (Rp)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0" placeholder="Kosongkan jika tidak ada diskon"
                        class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Mulai</label>
                    <input type="datetime-local" name="discount_starts_at" value="{{ old('discount_starts_at', optional($product->discount_starts_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Berakhir</label>
                    <input type="datetime-local" name="discount_ends_at" value="{{ old('discount_ends_at', optional($product->discount_ends_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">{{ old('description', $product->description) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Tampilkan produk ini di toko
        </label>
        <button type="submit" class="btn-tactile rounded-full gradient-brand text-white font-semibold px-6 py-3.5 text-sm shadow-glow hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>

@php
    $reorderUrl = route('seller.products.images.reorder', $product);
@endphp
@push('scripts')
<script>
(function () {
    const grid = document.getElementById('gallery-sortable');
    if (!grid) return;

    const statusEl = document.getElementById('reorder-status');
    let dragEl = null;

    grid.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('dragstart', function (e) {
            dragEl = item;
            item.classList.add('opacity-40');
            e.dataTransfer.effectAllowed = 'move';
        });
        item.addEventListener('dragend', function () {
            item.classList.remove('opacity-40');
            dragEl = null;
            saveOrder();
        });
        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (!dragEl || dragEl === item) return;
            const items = [...grid.querySelectorAll('.gallery-item')];
            const dragIndex = items.indexOf(dragEl);
            const targetIndex = items.indexOf(item);
            if (dragIndex < targetIndex) {
                item.after(dragEl);
            } else {
                item.before(dragEl);
            }
        });
    });

    // Dukungan sentuh (HP/tablet): HTML5 drag & drop API di atas hanya jalan dengan mouse,
    // jadi di sini kita tangani drag pakai jari lewat Pointer Events, dengan tekan-tahan
    // singkat dulu supaya scroll halaman normal (tap-tap biasa) tidak keganggu.
    let touchDragEl = null;
    let touchHoldTimer = null;
    let touchStarted = false;

    grid.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('pointerdown', function (e) {
            if (e.pointerType !== 'touch') return;
            touchHoldTimer = setTimeout(() => {
                touchDragEl = item;
                touchStarted = true;
                item.classList.add('opacity-40', 'scale-105', 'shadow-card');
                item.style.touchAction = 'none';
                item.setPointerCapture(e.pointerId);
                if (navigator.vibrate) navigator.vibrate(15);
            }, 250);
        });

        item.addEventListener('pointermove', function (e) {
            if (e.pointerType !== 'touch' || !touchStarted || touchDragEl !== item) return;
            e.preventDefault();
            const target = document.elementFromPoint(e.clientX, e.clientY);
            const targetItem = target ? target.closest('.gallery-item') : null;
            if (!targetItem || targetItem === touchDragEl) return;
            const items = [...grid.querySelectorAll('.gallery-item')];
            const dragIndex = items.indexOf(touchDragEl);
            const targetIndex = items.indexOf(targetItem);
            if (dragIndex < targetIndex) {
                targetItem.after(touchDragEl);
            } else {
                targetItem.before(touchDragEl);
            }
        }, { passive: false });

        function endTouchDrag() {
            clearTimeout(touchHoldTimer);
            if (touchStarted && touchDragEl) {
                touchDragEl.classList.remove('opacity-40', 'scale-105', 'shadow-card');
                touchDragEl.style.touchAction = '';
                touchDragEl = null;
                touchStarted = false;
                saveOrder();
            }
        }
        item.addEventListener('pointerup', endTouchDrag);
        item.addEventListener('pointercancel', endTouchDrag);
    });

    function saveOrder() {
        const order = [...grid.querySelectorAll('.gallery-item')].map(el => el.dataset.imageId);
        if (statusEl) statusEl.textContent = 'Menyimpan urutan...';

        fetch(@json($reorderUrl), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order: order }),
        })
        .then(res => res.json())
        .then(() => { if (statusEl) statusEl.textContent = 'Urutan tersimpan ✓'; setTimeout(() => statusEl && (statusEl.textContent = ''), 2000); })
        .catch(() => { if (statusEl) statusEl.textContent = 'Gagal menyimpan urutan.'; });
    }
})();
</script>
@endpush
@endsection
EDIT_EOF
echo "File ditimpa: $(pwd)/$T3"

rm -f storage/framework/views/*.php 2>/dev/null || true
php artisan view:clear
php artisan cache:clear
php artisan config:clear

echo ""
echo "Selesai. Sekarang MATIKAN server (Ctrl+C di terminal server) lalu jalankan ulang:"
echo "  php -S 127.0.0.1:8000 -t public"
echo "atau"
echo "  php artisan serve"
