<?php $__env->startSection('title', 'Daftar'); ?>
<?php $__env->startSection('content'); ?>
<section class="min-h-[calc(100vh-9rem)] grid lg:grid-cols-2">
    <div class="hidden lg:flex items-center justify-center bg-ink-900 p-14">
        <div class="max-w-sm">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white text-lg font-semibold">P</span>
            <h2 class="mt-6 text-3xl font-semibold leading-tight text-white">Buka toko Anda, jangkau pembeli lokal hari ini.</h2>
            <p class="mt-4 text-white/60 text-sm leading-relaxed">Gratis mendaftar, tanpa biaya bulanan. Unggah produk lalu mulai berjualan.</p>
            <ul class="mt-8 space-y-3 text-sm text-white/80">
                <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-clay-300"></span>Kelola pesanan langsung dari dashboard</li>
                <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-clay-300"></span>Pantau produk terlaris secara real-time</li>
                <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-clay-300"></span>Dukungan pembeli setia dari sekitar Anda</li>
            </ul>
        </div>
    </div>

    <div class="flex items-center justify-center px-4 sm:px-8 py-14 sm:py-20 ">
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-7">
                <span class="inline-flex lg:hidden h-12 w-12 items-center justify-center rounded-2xl bg-ink-900 text-white text-lg font-semibold mb-3">P</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Buat Akun Baru</h1>
                <p class="text-sm text-ink-400 mt-1.5">Gratis, cukup beberapa langkah saja.</p>
            </div>

            <?php if($errors->any()): ?>
            <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                <ul class="list-disc pl-4 space-y-0.5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="buyer" class="peer sr-only" <?php echo e(old('role','buyer')=='buyer'?'checked':''); ?> onchange="document.getElementById('storeFields').classList.add('hidden')">
                        <div class="rounded-xl border-2 border-ink-200 peer-checked:border-clay-500 peer-checked:bg-clay-50 peer-checked:shadow-soft px-4 py-3 text-center transition-all hover:border-clay-300">
                                                        <p class="text-sm font-semibold">Pembeli</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="seller" class="peer sr-only" <?php echo e(old('role')=='seller'?'checked':''); ?> onchange="document.getElementById('storeFields').classList.remove('hidden')">
                        <div class="rounded-xl border-2 border-ink-200 peer-checked:border-forest-500 peer-checked:bg-forest-50 peer-checked:shadow-soft px-4 py-3 text-center transition-all hover:border-forest-300">
                                                        <p class="text-sm font-semibold">Penjual</p>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                        class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required
                        class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div id="storeFields" class="<?php echo e(old('role')=='seller' ? '' : 'hidden'); ?>">
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Toko</label>
                    <input type="text" name="store_name" value="<?php echo e(old('store_name')); ?>"
                        class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" required
                            class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1.5">Ulangi Sandi</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                    </div>
                </div>
                <button type="submit" class="btn-tactile w-full rounded-full gradient-brand text-white font-semibold py-3.5 text-sm shadow-glow hover:brightness-110 transition">Buat Akun</button>
            </form>

            <div class="mt-6 pt-6 border-t border-ink-100 text-center lg:text-left text-sm text-ink-500">
                Sudah punya akun? <a href="<?php echo e(route('login')); ?>" class="font-semibold text-clay-600 hover:underline">Masuk</a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/auth/register.blade.php ENDPATH**/ ?>