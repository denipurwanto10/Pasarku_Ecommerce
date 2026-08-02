@extends('layouts.app')
@section('title', 'Kelola Banner')
@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-serif text-xl font-bold text-ink-900">Banner Promo Beranda</h2>
            <p class="text-sm text-ink-400 mt-1">Banner statis yang tampil di carousel halaman utama. Urutan berdasarkan "Urutan" terkecil ke terbesar.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn-tactile shrink-0 rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Banner</a>
    </div>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft divide-y divide-ink-100">
        @forelse($banners as $banner)
        <div class="flex items-center gap-4 px-5 py-4">
            <img src="{{ $banner->image_url }}" class="h-16 w-28 rounded-md object-cover border border-ink-100 shrink-0" alt="{{ $banner->title }}">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-ink-800 truncate">{{ $banner->title }}</p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $banner->is_active ? 'bg-forest-50 text-forest-700' : 'bg-ink-100 text-ink-500' }}">{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                @if($banner->subtitle)<p class="text-xs text-ink-400 truncate mt-0.5">{{ $banner->subtitle }}</p>@endif
                <p class="text-xs text-ink-300 mt-0.5">Urutan {{ $banner->sort_order }}{{ $banner->link_url ? ' · Tautan: '.$banner->link_url : '' }}</p>
            </div>
            <a href="{{ route('admin.banners.edit', $banner) }}" class="text-xs font-semibold text-forest-600 hover:underline shrink-0">Ubah</a>
            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Hapus banner ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
            </form>
        </div>
        @empty
        <div class="text-center py-16">
            <p class="text-ink-400 font-medium">Belum ada banner. Tambahkan banner pertama untuk halaman utama.</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $banners->links() }}</div>
</section>
@endsection
