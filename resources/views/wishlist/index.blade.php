@extends('layouts.app')
@section('title', 'Wishlist Saya')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Wishlist Saya</h1>

    @if($items->isEmpty())
    <div class="text-center py-24 rounded-lg bg-white border border-dashed border-ink-200 shadow-soft">
        <p class="text-4xl mb-3">💛</p>
        <p class="text-ink-500 font-medium mb-4">Belum ada produk yang kamu simpan.</p>
        <a href="{{ route('home') }}" class="btn-tactile inline-block rounded-md bg-clay-600 text-white font-semibold px-6 py-3 text-sm hover:bg-clay-700 hover:brightness-110 transition">Jelajahi Produk</a>
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5 sm:gap-3">
        @foreach($items as $wish)
        @php $product = $wish->product; @endphp
        <div class="market-card group relative p-2" id="wishlist-item-{{ $product->id }}">
            <a href="{{ route('products.show', $product) }}" class="block">
                <div class="rounded-md overflow-hidden bg-ink-50 aspect-square relative">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-300" loading="lazy">
                    @if($product->stock == 0)
                    <span class="absolute inset-0 bg-white/70 flex items-center justify-center text-ink-600 text-xs font-bold backdrop-blur-[1px]">Stok Habis</span>
                    @endif
                </div>
                <div class="mt-2 px-0.5 pb-0.5">
                    <p class="text-[13px] font-normal text-ink-800 leading-snug line-clamp-2">{{ $product->name }}</p>
                    <p class="mt-1.5">@include('partials.product-price', ['product' => $product, 'size' => 'sm'])</p>
                    <div class="mt-1">@include('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count])</div>
                    <p class="mt-1 text-xs text-ink-400 truncate">{{ $product->seller->store_name ?? $product->seller->name }}</p>
                </div>
            </a>
            <button type="button" onclick="removeWishlist({{ $product->id }})"
                class="absolute top-1.5 right-1.5 h-7 w-7 rounded-full bg-white shadow-sm flex items-center justify-center text-rose-500 hover:bg-rose-50 transition active:scale-90"
                title="Hapus dari wishlist">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
            </button>
        </div>
        @endforeach
    </div>
    @endif
</section>

<script>
function removeWishlist(productId) {
    fetch(`/wishlist/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    }).then(() => {
        const el = document.getElementById('wishlist-item-' + productId);
        if (el) {
            el.style.transition = 'opacity .2s ease, transform .2s ease';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.9)';
            setTimeout(() => el.remove(), 200);
        }
    });
}
</script>
@endsection
