<?php $__env->startSection('title', 'Halaman tidak ditemukan'); ?>
<?php $__env->startSection('content'); ?>
<section class="mx-auto flex min-h-[60vh] max-w-xl flex-col items-center justify-center px-6 py-20 text-center">
    <p class="font-mono text-sm tracking-widest text-ink-400">404</p>
    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-ink-900 sm:text-4xl">Halaman tidak ditemukan</h1>
    <p class="mt-3 max-w-sm text-ink-500">Alamatnya mungkin salah ketik, atau produknya sudah tidak tersedia.</p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="<?php echo e(\Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/')); ?>" class="btn-tactile rounded-full bg-ink-900 px-6 py-3 text-sm font-medium text-white hover:bg-ink-700">Kembali ke beranda</a>
        <a href="javascript:history.back()" class="btn-tactile rounded-full border border-ink-200 bg-white px-6 py-3 text-sm font-medium text-ink-700 hover:border-ink-300">Halaman sebelumnya</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/errors/404.blade.php ENDPATH**/ ?>