<?php $__env->startSection('title', 'Panel Admin'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-ink-900 mb-2">Panel Admin</h1>
    <?php echo $__env->make('partials.admin-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Pembeli</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900"><?php echo e($totalBuyers); ?></p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Penjual</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900"><?php echo e($totalSellers); ?></p>
            <?php if($pendingStores > 0): ?>
            <a href="<?php echo e(route('admin.stores.index', ['status' => 'pending'])); ?>" class="mt-1 inline-block text-xs font-semibold text-amber-600 hover:underline"><?php echo e($pendingStores); ?> menunggu persetujuan &rarr;</a>
            <?php endif; ?>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Produk</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900"><?php echo e($totalProducts); ?></p>
            <p class="mt-1 text-xs text-ink-400"><?php echo e($lowStockCount); ?> menipis · <?php echo e($outOfStockCount); ?> habis</p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide">Total Transaksi</p>
            <p class="mt-1 font-serif text-2xl font-bold text-ink-900">Rp<?php echo e(number_format($totalTransaction, 0, ',', '.')); ?></p>
            <p class="mt-1 text-xs text-ink-400"><?php echo e($totalOrders); ?> pesanan</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-lg font-bold text-ink-900">Toko Menunggu Persetujuan</h2>
                <a href="<?php echo e(route('admin.stores.index', ['status' => 'pending'])); ?>" class="text-xs font-semibold text-clay-600 hover:underline">Lihat semua</a>
            </div>
            <?php if($pendingSellers->isEmpty()): ?>
            <p class="text-sm text-ink-400">Tidak ada toko yang menunggu persetujuan saat ini.</p>
            <?php else: ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $pendingSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between gap-3 rounded-md border border-ink-100 p-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink-800 truncate"><?php echo e($seller->store_name); ?></p>
                        <p class="text-xs text-ink-400 truncate"><?php echo e($seller->name); ?> · <?php echo e($seller->email); ?></p>
                    </div>
                    <form action="<?php echo e(route('admin.stores.approve', $seller)); ?>" method="POST" class="shrink-0">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="text-xs font-semibold rounded-md bg-clay-600 text-white px-3 py-1.5 hover:bg-clay-700 hover:brightness-110 transition">Setujui</button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-lg font-bold text-ink-900">Transaksi Terbaru</h2>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-xs font-semibold text-clay-600 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="flex items-center justify-between gap-3 rounded-md border border-ink-100 p-3 hover:border-clay-300 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink-800 truncate"><?php echo e($order->order_number); ?></p>
                        <p class="text-xs text-ink-400 truncate"><?php echo e($order->buyer->name); ?></p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-ink-900">Rp<?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></p>
                        <p class="text-[10px] text-ink-400"><?php echo e($order->created_at->diffForHumans()); ?></p>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>