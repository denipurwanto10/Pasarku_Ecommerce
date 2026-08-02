@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900">Notifikasi</h1>
        @if($notifications->contains(fn($n) => is_null($n->read_at)))
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="text-xs font-semibold text-clay-600 hover:underline">Tandai semua dibaca</button>
        </form>
        @endif
    </div>

    @if($notifications->isEmpty())
    <div class="text-center py-16 rounded-lg bg-white shadow-soft border border-dashed border-ink-200">
        <p class="text-ink-400 font-medium">Belum ada notifikasi.</p>
    </div>
    @else
    <div class="space-y-2">
        @foreach($notifications as $notification)
        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left rounded-lg border p-4 flex items-start gap-3 transition-colors {{ $notification->read_at ? 'border-ink-100 bg-white' : 'border-clay-200 bg-clay-50/60' }} hover:border-clay-300">
                @if(!$notification->read_at)
                <span class="mt-1.5 h-2 w-2 rounded-full bg-clay-500 shrink-0"></span>
                @else
                <span class="mt-1.5 h-2 w-2 rounded-full bg-transparent shrink-0"></span>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink-800">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                    <p class="text-sm text-ink-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                    <p class="text-xs text-ink-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </button>
        </form>
        @endforeach
    </div>
    <div class="mt-8">{{ $notifications->links() }}</div>
    @endif
</section>
@endsection
