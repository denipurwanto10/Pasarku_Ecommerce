@extends('layouts.app')
@section('title', 'Ubah Alamat')
@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('addresses.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-ink-900 mb-8">Ubah Alamat</h1>

    @if($errors->any())
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('addresses.update', $address) }}" method="POST" class="rounded-lg border border-ink-100 bg-white shadow-card p-6 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Label Alamat</label>
            <input type="text" name="label" value="{{ old('label', $address->label) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Penerima</label>
                <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $address->phone) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kota</label>
            <input type="text" name="city" value="{{ old('city', $address->city) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Alamat Lengkap</label>
            <textarea name="detail" rows="3" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">{{ old('detail', $address->detail) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }} class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Jadikan alamat utama
        </label>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>
@endsection
