<?php $__env->startSection('title', 'Dashboard Toko'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-2">
        <p class="text-xs font-bold uppercase tracking-wide text-forest-600">Dashboard Toko</p>
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Halo, <?php echo e(auth()->user()->store_name ?? auth()->user()->name); ?> 👋</h1>
    </div>
    <?php echo $__env->make('partials.seller-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(auth()->user()->store_status !== 'approved'): ?>
    <div class="mb-8 rounded-lg border <?php echo e(auth()->user()->store_status === 'pending' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-rose-200 bg-rose-50 text-rose-700'); ?> px-4 py-3 text-sm font-medium">
        <?php if(auth()->user()->store_status === 'pending'): ?>
            ⏳ Toko kamu sedang menunggu persetujuan admin dan belum tampil ke publik. Kamu tetap bisa menyiapkan produk sekarang.
        <?php else: ?>
            🚫 Toko kamu dinonaktifkan oleh admin<?php echo e(auth()->user()->store_status_note ? ': '.auth()->user()->store_status_note : '.'); ?> Hubungi admin untuk informasi lebih lanjut.
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($lowStockCount > 0): ?>
    <div class="mb-8 rounded-lg border border-rose-200 bg-rose-50 p-4 sm:p-5">
        <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
            <div class="flex items-center gap-2">
                <span class="h-9 w-9 rounded-md bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </span>
                <div>
                    <p class="text-sm font-bold text-rose-700"><?php echo e($lowStockCount); ?> produk stoknya menipis atau habis</p>
                    <p class="text-xs text-rose-500">Segera lakukan restock supaya tidak kehilangan penjualan.</p>
                </div>
            </div>
            <a href="<?php echo e(route('seller.stock.index')); ?>" class="shrink-0 text-xs font-semibold rounded-full bg-rose-600 text-white px-4 py-2 hover:bg-rose-700 transition-colors">Kelola Stok</a>
        </div>
        <div class="flex gap-3 overflow-x-auto pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <?php $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('seller.products.edit', $p)); ?>" class="shrink-0 w-40 rounded-md bg-white border border-rose-100 p-2.5 hover:border-rose-300 transition-colors">
                <img src="<?php echo e($p->image_url); ?>" class="h-20 w-full rounded-lg object-cover" alt="<?php echo e($p->name); ?>">
                <p class="mt-2 text-xs font-semibold text-ink-800 line-clamp-1"><?php echo e($p->name); ?></p>
                <p class="text-[11px] font-bold <?php echo e($p->isOutOfStock() ? 'text-ink-500' : 'text-rose-600'); ?>"><?php echo e($p->stockBadgeLabel()); ?> · <?php echo e($p->stock); ?> pcs</p>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-10">
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card hover:-translate-y-0.5 transition-all">
            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-clay-100 to-clay-200 text-clay-700 flex items-center justify-center mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182.553-.44 1.278-.659 2.003-.659.725 0 1.45.22 2.003.659l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-ink-400 uppercase mb-1.5">Total Pendapatan</p>
            <p class="font-serif text-xl sm:text-2xl font-bold text-ink-900">Rp<?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card hover:-translate-y-0.5 transition-all">
            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-forest-100 to-forest-200 text-forest-700 flex items-center justify-center mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.994-4.694 2.615-7.152.083-.329-.153-.648-.494-.648H5.106M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-ink-400 uppercase mb-1.5">Produk Terjual</p>
            <p class="font-serif text-xl sm:text-2xl font-bold text-ink-900"><?php echo e($totalSold); ?></p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card hover:-translate-y-0.5 transition-all">
            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-amber-100 to-amber-200 text-amber-700 flex items-center justify-center mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg>
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-ink-400 uppercase mb-1.5">Pesanan Menunggu</p>
            <p class="font-serif text-xl sm:text-2xl font-bold text-clay-600"><?php echo e($pendingOrders); ?></p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card hover:-translate-y-0.5 transition-all">
            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-ink-100 to-ink-200 text-ink-700 flex items-center justify-center mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6.25 3.75h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-.75c0-.621-.504-1.125-1.125-1.125H3.375C2.754 4.5 2.25 5.004 2.25 5.625v.75c0 .621.504 1.125 1.125 1.125z" /></svg>
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-ink-400 uppercase mb-1.5">Produk Aktif</p>
            <p class="font-serif text-xl sm:text-2xl font-bold text-ink-900"><?php echo e($activeProductCount); ?> / <?php echo e($productCount); ?></p>
        </div>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card hover:-translate-y-0.5 transition-all">
            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-rose-100 to-rose-200 text-rose-600 flex items-center justify-center mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-ink-400 uppercase mb-1.5">Follower Toko</p>
            <p class="font-serif text-xl sm:text-2xl font-bold text-ink-900"><?php echo e($followerCount); ?></p>
        </div>
        <a href="<?php echo e(route('chat.index')); ?>" class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card hover:-translate-y-0.5 transition-all block">
            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-clay-100 to-clay-200 text-clay-700 flex items-center justify-center mb-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-ink-400 uppercase mb-1.5">Pesan Belum Dibaca</p>
            <p class="font-serif text-xl sm:text-2xl font-bold text-clay-600"><?php echo e($unreadChatCount); ?></p>
        </a>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-6 mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-serif text-lg font-bold text-ink-900">Tren Pendapatan (7 Hari Terakhir)</h2>
        </div>
        <div class="h-48 sm:h-56">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-serif text-lg sm:text-xl font-bold text-ink-900">Pesanan Terbaru</h2>
                <a href="<?php echo e(route('seller.orders.index')); ?>" class="text-sm font-semibold text-clay-600 hover:underline">Lihat semua</a>
            </div>
            <?php if($recentItems->isEmpty()): ?>
            <div class="rounded-lg bg-white border border-dashed border-ink-200 shadow-soft p-10 text-center text-sm text-ink-400">Belum ada pesanan masuk.</div>
            <?php else: ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $recentItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between rounded-lg border border-ink-100 bg-white shadow-soft p-4 gap-3 hover:border-clay-200 hover:shadow-card transition-all">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink-800 line-clamp-1"><?php echo e($item->product_name); ?></p>
                        <p class="text-xs text-ink-400 mt-0.5"><?php echo e($item->quantity); ?> pcs · dari <?php echo e($item->order->buyer->name); ?></p>
                    </div>
                    <span class="shrink-0 text-xs font-bold px-2.5 py-1 rounded-full bg-<?php echo e($item->order->statusColor()); ?>-50 text-<?php echo e($item->order->statusColor()); ?>-700"><?php echo e($item->order->statusLabel()); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>

        <div>
            <h2 class="font-serif text-lg sm:text-xl font-bold text-ink-900 mb-4">Produk Terlaris</h2>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center gap-3 rounded-lg border border-ink-100 bg-white shadow-soft p-3 hover:border-clay-200 hover:shadow-card transition-all">
                    <img src="<?php echo e($p->image_url); ?>" class="h-12 w-12 rounded-md object-cover shrink-0" alt="<?php echo e($p->name); ?>">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-ink-800 line-clamp-1"><?php echo e($p->name); ?></p>
                        <p class="text-xs text-ink-400"><?php echo e($p->sold_count); ?> terjual</p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="rounded-lg bg-white border border-dashed border-ink-200 shadow-soft p-6 text-center text-sm text-ink-400">Belum ada data.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($revenueTrend->pluck('label'), 15, 512) ?>,
        datasets: [{
            label: 'Pendapatan',
            data: <?php echo json_encode($revenueTrend->pluck('value'), 15, 512) ?>,
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.12)',
            tension: 0.35,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#7c3aed',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: (v) => 'Rp' + new Intl.NumberFormat('id-ID').format(v),
                    font: { size: 10 },
                },
                grid: { color: '#e2e8f0' },
            },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
        },
    },
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/seller/dashboard.blade.php ENDPATH**/ ?>