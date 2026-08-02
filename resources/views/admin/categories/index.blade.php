@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-xl font-bold text-ink-900">Kategori Produk</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn-tactile rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Kategori</a>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft divide-y divide-ink-100">
        @forelse($categories as $cat)
        <div class="flex items-center gap-3 px-5 py-4">
            <span class="text-xl">{{ $cat->icon ?? '🏷️' }}</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-ink-800">{{ $cat->name }}</p>
                <p class="text-xs text-ink-400">{{ $cat->products_count }} produk</p>
            </div>
            <a href="{{ route('admin.categories.edit', $cat) }}" class="text-xs font-semibold text-forest-600 hover:underline">Ubah</a>
            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                @csrf @method('DELETE')
                <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
            </form>
        </div>
        @empty
        <div class="text-center py-16">
            <p class="text-ink-400 font-medium">Belum ada kategori.</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $categories->links() }}</div>
</section>
@endsection
