<?php $__env->startSection('title', 'Pesan'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Pesan</h1>
        <p class="text-sm text-ink-400 mt-1">Percakapan kamu dengan <?php echo e(auth()->user()->role === 'seller' ? 'pembeli' : 'penjual'); ?>.</p>
    </div>

    <?php if($conversations->isEmpty()): ?>
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-3xl mb-3">💬</p>
        <p class="text-ink-400 font-medium">Belum ada percakapan.</p>
        <?php if(auth()->user()->role === 'buyer'): ?>
        <p class="text-xs text-ink-400 mt-1">Kunjungi halaman toko untuk mulai chat dengan penjual.</p>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="space-y-2">
        <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $other = $conversation->otherParticipant(auth()->user());
            $unread = $conversation->unreadCountFor(auth()->user());
            $last = $conversation->latestMessage;
        ?>
        <a href="<?php echo e(route('chat.show', $conversation)); ?>" class="flex items-center gap-3 rounded-lg border p-4 transition-colors <?php echo e($unread ? 'border-clay-200 bg-clay-50/60' : 'border-ink-100 bg-white'); ?> hover:border-clay-300 hover:shadow-soft">
            <span class="relative shrink-0">
                <span class="h-11 w-11 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-sm font-bold flex items-center justify-center">
                    <?php echo e(strtoupper(substr($other->store_name ?? $other->name, 0, 1))); ?>

                </span>
                <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full ring-2 ring-white <?php echo e($other->isOnline() ? 'bg-forest-500' : 'bg-ink-300'); ?>"></span>
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-ink-800 truncate"><?php echo e($other->store_name ?? $other->name); ?></p>
                    <?php if($last): ?>
                    <span class="text-[11px] text-ink-400 shrink-0"><?php echo e($last->created_at->diffForHumans(null, true)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center justify-between gap-2 mt-0.5">
                    <p class="text-xs text-ink-500 truncate"><?php echo e($last ? ($last->sender_id === auth()->id() ? 'Kamu: ' : '').$last->body : 'Belum ada pesan'); ?></p>
                    <?php if($unread > 0): ?>
                    <span class="h-5 min-w-5 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center shrink-0"><?php echo e($unread > 9 ? '9+' : $unread); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($conversations->links()); ?></div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/chat/index.blade.php ENDPATH**/ ?>