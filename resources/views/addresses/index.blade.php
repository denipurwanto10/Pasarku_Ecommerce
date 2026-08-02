@extends('layouts.app')
@section('title', 'Alamat Saya')
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Alamat Saya</h1>
        <a href="{{ route('addresses.create') }}" class="btn-tactile rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700 hover:brightness-110 transition">+ Tambah Alamat</a>
    </div>

    @if($addresses->isEmpty())
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium mb-4">Kamu belum menyimpan alamat apapun.</p>
        <a href="{{ route('addresses.create') }}" class="btn-tactile inline-block rounded-md bg-clay-600 text-white font-semibold px-6 py-3 text-sm hover:bg-clay-700 hover:brightness-110 transition">Tambah Alamat</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($addresses as $address)
        <div class="rounded-lg border border-ink-100 bg-white shadow-soft p-5">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-ink-800">{{ $address->label }}</p>
                        @if($address->is_default)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-forest-100 text-forest-700">Utama</span>
                        @endif
                    </div>
                    <p class="text-sm text-ink-600 mt-1">{{ $address->recipient_name }} · {{ $address->phone }}</p>
                    <p class="text-sm text-ink-500 mt-0.5">{{ $address->detail }}, {{ $address->city }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @if(!$address->is_default)
                    <form action="{{ route('addresses.setDefault', $address) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="text-xs font-semibold text-forest-600 hover:underline">Jadikan Utama</button>
                    </form>
                    @endif
                    <a href="{{ route('addresses.edit', $address) }}" class="text-xs font-semibold text-ink-500 hover:underline">Ubah</a>
                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs font-semibold text-rose-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endsection
