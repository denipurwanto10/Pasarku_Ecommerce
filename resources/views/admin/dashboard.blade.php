@extends('layouts.app')
@section('title', 'Panel Admin')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Pembeli</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900">{{ $totalBuyers }}</p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Penjual</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900">{{ $totalSellers }}</p>
            @if($pendingStores > 0)
            <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}" class="mt-1 inline-block text-xs font-semibold text-amber-600 hover:underline">{{ $pendingStores }} menunggu persetujuan &rarr;</a>
            @endif
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Produk</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900">{{ $totalProducts }}</p>
            <p class="mt-1 text-xs text-ink-400">{{ $lowStockCount }} menipis · {{ $outOfStockCount }} habis</p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Total Transaksi</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900">Rp{{ number_format($totalTransaction, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-ink-400">{{ $totalOrders }} pesanan</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-lg font-bold text-ink-900">Toko Menunggu Persetujuan</h2>
                <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-clay-600 hover:underline">Lihat semua</a>
            </div>
            @if($pendingSellers->isEmpty())
            <p class="text-sm text-ink-400">Tidak ada toko yang menunggu persetujuan saat ini.</p>
            @else
            <div class="space-y-3">
                @foreach($pendingSellers as $seller)
                <div class="flex items-center justify-between gap-3 rounded-md border border-ink-100 p-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink-800 truncate">{{ $seller->store_name }}</p>
                        <p class="text-xs text-ink-400 truncate">{{ $seller->name }} · {{ $seller->email }}</p>
                    </div>
                    <form action="{{ route('admin.stores.approve', $seller) }}" method="POST" class="shrink-0">
                        @csrf @method('PATCH')
                        <button class="text-xs font-semibold rounded-md bg-clay-600 text-white px-3 py-1.5 hover:bg-clay-700 hover:brightness-110 transition">Setujui</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-lg font-bold text-ink-900">Transaksi Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-clay-600 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between gap-3 rounded-md border border-ink-100 p-3 hover:border-clay-300 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink-800 truncate">{{ $order->order_number }}</p>
                        <p class="text-xs text-ink-400 truncate">{{ $order->buyer->name }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-ink-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-ink-400">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
