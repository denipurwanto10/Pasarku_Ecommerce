@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('orders.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-6 inline-flex items-center gap-1">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Kembali ke Pesanan Saya
    </a>

    <div class="rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8 print:shadow-none print:border-0 print:rounded-none" id="struk-pesanan">
        <div class="hidden print:flex items-center gap-2 mb-6">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-md gradient-brand text-white font-serif text-lg font-bold">P</span>
            <span class="font-serif text-xl font-bold text-ink-900">Pasarku</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="font-serif text-xl sm:text-2xl font-bold text-ink-900">{{ $order->order_number }}</h1>
                <p class="text-sm text-ink-400 mt-1">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <div class="flex items-center gap-2 print:hidden">
                <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full bg-{{ $order->statusColor() }}-50 text-{{ $order->statusColor() }}-700">{{ $order->statusLabel() }}</span>
                @if($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan {{ $order->order_number }}? Tindakan ini tidak bisa dibatalkan.');">
                    @csrf
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors">Batalkan Pesanan</button>
                </form>
                @endif
                <form action="{{ route('orders.reorder', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full border border-clay-300 text-clay-700 hover:bg-clay-50 transition-colors inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        Beli Lagi
                    </button>
                </form>
                <button type="button" onclick="window.print()" class="text-xs font-bold px-3 py-1.5 rounded-full border border-ink-200 text-ink-600 hover:bg-ink-50 transition-colors inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>
                    Cetak Struk
                </button>
            </div>
        </div>


        @php
            $steps = ['pending' => 'Menunggu', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai'];
            $stepKeys = array_keys($steps);
            $currentIndex = array_search($order->status, $stepKeys);
        @endphp
        @if($order->status !== 'cancelled')
        <div class="mb-8 rounded-lg bg-ink-50 border border-ink-100 p-4 sm:p-5">
            <div class="flex items-start">
                @foreach($steps as $key => $label)
                @php $isDone = $currentIndex !== false && $loop->index <= $currentIndex; @endphp
                <div class="flex-1 flex flex-col items-center relative">
                    @if(!$loop->first)
                    <div class="absolute top-3 right-1/2 w-full h-0.5 {{ $isDone ? 'bg-gradient-to-r from-forest-400 to-forest-600' : 'bg-ink-200' }}" style="left:-50%"></div>
                    @endif
                    <span class="relative z-10 h-6 w-6 rounded-full flex items-center justify-center text-[11px] font-bold {{ $isDone ? 'bg-gradient-to-br from-forest-400 to-forest-600 text-white shadow-soft' : 'bg-white border-2 border-ink-200 text-ink-400' }}">
                        @if($isDone)✓@else{{ $loop->iteration }}@endif
                    </span>
                    <span class="mt-2 text-[11px] font-semibold text-center {{ $isDone ? 'text-ink-800' : 'text-ink-400' }}">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="mb-8 rounded-lg bg-rose-50 border border-rose-100 p-4 text-sm text-rose-700 font-medium text-center">Pesanan ini telah dibatalkan.</div>
        @endif

        <div class="space-y-4 mb-6 divide-y divide-ink-100">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center gap-3 text-sm {{ !$loop->first ? 'pt-4' : '' }}">
                <div class="min-w-0">
                    <p class="font-medium text-ink-800">{{ $item->product_name }}@if($item->variant_name) <span class="font-normal text-ink-400">({{ $item->variant_name }})</span>@endif</p>
                    <p class="text-xs text-ink-400">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }} · Toko {{ $item->seller->store_name ?? $item->seller->name }}</p>
                    @if($order->status === 'completed' && $item->product)
                        @if(in_array($item->product_id, $reviewedProductIds))
                        <span class="inline-block mt-1.5 text-[11px] font-semibold text-forest-600">✓ Sudah diulas</span>
                        @else
                        <a href="{{ route('products.show', $item->product) }}#ulasan" class="inline-block mt-1.5 text-[11px] font-semibold text-clay-600 hover:text-clay-700 underline">Tulis Ulasan</a>
                        @endif
                    @endif
                </div>
                <p class="font-semibold text-ink-800 shrink-0">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-ink-200 pt-4 space-y-1.5 mb-6">
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Subtotal</span>
                <span class="text-ink-700 font-medium">Rp{{ number_format($order->total_amount + $order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Diskon Kupon @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                <span class="text-forest-600 font-medium">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($order->shipping_amount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Ongkos Kirim</span>
                <span class="text-ink-700 font-medium">Rp{{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between pt-1.5">
                <span class="font-semibold text-ink-800">Total Bayar</span>
                <span class="text-xl font-bold price-tag">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <div class="rounded-md bg-ink-50 border border-ink-100 p-4">
                <p class="text-xs font-semibold text-ink-400 uppercase mb-1">Alamat Pengiriman</p>
                <p class="text-ink-700">{{ $order->shipping_address }}</p>
                @if($order->shipping_city)<p class="text-ink-500 text-xs mt-0.5">Kota: {{ $order->shipping_city }}</p>@endif
                <p class="text-ink-500 mt-1">{{ $order->shipping_phone }}</p>
            </div>
            <div class="rounded-md bg-ink-50 border border-ink-100 p-4">
                <p class="text-xs font-semibold text-ink-400 uppercase mb-1">Pembayaran</p>
                <p class="text-ink-700">{{ $order->paymentMethodLabel() }}</p>
                @if($order->notes)<p class="text-ink-500 mt-1">Catatan: {{ $order->notes }}</p>@endif
            </div>
            <div class="rounded-md bg-ink-50 border border-ink-100 p-4 sm:col-span-2">
                <p class="text-xs font-semibold text-ink-400 uppercase mb-1">Pengiriman</p>
                @if($order->courier)
                <p class="text-ink-700">{{ $order->courierLabel() }}</p>
                @if($order->tracking_number)
                <p class="text-ink-500 mt-1 flex items-center gap-2">No. Resi: <span class="font-mono font-semibold text-ink-800 tracking-wide">{{ $order->tracking_number }}</span></p>
                @else
                <p class="text-ink-400 mt-1 text-xs">Nomor resi akan muncul setelah pesanan dikirim.</p>
                @endif
                @else
                <p class="text-ink-400 text-xs">Kurir belum ditentukan.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
