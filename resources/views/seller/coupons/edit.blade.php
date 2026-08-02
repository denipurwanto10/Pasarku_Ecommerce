@extends('layouts.app')
@section('title', 'Ubah Kupon')
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('seller.coupons.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Ubah Kupon</h1>

    @if($errors->any())
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('seller.coupons.update', $coupon) }}" method="POST" class="rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kode Kupon</label>
            <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required maxlength="30"
                class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Jenis Diskon</label>
                <select name="type" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                    <option value="percent" {{ old('type', $coupon->type)=='percent'?'selected':'' }}>Persentase (%)</option>
                    <option value="fixed" {{ old('type', $coupon->type)=='fixed'?'selected':'' }}>Nominal Tetap (Rp)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nilai Diskon</label>
                <input type="number" name="value" value="{{ old('value', $coupon->value) }}" min="1" required
                    class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Maks. Diskon (Rp, opsional)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" min="0"
                    class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Min. Belanja (Rp, opsional)</label>
                <input type="number" name="min_purchase" value="{{ old('min_purchase', $coupon->min_purchase) }}" min="0"
                    class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Batas Pemakaian (opsional)</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                    class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Berlaku Sampai (opsional)</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                    class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Aktifkan kupon ini
        </label>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>
@endsection
