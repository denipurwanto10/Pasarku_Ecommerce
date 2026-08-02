@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<section class="min-h-[calc(100vh-4rem)] grid lg:grid-cols-2">
    <div class="hidden lg:flex relative overflow-hidden gradient-brand items-center justify-center p-14 texture-grain">
        <div class="absolute inset-0 texture-dots opacity-20"></div>
        <div class="absolute -bottom-20 -left-16 h-72 w-72 rounded-full bg-forest-400/25 blur-3xl"></div>
        <div class="relative max-w-sm">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-white/15 backdrop-blur text-white font-serif text-2xl font-bold ring-1 ring-white/30">P</span>
            <h2 class="mt-6 font-serif text-3xl font-bold text-white leading-tight">Buka tokomu, jangkau pembeli lokal hari ini juga.</h2>
            <p class="mt-4 text-white/75 text-sm leading-relaxed">Gratis mendaftar, tanpa biaya bulanan. Cukup unggah produk dan mulai jualan.</p>
            <ul class="mt-8 space-y-3 text-sm text-white/85">
                <li class="flex items-center gap-2"><span class="h-5 w-5 rounded-full bg-white/20 flex items-center justify-center text-xs">✓</span>Kelola pesanan langsung dari dashboard</li>
                <li class="flex items-center gap-2"><span class="h-5 w-5 rounded-full bg-white/20 flex items-center justify-center text-xs">✓</span>Pantau produk terlaris secara real-time</li>
                <li class="flex items-center gap-2"><span class="h-5 w-5 rounded-full bg-white/20 flex items-center justify-center text-xs">✓</span>Dukungan pembeli setia dari sekitarmu</li>
            </ul>
        </div>
    </div>

    <div class="flex items-center justify-center px-4 sm:px-8 py-14 sm:py-20 texture-dots lg:bg-transparent">
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-7">
                <span class="inline-flex lg:hidden h-12 w-12 items-center justify-center rounded-lg gradient-brand text-white font-serif text-2xl font-bold shadow-glow mb-3">P</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Buat Akun Baru</h1>
                <p class="text-sm text-ink-400 mt-1.5">Gratis, cukup beberapa langkah saja.</p>
            </div>

            @if($errors->any())
            <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="buyer" class="peer sr-only" {{ old('role','buyer')=='buyer'?'checked':'' }} onchange="document.getElementById('storeFields').classList.add('hidden')">
                        <div class="rounded-md border-2 border-ink-200 peer-checked:border-clay-500 peer-checked:bg-clay-50 peer-checked:shadow-soft px-4 py-3 text-center transition-all hover:border-clay-300">
                            <p class="text-xl mb-1">🛍️</p>
                            <p class="text-sm font-semibold">Pembeli</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="seller" class="peer sr-only" {{ old('role')=='seller'?'checked':'' }} onchange="document.getElementById('storeFields').classList.remove('hidden')">
                        <div class="rounded-md border-2 border-ink-200 peer-checked:border-forest-500 peer-checked:bg-forest-50 peer-checked:shadow-soft px-4 py-3 text-center transition-all hover:border-forest-300">
                            <p class="text-xl mb-1">🏪</p>
                            <p class="text-sm font-semibold">Penjual</p>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div id="storeFields" class="{{ old('role')=='seller' ? '' : 'hidden' }}">
                    <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Toko</label>
                    <input type="text" name="store_name" value="{{ old('store_name') }}"
                        class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" required
                            class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1.5">Ulangi Sandi</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400">
                    </div>
                </div>
                <button type="submit" class="btn-tactile w-full rounded-md bg-clay-600 text-white font-semibold py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Buat Akun</button>
            </form>

            <div class="mt-6 pt-6 border-t border-ink-100 text-center lg:text-left text-sm text-ink-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-clay-600 hover:underline">Masuk</a>
            </div>
        </div>
    </div>
</section>
@endsection
