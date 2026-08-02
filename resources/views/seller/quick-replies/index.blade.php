@extends('layouts.app')
@section('title', 'Balasan Cepat')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Kelola Toko</h1>
    @include('partials.seller-nav')

    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <p class="text-sm text-ink-500 max-w-md">Buat template balasan cepat untuk pertanyaan yang sering ditanyakan pembeli, seperti stok, ongkir, atau cara pembayaran. Template ini bisa dipakai langsung saat membalas chat.</p>
        <a href="{{ route('seller.quick-replies.create') }}" class="btn-tactile shrink-0 rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Buat Template</a>
    </div>

    @if($quickReplies->isEmpty())
    <div class="text-center py-20 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium mb-4">Kamu belum punya template balasan cepat.</p>
        <a href="{{ route('seller.quick-replies.create') }}" class="btn-tactile inline-block rounded-md bg-clay-600 text-white font-semibold px-6 py-3 text-sm hover:bg-clay-700 hover:brightness-110 transition">Buat Template Pertama</a>
    </div>
    @else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($quickReplies as $qr)
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <p class="text-sm font-semibold text-ink-800">{{ $qr->title }}</p>
            <p class="mt-2 text-xs text-ink-500 leading-relaxed line-clamp-4">{{ $qr->body }}</p>
            <div class="mt-4 flex items-center gap-4 pt-3 border-t border-ink-100">
                <a href="{{ route('seller.quick-replies.edit', $qr) }}" class="text-xs font-semibold text-forest-600 hover:underline">Ubah</a>
                <form action="{{ route('seller.quick-replies.destroy', $qr) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                    @csrf @method('DELETE')
                    <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endsection
