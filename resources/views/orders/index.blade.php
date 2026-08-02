@extends('layouts.app')
@section('title', 'Pesanan Saya')
@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Pesanan Saya</h1>

    @if($orders->isEmpty())
    <div class="text-center py-24 rounded-lg bg-white border border-dashed border-ink-200 shadow-soft">
        <p class="text-4xl mb-3">📦</p>
        <p class="text-ink-500 font-medium mb-4">Kamu belum memiliki pesanan.</p>
        <a href="{{ route('home') }}" class="btn-tactile inline-block rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold px-6 py-3 text-sm transition">Mulai Belanja</a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($orders as $order)
        <div class="flex items-center gap-3 rounded-md border border-ink-100 bg-white p-4 sm:p-5 hover:border-clay-300 transition-all">
            <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-4 flex-1 min-w-0">
                <div class="flex -space-x-3 shrink-0">
                    @foreach($order->items->take(3) as $item)
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-md overflow-hidden bg-ink-50 ring-2 ring-white">
                        <img src="{{ $item->product->image_url ?? 'https://placehold.co/100x100?text=%20' }}" class="w-full h-full object-cover" alt="{{ $item->product_name }}">
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-md bg-ink-100 ring-2 ring-white flex items-center justify-center text-[11px] font-bold text-ink-500">+{{ $order->items->count() - 3 }}</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <p class="text-xs text-ink-400">{{ $order->order_number }} · {{ $order->created_at->translatedFormat('d M Y') }}</p>
                        <p class="text-sm font-semibold text-ink-800 mt-1">{{ $order->items->count() }} produk</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block text-xs font-bold px-3 py-1 rounded-full bg-{{ $order->statusColor() }}-50 text-{{ $order->statusColor() }}-700">{{ $order->statusLabel() }}</span>
                        <p class="font-serif text-lg font-bold text-ink-900 mt-1">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </a>
            <form action="{{ route('orders.reorder', $order) }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" title="Tambahkan semua produk di pesanan ini ke keranjang" class="btn-tactile flex items-center gap-1.5 text-xs font-semibold rounded-md border border-clay-300 text-clay-700 px-3 py-2.5 hover:bg-clay-50 transition whitespace-nowrap">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    <span class="hidden sm:inline">Beli Lagi</span>
                </button>
            </form>
        </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</section>
@endsection
