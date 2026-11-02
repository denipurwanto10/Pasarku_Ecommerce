<?php $__env->startSection('title', 'Kelola Banner'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-ink-900 mb-2">Panel Admin</h1>
    <?php echo $__env->make('partials.admin-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-serif text-xl font-bold text-ink-900">Banner Promo Beranda</h2>
            <p class="text-sm text-ink-400 mt-1">Banner statis yang tampil di carousel halaman utama. Urutan berdasarkan "Urutan" terkecil ke terbesar.</p>
        </div>
        <a href="<?php echo e(route('admin.banners.create')); ?>" class="btn-tactile shrink-0 rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Banner</a>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft divide-y divide-ink-100">
        <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-center gap-4 px-5 py-4">
            <img src="<?php echo e($banner->image_url); ?>" class="h-16 w-28 rounded-md object-cover border border-ink-100 shrink-0" alt="<?php echo e($banner->title); ?>">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-ink-800 truncate"><?php echo e($banner->title); ?></p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo e($banner->is_active ? 'bg-forest-50 text-forest-700' : 'bg-ink-100 text-ink-500'); ?>"><?php echo e($banner->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
                </div>
                <?php if($banner->subtitle): ?><p class="text-xs text-ink-400 truncate mt-0.5"><?php echo e($banner->subtitle); ?></p><?php endif; ?>
                <p class="text-xs text-ink-300 mt-0.5">Urutan <?php echo e($banner->sort_order); ?><?php echo e($banner->link_url ? ' · Tautan: '.$banner->link_url : ''); ?></p>
            </div>
            <a href="<?php echo e(route('admin.banners.edit', $banner)); ?>" class="text-xs font-semibold text-forest-600 hover:underline shrink-0">Ubah</a>
            <form action="<?php echo e(route('admin.banners.destroy', $banner)); ?>" method="POST" onsubmit="return confirm('Hapus banner ini?')" class="shrink-0">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-16">
            <p class="text-ink-400 font-medium">Belum ada banner. Tambahkan banner pertama untuk halaman utama.</p>
        </div>
        <?php endif; ?>
    </div>
    <div class="mt-6"><?php echo e($banners->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/admin/banners/index.blade.php ENDPATH**/ ?>