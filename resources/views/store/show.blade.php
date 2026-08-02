@extends('layouts.app')
@section('title', $seller->store_name ?? $seller->name)
@section('content')
<section class="relative">
    <div class="h-40 sm:h-56 w-full overflow-hidden bg-gradient-to-br from-forest-400 via-forest-500 to-clay-600">
        @if($seller->store_banner)
        <img src="{{ asset('storage/'.$seller->store_banner) }}" class="w-full h-full object-cover" alt="Banner {{ $seller->store_name }}">
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="-mt-12 sm:-mt-16 flex flex-col sm:flex-row sm:items-end gap-4 sm:gap-6">
            <div class="relative shrink-0">
                <span class="h-24 w-24 sm:h-32 sm:w-32 rounded-lg bg-gradient-to-br from-clay-400 to-clay-600 text-white text-3xl sm:text-4xl font-bold flex items-center justify-center ring-4 ring-white shadow-card">
                    {{ strtoupper(substr($seller->store_name ?? $seller->name, 0, 1)) }}
                </span>
                <span class="absolute bottom-1 right-1 h-4 w-4 sm:h-5 sm:w-5 rounded-full ring-4 ring-white {{ $seller->isOnline() ? 'bg-forest-500' : 'bg-ink-300' }}" title="{{ $seller->isOnline() ? 'Online' : 'Offline' }}"></span>
            </div>
            <div class="pb-1 min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 truncate">{{ $seller->store_name ?? $seller->name }}</h1>
                    @if($seller->isOnline())
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-forest-700 bg-forest-100 px-2.5 py-1 rounded-full shrink-0">
                        <span class="h-1.5 w-1.5 rounded-full bg-forest-500 animate-pulse"></span>Online
                    </span>
                    @else
                    <span class="inline-flex items-center text-xs font-medium text-ink-500 bg-ink-100 px-2.5 py-1 rounded-full shrink-0">
                        {{ $seller->last_seen_at ? 'Terakhir online '.$seller->last_seen_at->diffForHumans() : 'Belum pernah online' }}
                    </span>
                    @endif
                </div>
                @php($storeBadges = $seller->storeBadges())
                @if(!empty($storeBadges))
                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                    @foreach($storeBadges as $badge)
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-700">
                        <span>{{ $badge['icon'] }}</span>{{ $badge['label'] }}
                    </span>
                    @endforeach
                </div>
                @endif
                <p class="mt-1.5 text-sm text-ink-500 truncate">{{ $seller->address ? Str::limit($seller->address, 60) : 'Penjual terpercaya di Pasarku' }}</p>
            </div>

            <div class="flex items-center gap-2 pb-1 shrink-0">
                <button type="button" onclick="shareContent(@js($seller->store_name ?? $seller->name), @js(route('store.show', $seller)))"
                    class="btn-tactile h-11 w-11 rounded-full border border-ink-200 bg-white text-ink-600 flex items-center justify-center shadow-soft hover:border-clay-300 hover:text-clay-600 transition-colors" title="Bagikan Toko">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.933 2.185 2.25 2.25 0 00-3.933-2.185zm0-12.814a2.25 2.25 0 103.933-2.186 2.25 2.25 0 00-3.933 2.186z" /></svg>
                </button>
                @if(!$isOwner)
                @auth
                    @if(auth()->user()->role === 'buyer')
                    <button type="button" id="follow-btn" onclick="toggleFollow(this, {{ $seller->id }})" data-following="{{ $isFollowing ? '1' : '0' }}"
                        class="btn-tactile text-sm font-semibold rounded-full px-4 py-2.5 shadow-soft transition-colors {{ $isFollowing ? 'bg-ink-100 text-ink-700 border border-ink-200' : 'gradient-brand text-white shadow-glow hover:brightness-110' }}">
                        <span id="follow-btn-label">{{ $isFollowing ? 'Mengikuti' : '+ Ikuti Toko' }}</span>
                    </button>
                    <form action="{{ route('chat.start', $seller) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-tactile h-11 w-11 rounded-full border border-ink-200 bg-white text-ink-600 flex items-center justify-center shadow-soft hover:border-clay-300 hover:text-clay-600 transition-colors" title="Chat Penjual">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                        </button>
                    </form>
                    @endif
                @endauth
                @endif
            </div>
        </div>

        @if($seller->store_description)
        <p class="mt-6 text-sm text-ink-500 leading-relaxed max-w-2xl">{{ $seller->store_description }}</p>
        @endif

        <div class="mt-6 flex items-center gap-4 sm:gap-6 flex-wrap">
            <div class="rounded-lg bg-white px-4 py-3 shadow-soft border border-ink-100">
                <p class="font-serif text-xl font-bold text-ink-900">{{ $productCount }}</p>
                <p class="text-xs text-ink-500 mt-0.5">Produk</p>
            </div>
            <div class="rounded-lg bg-white px-4 py-3 shadow-soft border border-ink-100">
                <p class="font-serif text-xl font-bold text-ink-900">{{ $totalSold }}</p>
                <p class="text-xs text-ink-500 mt-0.5">Terjual</p>
            </div>
            <div class="rounded-lg bg-white px-4 py-3 shadow-soft border border-ink-100">
                <div class="flex items-center gap-1.5">
                    <p class="font-serif text-xl font-bold text-ink-900">{{ $ratingAvg > 0 ? number_format($ratingAvg, 1) : '-' }}</p>
                    <svg class="h-4 w-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 21.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                </div>
                <p class="text-xs text-ink-500 mt-0.5">{{ $ratingCount }} ulasan</p>
            </div>
            <div class="rounded-lg bg-white px-4 py-3 shadow-soft border border-ink-100">
                <p class="font-serif text-xl font-bold text-ink-900" id="follower-count">{{ $followerCount }}</p>
                <p class="text-xs text-ink-500 mt-0.5">Follower</p>
            </div>
            <div class="rounded-lg bg-white px-4 py-3 shadow-soft border border-ink-100">
                <p class="font-serif text-xl font-bold text-ink-900">{{ $seller->created_at->translatedFormat('M Y') }}</p>
                <p class="text-xs text-ink-500 mt-0.5">Bergabung</p>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="font-serif text-2xl font-bold text-ink-900 mb-4">Produk Toko Ini</h2>

    @if($categories->isNotEmpty())
    <div class="flex gap-2 overflow-x-auto pb-2 mb-4 -mx-4 px-4 sm:-mx-1 sm:px-1 scroll-smooth [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
        <a href="{{ route('store.show', array_filter(array_merge(request()->except(['category', 'page']), ['seller' => $seller->store_slug ?? $seller->id]))) }}"
            class="btn-tactile shrink-0 rounded-full px-4 py-2 text-sm font-semibold border transition-all {{ !request('category') ? 'gradient-brand text-white border-transparent shadow-glow' : 'border-ink-200 text-ink-600 hover:border-clay-300 bg-white' }}">Semua</a>
        @foreach($categories as $cat)
        <a href="{{ route('store.show', array_filter(array_merge(request()->except(['category', 'page']), ['seller' => $seller->store_slug ?? $seller->id, 'category' => $cat->slug]))) }}"
            class="btn-tactile shrink-0 rounded-full px-4 py-2 text-sm font-semibold border transition-all {{ request('category') === $cat->slug ? 'gradient-brand text-white border-transparent shadow-glow' : 'border-ink-200 text-ink-600 hover:border-clay-300 bg-white' }}">
            <span class="mr-1">{{ $cat->icon }}</span>{{ $cat->name }}
            <span class="opacity-60">({{ $cat->products_count }})</span>
        </a>
        @endforeach
    </div>
    @endif

    <div class="flex items-center justify-end mb-6 flex-wrap gap-3">
        <form method="GET" class="flex items-center gap-2 flex-wrap">
            @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari di toko ini"
                class="text-sm rounded-full border border-ink-200 px-4 py-2 bg-white shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400">

            <details class="relative">
                <summary class="list-none cursor-pointer text-sm rounded-full border border-ink-200 px-4 py-2 bg-white shadow-soft hover:border-clay-300 transition-colors flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m9 12h3.75M16.5 18a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0M3.75 18H13.5m-9-6h9.75m-9.75 0a1.5 1.5 0 003 0m-3 0a1.5 1.5 0 013 0m6.75 0H20.25" /></svg>
                    Filter
                    @if(request('price_min') || request('price_max') || request('rating') || request('stock') || request('diskon'))
                    <span class="h-1.5 w-1.5 rounded-full bg-clay-500"></span>
                    @endif
                </summary>
                <div class="absolute right-0 z-20 mt-2 w-72 max-w-[calc(100vw-2rem)] rounded-lg bg-white shadow-card border border-ink-100 p-4 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-ink-600">Rentang Harga</label>
                        <div class="mt-1.5 flex items-center gap-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min" min="0"
                                class="w-full rounded-md border border-ink-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <span class="text-ink-300">–</span>
                            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max" min="0"
                                class="w-full rounded-md border border-ink-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink-600">Rating Minimal</label>
                        <select name="rating" class="mt-1.5 w-full rounded-md border border-ink-200 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <option value="">Semua Rating</option>
                            <option value="4" {{ request('rating')=='4'?'selected':'' }}>4★ ke atas</option>
                            <option value="3" {{ request('rating')=='3'?'selected':'' }}>3★ ke atas</option>
                            <option value="2" {{ request('rating')=='2'?'selected':'' }}>2★ ke atas</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink-600">Ketersediaan</label>
                        <select name="stock" class="mt-1.5 w-full rounded-md border border-ink-200 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <option value="">Semua Produk</option>
                            <option value="tersedia" {{ request('stock')=='tersedia'?'selected':'' }}>Stok Tersedia</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="diskon" value="1" {{ request('diskon')?'checked':'' }}
                            class="h-4 w-4 rounded border-ink-300 text-clay-600 focus:ring-clay-400">
                        <span class="text-sm text-ink-700">Sedang Diskon</span>
                    </label>
                    <div class="flex items-center gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-md bg-clay-600 text-white text-sm font-semibold py-2 hover:bg-clay-700 hover:brightness-110 transition">Terapkan</button>
                        @if(request('price_min') || request('price_max') || request('rating') || request('stock') || request('diskon'))
                        <a href="{{ route('store.show', array_filter(['seller' => $seller->store_slug ?? $seller->id, 'category' => request('category'), 'q' => request('q')])) }}" class="rounded-full border border-ink-200 text-ink-500 text-sm font-semibold px-4 py-2 hover:bg-ink-50 transition-colors">Reset</a>
                        @endif
                    </div>
                </div>
            </details>

            <select name="sort" onchange="this.form.submit()" class="text-sm rounded-full border border-ink-200 pl-3 pr-8 py-2 bg-white shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400">
                <option value="terbaru" {{ $sort=='terbaru'?'selected':'' }}>Terbaru</option>
                <option value="termurah" {{ $sort=='termurah'?'selected':'' }}>Harga Terendah</option>
                <option value="termahal" {{ $sort=='termahal'?'selected':'' }}>Harga Tertinggi</option>
                <option value="terlaris" {{ $sort=='terlaris'?'selected':'' }}>Terlaris</option>
                <option value="rating" {{ $sort=='rating'?'selected':'' }}>Rating Tertinggi</option>
            </select>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-20 rounded-lg bg-white border border-dashed border-ink-200 shadow-soft">
            <p class="text-3xl mb-3">🛍️</p>
            <p class="text-ink-400 font-medium">Toko ini belum punya produk yang cocok dengan pencarianmu.</p>
        </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5 sm:gap-3">
        @foreach($products as $product)
        <a href="{{ route('products.show', $product) }}" class="market-card group block p-2">
            <div class="rounded-md overflow-hidden bg-ink-50 aspect-square relative">
                <img src="{{ $product->image_url }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-300" alt="{{ $product->name }}" loading="lazy">
                @auth
                    @if(auth()->user()->role === 'buyer')
                    <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this, {{ $product->id }});"
                        data-wishlisted="{{ in_array($product->id, $wishlistIds) ? '1' : '0' }}"
                        class="wishlist-btn absolute top-1.5 right-1.5 h-7 w-7 rounded-full bg-white shadow-sm flex items-center justify-center transition-transform active:scale-90 {{ in_array($product->id, $wishlistIds) ? 'text-rose-500' : 'text-ink-400 hover:text-rose-500' }}">
                        <svg class="h-4 w-4" fill="{{ in_array($product->id, $wishlistIds) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                    </button>
                    @endif
                @endauth
            </div>
            <p class="mt-2 px-0.5 text-[13px] font-normal text-ink-800 line-clamp-2">{{ $product->name }}</p>
            <div class="px-0.5 mt-1">@include('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count])</div>
            <p class="mt-1 px-0.5 pb-0.5">@include('partials.product-price', ['product' => $product, 'size' => 'sm'])</p>
        </a>
        @endforeach
    </div>
    <div class="mt-10">{{ $products->links() }}</div>
    @endif
</section>
@endsection
