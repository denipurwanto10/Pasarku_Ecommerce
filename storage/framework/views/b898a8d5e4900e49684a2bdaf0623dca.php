<?php $__env->startSection('title', 'Halaman Tidak Ditemukan'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center relative">
    <p class="font-serif text-7xl sm:text-8xl font-bold select-none bg-gradient-to-br from-clay-400 to-clay-700 bg-clip-text text-transparent">404</p>
    <h1 class="mt-4 font-serif text-2xl sm:text-3xl font-bold text-ink-900">Halaman yang kamu cari tidak ada</h1>
    <p class="mt-3 text-ink-500 max-w-md mx-auto">Mungkin produknya sudah tidak dijual, atau alamatnya salah ketik. Yuk kembali jelajahi produk lainnya.</p>
    <a href="<?php echo e(route('home')); ?>" class="btn-tactile inline-block mt-8 rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Kembali ke Beranda</a>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/errors/404.blade.php ENDPATH**/ ?>