@extends('layouts.app')
@section('title', 'Ubah Banner')
@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('admin.banners.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-ink-900 mb-8">Ubah Banner</h1>

    @if($errors->any())
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-ink-100 bg-white shadow-card p-6 space-y-5">
        @csrf @method('PUT')
        <div class="flex items-center gap-4">
            <img src="{{ $banner->image_url }}" class="h-16 w-28 rounded-md object-cover border border-ink-100 shrink-0" alt="{{ $banner->title }}">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Ganti Gambar (opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:gradient-brand file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5 file:shadow-glow">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Judul</label>
            <input type="text" name="title" value="{{ old('title', $banner->title) }}" required maxlength="150" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Subjudul (opsional)</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" maxlength="255" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Tautan (opsional)</label>
                <input type="text" name="link_url" value="{{ old('link_url', $banner->link_url) }}" placeholder="/?category=fashion" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Label Tombol</label>
                <input type="text" name="button_label" value="{{ old('button_label', $banner->button_label) }}" placeholder="Lihat Promo" maxlength="50" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Urutan Tampil</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Tampilkan banner ini di beranda
        </label>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>
@endsection
