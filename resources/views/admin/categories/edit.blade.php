@extends('layouts.app')
@section('title', 'Ubah Kategori')
@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali</a>
    <h1 class="font-serif text-2xl font-bold text-ink-900 mb-8">Ubah Kategori</h1>

    @if($errors->any())
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="rounded-lg border border-ink-100 bg-white shadow-card p-6 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Ikon (emoji, opsional)</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>
</section>
@endsection
