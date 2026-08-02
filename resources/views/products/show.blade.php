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
            <div class="rounded-md overflow-hidden bg-white p-1.5 aspect-square border border-ink-100">
                <img id="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
            </div>
            @if($product->images->isNotEmpty())
            <div class="mt-3 grid grid-cols-5 gap-2">
                <button type="button" onclick="document.getElementById('main-image').src = '{{ $product->image_url }}'"
                    class="rounded-sm overflow-hidden aspect-square ring-2 ring-clay-500 bg-ink-50">
                    <img src="{{ $product->image_url }}" class="w-full h-full object-cover" alt="Cover">
                </button>
                @foreach($product->images as $img)
                <button type="button" onclick="document.getElementById('main-image').src = '{{ $img->url }}'"
                    class="rounded-sm overflow-hidden aspect-square border border-ink-100 hover:border-clay-300 bg-ink-50 transition">
                    <img src="{{ $img->url }}" class="w-full h-full object-cover" alt="Foto {{ $loop->iteration }}">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <div>
            @if($product->category)
            <a href="{{ route('home', ['category' => $product->category->slug]) }}" class="inline-block text-xs font-semibold text-clay-600 hover:underline">{{ $product->category->name }}</a>
            @endif
            <div class="mt-4 flex items-start justify-between gap-3">
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 leading-tight">{{ $product->name }}</h1>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="shareContent(@js($product->name), @js(route('products.show', $product)))"
                        class="h-11 w-11 rounded-full border border-ink-200 flex items-center justify-center text-ink-400 hover:text-clay-600 hover:border-clay-300 transition-colors active:scale-90"
                        title="Bagikan produk">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.933 2.185 2.25 2.25 0 00-3.933-2.185zm0-12.814a2.25 2.25 0 103.933-2.186 2.25 2.25 0 00-3.933 2.186z" /></svg>
                    </button>
                    @auth
                        @if(auth()->user()->role === 'buyer')
                        <button type="button" onclick="toggleWishlist(this, {{ $product->id }})" data-wishlisted="{{ $isWishlisted ? '1' : '0' }}"
                            class="wishlist-btn h-11 w-11 rounded-full border border-ink-200 flex items-center justify-center transition-colors active:scale-90 {{ $isWishlisted ? 'text-rose-500 border-rose-200 bg-rose-50' : 'text-ink-400 hover:text-rose-500 hover:border-rose-200' }}"
                            title="Simpan ke wishlist">
                            <svg class="h-5 w-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                        </button>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="mt-2">
                <a href="#ulasan" class="inline-flex">
                    @include('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count, 'showEmpty' => true])
                </a>
            </div>
            <div class="mt-3 flex items-center gap-3 text-sm text-ink-400 flex-wrap">
                <span>{{ $product->sold_count }} terjual</span>
                <span>·</span>
                <span>Stok {{ $product->hasVariants() ? $product->effectiveStock() : $product->stock }}</span>
                @if($product->stockBadgeLabel())
                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $product->isOutOfStock() ? 'bg-ink-100 text-ink-500' : 'bg-rose-50 text-rose-600' }}">{{ $product->stockBadgeLabel() }}</span>
                @endif
            </div>
            <div class="mt-5 rounded-md bg-ink-50/60 px-4 py-3">
                @if($product->isOnDiscount())
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs px-1.5 py-0.5 rounded-sm badge-discount">-{{ $product->discount_percent }}%</span>
                    <p class="text-3xl sm:text-4xl font-bold price-tag">Rp{{ number_format($product->final_price, 0, ',', '.') }}</p>
                </div>
                <p class="text-sm price-strike mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                @if($product->hasFlashSaleQuota())
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs font-semibold text-rose-600 mb-1">
                        <span>⚡ Flash Sale — sisa {{ $product->flashSaleRemaining() }} unit</span>
                        <span>Terjual {{ $product->flash_sale_sold }}/{{ $product->flash_sale_stock }}</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-rose-100 overflow-hidden">
                        <div class="h-full bg-rose-500" style="width: {{ $product->flashSaleProgressPercent() }}%"></div>
                    </div>
                </div>
                @endif
                @else
                <p class="text-3xl sm:text-4xl font-bold text-ink-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                @endif
            </div>

            <div class="mt-6 rounded-lg border border-ink-100 bg-white shadow-soft p-4 flex items-center gap-3 hover:border-forest-300 hover:shadow-card transition-all">
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

            @if($product->hasVariants())
            <div class="mt-6">
                <h3 class="text-sm font-semibold text-ink-700 mb-2">Pilih Varian</h3>
                <div class="flex flex-wrap gap-2" id="variant-picker">
                    @foreach($product->variants as $variant)
                    <button type="button"
                        class="variant-option rounded-md border px-3.5 py-2 text-left transition-colors {{ $variant->isOutOfStock() ? 'opacity-40 cursor-not-allowed border-ink-100' : 'border-ink-200 hover:border-clay-400' }}"
                        data-variant-id="{{ $variant->id }}"
                        data-price="{{ (int) $variant->final_price }}"
                        data-stock="{{ $variant->stock }}"
                        {{ $variant->isOutOfStock() ? 'disabled' : '' }}
                        onclick="selectVariant(this)">
                        <span class="block text-sm font-medium text-ink-700">{{ $variant->name }}</span>
                        <span class="block text-[11px] text-ink-400">Rp{{ number_format($variant->final_price, 0, ',', '.') }} · {{ $variant->isOutOfStock() ? 'Habis' : 'Stok '.$variant->stock }}</span>
                    </button>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-rose-500" id="variant-hint">Pilih salah satu varian di atas sebelum membeli.</p>
            </div>
            @endif

            @auth
                @if(auth()->user()->role === 'buyer')
                <form action="{{ route('cart.store', $product) }}" method="POST" class="mt-8 hidden lg:flex items-center gap-3" onsubmit="return validateVariantSelection()">
                    @csrf
                    <input type="hidden" name="product_variant_id" class="variant-id-input" value="">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->hasVariants() ? 1 : $product->stock }}"
                        class="quantity-input w-20 rounded-md border border-ink-200 px-4 py-3 text-sm text-center focus:outline-none focus:ring-2 focus:ring-clay-400">
                    <button type="submit" class="buy-submit-btn btn-tactile flex-1 rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold py-3.5 text-sm transition disabled:opacity-40 disabled:cursor-not-allowed"
                        {{ ($product->hasVariants() || $product->stock == 0) ? 'disabled' : '' }}>
                        {{ $product->hasVariants() ? 'Pilih Varian Dulu' : ($product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang') }}
                    </button>
                </form>
                @if(!$product->hasVariants() && $product->stock == 0)
                <button type="button" onclick="toggleStockAlert(this, {{ $product->id }})" data-alerted="{{ $hasStockAlert ? '1' : '0' }}"
                    class="stock-alert-btn mt-3 hidden lg:flex w-full items-center justify-center gap-2 rounded-md border font-semibold py-3 text-sm transition-colors {{ $hasStockAlert ? 'bg-forest-50 border-forest-200 text-forest-700' : 'border-ink-200 text-ink-600 hover:border-forest-300 hover:text-forest-700' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    <span class="stock-alert-label">{{ $hasStockAlert ? 'Kami akan beri tahu kamu' : 'Beri Tahu Saya Saat Tersedia' }}</span>
                </button>
                @endif
                <!-- Mobile sticky buy bar -->
                <form action="{{ route('cart.store', $product) }}" method="POST" data-fixed-escape class="lg:hidden fixed bottom-16 left-0 right-0 z-30 bg-white border-t border-ink-100 px-4 py-3 flex items-center gap-3 shadow-[0_-4px_16px_rgba(15,23,42,0.08)]" onsubmit="return validateVariantSelection()">
                    @csrf
                    <input type="hidden" name="product_variant_id" class="variant-id-input" value="">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->hasVariants() ? 1 : $product->stock }}"
                        class="quantity-input w-16 rounded-md border border-ink-200 bg-white px-2 py-3 text-sm text-center focus:outline-none focus:ring-2 focus:ring-clay-400">
                    @if(!$product->hasVariants() && $product->stock == 0)
                    <button type="button" onclick="toggleStockAlert(this, {{ $product->id }})" data-alerted="{{ $hasStockAlert ? '1' : '0' }}"
                        class="stock-alert-btn flex-1 rounded-md border font-semibold py-3.5 text-sm transition-colors {{ $hasStockAlert ? 'bg-forest-50 border-forest-200 text-forest-700' : 'border-ink-300 text-ink-600' }}">
                        <span class="stock-alert-label">{{ $hasStockAlert ? 'Kami akan beri tahu kamu' : 'Beri Tahu Saya' }}</span>
                    </button>
                    @else
                    <button type="submit" class="buy-submit-btn btn-tactile flex-1 rounded-md bg-clay-600 text-white font-semibold py-3.5 text-sm transition disabled:opacity-40 disabled:cursor-not-allowed"
                        {{ ($product->hasVariants() || $product->stock == 0) ? 'disabled' : '' }}>
                        {{ $product->hasVariants() ? 'Pilih Varian Dulu' : 'Tambah ke Keranjang' }}
                    </button>
                    @endif
                </form>
                @else
                <div class="mt-8 rounded-md bg-ink-50 px-4 py-3 text-sm text-ink-500">Masuk sebagai pembeli untuk membeli produk ini.</div>
                @endif
            @else
                <a href="{{ route('login') }}" class="mt-8 hidden lg:block text-center rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold py-3.5 text-sm transition">Masuk untuk Membeli</a>
                <a href="{{ route('login') }}" data-fixed-escape class="lg:hidden fixed bottom-16 left-0 right-0 z-30 border-t border-clay-800 px-4 py-3.5 text-center font-semibold text-sm text-white bg-clay-600">Masuk untuk Membeli</a>
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
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="rounded-lg border border-ink-100 bg-white shadow-soft p-5 mb-8">
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
                        class="w-full rounded-lg border border-ink-200 px-4 py-3 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400"></textarea>
                    <button type="submit" class="btn-tactile mt-3 rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold px-6 py-2.5 text-sm transition">Kirim Ulasan</button>
                </form>
                @elseif($alreadyReviewed)
                <div class="rounded-lg bg-forest-50 border border-forest-100 px-4 py-3 text-sm text-forest-700 mb-8">Terima kasih, kamu sudah memberi ulasan untuk produk ini.</div>
                @else
                <div class="rounded-lg bg-ink-50 px-4 py-3 text-sm text-ink-500 mb-8">Ulasan hanya bisa ditulis oleh pembeli yang pesanannya sudah selesai untuk produk ini.</div>
                @endif
            @endif
        @endauth

        @if($reviews->isEmpty())
        <div class="rounded-lg border border-dashed border-ink-200 px-6 py-10 text-center text-sm text-ink-400">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</div>
        @else
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4">
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

    <div id="tanya-jawab" class="mt-16 scroll-mt-24">
        <h2 class="font-serif text-2xl font-bold text-ink-900 mb-6">Tanya Jawab Produk</h2>

        @auth
            @if($product->seller_id === auth()->id())
            <div class="rounded-lg bg-ink-50 px-4 py-3 text-sm text-ink-500 mb-8">Ini produk kamu. Jawab pertanyaan pembeli langsung di bawah ini.</div>
            @else
            <form action="{{ route('questions.store', $product) }}" method="POST" class="rounded-lg border border-ink-100 bg-white shadow-soft p-5 mb-8">
                @csrf
                <p class="text-sm font-semibold text-ink-800 mb-3">Ada yang ingin ditanyakan soal produk ini?</p>
                <textarea name="question" rows="2" minlength="5" maxlength="500" required placeholder="Contoh: Apakah tersedia warna lain? Berapa lama pengiriman ke luar kota?"
                    class="w-full rounded-lg border border-ink-200 px-4 py-3 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400"></textarea>
                <button type="submit" class="btn-tactile mt-3 rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold px-6 py-2.5 text-sm transition">Kirim Pertanyaan</button>
            </form>
            @endif
        @else
        <div class="rounded-lg bg-ink-50 px-4 py-3 text-sm text-ink-500 mb-8"><a href="{{ route('login') }}" class="font-semibold text-clay-600 hover:underline">Masuk</a> untuk bertanya tentang produk ini.</div>
        @endauth

        @if($questions->isEmpty())
        <div class="rounded-lg border border-dashed border-ink-200 px-6 py-10 text-center text-sm text-ink-400">Belum ada pertanyaan untuk produk ini. Jadilah yang pertama bertanya!</div>
        @else
        <div class="space-y-4">
            @foreach($questions as $q)
            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4">
                <div class="flex items-start gap-3">
                    <span class="h-9 w-9 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-xs font-bold flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($q->user->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-ink-800">{{ $q->user->name }} <span class="text-xs font-normal text-ink-400">· {{ $q->created_at->translatedFormat('d M Y') }}</span></p>
                            @if(auth()->check() && auth()->id() === $q->user_id && !$q->isAnswered())
                            <form action="{{ route('questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-ink-400 hover:text-rose-500">Hapus</button>
                            </form>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-ink-600 leading-relaxed">{{ $q->question }}</p>

                        @if($q->isAnswered())
                        <div class="mt-3 rounded-md bg-forest-50 border border-forest-100 p-3">
                            <p class="text-xs font-bold text-forest-700 mb-1">Jawaban Penjual · {{ $q->answered_at->translatedFormat('d M Y') }}</p>
                            <p class="text-sm text-ink-700 leading-relaxed">{{ $q->answer }}</p>
                        </div>
                        @elseif(auth()->check() && auth()->id() === $product->seller_id)
                        <form action="{{ route('questions.answer', $q) }}" method="POST" class="mt-3">
                            @csrf
                            <textarea name="answer" rows="2" minlength="2" maxlength="1000" required placeholder="Tulis jawabanmu..."
                                class="w-full rounded-md border border-ink-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400"></textarea>
                            <button type="submit" class="btn-tactile mt-2 rounded-md bg-ink-900 text-white text-xs font-bold px-4 py-2 hover:bg-ink-800 transition">Kirim Jawaban</button>
                        </form>
                        @else
                        <p class="mt-2 text-xs text-ink-400 italic">Menunggu jawaban penjual.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $questions->links() }}</div>
        @endif
    </div>

    @if($related->isNotEmpty())
    <div class="mt-16">
        <h2 class="font-serif text-2xl font-bold text-ink-900 mb-6">Produk Serupa</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-2.5 sm:gap-3">
            @foreach($related as $r)
            <a href="{{ route('products.show', $r) }}" class="market-card group block p-2">
                <div class="rounded-md overflow-hidden bg-ink-50 aspect-square">
                    <img src="{{ $r->image_url }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-300" alt="{{ $r->name }}" loading="lazy">
                </div>
                <p class="mt-2 px-0.5 text-[13px] font-normal text-ink-800 line-clamp-2">{{ $r->name }}</p>
                <div class="px-0.5 mt-1">@include('partials.rating-stars', ['rating' => $r->rating_avg, 'count' => $r->rating_count])</div>
                <p class="mt-1 px-0.5 pb-0.5 text-base font-bold price-tag">Rp{{ number_format($r->price, 0, ',', '.') }}</p>
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

function selectVariant(btn) {
    document.querySelectorAll('.variant-option').forEach(function (b) {
        b.classList.remove('border-clay-600', 'ring-2', 'ring-clay-300', 'bg-clay-50');
    });
    btn.classList.add('border-clay-600', 'ring-2', 'ring-clay-300', 'bg-clay-50');

    const variantId = btn.dataset.variantId;
    const price = parseInt(btn.dataset.price, 10) || 0;
    const stock = parseInt(btn.dataset.stock, 10) || 0;

    document.querySelectorAll('.variant-id-input').forEach(function (i) { i.value = variantId; });

    document.querySelectorAll('.quantity-input').forEach(function (i) {
        i.max = stock;
        if (parseInt(i.value, 10) > stock) i.value = stock > 0 ? stock : 1;
    });

    document.querySelectorAll('.buy-submit-btn').forEach(function (b) {
        b.disabled = stock <= 0;
        b.textContent = stock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang';
    });

    const hint = document.getElementById('variant-hint');
    if (hint) {
        hint.classList.remove('text-rose-500');
        hint.classList.add('text-ink-400');
        hint.textContent = 'Varian dipilih: ' + (btn.querySelector('span')?.textContent || '') + ' · Rp' + price.toLocaleString('id-ID');
    }
}

function validateVariantSelection() {
    const picker = document.getElementById('variant-picker');
    if (!picker) return true;

    const input = document.querySelector('.variant-id-input');
    if (!input || !input.value) {
        const hint = document.getElementById('variant-hint');
        if (hint) hint.textContent = 'Pilih salah satu varian di atas sebelum membeli.';
        picker.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    return true;
}
</script>
@endsection
