<?php $__env->startSection('title', 'Kelola Kategori'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-ink-900 mb-2">Panel Admin</h1>
    <?php echo $__env->make('partials.admin-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-xl font-bold text-ink-900">Kategori Produk</h2>
        <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn-tactile rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Kategori</a>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft divide-y divide-ink-100">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-center gap-3 px-5 py-4">
            <span class="text-xl"><?php echo e($cat->icon ?? '🏷️'); ?></span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-ink-800"><?php echo e($cat->name); ?></p>
                <p class="text-xs text-ink-400"><?php echo e($cat->products_count); ?> produk</p>
            </div>
            <a href="<?php echo e(route('admin.categories.edit', $cat)); ?>" class="text-xs font-semibold text-forest-600 hover:underline">Ubah</a>
            <form action="<?php echo e(route('admin.categories.destroy', $cat)); ?>" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-16">
            <p class="text-ink-400 font-medium">Belum ada kategori.</p>
        </div>
        <?php endif; ?>
    </div>
    <div class="mt-6"><?php echo e($categories->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>