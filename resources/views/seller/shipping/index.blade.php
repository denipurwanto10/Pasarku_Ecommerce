@extends('layouts.app')
@section('title', 'Pengaturan Ongkir')
@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    @include('partials.seller-nav')

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6 mb-6">
        <h2 class="font-serif text-lg font-bold text-ink-900 mb-1">Mode Ongkir Toko</h2>
        <p class="text-sm text-ink-400 mb-5">Pilih apakah tokomu memakai tarif flat untuk semua kota, atau tarif berbeda per kota tujuan.</p>

        <form action="{{ route('seller.shipping.update') }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid sm:grid-cols-2 gap-3">
                <label class="flex items-start gap-2 rounded-md border p-4 cursor-pointer {{ $seller->shipping_type === 'flat' ? 'border-clay-400 bg-clay-50/50' : 'border-ink-200' }}">
                    <input type="radio" name="shipping_type" value="flat" {{ $seller->shipping_type === 'flat' ? 'checked' : '' }} class="mt-1 text-clay-600 focus:ring-clay-400">
                    <span>
                        <span class="block text-sm font-semibold text-ink-800">Tarif Flat</span>
                        <span class="block text-xs text-ink-400">Satu tarif ongkir untuk semua kota tujuan.</span>
                    </span>
                </label>
                <label class="flex items-start gap-2 rounded-md border p-4 cursor-pointer {{ $seller->shipping_type === 'per_city' ? 'border-clay-400 bg-clay-50/50' : 'border-ink-200' }}">
                    <input type="radio" name="shipping_type" value="per_city" {{ $seller->shipping_type === 'per_city' ? 'checked' : '' }} class="mt-1 text-clay-600 focus:ring-clay-400">
                    <span>
                        <span class="block text-sm font-semibold text-ink-800">Tarif per Kota</span>
                        <span class="block text-xs text-ink-400">Atur tarif berbeda untuk tiap kota di bawah.</span>
                    </span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Tarif Flat / Default (Rp)</label>
                <input type="number" name="shipping_flat_rate" value="{{ old('shipping_flat_rate', $seller->shipping_flat_rate) }}" min="0" required class="w-full sm:w-64 rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
                <p class="mt-1.5 text-xs text-ink-400">Dipakai untuk semua kota (mode flat), atau sebagai tarif fallback bila kota tujuan belum diatur (mode per kota).</p>
            </div>
            <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Pengaturan</button>
        </form>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
        <h2 class="font-serif text-lg font-bold text-ink-900 mb-1">Tarif per Kota</h2>
        <p class="text-sm text-ink-400 mb-5">Hanya berlaku bila mode ongkir toko diatur ke "Tarif per Kota".</p>

        <form action="{{ route('seller.shipping.city.store') }}" method="POST" class="flex flex-wrap items-end gap-3 mb-6">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Nama Kota</label>
                <input type="text" name="city" required placeholder="Contoh: Jakarta Selatan" class="rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink-700 mb-1.5">Tarif (Rp)</label>
                <input type="number" name="rate" min="0" required class="w-32 rounded-md border border-ink-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            </div>
            <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">Simpan Tarif</button>
        </form>

        @if($cityRates->isEmpty())
        <p class="text-sm text-ink-400">Belum ada tarif kota yang diatur.</p>
        @else
        <div class="divide-y divide-ink-100">
            @foreach($cityRates as $rate)
            <div class="flex items-center justify-between py-3">
                <p class="text-sm font-medium text-ink-800">{{ $rate->city }}</p>
                <div class="flex items-center gap-3">
                    <p class="text-sm font-semibold text-ink-700">Rp{{ number_format($rate->rate, 0, ',', '.') }}</p>
                    <form action="{{ route('seller.shipping.city.destroy', $rate) }}" method="POST" onsubmit="return confirm('Hapus tarif kota ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
