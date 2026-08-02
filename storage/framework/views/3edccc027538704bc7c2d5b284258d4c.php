<?php ($size = $size ?? 'lg'); ?>
<?php if($product->isOnDiscount()): ?>
<div class="flex items-center gap-1.5 flex-wrap">
    <span class="text-[10px] font-bold px-1 py-0.5 rounded-sm badge-discount">-<?php echo e($product->discount_percent); ?>%</span>
    <p class="<?php echo e($size === 'sm' ? 'text-base' : 'text-lg'); ?> font-bold price-tag">Rp<?php echo e(number_format($product->final_price, 0, ',', '.')); ?></p>
</div>
<p class="text-xs price-strike -mt-1">Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></p>
<?php else: ?>
<p class="<?php echo e($size === 'sm' ? 'text-base' : 'text-lg'); ?> font-bold text-ink-900">Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></p>
<?php endif; ?>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/partials/product-price.blade.php ENDPATH**/ ?>