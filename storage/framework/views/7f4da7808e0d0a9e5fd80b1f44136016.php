<?php $__env->startSection('title', 'Ubah Alamat'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="<?php echo e(route('addresses.index')); ?>" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-ink-900 mb-8">Ubah Alamat</h1>

    <?php if($errors->any()): ?>
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('addresses.update', $address)); ?>" method="POST" class="rounded-lg border border-ink-100 bg-white shadow-card p-6 space-y-5">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Label Alamat</label>
            <input type="text" name="label" value="<?php echo e(old('label', $address->label)); ?>" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Penerima</label>
                <input type="text" name="recipient_name" value="<?php echo e(old('recipient_name', $address->recipient_name)); ?>" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. Telepon</label>
                <input type="text" name="phone" value="<?php echo e(old('phone', $address->phone)); ?>" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kota</label>
            <input type="text" name="city" value="<?php echo e(old('city', $address->city)); ?>" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Alamat Lengkap</label>
            <textarea name="detail" rows="3" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors"><?php echo e(old('detail', $address->detail)); ?></textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_default" value="1" <?php echo e($address->is_default ? 'checked' : ''); ?> class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Jadikan alamat utama
        </label>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/addresses/edit.blade.php ENDPATH**/ ?>