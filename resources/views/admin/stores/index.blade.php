@extends('layouts.app')
@section('title', 'Kelola Toko')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama toko / penjual..."
            class="flex-1 min-w-[200px] rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
        <select name="status" onchange="this.form.submit()" class="rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Menunggu Persetujuan</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Aktif</option>
            <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Dinonaktifkan</option>
        </select>
        <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700">Cari</button>
    </form>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($stores as $store)
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <a href="{{ route('admin.stores.show', $store) }}" class="font-semibold text-ink-800 hover:text-clay-600 transition-colors truncate block">{{ $store->store_name }}</a>
                    <p class="text-xs text-ink-400 truncate">{{ $store->name }} · {{ $store->email }}</p>
                </div>
                <span class="shrink-0 text-[10px] font-bold px-2 py-1 rounded-full
                    {{ $store->store_status === 'approved' ? 'bg-forest-100 text-forest-700' : ($store->store_status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-600') }}">
                    {{ $store->storeStatusLabel() }}
                </span>
            </div>
            <p class="mt-2 text-xs text-ink-400">{{ $store->products_count }} produk</p>
            <div class="mt-4 flex items-center gap-2">
                @if($store->store_status !== 'approved')
                <form action="{{ route('admin.stores.approve', $store) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="text-xs font-semibold rounded-md bg-clay-600 text-white px-3 py-1.5 hover:bg-clay-700 hover:brightness-110 transition">Setujui</button>
                </form>
                @endif
                @if($store->store_status !== 'suspended')
                <button type="button" onclick="document.getElementById('suspend-{{ $store->id }}').classList.toggle('hidden')" class="text-xs font-semibold rounded-full border border-rose-300 text-rose-600 px-3 py-1.5 hover:bg-rose-50 transition">Nonaktifkan</button>
                @endif
                <a href="{{ route('admin.stores.show', $store) }}" class="text-xs font-semibold text-ink-500 hover:underline ml-auto">Detail</a>
            </div>
            @if($store->store_status !== 'suspended')
            <form id="suspend-{{ $store->id }}" action="{{ route('admin.stores.suspend', $store) }}" method="POST" class="hidden mt-3 space-y-2">
                @csrf @method('PATCH')
                <textarea name="note" rows="2" placeholder="Alasan (opsional)" class="w-full rounded-md border border-ink-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-rose-300"></textarea>
                <button class="w-full rounded-full bg-rose-600 text-white text-xs font-semibold py-2 hover:bg-rose-700 transition">Konfirmasi Nonaktifkan</button>
            </form>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-16 rounded-lg bg-white border border-dashed border-ink-200 shadow-soft">
            <p class="text-ink-400 font-medium">Tidak ada toko yang cocok dengan pencarian.</p>
        </div>
        @endforelse
    </div>
    <div class="mt-8">{{ $stores->links() }}</div>
</section>
@endsection
