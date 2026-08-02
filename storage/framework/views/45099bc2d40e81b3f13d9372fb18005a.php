<?php $__env->startSection('title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-white border-b border-ink-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden text-xs text-ink-500">
            <span class="shrink-0 flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Transaksi Aman</span>
            <span class="shrink-0 flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125" /></svg> <?php echo e(\App\Models\Product::count()); ?>+ Produk</span>
            <span class="shrink-0 flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg> <?php echo e(\App\Models\User::where('role','seller')->count()); ?>+ Penjual Lokal</span>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3 sm:gap-4">
        <?php $__currentLoopData = $categories->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('home', ['category' => $cat->slug])); ?>" class="flex flex-col items-center gap-1.5 group">
            <span class="h-12 w-12 sm:h-14 sm:w-14 rounded-lg market-card flex items-center justify-center text-xl sm:text-2xl group-hover:border-clay-300"><?php echo e($cat->icon ?: '🛍️'); ?></span>
            <span class="text-[11px] text-ink-600 text-center leading-tight line-clamp-1 group-hover:text-clay-600"><?php echo e($cat->name); ?></span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<?php if($banners->isNotEmpty()): ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    <div class="relative rounded-lg overflow-hidden shadow-card" id="banner-carousel">
        <div id="banner-track" class="flex transition-transform duration-500 ease-out">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($banner->link_url ?: '#'); ?>" class="relative w-full shrink-0 block aspect-[3/1] sm:aspect-[4/1] bg-ink-900">
                <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-ink-900/70 via-ink-900/20 to-transparent"></div>
                <div class="absolute inset-0 flex flex-col justify-center px-6 sm:px-12 max-w-lg">
                    <h3 class="font-serif text-lg sm:text-3xl font-bold text-white leading-tight"><?php echo e($banner->title); ?></h3>
                    <?php if($banner->subtitle): ?>
                    <p class="mt-1.5 text-xs sm:text-sm text-white/80 line-clamp-2"><?php echo e($banner->subtitle); ?></p>
                    <?php endif; ?>
                    <?php if($banner->button_label): ?>
                    <span class="mt-4 inline-flex w-fit items-center rounded-md bg-clay-600 text-white text-xs sm:text-sm font-semibold px-4 py-2 hover:bg-clay-700"><?php echo e($banner->button_label); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if($banners->count() > 1): ?>
        <button type="button" onclick="bannerGo(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 h-9 w-9 rounded-full bg-white/90 text-ink-700 shadow-soft flex items-center justify-center hover:bg-white transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        </button>
        <button type="button" onclick="bannerGo(1)" class="absolute right-3 top-1/2 -translate-y-1/2 h-9 w-9 rounded-full bg-white/90 text-ink-700 shadow-soft flex items-center justify-center hover:bg-white transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
        </button>
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5" id="banner-dots">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" onclick="bannerGoTo(<?php echo e($i); ?>)" class="banner-dot h-1.5 rounded-full transition-all <?php echo e($i === 0 ? 'w-5 bg-white' : 'w-1.5 bg-white/50'); ?>"></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const track = document.getElementById('banner-track');
    if (!track) return;
    const slides = track.children.length;
    let current = 0;
    let timer;

    window.bannerGoTo = function (index) {
        current = ((index % slides) + slides) % slides;
        track.style.transform = `translateX(-${current * 100}%)`;
        document.querySelectorAll('.banner-dot').forEach((dot, i) => {
            dot.classList.toggle('w-5', i === current);
            dot.classList.toggle('bg-white', i === current);
            dot.classList.toggle('w-1.5', i !== current);
            dot.classList.toggle('bg-white/50', i !== current);
        });
    };
    window.bannerGo = function (dir) { bannerGoTo(current + dir); resetTimer(); };

    function resetTimer() {
        clearInterval(timer);
        if (slides > 1) timer = setInterval(() => bannerGoTo(current + 1), 6000);
    }
    resetTimer();
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if($flashSaleProducts->isNotEmpty()): ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    <div class="rounded-lg overflow-hidden bg-ink-900 relative">
        <!-- Cahaya berlapis: merah muda (urgensi) + ungu brand + kuning keemasan, bukan warna ungu rata -->
        <div class="absolute -top-24 -left-16 h-64 w-64 rounded-full bg-rose-500/30 blur-3xl"></div>
        <div class="absolute -bottom-28 right-0 h-72 w-72 rounded-full bg-clay-500/30 blur-3xl"></div>
        <div class="absolute top-0 right-1/3 h-40 w-40 rounded-full bg-amber-400/20 blur-3xl"></div>
        <div class="absolute inset-x-0 top-0 h-10 flash-stripe opacity-60"></div>

        <div class="relative px-4 sm:px-6 py-5">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-amber-500 shadow-glow">
                        <svg class="h-4.5 w-4.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11.983 1.907a.75.75 0 00-1.292-.657L5.03 8.53a.75.75 0 00.53 1.28h4.94l-1.933 7.905a.75.75 0 001.293.657l6.65-8.5a.75.75 0 00-.5-1.212h-4.98l1.953-6.753z" clip-rule="evenodd" /></svg>
                    </span>
                    <div>
                        <h2 class="font-serif text-lg sm:text-xl font-bold text-white leading-tight">Flash Sale</h2>
                        <p class="text-[11px] text-white/50 font-medium hidden sm:block">Diskon gila-gilaan, stok terbatas</p>
                    </div>
                </div>
                <div class="flex items-center gap-2" data-flash-countdown="<?php echo e($flashSaleEndsAt?->timestamp); ?>">
                    <span class="text-[11px] text-white/60 font-medium hidden xs:inline">Berakhir dalam</span>
                    <div class="flex items-center gap-1 font-mono text-xs sm:text-sm font-bold text-white">
                        <span class="rounded-md bg-white/10 border border-white/15 backdrop-blur px-2 py-1 min-w-[2.25rem] text-center" data-flash-hh>00</span>
                        <span class="text-rose-400 flash-pulse">:</span>
                        <span class="rounded-md bg-white/10 border border-white/15 backdrop-blur px-2 py-1 min-w-[2.25rem] text-center" data-flash-mm>00</span>
                        <span class="text-rose-400 flash-pulse">:</span>
                        <span class="rounded-md bg-white/10 border border-white/15 backdrop-blur px-2 py-1 min-w-[2.25rem] text-center" data-flash-ss>00</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex gap-3 overflow-x-auto pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <?php $__currentLoopData = $flashSaleProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($progress = $product->hasFlashSaleQuota() ? $product->flashSaleProgressPercent() : ($product->stock > 0 ? min(96, max(6, round($product->sold_count / max($product->sold_count + $product->stock, 1) * 100))) : 100)); ?>
                <a href="<?php echo e(route('products.show', $product)); ?>" class="flash-card shrink-0 w-32 sm:w-40 rounded-lg bg-white p-2 relative">
                    <div class="rounded-md overflow-hidden bg-ink-50 aspect-square relative">
                        <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover" loading="lazy">
                        <span class="absolute top-0 left-2 flash-ribbon bg-gradient-to-b from-rose-500 to-rose-600 text-white text-[10px] font-extrabold px-1.5 pt-0.5 pb-2 shadow-sm">-<?php echo e($product->discount_percent); ?>%</span>
                    </div>
                    <p class="mt-2 text-[12px] font-normal text-ink-800 leading-snug line-clamp-2"><?php echo e($product->name); ?></p>
                    <p class="mt-1 price-tag text-sm">Rp<?php echo e(number_format($product->discount_price, 0, ',', '.')); ?></p>
                    <p class="price-strike text-[11px]">Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></p>
                    <div class="mt-1.5 h-1.5 rounded-full bg-ink-100 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-rose-500 to-amber-500" style="width: <?php echo e($progress); ?>%"></div>
                    </div>
                    <p class="mt-1 text-[10px] text-ink-400"><?php echo e($product->hasFlashSaleQuota() ? 'Sisa '.$product->flashSaleRemaining().' unit' : ($progress >= 96 ? 'Hampir habis' : $progress.'% terjual')); ?></p>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const el = document.querySelector('[data-flash-countdown]');
    if (!el) return;
    const endTs = parseInt(el.dataset.flashCountdown, 10) * 1000;
    if (!endTs) return;
    const hh = el.querySelector('[data-flash-hh]');
    const mm = el.querySelector('[data-flash-mm]');
    const ss = el.querySelector('[data-flash-ss]');
    const pad = n => String(Math.max(0, n)).padStart(2, '0');
    function tick() {
        const diff = endTs - Date.now();
        if (diff <= 0) { hh.textContent = mm.textContent = ss.textContent = '00'; clearInterval(timer); return; }
        const totalSeconds = Math.floor(diff / 1000);
        hh.textContent = pad(Math.floor(totalSeconds / 3600));
        mm.textContent = pad(Math.floor((totalSeconds % 3600) / 60));
        ss.textContent = pad(totalSeconds % 60);
    }
    tick();
    const timer = setInterval(tick, 1000);
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<section id="produk" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h2 class="font-serif text-2xl font-bold text-ink-900">
            <?php if(request('q')): ?> Hasil untuk "<?php echo e(request('q')); ?>" <?php else: ?> Produk Pilihan <?php endif; ?>
        </h2>
        <form method="GET" action="<?php echo e(route('home')); ?>" class="flex items-center gap-2 flex-wrap">
            <?php if(request('q')): ?><input type="hidden" name="q" value="<?php echo e(request('q')); ?>"><?php endif; ?>
            <?php if(request('category')): ?><input type="hidden" name="category" value="<?php echo e(request('category')); ?>"><?php endif; ?>

            <details class="relative">
                <summary class="list-none cursor-pointer text-sm rounded-md border border-ink-200 px-3 py-2 bg-white hover:border-clay-300 transition-colors flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-ink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m9 12h3.75M16.5 18a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0M3.75 18H13.5m-9-6h9.75m-9.75 0a1.5 1.5 0 003 0m-3 0a1.5 1.5 0 013 0m6.75 0H20.25" /></svg>
                    Filter
                    <?php if(request('price_min') || request('price_max') || request('rating') || request('trusted')): ?>
                    <span class="h-1.5 w-1.5 rounded-full bg-clay-500"></span>
                    <?php endif; ?>
                </summary>
                <div class="absolute right-0 z-20 mt-2 w-72 max-w-[calc(100vw-2rem)] rounded-lg bg-white shadow-card border border-ink-100 p-4 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-ink-600">Rentang Harga</label>
                        <div class="mt-1.5 flex items-center gap-2">
                            <input type="number" name="price_min" value="<?php echo e(request('price_min')); ?>" placeholder="Min" min="0"
                                class="w-full rounded-md border border-ink-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <span class="text-ink-300">–</span>
                            <input type="number" name="price_max" value="<?php echo e(request('price_max')); ?>" placeholder="Max" min="0"
                                class="w-full rounded-md border border-ink-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink-600">Rating Minimal</label>
                        <select name="rating" class="mt-1.5 w-full rounded-md border border-ink-200 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-clay-400">
                            <option value="">Semua Rating</option>
                            <option value="4" <?php echo e(request('rating')=='4'?'selected':''); ?>>4★ ke atas</option>
                            <option value="3" <?php echo e(request('rating')=='3'?'selected':''); ?>>3★ ke atas</option>
                            <option value="2" <?php echo e(request('rating')=='2'?'selected':''); ?>>2★ ke atas</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="trusted" value="1" <?php echo e(request('trusted') ? 'checked' : ''); ?>

                            class="h-4 w-4 rounded border-ink-300 text-clay-600 focus:ring-clay-400">
                        <span class="text-sm text-ink-700">🛡️ Hanya dari Toko Terpercaya / Toko Pilihan</span>
                    </label>
                    <div class="flex items-center gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white text-sm font-semibold py-2 transition">Terapkan</button>
                        <?php if(request('price_min') || request('price_max') || request('rating') || request('trusted')): ?>
                        <a href="<?php echo e(route('home', array_filter(['category' => request('category'), 'q' => request('q')]))); ?>" class="rounded-md border border-ink-200 text-ink-500 text-sm font-semibold px-4 py-2 hover:bg-ink-50 transition-colors">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
            </details>

            <label class="text-xs text-ink-400 font-medium">Urutkan:</label>
            <select name="sort" onchange="this.form.submit()" class="text-sm rounded-md border border-ink-200 pl-3 pr-8 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-clay-400">
                <option value="terbaru" <?php echo e($sort=='terbaru'?'selected':''); ?>>Terbaru</option>
                <option value="termurah" <?php echo e($sort=='termurah'?'selected':''); ?>>Harga Terendah</option>
                <option value="termahal" <?php echo e($sort=='termahal'?'selected':''); ?>>Harga Tertinggi</option>
                <option value="terlaris" <?php echo e($sort=='terlaris'?'selected':''); ?>>Terlaris</option>
                <option value="rating" <?php echo e($sort=='rating'?'selected':''); ?>>Rating Tertinggi</option>
            </select>
        </form>
    </div>

    <?php if($products->isEmpty()): ?>
        <div class="text-center py-20 rounded-lg bg-white border border-dashed border-ink-200">
            <p class="text-3xl mb-3">🔍</p>
            <p class="text-ink-400 font-medium">Belum ada produk yang cocok. Coba kata kunci lain ya.</p>
        </div>
    <?php else: ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5 sm:gap-3">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="market-card group relative p-2 transition-all duration-200">
            <a href="<?php echo e(route('products.show', $product)); ?>" class="block">
                <div class="rounded-md overflow-hidden bg-ink-50 aspect-square relative">
                    <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-300" loading="lazy">
                    <?php if($product->isOutOfStock()): ?>
                    <span class="absolute inset-0 bg-white/70 flex items-center justify-center text-ink-600 text-xs font-bold backdrop-blur-[1px]">Stok Habis</span>
                    <?php elseif($product->isLowStock()): ?>
                    <span class="absolute top-1.5 left-1.5 bg-white text-ink-700 text-[10px] font-bold px-1.5 py-0.5 rounded-sm shadow-sm">Sisa <?php echo e($product->stock); ?></span>
                    <?php endif; ?>
                    <?php if($product->isOnDiscount()): ?>
                    <span class="absolute top-1.5 <?php echo e($product->isLowStock() ? 'left-1.5 mt-6' : 'left-1.5'); ?> badge-discount text-[10px] px-1.5 py-0.5 rounded-sm shadow-sm">-<?php echo e($product->discount_percent); ?>%</span>
                    <?php endif; ?>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role === 'buyer'): ?>
                        <button type="button"
                            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this, <?php echo e($product->id); ?>);"
                            data-wishlisted="<?php echo e(in_array($product->id, $wishlistIds) ? '1' : '0'); ?>"
                            class="wishlist-btn absolute top-1.5 right-1.5 h-7 w-7 rounded-full bg-white shadow-sm flex items-center justify-center transition-transform active:scale-90 <?php echo e(in_array($product->id, $wishlistIds) ? 'text-rose-500' : 'text-ink-400 hover:text-rose-500'); ?>"
                            title="Simpan ke wishlist">
                            <svg class="h-4 w-4" fill="<?php echo e(in_array($product->id, $wishlistIds) ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                        </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role === 'buyer' && $product->stock > 0): ?>
                        <span
                            onclick="event.preventDefault(); event.stopPropagation(); this.closest('div.relative').querySelector('form.quick-add').requestSubmit();"
                            class="btn-tactile absolute bottom-1.5 right-1.5 h-8 w-8 rounded-md bg-clay-600 text-white flex items-center justify-center opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 sm:transition-all duration-200 hover:bg-clay-700 cursor-pointer"
                            title="Tambah ke keranjang">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="mt-2 px-0.5 pb-0.5">
                    <p class="text-[13px] font-normal text-ink-800 leading-snug line-clamp-2"><?php echo e($product->name); ?></p>
                    <p class="mt-1.5"><?php echo $__env->make('partials.product-price', ['product' => $product, 'size' => 'sm'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></p>
                    <div class="mt-1"><?php echo $__env->make('partials.rating-stars', ['rating' => $product->rating_avg, 'count' => $product->rating_count], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                    <div class="mt-1 flex items-center justify-between text-xs text-ink-400 gap-1">
                        <span class="truncate flex items-center gap-1">
                            <?php echo e($product->seller->store_name ?? $product->seller->name); ?>

                            <?php if(in_array($product->seller_id, $trustedSellerIds)): ?>
                            <span title="Toko Terpercaya / Toko Pilihan">🛡️</span>
                            <?php endif; ?>
                        </span>
                        <span class="shrink-0"><?php echo e($product->sold_count); ?> terjual</span>
                    </div>
                </div>
            </a>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->role === 'buyer' && $product->stock > 0): ?>
                <form class="quick-add hidden" action="<?php echo e(route('cart.store', $product)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="quantity" value="1">
                </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-10"><?php echo e($products->links()); ?></div>
    <?php endif; ?>
</section>

<section id="recently-viewed-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 hidden">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-2xl font-bold text-ink-900">Baru Saja Dilihat</h2>
        <button onclick="clearRecentlyViewed()" class="text-xs font-semibold text-ink-400 hover:text-rose-500">Hapus riwayat</button>
    </div>
    <div id="recently-viewed-grid" class="flex gap-4 overflow-x-auto pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"></div>
</section>

<script>
(function () {
    try {
        const list = JSON.parse(localStorage.getItem('pasarku_recently_viewed') || '[]');
        if (!list.length) return;
        const section = document.getElementById('recently-viewed-section');
        const grid = document.getElementById('recently-viewed-grid');
        grid.innerHTML = list.map(p => `
            <a href="${p.url}" class="group block shrink-0 w-32 sm:w-40">
                <div class="rounded-md overflow-hidden bg-ink-50 aspect-square border border-ink-100 group-hover:border-clay-300 transition-colors">
                    <img src="${p.image}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="${p.name}" loading="lazy">
                </div>
                <p class="mt-2 text-xs font-semibold text-ink-800 leading-snug line-clamp-2 group-hover:text-clay-600 transition-colors">${p.name}</p>
                <p class="mt-1 font-serif text-sm font-bold text-ink-900">${p.price}</p>
            </a>
        `).join('');
        section.classList.remove('hidden');
    } catch (e) {}
})();
function clearRecentlyViewed() {
    localStorage.removeItem('pasarku_recently_viewed');
    document.getElementById('recently-viewed-section').classList.add('hidden');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/home.blade.php ENDPATH**/ ?>