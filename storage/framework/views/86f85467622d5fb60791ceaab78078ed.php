<?php $__env->startSection('title', 'Produk Saya'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    <?php echo $__env->make('partials.seller-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <form method="GET" class="flex-1 max-w-sm">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari produk milikmu..."
                class="w-full rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
        </form>
        <a href="<?php echo e(route('seller.products.create')); ?>" class="btn-tactile rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Produk</a>
    </div>

    <?php if($products->isEmpty()): ?>
    <div class="text-center py-20 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium mb-4">Kamu belum punya produk. Yuk tambahkan produk pertamamu.</p>
        <a href="<?php echo e(route('seller.products.create')); ?>" class="btn-tactile inline-block rounded-md bg-clay-600 text-white font-semibold px-6 py-3 text-sm hover:bg-clay-700 hover:brightness-110 transition">Tambah Produk</a>
    </div>
    <?php else: ?>

    <!-- Mobile: card list -->
    <div class="sm:hidden space-y-3">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4">
            <div class="flex items-center gap-3">
                <img src="<?php echo e($product->image_url); ?>" class="h-14 w-14 rounded-md object-cover shrink-0" alt="<?php echo e($product->name); ?>">
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-ink-800 text-sm line-clamp-1"><?php echo e($product->name); ?></p>
                    <p class="text-xs text-ink-400"><?php echo e($product->category->name ?? '-'); ?></p>
                    <div class="mt-0.5"><?php echo $__env->make('partials.product-price', ['product' => $product, 'size' => 'sm'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                </div>
                <div class="shrink-0 flex flex-col items-end gap-1">
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full <?php echo e($product->is_active ? 'bg-forest-100 text-forest-700' : 'bg-ink-100 text-ink-500'); ?>">
                        <?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?>

                    </span>
                    <?php if($product->moderation_status === 'rejected'): ?>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-rose-100 text-rose-600">Disembunyikan Admin</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-ink-100 pt-3">
                <p class="text-xs text-ink-400">Stok <?php echo e($product->hasVariants() ? $product->effectiveStock() : $product->stock); ?>

                    <?php if($product->stockBadgeLabel()): ?>
                    <span class="ml-1 font-bold <?php echo e($product->isOutOfStock() ? 'text-ink-500' : 'text-rose-600'); ?>">· <?php echo e($product->stockBadgeLabel()); ?></span>
                    <?php endif; ?>
                </p>
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('seller.products.edit', $product)); ?>" class="text-xs font-semibold text-forest-600">Ubah</a>
                    <form action="<?php echo e(route('seller.products.destroy', $product)); ?>" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="text-xs font-semibold text-rose-500">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Desktop / tablet: table -->
    <div class="hidden sm:block overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-soft">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-semibold px-4 py-3">Produk</th>
                    <th class="text-left font-semibold px-4 py-3">Kategori</th>
                    <th class="text-left font-semibold px-4 py-3">Harga</th>
                    <th class="text-left font-semibold px-4 py-3">Stok</th>
                    <th class="text-left font-semibold px-4 py-3">Status</th>
                    <th class="text-right font-semibold px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-clay-50/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="<?php echo e($product->image_url); ?>" class="h-10 w-10 rounded-lg object-cover shrink-0" alt="<?php echo e($product->name); ?>">
                            <span class="font-medium text-ink-800 line-clamp-1 max-w-[220px]"><?php echo e($product->name); ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-ink-500"><?php echo e($product->category->name ?? '-'); ?></td>
                    <td class="px-4 py-3 text-ink-800 font-medium"><?php echo $__env->make('partials.product-price', ['product' => $product, 'size' => 'sm'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                    <td class="px-4 py-3 text-ink-500">
                        <?php echo e($product->hasVariants() ? $product->effectiveStock() : $product->stock); ?>

                        <?php if($product->stockBadgeLabel()): ?>
                        <span class="block text-[10px] font-bold <?php echo e($product->isOutOfStock() ? 'text-ink-400' : 'text-rose-600'); ?>"><?php echo e($product->stockBadgeLabel()); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($product->is_active ? 'bg-forest-100 text-forest-700' : 'bg-ink-100 text-ink-500'); ?>">
                            <?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?>

                        </span>
                        <?php if($product->moderation_status === 'rejected'): ?>
                        <span class="block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-600 w-fit">Disembunyikan Admin</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('seller.products.edit', $product)); ?>" class="text-xs font-semibold text-forest-600 hover:underline">Ubah</a>
                            <form action="<?php echo e(route('seller.products.destroy', $product)); ?>" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-6"><?php echo e($products->links()); ?></div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/seller/products/index.blade.php ENDPATH**/ ?>