<?php $__env->startSection('title', 'Ubah Produk'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="<?php echo e(route('seller.products.index')); ?>" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Ubah Produk</h1>

    <?php if($product->moderation_status === 'rejected'): ?>
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <p class="font-semibold">Produk ini disembunyikan oleh admin.</p>
        <?php if($product->moderation_note): ?><p class="mt-1">Alasan: <?php echo e($product->moderation_note); ?></p><?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('seller.products.update', $product)); ?>" method="POST" enctype="multipart/form-data" class="rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8 space-y-5">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="flex items-center gap-4">
            <img src="<?php echo e($product->image_url); ?>" class="h-20 w-20 rounded-md object-cover border border-ink-100 shadow-soft" alt="<?php echo e($product->name); ?>">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Ganti Foto Cover (opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:gradient-brand file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5 file:shadow-glow">
            </div>
        </div>

        <?php if($product->images->isNotEmpty()): ?>
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-semibold text-ink-700">Galeri Foto Saat Ini</label>
                <span id="reorder-status" class="text-xs text-ink-400"></span>
            </div>
            <div id="gallery-sortable" class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="gallery-item relative group cursor-grab active:cursor-grabbing" draggable="true" data-image-id="<?php echo e($img->id); ?>">
                    <img src="<?php echo e($img->url); ?>" class="w-full aspect-square object-cover rounded-md border border-ink-100 pointer-events-none group-has-[:checked]:opacity-40 group-has-[:checked]:ring-2 group-has-[:checked]:ring-rose-400 transition">
                    <span class="absolute top-1 left-1 h-5 w-5 rounded-full bg-white/90 border border-ink-200 flex items-center justify-center text-ink-400" title="Geser untuk urutkan">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
                    </span>
                    <label class="absolute inset-0 cursor-pointer">
                        <input type="checkbox" name="delete_images[]" value="<?php echo e($img->id); ?>" class="sr-only peer">
                        <span class="absolute top-1 right-1 h-5 w-5 rounded-full bg-white/90 border border-ink-200 flex items-center justify-center text-[10px] text-rose-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition">✕</span>
                    </label>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="mt-1.5 text-xs text-ink-400">Seret foto untuk mengubah urutan (di HP: tekan-tahan sebentar lalu geser), tersimpan otomatis. Klik tanda ✕ untuk menandai foto yang akan dihapus saat kamu menyimpan perubahan.</p>
        </div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Tambah Foto Galeri (opsional, maks 6)</label>
            <input type="file" name="images[]" accept="image/*" multiple class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-ink-800 file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Produk</label>
            <input type="text" name="name" value="<?php echo e(old('name', $product->name)); ?>" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kategori</label>
                <select name="category_id" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                    <option value="">Pilih kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $product->category_id)==$cat->id?'selected':''); ?>><?php echo e($cat->icon); ?> <?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Harga (Rp)</label>
                <input type="number" name="price" value="<?php echo e(old('price', $product->price)); ?>" min="0" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Stok</label>
            <input type="number" name="stock" value="<?php echo e(old('stock', $product->stock)); ?>" min="0" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            <p class="mt-1.5 text-xs text-ink-400">Perubahan stok di sini otomatis tercatat di <a href="<?php echo e(route('seller.stock.history', $product)); ?>" class="underline hover:text-clay-600">riwayat stok</a>.</p>
        </div>
        <div class="rounded-lg border border-dashed border-rose-200 bg-rose-50/40 p-4 space-y-4">
            <p class="text-sm font-semibold text-rose-700">Diskon Produk (opsional, harga coret — terpisah dari kupon)</p>
            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Harga Diskon (Rp)</label>
                    <input type="number" name="discount_price" value="<?php echo e(old('discount_price', $product->discount_price)); ?>" min="0" placeholder="Kosongkan jika tidak ada diskon"
                        class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Mulai</label>
                    <input type="datetime-local" name="discount_starts_at" value="<?php echo e(old('discount_starts_at', optional($product->discount_starts_at)->format('Y-m-d\TH:i'))); ?>" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5">Berakhir</label>
                    <input type="datetime-local" name="discount_ends_at" value="<?php echo e(old('discount_ends_at', optional($product->discount_ends_at)->format('Y-m-d\TH:i'))); ?>" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Kuota Flash Sale (opsional)</label>
                <input type="number" name="flash_sale_stock" value="<?php echo e(old('flash_sale_stock', $product->flash_sale_stock)); ?>" min="1" placeholder="Kosongkan jika tidak dibatasi kuota"
                    class="w-full sm:w-1/3 rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                <?php if($product->hasFlashSaleQuota()): ?>
                <p class="mt-1 text-xs text-ink-500">Sudah terjual <?php echo e($product->flash_sale_sold); ?> dari <?php echo e($product->flash_sale_stock); ?> kuota flash sale.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="rounded-lg border border-dashed border-ink-200 bg-ink-50/40 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-ink-700">Varian Produk (opsional — ukuran, warna, dll.)</p>
                <button type="button" onclick="addVariantRow()" class="text-xs font-semibold text-clay-600 hover:text-clay-700">+ Tambah Varian</button>
            </div>
            <p class="text-xs text-ink-400 -mt-1">Kosongkan/hapus semua baris jika produk tidak punya varian. Jika diisi, pembeli wajib memilih salah satu varian sebelum membeli.</p>
            <input type="hidden" name="variants_present" value="1">
            <div id="variant-rows" class="space-y-2">
                <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="variant-row grid grid-cols-12 gap-2 items-start">
                    <input type="hidden" name="variant_id[]" value="<?php echo e($variant->id); ?>">
                    <input type="text" name="variant_name[]" value="<?php echo e(old('variant_name.'.$loop->index, $variant->name)); ?>" placeholder="Nama varian, mis. Merah / L" class="col-span-5 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                    <input type="number" name="variant_price_adjustment[]" value="<?php echo e(old('variant_price_adjustment.'.$loop->index, $variant->price_adjustment)); ?>" placeholder="+/- harga" class="col-span-3 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                    <input type="number" name="variant_stock[]" value="<?php echo e(old('variant_stock.'.$loop->index, $variant->stock)); ?>" min="0" placeholder="Stok" class="col-span-2 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                    <input type="text" name="variant_sku[]" value="<?php echo e($variant->sku); ?>" class="hidden">
                    <button type="button" onclick="this.closest('.variant-row').remove()" class="col-span-2 h-full rounded-md border border-rose-200 text-rose-500 text-xs font-semibold py-2.5 hover:bg-rose-50">Hapus</button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <template id="variant-row-template">
            <div class="variant-row grid grid-cols-12 gap-2 items-start">
                <input type="text" name="variant_name[]" placeholder="Nama varian, mis. Merah / L" class="col-span-5 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                <input type="number" name="variant_price_adjustment[]" value="0" placeholder="+/- harga" class="col-span-3 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                <input type="number" name="variant_stock[]" value="0" min="0" placeholder="Stok" class="col-span-2 rounded-md border border-ink-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                <input type="text" name="variant_sku[]" class="hidden">
                <button type="button" onclick="this.closest('.variant-row').remove()" class="col-span-2 h-full rounded-md border border-rose-200 text-rose-500 text-xs font-semibold py-2.5 hover:bg-rose-50">Hapus</button>
            </div>
        </template>
        <script>
        function addVariantRow() {
            const tpl = document.getElementById('variant-row-template');
            const clone = tpl.content.cloneNode(true);
            document.getElementById('variant-rows').appendChild(clone);
        }
        </script>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors"><?php echo e(old('description', $product->description)); ?></textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" <?php echo e($product->is_active ? 'checked' : ''); ?> class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Tampilkan produk ini di toko
        </label>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>

<?php
    $reorderUrl = route('seller.products.images.reorder', $product);
?>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const grid = document.getElementById('gallery-sortable');
    if (!grid) return;

    const statusEl = document.getElementById('reorder-status');
    let dragEl = null;

    grid.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('dragstart', function (e) {
            dragEl = item;
            item.classList.add('opacity-40');
            e.dataTransfer.effectAllowed = 'move';
        });
        item.addEventListener('dragend', function () {
            item.classList.remove('opacity-40');
            dragEl = null;
            saveOrder();
        });
        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (!dragEl || dragEl === item) return;
            const items = [...grid.querySelectorAll('.gallery-item')];
            const dragIndex = items.indexOf(dragEl);
            const targetIndex = items.indexOf(item);
            if (dragIndex < targetIndex) {
                item.after(dragEl);
            } else {
                item.before(dragEl);
            }
        });
    });

    // Dukungan sentuh (HP/tablet): HTML5 drag & drop API di atas hanya jalan dengan mouse,
    // jadi di sini kita tangani drag pakai jari lewat Pointer Events, dengan tekan-tahan
    // singkat dulu supaya scroll halaman normal (tap-tap biasa) tidak keganggu.
    let touchDragEl = null;
    let touchHoldTimer = null;
    let touchStarted = false;

    grid.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('pointerdown', function (e) {
            if (e.pointerType !== 'touch') return;
            touchHoldTimer = setTimeout(() => {
                touchDragEl = item;
                touchStarted = true;
                item.classList.add('opacity-40', 'scale-105', 'shadow-card');
                item.style.touchAction = 'none';
                item.setPointerCapture(e.pointerId);
                if (navigator.vibrate) navigator.vibrate(15);
            }, 250);
        });

        item.addEventListener('pointermove', function (e) {
            if (e.pointerType !== 'touch' || !touchStarted || touchDragEl !== item) return;
            e.preventDefault();
            const target = document.elementFromPoint(e.clientX, e.clientY);
            const targetItem = target ? target.closest('.gallery-item') : null;
            if (!targetItem || targetItem === touchDragEl) return;
            const items = [...grid.querySelectorAll('.gallery-item')];
            const dragIndex = items.indexOf(touchDragEl);
            const targetIndex = items.indexOf(targetItem);
            if (dragIndex < targetIndex) {
                targetItem.after(touchDragEl);
            } else {
                targetItem.before(touchDragEl);
            }
        }, { passive: false });

        function endTouchDrag() {
            clearTimeout(touchHoldTimer);
            if (touchStarted && touchDragEl) {
                touchDragEl.classList.remove('opacity-40', 'scale-105', 'shadow-card');
                touchDragEl.style.touchAction = '';
                touchDragEl = null;
                touchStarted = false;
                saveOrder();
            }
        }
        item.addEventListener('pointerup', endTouchDrag);
        item.addEventListener('pointercancel', endTouchDrag);
    });

    function saveOrder() {
        const order = [...grid.querySelectorAll('.gallery-item')].map(el => el.dataset.imageId);
        if (statusEl) statusEl.textContent = 'Menyimpan urutan...';

        fetch(<?php echo json_encode($reorderUrl, 15, 512) ?>, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order: order }),
        })
        .then(res => res.json())
        .then(() => { if (statusEl) statusEl.textContent = 'Urutan tersimpan ✓'; setTimeout(() => statusEl && (statusEl.textContent = ''), 2000); })
        .catch(() => { if (statusEl) statusEl.textContent = 'Gagal menyimpan urutan.'; });
    }
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/seller/products/edit.blade.php ENDPATH**/ ?>