@extends('layouts.app')
@section('title', $store->store_name)
@section('content')
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('admin.stores.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali ke daftar toko</a>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6 mb-6">
        <div class="flex items-start justify-between gap-3 flex-wrap">
            <div>
                <h1 class="font-serif text-2xl font-bold text-ink-900">{{ $store->store_name }}</h1>
                <p class="text-sm text-ink-400 mt-1">{{ $store->name }} · {{ $store->email }} · {{ $store->phone }}</p>
                <p class="text-sm text-ink-400">{{ $store->address }}, {{ $store->city }}</p>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-full
                {{ $store->store_status === 'approved' ? 'bg-forest-100 text-forest-700' : ($store->store_status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-600') }}">
                {{ $store->storeStatusLabel() }}
            </span>
        </div>
        @if($store->store_status_note)
        <p class="mt-3 text-sm text-rose-600 bg-rose-50 border border-rose-200 rounded-md px-4 py-2.5">Catatan: {{ $store->store_status_note }}</p>
        @endif
        @if($store->store_description)
        <p class="mt-4 text-sm text-ink-600">{{ $store->store_description }}</p>
        @endif

        <div class="mt-5 flex items-center gap-2 flex-wrap">
            @if($store->store_status !== 'approved')
            <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                @csrf @method('PATCH')
                <button class="text-sm font-semibold rounded-md bg-clay-600 text-white px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">Setujui Toko</button>
            </form>
            @endif
            @if($store->store_status !== 'suspended')
            <button type="button" onclick="document.getElementById('suspend-form').classList.toggle('hidden')" class="text-sm font-semibold rounded-full border border-rose-300 text-rose-600 px-5 py-2.5 hover:bg-rose-50 transition">Nonaktifkan Toko</button>
            @endif
        </div>
        @if($store->store_status !== 'suspended')
        <form id="suspend-form" action="{{ route('admin.stores.suspend', $store) }}" method="POST" class="hidden mt-4 space-y-2 max-w-md">
            @csrf @method('PATCH')
            <textarea name="note" rows="2" placeholder="Alasan penonaktifan (opsional)" class="w-full rounded-md border border-ink-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300"></textarea>
            <button class="rounded-full bg-rose-600 text-white text-sm font-semibold px-5 py-2 hover:bg-rose-700 transition">Konfirmasi Nonaktifkan</button>
        </form>
        @endif
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
        <h2 class="font-serif text-lg font-bold text-ink-900 mb-4">Produk Terbaru ({{ $store->products_count }} total)</h2>
        @if($products->isEmpty())
        <p class="text-sm text-ink-400">Toko ini belum menambahkan produk.</p>
        @else
        <div class="divide-y divide-ink-100">
            @foreach($products as $product)
            <div class="flex items-center gap-3 py-3">
                <img src="{{ $product->image_url }}" class="h-12 w-12 rounded-lg object-cover shrink-0" alt="{{ $product->name }}">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-ink-400">Rp{{ number_format($product->price, 0, ',', '.') }} · Stok {{ $product->stock }}</p>
                </div>
                <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $product->moderation_status === 'approved' ? 'bg-forest-100 text-forest-700' : 'bg-rose-100 text-rose-600' }}">
                    {{ $product->moderation_status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
