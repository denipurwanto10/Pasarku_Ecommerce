<?php $__env->startSection('title', 'Detail Pesanan'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="<?php echo e(route('orders.index')); ?>" class="text-sm text-ink-400 hover:text-clay-600 mb-6 inline-flex items-center gap-1">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Kembali ke Pesanan Saya
    </a>

    <div class="rounded-3xl border border-ink-100 bg-white shadow-card p-5 sm:p-8">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="font-serif text-xl sm:text-2xl font-bold text-ink-900"><?php echo e($order->order_number); ?></h1>
                <p class="text-sm text-ink-400 mt-1"><?php echo e($order->created_at->translatedFormat('d F Y, H:i')); ?></p>
            </div>
            <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full bg-<?php echo e($order->statusColor()); ?>-50 text-<?php echo e($order->statusColor()); ?>-700"><?php echo e($order->statusLabel()); ?></span>
        </div>

        <?php
            $steps = ['pending' => 'Menunggu', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai'];
            $stepKeys = array_keys($steps);
            $currentIndex = array_search($order->status, $stepKeys);
        ?>
        <?php if($order->status !== 'cancelled'): ?>
        <div class="mb-8 rounded-2xl bg-ink-50 border border-ink-100 p-4 sm:p-5">
            <div class="flex items-start">
                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isDone = $currentIndex !== false && $loop->index <= $currentIndex; ?>
                <div class="flex-1 flex flex-col items-center relative">
                    <?php if(!$loop->first): ?>
                    <div class="absolute top-3 right-1/2 w-full h-0.5 <?php echo e($isDone ? 'bg-gradient-to-r from-forest-400 to-forest-600' : 'bg-ink-200'); ?>" style="left:-50%"></div>
                    <?php endif; ?>
                    <span class="relative z-10 h-6 w-6 rounded-full flex items-center justify-center text-[11px] font-bold <?php echo e($isDone ? 'bg-gradient-to-br from-forest-400 to-forest-600 text-white shadow-soft' : 'bg-white border-2 border-ink-200 text-ink-400'); ?>">
                        <?php if($isDone): ?>✓<?php else: ?><?php echo e($loop->iteration); ?><?php endif; ?>
                    </span>
                    <span class="mt-2 text-[11px] font-semibold text-center <?php echo e($isDone ? 'text-ink-800' : 'text-ink-400'); ?>"><?php echo e($label); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php else: ?>
        <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-100 p-4 text-sm text-rose-700 font-medium text-center">Pesanan ini telah dibatalkan.</div>
        <?php endif; ?>

        <div class="space-y-4 mb-6 divide-y divide-ink-100">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between items-center gap-3 text-sm <?php echo e(!$loop->first ? 'pt-4' : ''); ?>">
                <div class="min-w-0">
                    <p class="font-medium text-ink-800"><?php echo e($item->product_name); ?></p>
                    <p class="text-xs text-ink-400"><?php echo e($item->quantity); ?> x Rp<?php echo e(number_format($item->price, 0, ',', '.')); ?> · Toko <?php echo e($item->seller->store_name ?? $item->seller->name); ?></p>
                    <?php if($order->status === 'completed' && $item->product): ?>
                        <?php if(in_array($item->product_id, $reviewedProductIds)): ?>
                        <span class="inline-block mt-1.5 text-[11px] font-semibold text-forest-600">✓ Sudah diulas</span>
                        <?php else: ?>
                        <a href="<?php echo e(route('products.show', $item->product)); ?>#ulasan" class="inline-block mt-1.5 text-[11px] font-semibold text-clay-600 hover:text-clay-700 underline">Tulis Ulasan</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <p class="font-semibold text-ink-800 shrink-0">Rp<?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="border-t border-dashed border-ink-200 pt-4 space-y-1.5 mb-6">
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Subtotal</span>
                <span class="text-ink-700 font-medium">Rp<?php echo e(number_format($order->total_amount + $order->discount_amount, 0, ',', '.')); ?></span>
            </div>
            <?php if($order->discount_amount > 0): ?>
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Diskon Kupon <?php if($order->coupon_code): ?>(<?php echo e($order->coupon_code); ?>)<?php endif; ?></span>
                <span class="text-forest-600 font-medium">-Rp<?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            <?php if($order->shipping_amount > 0): ?>
            <div class="flex justify-between text-sm">
                <span class="text-ink-500">Ongkos Kirim</span>
                <span class="text-ink-700 font-medium">Rp<?php echo e(number_format($order->shipping_amount, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between pt-1.5">
                <span class="font-semibold text-ink-800">Total Bayar</span>
                <span class="font-serif text-xl font-bold bg-gradient-to-r from-clay-600 to-clay-800 bg-clip-text text-transparent">Rp<?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></span>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <div class="rounded-xl bg-ink-50 border border-ink-100 p-4">
                <p class="text-xs font-semibold text-ink-400 uppercase mb-1">Alamat Pengiriman</p>
                <p class="text-ink-700"><?php echo e($order->shipping_address); ?></p>
                <?php if($order->shipping_city): ?><p class="text-ink-500 text-xs mt-0.5">Kota: <?php echo e($order->shipping_city); ?></p><?php endif; ?>
                <p class="text-ink-500 mt-1"><?php echo e($order->shipping_phone); ?></p>
            </div>
            <div class="rounded-xl bg-ink-50 border border-ink-100 p-4">
                <p class="text-xs font-semibold text-ink-400 uppercase mb-1">Pembayaran</p>
                <p class="text-ink-700"><?php echo e($order->paymentMethodLabel()); ?></p>
                <?php if($order->notes): ?><p class="text-ink-500 mt-1">Catatan: <?php echo e($order->notes); ?></p><?php endif; ?>
            </div>
            <div class="rounded-xl bg-ink-50 border border-ink-100 p-4 sm:col-span-2">
                <p class="text-xs font-semibold text-ink-400 uppercase mb-1">Pengiriman</p>
                <?php if($order->courier): ?>
                <p class="text-ink-700"><?php echo e($order->courierLabel()); ?></p>
                <?php if($order->tracking_number): ?>
                <p class="text-ink-500 mt-1 flex items-center gap-2">No. Resi: <span class="font-mono font-semibold text-ink-800 tracking-wide"><?php echo e($order->tracking_number); ?></span></p>
                <?php else: ?>
                <p class="text-ink-400 mt-1 text-xs">Nomor resi akan muncul setelah pesanan dikirim.</p>
                <?php endif; ?>
                <?php else: ?>
                <p class="text-ink-400 text-xs">Kurir belum ditentukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/orders/show.blade.php ENDPATH**/ ?>