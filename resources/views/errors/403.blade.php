@extends('layouts.app')
@section('title', 'Akses Ditolak')
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center relative">
    <p class="font-serif text-7xl sm:text-8xl font-bold select-none bg-gradient-to-br from-clay-400 to-clay-700 bg-clip-text text-transparent">403</p>
    <h1 class="mt-4 font-serif text-2xl sm:text-3xl font-bold text-ink-900">Kamu tidak punya akses ke halaman ini</h1>
    <p class="mt-3 text-ink-500 max-w-md mx-auto">Coba masuk dengan akun yang sesuai, atau kembali ke beranda.</p>
    <a href="{{ route('home') }}" class="btn-tactile inline-block mt-8 rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Kembali ke Beranda</a>
</section>
@endsection
