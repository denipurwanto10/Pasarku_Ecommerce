@extends('layouts.app')
@section('title', 'Moderasi Produk')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk..."
            class="flex-1 min-w-[200px] rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
        <select name="status" onchange="this.form.submit()" class="rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            <option value="">Semua Status</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak/Disembunyikan</option>
        </select>
        <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700">Cari</button>
    </form>

    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-soft">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-semibold px-4 py-3">Produk</th>
                    <th class="text-left font-semibold px-4 py-3">Toko</th>
                    <th class="text-left font-semibold px-4 py-3">Harga</th>
                    <th class="text-left font-semibold px-4 py-3">Stok</th>
                    <th class="text-left font-semibold px-4 py-3">Status</th>
                    <th class="text-right font-semibold px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach($products as $product)
                <tr class="hover:bg-clay-50/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" class="h-10 w-10 rounded-lg object-cover shrink-0" alt="{{ $product->name }}">
                            <span class="font-medium text-ink-800 line-clamp-1 max-w-[200px]">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-ink-500">{{ $product->seller->store_name ?? '-' }}</td>
                    <td class="px-4 py-3 text-ink-800 font-medium">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-ink-500">{{ $product->stock }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $product->moderation_status === 'approved' ? 'bg-forest-100 text-forest-700' : 'bg-rose-100 text-rose-600' }}">
                            {{ $product->moderation_status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                        </span>
                        @if($product->moderation_note)
                        <p class="mt-1 text-[10px] text-ink-400 max-w-[160px] truncate" title="{{ $product->moderation_note }}">{{ $product->moderation_note }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            @if($product->moderation_status !== 'approved')
                            <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="text-xs font-semibold text-forest-600 hover:underline">Setujui</button>
                            </form>
                            @else
                            <button type="button" onclick="document.getElementById('reject-{{ $product->id }}').classList.toggle('hidden')" class="text-xs font-semibold text-rose-500 hover:underline">Sembunyikan</button>
                            @endif
                        </div>
                        <form id="reject-{{ $product->id }}" action="{{ route('admin.products.reject', $product) }}" method="POST" class="hidden mt-2 flex items-center gap-1.5 justify-end">
                            @csrf @method('PATCH')
                            <input type="text" name="note" required placeholder="Alasan" class="rounded-lg border border-ink-200 px-2 py-1 text-xs w-32 focus:outline-none focus:ring-2 focus:ring-rose-300">
                            <button class="text-xs font-semibold rounded-lg bg-rose-600 text-white px-2 py-1">OK</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
</section>
@endsection
