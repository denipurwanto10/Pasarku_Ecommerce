<?php $__env->startSection('title', 'Alamat Saya'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Alamat Saya</h1>
        <a href="<?php echo e(route('addresses.create')); ?>" class="btn-tactile rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Alamat</a>
    </div>

    <?php if($addresses->isEmpty()): ?>
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium mb-4">Kamu belum menyimpan alamat apapun.</p>
        <a href="<?php echo e(route('addresses.create')); ?>" class="btn-tactile inline-block rounded-md bg-clay-600 text-white font-semibold px-6 py-3 text-sm hover:bg-clay-700 hover:brightness-110 transition">Tambah Alamat</a>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-ink-800"><?php echo e($address->label); ?></p>
                        <?php if($address->is_default): ?>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-forest-100 text-forest-700">Utama</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-ink-600 mt-1"><?php echo e($address->recipient_name); ?> · <?php echo e($address->phone); ?></p>
                    <p class="text-sm text-ink-500 mt-0.5"><?php echo e($address->detail); ?>, <?php echo e($address->city); ?></p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <?php if(!$address->is_default): ?>
                    <form action="<?php echo e(route('addresses.setDefault', $address)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="text-xs font-semibold text-forest-600 hover:underline">Jadikan Utama</button>
                    </form>
                    <?php endif; ?>
                    <a href="<?php echo e(route('addresses.edit', $address)); ?>" class="text-xs font-semibold text-ink-500 hover:underline">Ubah</a>
                    <form action="<?php echo e(route('addresses.destroy', $address)); ?>" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/addresses/index.blade.php ENDPATH**/ ?>