@extends('layouts.app')
@section('title', $user->name)
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-ink-400 hover:text-clay-600 mb-4 inline-block">&larr; Kembali ke daftar pengguna</a>

    <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="font-serif text-2xl font-bold text-ink-900">{{ $user->name }}</h1>
                <p class="text-sm text-ink-400 mt-1">{{ $user->email }} · {{ $user->phone ?? '-' }}</p>
                <p class="text-xs font-semibold text-ink-500 capitalize mt-1">Peran: {{ $user->role }}</p>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $user->is_suspended ? 'bg-rose-100 text-rose-600' : 'bg-forest-100 text-forest-700' }}">
                {{ $user->is_suspended ? 'Nonaktif' : 'Aktif' }}
            </span>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-4">
            @if($user->role === 'seller')
            <div class="rounded-md bg-ink-50 p-3">
                <p class="text-xs text-ink-400">Jumlah Produk</p>
                <p class="font-serif text-lg font-bold text-ink-900">{{ $user->products_count }}</p>
            </div>
            @endif
            @if($user->role === 'buyer')
            <div class="rounded-md bg-ink-50 p-3">
                <p class="text-xs text-ink-400">Jumlah Pesanan</p>
                <p class="font-serif text-lg font-bold text-ink-900">{{ $user->orders_count }}</p>
            </div>
            @endif
        </div>

        @if($user->role !== 'admin')
        <div class="mt-6">
            @if($user->is_suspended)
            <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                @csrf @method('PATCH')
                <button class="text-sm font-semibold rounded-md bg-clay-600 text-white px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">Aktifkan Akun</button>
            </form>
            @else
            <form action="{{ route('admin.users.suspend', $user) }}" method="POST" onsubmit="return confirm('Nonaktifkan akun ini?')">
                @csrf @method('PATCH')
                <button class="text-sm font-semibold rounded-full border border-rose-300 text-rose-600 px-5 py-2.5 hover:bg-rose-50 transition">Nonaktifkan Akun</button>
            </form>
            @endif
        </div>
        @endif
    </div>
</section>
@endsection
