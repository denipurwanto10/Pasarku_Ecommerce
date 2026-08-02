<?php
    $rating = (float) ($rating ?? 0);
    $count = (int) ($count ?? 0);
    $size = $size ?? 'h-3.5 w-3.5';
?>
<div class="flex items-center gap-1">
    <div class="flex text-amber-400 shrink-0">
        <?php for($i = 1; $i <= 5; $i++): ?>
        <svg class="<?php echo e($size); ?>" fill="<?php echo e($i <= round($rating) ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 21.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
        </svg>
        <?php endfor; ?>
    </div>
    <?php if($count > 0): ?>
        <span class="text-xs text-ink-500 shrink-0"><?php echo e(number_format($rating, 1)); ?> <span class="text-ink-400">(<?php echo e($count); ?>)</span></span>
    <?php elseif($showEmpty ?? false): ?>
        <span class="text-xs text-ink-400 shrink-0">Belum ada ulasan</span>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/partials/rating-stars.blade.php ENDPATH**/ ?>