@extends('layouts.app')
@section('title', 'Riwayat Stok')
@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('seller.stock.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali ke Manajemen Stok</a>

    <div class="flex items-center gap-3 mb-6">
        <img src="{{ $product->image_url }}" class="h-14 w-14 rounded-md object-cover" alt="{{ $product->name }}">
        <div>
            <h1 class="font-serif text-xl font-bold text-ink-900">{{ $product->name }}</h1>
            <p class="text-sm text-ink-400">Stok saat ini: <span class="font-semibold text-ink-700">{{ $product->stock }}</span></p>
        </div>
    </div>

    @if($histories->isEmpty())
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium">Belum ada riwayat perubahan stok.</p>
    </div>
    @else
    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-soft">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-semibold px-4 py-3">Tanggal</th>
                    <th class="text-left font-semibold px-4 py-3">Tipe</th>
                    <th class="text-left font-semibold px-4 py-3">Perubahan</th>
                    <th class="text-left font-semibold px-4 py-3">Stok Setelahnya</th>
                    <th class="text-left font-semibold px-4 py-3">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach($histories as $h)
                <tr>
                    <td class="px-4 py-3 text-ink-500">{{ $h->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-bold px-2 py-1 rounded-full bg-ink-100 text-ink-600">{{ $h->typeLabel() }}</span></td>
                    <td class="px-4 py-3 font-semibold {{ $h->quantity_change >= 0 ? 'text-forest-600' : 'text-rose-600' }}">{{ $h->quantity_change >= 0 ? '+' : '' }}{{ $h->quantity_change }}</td>
                    <td class="px-4 py-3 text-ink-700">{{ $h->stock_after }}</td>
                    <td class="px-4 py-3 text-ink-400">{{ $h->note ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $histories->links() }}</div>
    @endif
</section>
@endsection
