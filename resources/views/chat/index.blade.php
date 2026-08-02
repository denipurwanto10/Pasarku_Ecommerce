@extends('layouts.app')
@section('title', 'Pesan')
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Pesan</h1>
        <p class="text-sm text-ink-400 mt-1">Percakapan kamu dengan {{ auth()->user()->role === 'seller' ? 'pembeli' : 'penjual' }}.</p>
    </div>

    @if($conversations->isEmpty())
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-3xl mb-3">💬</p>
        <p class="text-ink-400 font-medium">Belum ada percakapan.</p>
        @if(auth()->user()->role === 'buyer')
        <p class="text-xs text-ink-400 mt-1">Kunjungi halaman toko untuk mulai chat dengan penjual.</p>
        @endif
    </div>
    @else
    <div class="space-y-2">
        @foreach($conversations as $conversation)
        @php
            $other = $conversation->otherParticipant(auth()->user());
            $unread = $conversation->unreadCountFor(auth()->user());
            $last = $conversation->latestMessage;
        @endphp
        <a href="{{ route('chat.show', $conversation) }}" class="flex items-center gap-3 rounded-lg border p-4 transition-colors {{ $unread ? 'border-clay-200 bg-clay-50/60' : 'border-ink-100 bg-white' }} hover:border-clay-300 hover:shadow-soft">
            <span class="relative shrink-0">
                <span class="h-11 w-11 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-sm font-bold flex items-center justify-center">
                    {{ strtoupper(substr($other->store_name ?? $other->name, 0, 1)) }}
                </span>
                <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full ring-2 ring-white {{ $other->isOnline() ? 'bg-forest-500' : 'bg-ink-300' }}"></span>
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-ink-800 truncate">{{ $other->store_name ?? $other->name }}</p>
                    @if($last)
                    <span class="text-[11px] text-ink-400 shrink-0">{{ $last->created_at->diffForHumans(null, true) }}</span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-2 mt-0.5">
                    <p class="text-xs text-ink-500 truncate">{{ $last ? ($last->sender_id === auth()->id() ? 'Kamu: ' : '').$last->body : 'Belum ada pesan' }}</p>
                    @if($unread > 0)
                    <span class="h-5 min-w-5 px-1 rounded-full bg-clay-500 text-white text-[10px] font-bold flex items-center justify-center shrink-0">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $conversations->links() }}</div>
    @endif
</section>
@endsection
