<?php $__env->startSection('title', 'Pesanan Masuk'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    <?php echo $__env->make('partials.seller-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form method="GET" class="flex gap-2 mb-6 overflow-x-auto pb-1 -mx-4 px-4 sm:mx-0 sm:px-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <a href="<?php echo e(route('seller.orders.index')); ?>" class="btn-tactile shrink-0 rounded-full px-4 py-2 text-sm font-semibold border transition-all <?php echo e(!request('status') ? 'gradient-brand text-white border-transparent shadow-glow' : 'border-ink-200 text-ink-600 bg-white hover:border-clay-300'); ?>">Semua</a>
        <?php $__currentLoopData = ['pending'=>'Menunggu','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('seller.orders.index', ['status'=>$key])); ?>" class="btn-tactile shrink-0 rounded-full px-4 py-2 text-sm font-semibold border transition-all <?php echo e(request('status')==$key ? 'gradient-brand text-white border-transparent shadow-glow' : 'border-ink-200 text-ink-600 bg-white hover:border-clay-300'); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </form>

    <?php if($items->isEmpty()): ?>
    <div class="text-center py-20 rounded-lg bg-white shadow-soft border border-dashed border-ink-200 text-ink-400">Belum ada pesanan untuk kategori ini.</div>
    <?php else: ?>
    <div class="space-y-4">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-4 sm:p-5 hover:border-clay-200 hover:shadow-card transition-all">
            <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
                <div>
                    <p class="text-xs text-ink-400 mb-1"><?php echo e($item->order->order_number); ?> · <?php echo e($item->order->created_at->translatedFormat('d M Y, H:i')); ?></p>
                    <p class="text-sm font-semibold text-ink-800"><?php echo e($item->product_name); ?></p>
                    <p class="text-xs text-ink-500 mt-0.5"><?php echo e($item->quantity); ?> x Rp<?php echo e(number_format($item->price,0,',','.')); ?> = <span class="font-semibold text-ink-800">Rp<?php echo e(number_format($item->subtotal,0,',','.')); ?></span></p>
                    <p class="text-xs text-ink-400 mt-2">Pembeli: <?php echo e($item->order->buyer->name); ?> · <?php echo e($item->order->shipping_phone); ?></p>
                    <p class="text-xs text-ink-400"><?php echo e(Str::limit($item->order->shipping_address, 60)); ?></p>
                </div>
                <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full bg-<?php echo e($item->order->statusColor()); ?>-50 text-<?php echo e($item->order->statusColor()); ?>-700 shrink-0"><?php echo e($item->order->statusLabel()); ?></span>
            </div>

            <form action="<?php echo e(route('seller.orders.status', $item->order)); ?>" method="POST" class="flex flex-wrap items-end gap-2 pt-3 border-t border-ink-100">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div>
                    <label class="block text-[11px] font-semibold text-ink-500 mb-1">Status</label>
                    <select name="status" class="text-xs font-semibold rounded-md border border-ink-200 pl-3 pr-7 py-2 bg-white shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400">
                        <?php $__currentLoopData = ['pending'=>'Menunggu','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($item->order->status==$key?'selected':''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-ink-500 mb-1">Kurir</label>
                    <select name="courier" class="text-xs font-semibold rounded-md border border-ink-200 pl-3 pr-7 py-2 bg-white shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400">
                        <option value="">- Pilih -</option>
                        <?php $__currentLoopData = ['jne_reg'=>'JNE Reguler','jnt_express'=>'J&T Express','sicepat'=>'SiCepat','grab_instant'=>'GrabExpress Instant']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($item->order->courier==$key?'selected':''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-[11px] font-semibold text-ink-500 mb-1">No. Resi</label>
                    <input type="text" name="tracking_number" value="<?php echo e($item->order->tracking_number); ?>" placeholder="Opsional"
                        class="w-full text-xs rounded-md border border-ink-200 px-3 py-2 shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400">
                </div>
                <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white text-xs font-bold px-4 py-2 transition">Simpan</button>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($items->links()); ?></div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/seller/orders/index.blade.php ENDPATH**/ ?>