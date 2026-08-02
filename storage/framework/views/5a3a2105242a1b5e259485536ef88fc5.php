<?php $__env->startSection('title', 'Checkout'); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-3xl font-bold text-ink-900 mb-8">Checkout</h1>

    <div class="grid lg:grid-cols-3 gap-8">
        <form id="checkout-form" action="<?php echo e(route('checkout.store')); ?>" method="POST" class="lg:col-span-2 space-y-6">
            <?php echo csrf_field(); ?>
            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
                <h3 class="font-serif text-lg font-bold text-ink-900 mb-4 flex items-center gap-2"><span class="h-6 w-6 rounded-full bg-clay-600 text-white text-xs font-bold flex items-center justify-center">1</span>Alamat Pengiriman</h3>

                <?php if($addresses->isNotEmpty()): ?>
                <div class="space-y-2 mb-4">
                    <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-start gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="address_id" value="<?php echo e($address->id); ?>" data-city="<?php echo e($address->city); ?>" class="mt-1 address-radio text-clay-600 focus:ring-clay-400" <?php echo e($loop->first ? 'checked' : ''); ?> onchange="toggleManualAddress(false); recalcShipping('<?php echo e($address->city); ?>')">
                        <span class="min-w-0">
                            <span class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-ink-800"><?php echo e($address->label); ?></span>
                                <?php if($address->is_default): ?><span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-forest-100 text-forest-700">Utama</span><?php endif; ?>
                            </span>
                            <span class="block text-xs text-ink-500 mt-0.5"><?php echo e($address->recipient_name); ?> · <?php echo e($address->phone); ?></span>
                            <span class="block text-xs text-ink-400"><?php echo e($address->detail); ?>, <?php echo e($address->city); ?></span>
                        </span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-3 rounded-md border border-dashed border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50">
                        <input type="radio" name="address_id" value="" class="address-radio text-clay-600 focus:ring-clay-400" onchange="toggleManualAddress(true)">
                        <span class="text-sm font-medium text-ink-700">Gunakan alamat lain untuk pesanan ini</span>
                    </label>
                </div>
                <?php endif; ?>

                <div id="manual-address" class="space-y-4 <?php echo e($addresses->isNotEmpty() ? 'hidden' : ''); ?>">
                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1.5">Alamat Lengkap</label>
                        <textarea name="shipping_address" rows="3" <?php echo e($addresses->isEmpty() ? 'required' : ''); ?>

                            class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400"><?php echo e(old('shipping_address', auth()->user()->address)); ?></textarea>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. HP Penerima</label>
                            <input type="text" name="shipping_phone" value="<?php echo e(old('shipping_phone', auth()->user()->phone)); ?>" <?php echo e($addresses->isEmpty() ? 'required' : ''); ?>

                                class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kota</label>
                            <input type="text" name="shipping_city" id="manual-city" value="<?php echo e(old('shipping_city', $selectedCity)); ?>" <?php echo e($addresses->isEmpty() ? 'required' : 'disabled'); ?>

                                oninput="recalcShipping(this.value)"
                                class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                        </div>
                    </div>
                </div>
                <?php if($addresses->isNotEmpty()): ?>
                <input type="hidden" name="shipping_city" id="hidden-city" value="<?php echo e($selectedCity); ?>">
                <?php endif; ?>
            </div>

            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
                <h3 class="font-serif text-lg font-bold text-ink-900 mb-4 flex items-center gap-2"><span class="h-6 w-6 rounded-full bg-clay-600 text-white text-xs font-bold flex items-center justify-center">2</span>Kurir Pengiriman</h3>
                <div class="grid sm:grid-cols-2 gap-2">
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="courier" value="jne_reg" checked required class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">JNE Reguler</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="courier" value="jnt_express" class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">J&amp;T Express</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="courier" value="sicepat" class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">SiCepat</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="courier" value="grab_instant" class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">GrabExpress Instant</span>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
                <h3 class="font-serif text-lg font-bold text-ink-900 mb-4 flex items-center gap-2"><span class="h-6 w-6 rounded-full bg-clay-600 text-white text-xs font-bold flex items-center justify-center">3</span>Metode Pembayaran</h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="payment_method" value="transfer_bank" checked class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">Transfer Bank</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="payment_method" value="e_wallet" class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">E-Wallet (OVO / GoPay / Dana)</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="payment_method" value="cod" class="text-clay-600 focus:ring-clay-400">
                        <span class="text-sm font-medium">Bayar di Tempat (COD)</span>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
                <label class="block text-sm font-semibold text-ink-700 mb-1.5 flex items-center gap-2"><span class="h-6 w-6 rounded-full bg-clay-600 text-white text-xs font-bold flex items-center justify-center">4</span>Catatan (opsional)</label>
                <textarea name="notes" rows="2" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400" placeholder="Contoh: titip di security jika tidak ada orang"><?php echo e(old('notes')); ?></textarea>
            </div>
        </form>

        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6 h-fit lg:sticky lg:top-24">
            <h3 class="font-serif text-lg font-bold text-ink-900 mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto mb-4 pr-1">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between text-sm gap-2">
                    <span class="text-ink-500 line-clamp-1"><?php echo e($item->quantity); ?>x <?php echo e($item->product->name); ?></span>
                    <span class="text-ink-800 font-medium shrink-0">Rp<?php echo e(number_format($item->quantity * $item->product->final_price, 0, ',', '.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="border-t border-ink-100 my-4"></div>

            <?php if($coupon): ?>
            <div class="mb-4 rounded-md bg-forest-50 border border-forest-100 px-3 py-2.5 flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-bold text-forest-700 truncate">🎟️ <?php echo e($coupon->code); ?> terpakai</p>
                    <p class="text-[11px] text-forest-600">Hemat Rp<?php echo e(number_format($discount, 0, ',', '.')); ?></p>
                </div>
                <form action="<?php echo e(route('checkout.coupon.remove')); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-600 shrink-0">Batalkan</button>
                </form>
            </div>
            <?php else: ?>
            <form action="<?php echo e(route('checkout.coupon.apply')); ?>" method="POST" class="mb-4 flex items-center gap-2">
                <?php echo csrf_field(); ?>
                <input type="text" name="code" placeholder="Kode kupon" class="w-full rounded-md border border-ink-200 px-3 py-2.5 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-clay-400">
                <button type="submit" class="shrink-0 rounded-md border-2 border-ink-900 px-4 py-2.5 text-xs font-bold hover:bg-ink-900 hover:text-white transition-colors">Pakai</button>
            </form>
            <?php endif; ?>

            <div class="space-y-1.5 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-ink-500">Subtotal</span>
                    <span class="text-ink-700 font-medium">Rp<?php echo e(number_format($total, 0, ',', '.')); ?></span>
                </div>
                <?php if($discount > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-ink-500">Diskon Kupon</span>
                    <span class="text-forest-600 font-medium">-Rp<?php echo e(number_format($discount, 0, ',', '.')); ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between text-sm">
                    <span class="text-ink-500">Ongkos Kirim</span>
                    <span id="shipping-amount" class="text-ink-700 font-medium" data-value="<?php echo e($shipping['total']); ?>">Rp<?php echo e(number_format($shipping['total'], 0, ',', '.')); ?></span>
                </div>
            </div>

            <div class="border-t border-ink-100 my-4"></div>
            <div class="flex justify-between mb-6">
                <span class="font-semibold text-ink-800">Total Bayar</span>
                <span id="grand-total" class="text-xl font-bold price-tag" data-subtotal="<?php echo e($total - $discount); ?>">Rp<?php echo e(number_format($grandTotal, 0, ',', '.')); ?></span>
            </div>
            <button type="submit" form="checkout-form" class="btn-tactile w-full rounded-md bg-clay-600 text-white hover:bg-clay-700 text-white font-semibold py-3.5 text-sm transition">Buat Pesanan</button>
        </div>
    </div>
</section>

<script>
function toggleManualAddress(showManual) {
    const manual = document.getElementById('manual-address');
    const manualCity = document.getElementById('manual-city');
    const hiddenCity = document.getElementById('hidden-city');
    if (!manual) return;
    manual.classList.toggle('hidden', !showManual);
    if (manualCity) manualCity.disabled = !showManual;
    if (hiddenCity) hiddenCity.disabled = showManual;
    if (showManual && manualCity) recalcShipping(manualCity.value);
}

function recalcShipping(city) {
    const hiddenCity = document.getElementById('hidden-city');
    if (hiddenCity && !hiddenCity.disabled) hiddenCity.value = city;

    if (!city) return;

    fetch("<?php echo e(route('checkout.shipping.calculate')); ?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ city }),
    })
        .then(res => res.json())
        .then(data => {
            const shippingEl = document.getElementById('shipping-amount');
            const totalEl = document.getElementById('grand-total');
            if (!shippingEl || !totalEl) return;
            shippingEl.dataset.value = data.total;
            shippingEl.textContent = 'Rp' + Number(data.total).toLocaleString('id-ID');
            const subtotal = Number(totalEl.dataset.subtotal || 0);
            totalEl.textContent = 'Rp' + Number(subtotal + data.total).toLocaleString('id-ID');
        })
        .catch(() => {});
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/checkout/index.blade.php ENDPATH**/ ?>