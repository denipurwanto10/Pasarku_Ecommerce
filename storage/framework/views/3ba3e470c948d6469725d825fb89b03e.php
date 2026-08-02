<div class="flex gap-1 overflow-x-auto mb-8 border-b border-ink-100 -mx-4 px-4 sm:mx-0 sm:px-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
    <a href="<?php echo e(route('seller.dashboard')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.dashboard') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Dashboard</a>
    <a href="<?php echo e(route('seller.products.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.products.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Produk Saya</a>
    <a href="<?php echo e(route('seller.orders.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.orders.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Pesanan Masuk</a>
    <a href="<?php echo e(route('seller.coupons.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.coupons.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Kupon</a>
    <a href="<?php echo e(route('seller.stock.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.stock.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Stok</a>
    <a href="<?php echo e(route('seller.quick-replies.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.quick-replies.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Balasan Cepat</a>
    <a href="<?php echo e(route('seller.shipping.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors <?php echo e(request()->routeIs('seller.shipping.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">Ongkir</a>
    <a href="<?php echo e(route('chat.index')); ?>" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px transition-colors flex items-center gap-1.5 <?php echo e(request()->routeIs('chat.*') ? 'border-clay-500 text-clay-600' : 'border-transparent text-ink-400 hover:text-ink-700'); ?>">
        Pesan
        <?php ($sellerUnread = auth()->user()->unreadMessagesCount()); ?>
        <?php if($sellerUnread > 0): ?>
        <span class="h-4 min-w-4 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center"><?php echo e($sellerUnread > 9 ? '9+' : $sellerUnread); ?></span>
        <?php endif; ?>
    </a>
    <?php if(auth()->user()->store_slug): ?>
    <a href="<?php echo e(route('store.show', auth()->user())); ?>" target="_blank" class="shrink-0 px-4 py-3 text-sm font-semibold border-b-2 -mb-px border-transparent text-ink-400 hover:text-ink-700 transition-colors flex items-center gap-1">
        Lihat Toko
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
    </a>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\ecommerce\resources\views/partials/seller-nav.blade.php ENDPATH**/ ?>