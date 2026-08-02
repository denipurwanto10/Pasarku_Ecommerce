@extends('layouts.app')
@section('title', 'Keranjang')
@section('content')
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-3xl font-bold text-ink-900 mb-8">Keranjang Belanja</h1>

    @if($items->isEmpty())
    <div class="text-center py-24 rounded-lg bg-white border border-dashed border-ink-200 shadow-soft">
        <p class="text-4xl mb-3">🛒</p>
        <p class="text-ink-500 font-medium mb-4">Keranjangmu masih kosong.</p>
        <a href="{{ route('home') }}" class="btn-tactile inline-block rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold px-6 py-3 text-sm transition">Mulai Belanja</a>
    </div>
    @else
    <div class="grid lg:grid-cols-3 gap-8 pb-4">
        <div class="lg:col-span-2 space-y-3">
            @foreach($items as $item)
            <div class="flex gap-3 sm:gap-4 rounded-md border border-ink-100 bg-white p-3 sm:p-4 hover:border-clay-200 transition-all">
                <a href="{{ route('products.show', $item->product) }}" class="h-20 w-20 sm:h-24 sm:w-24 shrink-0 rounded-md overflow-hidden bg-ink-50">
                    <img src="{{ $item->product->image_url }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                </a>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('products.show', $item->product) }}" class="text-sm font-semibold text-ink-800 hover:text-clay-600 line-clamp-2">{{ $item->product->name }}</a>
                    @if($item->variant)
                    <p class="text-xs font-medium text-clay-600 mt-0.5">Varian: {{ $item->variant->name }}</p>
                    @endif
                    <p class="text-xs text-ink-400 mt-1 truncate">{{ $item->product->seller->store_name ?? $item->product->seller->name }}</p>
                    <p class="mt-2 text-base font-bold text-ink-900">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-col items-end justify-between shrink-0">
                    <form action="{{ route('cart.destroy', $item) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-xs text-rose-500 hover:text-rose-700 font-medium">Hapus</button>
                    </form>
                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center border border-ink-200 rounded-md overflow-hidden">
                        @csrf @method('PATCH')
                        <button type="button" onclick="let i=this.nextElementSibling; if(+i.value>1){i.value=+i.value-1; this.form.requestSubmit();}"
                            class="btn-tactile h-8 w-8 sm:h-9 sm:w-9 flex items-center justify-center text-ink-500 hover:bg-ink-50 disabled:opacity-30" {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->available_stock }}"
                            onchange="this.form.submit()" class="w-9 sm:w-10 text-center py-2 text-sm focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button type="button" onclick="let i=this.previousElementSibling; if(+i.value<{{ $item->available_stock }}){i.value=+i.value+1; this.form.requestSubmit();}"
                            class="btn-tactile h-8 w-8 sm:h-9 sm:w-9 flex items-center justify-center text-ink-500 hover:bg-ink-50 disabled:opacity-30" {{ $item->quantity >= $item->available_stock ? 'disabled' : '' }}>+</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5 sm:p-6 h-fit lg:sticky lg:top-24">
            <h3 class="font-serif text-lg font-bold text-ink-900 mb-4">Ringkasan Belanja</h3>
            <div class="flex justify-between text-sm text-ink-500 mb-2">
                <span>Total ({{ $items->sum('quantity') }} barang)</span>
                <span class="font-semibold text-ink-800">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-dashed border-ink-200 my-4"></div>
            <div class="flex justify-between mb-6">
                <span class="font-semibold text-ink-800">Total Bayar</span>
                <span class="text-xl font-bold price-tag">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="btn-tactile block text-center rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold py-3.5 text-sm transition">Lanjut ke Pembayaran</a>
            <p class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-ink-400">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                Transaksi aman & terenkripsi
            </p>
        </div>
    </div>
    @endif
</section>
@endsection
