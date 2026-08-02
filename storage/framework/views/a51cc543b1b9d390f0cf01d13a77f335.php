<?php $__env->startSection('title', 'Manajemen Stok'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    <?php echo $__env->make('partials.seller-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-6">
        <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari produk..."
            class="flex-1 min-w-[200px] rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
        <select name="filter" onchange="this.form.submit()" class="rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            <option value="">Semua Stok</option>
            <option value="low" <?php echo e(request('filter')=='low'?'selected':''); ?>>Stok Menipis</option>
            <option value="out" <?php echo e(request('filter')=='out'?'selected':''); ?>>Stok Habis</option>
        </select>
        <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700">Cari</button>
    </form>

    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-soft">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-semibold px-4 py-3">Produk</th>
                    <th class="text-left font-semibold px-4 py-3">Stok Saat Ini</th>
                    <th class="text-left font-semibold px-4 py-3">Sesuaikan Stok</th>
                    <th class="text-right font-semibold px-4 py-3">Riwayat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-clay-50/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="<?php echo e($product->image_url); ?>" class="h-10 w-10 rounded-lg object-cover shrink-0" alt="<?php echo e($product->name); ?>">
                            <span class="font-medium text-ink-800 line-clamp-1 max-w-[200px]"><?php echo e($product->name); ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold text-ink-800"><?php echo e($product->stock); ?></span>
                        <?php if($product->stockBadgeLabel()): ?>
                        <span class="ml-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo e($product->isOutOfStock() ? 'bg-ink-100 text-ink-500' : 'bg-rose-100 text-rose-600'); ?>"><?php echo e($product->stockBadgeLabel()); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <form action="<?php echo e(route('seller.stock.adjust', $product)); ?>" method="POST" class="flex items-center gap-1.5">
                            <?php echo csrf_field(); ?>
                            <input type="number" name="quantity" placeholder="+/-" required class="w-20 rounded-lg border border-ink-200 px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <input type="text" name="note" placeholder="Catatan (opsional)" class="w-32 rounded-lg border border-ink-200 px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <button class="text-xs font-semibold rounded-lg bg-ink-800 text-white px-2.5 py-1.5">Simpan</button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="<?php echo e(route('seller.stock.history', $product)); ?>" class="text-xs font-semibold text-clay-600 hover:underline">Lihat Riwayat</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-6"><?php echo e($products->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/seller/stock/index.blade.php ENDPATH**/ ?>