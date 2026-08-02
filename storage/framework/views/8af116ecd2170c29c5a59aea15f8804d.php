<?php $__env->startSection('title', 'Bandingkan Produk'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6 flex-col sm:flex-row">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Bandingkan Produk</h1>
            <p class="text-sm text-ink-400 mt-1">Lihat harga, rating, dan detail produk pilihanmu berdampingan.</p>
        </div>
        <?php if($products->isNotEmpty()): ?>
        <button type="button" onclick="clearCompare(); window.location.href='<?php echo e(route('home')); ?>';"
            class="shrink-0 text-xs font-semibold text-ink-500 hover:text-rose-600 border border-ink-200 rounded-md px-3 py-2 transition">
            Hapus Semua
        </button>
        <?php endif; ?>
    </div>

    <?php if($products->isEmpty()): ?>
    <div class="text-center py-20 rounded-lg bg-white border border-dashed border-ink-200">
        <p class="text-3xl mb-3">⚖️</p>
        <p class="text-ink-500 font-medium mb-1">Belum ada produk untuk dibandingkan.</p>
        <p class="text-ink-400 text-sm mb-5">Ketuk ikon perbandingan pada produk yang kamu suka, lalu kembali ke sini.</p>
        <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center rounded-md bg-clay-600 hover:bg-clay-700 text-white text-sm font-semibold px-5 py-2.5 transition">Jelajahi Produk</a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-card">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr>
                    <th class="sticky left-0 z-10 bg-white text-left px-4 py-4 w-32 text-xs font-semibold text-ink-400 uppercase tracking-wide align-bottom">Produk</th>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="px-4 py-4 min-w-[190px] align-bottom">
                        <div class="relative">
                            <button type="button" onclick="removeCompareItem(<?php echo e($product->id); ?>)"
                                class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-ink-100 hover:bg-rose-100 hover:text-rose-600 text-ink-500 flex items-center justify-center text-sm transition"
                                title="Hapus dari perbandingan">&times;</button>
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="block">
                                <div class="rounded-md overflow-hidden bg-ink-50 aspect-square">
                                    <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-[13px] font-semibold text-ink-800 leading-snug line-clamp-2"><?php echo e($product->name); ?></p>
                            </a>
                        </div>
                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?>
                    <th class="px-4 py-4 min-w-[150px] align-bottom">
                        <a href="<?php echo e(route('home')); ?>" class="flex flex-col items-center justify-center gap-2 aspect-square rounded-md border-2 border-dashed border-ink-200 text-ink-400 hover:text-clay-600 hover:border-clay-300 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            <span class="text-xs font-semibold">Tambah Produk</span>
                        </a>
                    </th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Harga</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4"><?php echo $__env->make('partials.product-price', ['product' => $product, 'size' => 'sm'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Rating</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4"><?php echo $__env->make('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Terjual</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4 text-ink-700"><?php echo e($product->sold_count); ?> terjual</td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Stok</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4 text-ink-700">
                        <?php if($product->isOutOfStock()): ?>
                            <span class="text-rose-600 font-medium">Stok Habis</span>
                        <?php elseif($product->isLowStock()): ?>
                            <span class="text-amber-600 font-medium">Sisa <?php echo e($product->stock); ?></span>
                        <?php else: ?>
                            <?php echo e($product->stock); ?> tersedia
                        <?php endif; ?>
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Kategori</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4 text-ink-700"><?php echo e($product->category->name ?? '-'); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500">Toko</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4 text-ink-700">
                        <a href="<?php echo e(route('store.show', $product->seller)); ?>" class="hover:text-clay-600"><?php echo e($product->seller->store_name ?? $product->seller->name); ?></a>
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4 text-xs font-semibold text-ink-500 align-top">Deskripsi</td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4 text-ink-500 text-xs leading-relaxed align-top"><?php echo e(Str::limit(strip_tags((string) $product->description), 140) ?: '-'); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
                <tr>
                    <td class="sticky left-0 z-10 bg-white px-4 py-4"></td>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-4 py-4">
                        <div class="flex flex-col gap-2">
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="text-center rounded-md border border-ink-200 text-ink-700 text-xs font-semibold py-2.5 hover:bg-ink-50 transition">Lihat Detail</a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->role === 'buyer' && ! $product->isOutOfStock()): ?>
                                <form action="<?php echo e(route('cart.store', $product)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full rounded-md bg-clay-600 hover:bg-clay-700 text-white text-xs font-semibold py-2.5 transition">+ Keranjang</button>
                                </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($products->count() < 4): ?><td></td><?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/products/compare.blade.php ENDPATH**/ ?>