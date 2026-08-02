@extends('layouts.app')
@section('title', 'Kupon Toko')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    @include('partials.seller-nav')

    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <p class="text-sm text-ink-500 max-w-md">Buat kupon diskon khusus untuk pembeli yang belanja produk dari tokomu.</p>
        <a href="{{ route('seller.coupons.create') }}" class="btn-tactile rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Buat Kupon</a>
    </div>

    @if($coupons->isEmpty())
    <div class="text-center py-20 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium mb-4">Kamu belum punya kupon. Buat kupon pertama untuk menarik pembeli.</p>
        <a href="{{ route('seller.coupons.create') }}" class="btn-tactile inline-block rounded-md bg-clay-600 text-white font-semibold px-6 py-3 text-sm hover:bg-clay-700 hover:brightness-110 transition">Buat Kupon</a>
    </div>
    @else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($coupons as $coupon)
        @php
            $expired = $coupon->expires_at && $coupon->expires_at->isPast();
            $usedUp = $coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit;
        @endphp
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5 relative overflow-hidden">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div>
                    <p class="font-mono font-bold text-ink-900 tracking-wide">{{ $coupon->code }}</p>
                    <p class="text-xs text-ink-400 mt-0.5">{{ $coupon->typeLabel() }}</p>
                </div>
                <span class="shrink-0 text-[10px] font-bold px-2 py-1 rounded-full {{ $coupon->is_active && !$expired && !$usedUp ? 'bg-forest-100 text-forest-700' : 'bg-ink-100 text-ink-500' }}">
                    {{ !$coupon->is_active ? 'Nonaktif' : ($expired ? 'Kadaluarsa' : ($usedUp ? 'Kuota Habis' : 'Aktif')) }}
                </span>
            </div>
            <p class="font-serif text-2xl font-bold text-ink-900">
                {{ $coupon->type === 'percent' ? $coupon->value.'%' : 'Rp'.number_format($coupon->value, 0, ',', '.') }}
            </p>
            <div class="mt-3 space-y-1 text-xs text-ink-500">
                @if($coupon->max_discount)<p>Maks. diskon Rp{{ number_format($coupon->max_discount, 0, ',', '.') }}</p>@endif
                @if($coupon->min_purchase)<p>Min. belanja Rp{{ number_format($coupon->min_purchase, 0, ',', '.') }}</p>@endif
                <p>Terpakai {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }} kali</p>
                @if($coupon->expires_at)<p>Berlaku sampai {{ $coupon->expires_at->translatedFormat('d M Y') }}</p>@endif
            </div>
            <div class="mt-4 flex items-center gap-4 pt-3 border-t border-ink-100">
                <a href="{{ route('seller.coupons.edit', $coupon) }}" class="text-xs font-semibold text-forest-600 hover:underline">Ubah</a>
                <form action="{{ route('seller.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Hapus kupon ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $coupons->links() }}</div>
    @endif
</section>
@endsection
