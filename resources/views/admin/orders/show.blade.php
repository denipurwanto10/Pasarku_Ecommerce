@extends('layouts.app')
@section('title', $order->order_number)
@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-6 inline-block">&larr; Kembali ke daftar transaksi</a>

    <div class="rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="font-serif text-xl sm:text-2xl font-bold text-ink-900">{{ $order->order_number }}</h1>
                <p class="text-sm text-ink-400 mt-1">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
                <p class="text-sm text-ink-500 mt-1">Pembeli: {{ $order->buyer->name }} ({{ $order->buyer->email }})</p>
            </div>
            <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full bg-{{ $order->statusColor() }}-50 text-{{ $order->statusColor() }}-700">{{ $order->statusLabel() }}</span>
        </div>

        <div class="space-y-4 mb-6 divide-y divide-ink-100">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center gap-3 text-sm {{ !$loop->first ? 'pt-4' : '' }}">
                <div class="min-w-0">
                    <p class="font-medium text-ink-800">{{ $item->product_name }}@if($item->variant_name) <span class="font-normal text-ink-400">({{ $item->variant_name }})</span>@endif</p>
                    <p class="text-xs text-ink-400">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }} · Toko {{ $item->seller->store_name ?? $item->seller->name }}</p>
                </div>
                <p class="font-semibold text-ink-800 shrink-0">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-ink-200 pt-4 space-y-1.5 mb-6">
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Subtotal</span>
                <span class="text-ink-700 font-medium">Rp{{ number_format($order->total_amount + $order->discount_amount - $order->shipping_amount, 0, ',', '.') }}</span>
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
                <p class="text-ink-500 mt-1">No. Resi: <span class="font-mono font-semibold text-ink-800">{{ $order->tracking_number }}</span></p>
                @endif
                @else
                <p class="text-ink-400 text-xs">Kurir belum ditentukan.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
