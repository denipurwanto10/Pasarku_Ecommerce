@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-3xl font-bold text-ink-900 mb-8">Checkout</h1>

    <div class="grid lg:grid-cols-3 gap-8">
        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="lg:col-span-2 space-y-6">
            @csrf
            <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
                <h3 class="font-serif text-lg font-bold text-ink-900 mb-4 flex items-center gap-2"><span class="h-6 w-6 rounded-full bg-clay-600 text-white text-xs font-bold flex items-center justify-center">1</span>Alamat Pengiriman</h3>

                @if($addresses->isNotEmpty())
                <div class="space-y-2 mb-4">
                    @foreach($addresses as $address)
                    <label class="flex items-start gap-3 rounded-md border border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50 has-[:checked]:shadow-soft">
                        <input type="radio" name="address_id" value="{{ $address->id }}" data-city="{{ $address->city }}" class="mt-1 address-radio text-clay-600 focus:ring-clay-400" {{ $loop->first ? 'checked' : '' }} onchange="toggleManualAddress(false); recalcShipping('{{ $address->city }}')">
                        <span class="min-w-0">
                            <span class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-ink-800">{{ $address->label }}</span>
                                @if($address->is_default)<span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-forest-100 text-forest-700">Utama</span>@endif
                            </span>
                            <span class="block text-xs text-ink-500 mt-0.5">{{ $address->recipient_name }} · {{ $address->phone }}</span>
                            <span class="block text-xs text-ink-400">{{ $address->detail }}, {{ $address->city }}</span>
                        </span>
                    </label>
                    @endforeach
                    <label class="flex items-center gap-3 rounded-md border border-dashed border-ink-200 px-4 py-3 cursor-pointer transition-colors hover:border-ink-300 has-[:checked]:border-clay-500 has-[:checked]:bg-clay-50">
                        <input type="radio" name="address_id" value="" class="address-radio text-clay-600 focus:ring-clay-400" onchange="toggleManualAddress(true)">
                        <span class="text-sm font-medium text-ink-700">Gunakan alamat lain untuk pesanan ini</span>
                    </label>
                </div>
                @endif

                <div id="manual-address" class="space-y-4 {{ $addresses->isNotEmpty() ? 'hidden' : '' }}">
                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1.5">Alamat Lengkap</label>
                        <textarea name="shipping_address" rows="3" {{ $addresses->isEmpty() ? 'required' : '' }}
                            class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. HP Penerima</label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" {{ $addresses->isEmpty() ? 'required' : '' }}
                                class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kota</label>
                            <input type="text" name="shipping_city" id="manual-city" value="{{ old('shipping_city', $selectedCity) }}" {{ $addresses->isEmpty() ? 'required' : 'disabled' }}
                                oninput="recalcShipping(this.value)"
                                class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
                        </div>
                    </div>
                </div>
                @if($addresses->isNotEmpty())
                <input type="hidden" name="shipping_city" id="hidden-city" value="{{ $selectedCity }}">
                @endif
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
                <textarea name="notes" rows="2" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400" placeholder="Contoh: titip di security jika tidak ada orang">{{ old('notes') }}</textarea>
            </div>
        </form>

        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6 h-fit lg:sticky lg:top-24">
            <h3 class="font-serif text-lg font-bold text-ink-900 mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto mb-4 pr-1">
                @foreach($items as $item)
                <div class="flex justify-between text-sm gap-2">
                    <span class="text-ink-500 line-clamp-1">{{ $item->quantity }}x {{ $item->product->name }}</span>
                    <span class="text-ink-800 font-medium shrink-0">Rp{{ number_format($item->quantity * $item->product->final_price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t border-ink-100 my-4"></div>

            @if($coupon)
            <div class="mb-4 rounded-md bg-forest-50 border border-forest-100 px-3 py-2.5 flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-bold text-forest-700 truncate">🎟️ {{ $coupon->code }} terpakai</p>
                    <p class="text-[11px] text-forest-600">Hemat Rp{{ number_format($discount, 0, ',', '.') }}</p>
                </div>
                <form action="{{ route('checkout.coupon.remove') }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-600 shrink-0">Batalkan</button>
                </form>
            </div>
            @else
            <form action="{{ route('checkout.coupon.apply') }}" method="POST" class="mb-4 flex items-center gap-2">
                @csrf
                <input type="text" name="code" placeholder="Kode kupon" class="w-full rounded-md border border-ink-200 px-3 py-2.5 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-clay-400">
                <button type="submit" class="shrink-0 rounded-md border-2 border-ink-900 px-4 py-2.5 text-xs font-bold hover:bg-ink-900 hover:text-white transition-colors">Pakai</button>
            </form>
            @endif

            <div class="space-y-1.5 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-ink-500">Subtotal</span>
                    <span class="text-ink-700 font-medium">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                @if($discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-ink-500">Diskon Kupon</span>
                    <span class="text-forest-600 font-medium">-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-ink-500">Ongkos Kirim</span>
                    <span id="shipping-amount" class="text-ink-700 font-medium" data-value="{{ $shipping['total'] }}">Rp{{ number_format($shipping['total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-ink-100 my-4"></div>
            <div class="flex justify-between mb-6">
                <span class="font-semibold text-ink-800">Total Bayar</span>
                <span id="grand-total" class="text-xl font-bold price-tag" data-subtotal="{{ $total - $discount }}">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
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

    fetch("{{ route('checkout.shipping.calculate') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
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
@endsection
