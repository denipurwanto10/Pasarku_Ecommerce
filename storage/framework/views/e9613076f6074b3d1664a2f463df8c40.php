<?php $__env->startSection('title', 'Pengaturan Ongkir'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    <?php echo $__env->make('partials.seller-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6 mb-6">
        <h2 class="font-serif text-lg font-bold text-ink-900 mb-1">Mode Ongkir Toko</h2>
        <p class="text-sm text-ink-400 mb-5">Pilih apakah tokomu memakai tarif flat untuk semua kota, atau tarif berbeda per kota tujuan.</p>

        <form action="<?php echo e(route('seller.shipping.update')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="grid sm:grid-cols-2 gap-3">
                <label class="flex items-start gap-2 rounded-md border p-4 cursor-pointer <?php echo e($seller->shipping_type === 'flat' ? 'border-clay-400 bg-clay-50/50' : 'border-ink-200'); ?>">
                    <input type="radio" name="shipping_type" value="flat" <?php echo e($seller->shipping_type === 'flat' ? 'checked' : ''); ?> class="mt-1 text-clay-600 focus:ring-clay-400">
                    <span>
                        <span class="block text-sm font-semibold text-ink-800">Tarif Flat</span>
                        <span class="block text-xs text-ink-400">Satu tarif ongkir untuk semua kota tujuan.</span>
                    </span>
                </label>
                <label class="flex items-start gap-2 rounded-md border p-4 cursor-pointer <?php echo e($seller->shipping_type === 'per_city' ? 'border-clay-400 bg-clay-50/50' : 'border-ink-200'); ?>">
                    <input type="radio" name="shipping_type" value="per_city" <?php echo e($seller->shipping_type === 'per_city' ? 'checked' : ''); ?> class="mt-1 text-clay-600 focus:ring-clay-400">
                    <span>
                        <span class="block text-sm font-semibold text-ink-800">Tarif per Kota</span>
                        <span class="block text-xs text-ink-400">Atur tarif berbeda untuk tiap kota di bawah.</span>
                    </span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Tarif Flat / Default (Rp)</label>
                <input type="number" name="shipping_flat_rate" value="<?php echo e(old('shipping_flat_rate', $seller->shipping_flat_rate)); ?>" min="0" required class="w-full sm:w-64 rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                <p class="mt-1.5 text-xs text-ink-400">Dipakai untuk semua kota (mode flat), atau sebagai tarif fallback bila kota tujuan belum diatur (mode per kota).</p>
            </div>
            <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Pengaturan</button>
        </form>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
        <h2 class="font-serif text-lg font-bold text-ink-900 mb-1">Tarif per Kota</h2>
        <p class="text-sm text-ink-400 mb-5">Hanya berlaku bila mode ongkir toko diatur ke "Tarif per Kota".</p>

        <form action="<?php echo e(route('seller.shipping.city.store')); ?>" method="POST" class="flex flex-wrap items-end gap-3 mb-6">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Nama Kota</label>
                <input type="text" name="city" required placeholder="Contoh: Jakarta Selatan" class="rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Tarif (Rp)</label>
                <input type="number" name="rate" min="0" required class="w-32 rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            </div>
            <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">Simpan Tarif</button>
        </form>

        <?php if($cityRates->isEmpty()): ?>
        <p class="text-sm text-ink-400">Belum ada tarif kota yang diatur.</p>
        <?php else: ?>
        <div class="divide-y divide-ink-100">
            <?php $__currentLoopData = $cityRates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between py-3">
                <p class="text-sm font-medium text-ink-800"><?php echo e($rate->city); ?></p>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-semibold text-ink-700">Rp<?php echo e(number_format($rate->rate, 0, ',', '.')); ?></p>
                    <form action="<?php echo e(route('seller.shipping.city.destroy', $rate)); ?>" method="POST" onsubmit="return confirm('Hapus tarif kota ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/seller/shipping/index.blade.php ENDPATH**/ ?>