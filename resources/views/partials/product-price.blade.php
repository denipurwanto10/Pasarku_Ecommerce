@php($size = $size ?? 'lg')
@if($product->isOnDiscount())
<div class="flex items-center gap-1.5 flex-wrap">
    <span class="text-[10px] font-bold px-1 py-0.5 rounded-sm badge-discount">-{{ $product->discount_percent }}%</span>
    <p class="{{ $size === 'sm' ? 'text-base' : 'text-lg' }} font-bold price-tag">Rp{{ number_format($product->final_price, 0, ',', '.') }}</p>
</div>
<p class="text-xs price-strike -mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
@else
<p class="{{ $size === 'sm' ? 'text-base' : 'text-lg' }} font-bold text-ink-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
@endif
