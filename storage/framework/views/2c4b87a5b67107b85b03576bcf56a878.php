<?php $__env->startSection('title', 'Notifikasi'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Notifikasi</h1>
        <?php if($notifications->contains(fn($n) => is_null($n->read_at))): ?>
        <form action="<?php echo e(route('notifications.readAll')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="text-xs font-semibold text-clay-600 hover:underline">Tandai semua dibaca</button>
        </form>
        <?php endif; ?>
    </div>

    <?php if($notifications->isEmpty()): ?>
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium">Belum ada notifikasi.</p>
    </div>
    <?php else: ?>
    <div class="space-y-2">
        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <form action="<?php echo e(route('notifications.read', $notification->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full text-left rounded-lg border p-4 flex items-start gap-3 transition-colors <?php echo e($notification->read_at ? 'border-ink-100 bg-white' : 'border-clay-200 bg-clay-50/60'); ?> hover:border-clay-300">
                <?php if(!$notification->read_at): ?>
                <span class="mt-1.5 h-2 w-2 rounded-full bg-clay-500 shrink-0"></span>
                <?php else: ?>
                <span class="mt-1.5 h-2 w-2 rounded-full bg-transparent shrink-0"></span>
                <?php endif; ?>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink-800"><?php echo e($notification->data['title'] ?? 'Notifikasi'); ?></p>
                    <p class="text-sm text-ink-500 mt-0.5"><?php echo e($notification->data['message'] ?? ''); ?></p>
                    <p class="text-xs text-ink-400 mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                </div>
            </button>
        </form>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-8"><?php echo e($notifications->links()); ?></div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/notifications/index.blade.php ENDPATH**/ ?>