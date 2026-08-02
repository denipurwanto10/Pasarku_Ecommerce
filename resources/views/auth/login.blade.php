@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<section class="min-h-[calc(100vh-4rem)] grid lg:grid-cols-2">
    <div class="hidden lg:flex relative overflow-hidden gradient-brand items-center justify-center p-14 texture-grain">
        <div class="absolute inset-0 texture-dots opacity-30"></div>
        <div class="absolute -top-16 -right-16 h-72 w-72 rounded-full bg-forest-400/25 blur-3xl"></div>
        <div class="relative max-w-sm">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-white/15 backdrop-blur text-white font-serif text-2xl font-bold ring-1 ring-white/30">P</span>
            <h2 class="mt-6 font-serif text-3xl font-bold text-white leading-tight">Setiap belanja mendukung usaha kecil untuk terus tumbuh.</h2>
            <p class="mt-4 text-white/75 text-sm leading-relaxed">Ribuan penjual lokal sudah berjualan di Pasarku — dari kebutuhan harian sampai barang unik buatan tangan.</p>
            <div class="mt-8 flex items-center gap-8">
                <div><p class="font-serif text-2xl font-bold text-white">{{ \App\Models\Product::count() }}+</p><p class="text-xs text-white/60 mt-0.5">Produk</p></div>
                <div class="h-8 w-px bg-white/15"></div>
                <div><p class="font-serif text-2xl font-bold text-white">{{ \App\Models\User::where('role','seller')->count() }}+</p><p class="text-xs text-white/60 mt-0.5">Penjual</p></div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center px-4 sm:px-8 py-14 sm:py-20 texture-dots lg:bg-transparent">
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-8">
                <span class="inline-flex lg:hidden h-12 w-12 items-center justify-center rounded-lg gradient-brand text-white font-serif text-2xl font-bold shadow-glow mb-3">P</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Selamat Datang Kembali</h1>
                <p class="text-sm text-ink-400 mt-1.5">Masuk untuk melanjutkan belanja atau kelola tokomu.</p>
            </div>

            @if($errors->any())
            <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required
                        class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400 transition">
                </div>
                <label class="flex items-center gap-2 text-sm text-ink-500">
                    <input type="checkbox" name="remember" class="rounded border-ink-300 text-clay-600 focus:ring-clay-400"> Ingat saya
                </label>
                <button type="submit" class="btn-tactile w-full rounded-md bg-clay-600 text-white font-semibold py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Masuk</button>
            </form>

            <div class="mt-6 pt-6 border-t border-ink-100 text-center lg:text-left text-sm text-ink-500">
                Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-clay-600 hover:underline">Daftar sekarang</a>
            </div>

            <div class="mt-6 rounded-lg bg-ink-50 border border-ink-100 px-4 py-3 text-xs text-ink-500 space-y-1">
                <p class="font-semibold text-ink-600">Akun demo:</p>
                <p>Pembeli — buyer@pasarku.test / password</p>
                <p>Penjual — seller@pasarku.test / password</p>
            </div>
        </div>
    </div>
</section>
@endsection
