<?php $__env->startSection('title', 'Pesanan Saya'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Pesanan Saya</h1>

    <?php if($orders->isEmpty()): ?>
    <div class="text-center py-24 rounded-3xl bg-white border border-dashed border-ink-200 shadow-soft">
        <p class="text-4xl mb-3">📦</p>
        <p class="text-ink-500 font-medium mb-4">Kamu belum memiliki pesanan.</p>
        <a href="<?php echo e(route('home')); ?>" class="btn-tactile inline-block rounded-full gradient-brand text-white font-semibold px-6 py-3 text-sm shadow-glow hover:brightness-110 transition">Mulai Belanja</a>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('orders.show', $order)); ?>" class="flex items-center gap-4 rounded-2xl border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-300 hover:shadow-card transition-all">
            <div class="flex -space-x-3 shrink-0">
                <?php $__currentLoopData = $order->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl overflow-hidden bg-ink-50 ring-2 ring-white">
                    <img src="<?php echo e($item->product->image_url ?? 'https://placehold.co/100x100?text=%20'); ?>" class="w-full h-full object-cover" alt="<?php echo e($item->product_name); ?>">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($order->items->count() > 3): ?>
                <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl bg-ink-100 ring-2 ring-white flex items-center justify-center text-[11px] font-bold text-ink-500">+<?php echo e($order->items->count() - 3); ?></div>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs text-ink-400"><?php echo e($order->order_number); ?> · <?php echo e($order->created_at->translatedFormat('d M Y')); ?></p>
                    <p class="text-sm font-semibold text-ink-800 mt-1"><?php echo e($order->items->count()); ?> produk</p>
                </div>
                <div class="text-right">
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full bg-<?php echo e($order->statusColor()); ?>-50 text-<?php echo e($order->statusColor()); ?>-700"><?php echo e($order->statusLabel()); ?></span>
                    <p class="font-serif text-lg font-bold text-ink-900 mt-1">Rp<?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></p>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($orders->links()); ?></div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/orders/index.blade.php ENDPATH**/ ?>