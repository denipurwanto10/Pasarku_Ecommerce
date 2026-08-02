@extends('layouts.app')
@section('title', 'Bandingkan Produk')

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6 flex-col sm:flex-row">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Bandingkan Produk</h1>
            <p class="text-sm text-ink-400 mt-1">Lihat harga, rating, dan detail produk pilihanmu berdampingan.</p>
        </div>
        @if($products->isNotEmpty())
        <button type="button" onclick="clearCompare(); window.location.href='{{ route('home') }}';"
            class="shrink-0 text-xs font-semibold text-ink-500 hover:text-rose-600 border border-ink-200 rounded-md px-3 py-2 transition">
            Hapus Semua
        </button>
        @endif
    </div>

    @if($products->isEmpty())
    <div class="text-center py-20 rounded-lg bg-white border border-dashed border-ink-200">
        <p class="text-3xl mb-3">⚖️</p>
        <p class="text-ink-500 font-medium mb-1">Belum ada produk untuk dibandingkan.</p>
        <p class="text-ink-400 text-sm mb-5">Ketuk ikon perbandingan pada produk yang kamu suka, lalu kembali ke sini.</p>
        <a href="{{ route('home') }}" class="inline-flex items-center rounded-md bg-clay-600 hover:bg-clay-700 text-white text-sm font-semibold px-5 py-2.5 transition">Jelajahi Produk</a>
    </div>
    @else
    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-card">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr>
                    <th class="sticky left-0 z-10 bg-white text-left px-4 py-4 w-32 text-xs font-semibold text-ink-400 uppercase tracking-wide align-bottom">Produk</th>
                    @foreach($products as $product)
                    <th class="px-4 py-4 min-w-[190px] align-bottom">
                        <div class="relative">
                            <button type="button" onclick="removeCompareItem({{ $product->id }})"
                                class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-ink-100 hover:bg-rose-100 hover:text-rose-600 text-ink-500 flex items-center justify-center text-sm transition"
                                title="Hapus dari perbandingan">&times;</button>
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <div class="rounded-md overflow-hidden bg-ink-50 aspect-square">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-[13px] font-semibold text-ink-800 leading-snug line-clamp-2">{{ $product->name }}</p>
                            </a>
                        </div>
                    </th>
                    @endforeach
                    @if($products->count() < 4)
                    <th class="px-4 py-4 min-w-[150px] align-bottom">
                        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-2 aspect-square rounded-md border-2 border-dashed border-ink-200 text-ink-400 hover:text-clay-600 hover:border-clay-300 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span class="text-xs font-semibold">Tambah Produk</span>
                        </a>
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Harga</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4">@include('partials.product-price', ['product' => $product, 'size' => 'sm'])</td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Rating</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4">@include('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count])</td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Terjual</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4 text-ink-700">{{ $product->sold_count }} terjual</td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Stok</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4 text-ink-700">
                        @if($product->isOutOfStock())
                            <span class="text-rose-600 font-medium">Stok Habis</span>
                        @elseif($product->isLowStock())
                            <span class="text-amber-600 font-medium">Sisa {{ $product->stock }}</span>
                        @else
                            {{ $product->stock }} tersedia
                        @endif
                    </td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Kategori</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4 text-ink-700">{{ $product->category->name ?? '-' }}</td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Toko</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4 text-ink-700">
                        <a href="{{ route('store.show', $product->seller) }}" class="hover:text-clay-600">{{ $product->seller->store_name ?? $product->seller->name }}</a>
                    </td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500 align-top">Deskripsi</td>
                    @foreach($products as $product)
                    <td class="px-4 py-4 text-ink-500 text-xs leading-relaxed align-top">{{ Str::limit(strip_tags((string) $product->description), 140) ?: '-' }}</td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4"></td>
                    @foreach($products as $product)
                    <td class="px-4 py-4">
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('products.show', $product) }}" class="text-center rounded-md border border-ink-200 text-ink-700 text-xs font-semibold py-2.5 hover:bg-ink-50 transition">Lihat Detail</a>
                            @auth
                                @if(auth()->user()->role === 'buyer' && ! $product->isOutOfStock())
                                <form action="{{ route('cart.store', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full rounded-md bg-clay-600 hover:bg-clay-700 text-white text-xs font-semibold py-2.5 transition">+ Keranjang</button>
                                </form>
                                @endif
                            @endauth
                        </div>
                    </td>
                    @endforeach
                    @if($products->count() < 4)<td></td>@endif
                </tr>
            </tbody>
        </table>
    </div>
    @endif
</section>
@endsection
