@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-8">Profil Saya</h1>

    @if($errors->any())
    <div class="mb-5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3">
        <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Form Informasi Profil & Foto --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8 space-y-6">
        @csrf @method('PUT')

        <div class="flex items-center gap-5">
            <div class="relative shrink-0">
                @if($user->avatar)
                <img id="avatar-preview" src="{{ asset('storage/'.$user->avatar) }}" class="h-20 w-20 rounded-full object-cover border border-ink-100 shadow-soft" alt="Foto profil {{ $user->name }}">
                @else
                <span id="avatar-preview-fallback" class="h-20 w-20 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-2xl font-bold flex items-center justify-center shadow-soft">{{ strtoupper(substr($user->name,0,1)) }}</span>
                <img id="avatar-preview" src="" class="hidden h-20 w-20 rounded-full object-cover border border-ink-100 shadow-soft" alt="Pratinjau foto profil">
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Foto Profil</label>
                <input type="file" name="avatar" id="avatar-input" accept="image/png,image/jpeg,image/webp" class="w-full rounded-md border border-ink-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-full file:border-0 file:gradient-brand file:text-white file:text-xs file:font-semibold file:px-3 file:py-1.5 file:shadow-glow">
                <p class="text-xs text-ink-400 mt-1.5">Format JPG, PNG, atau WEBP. Maks 2MB.</p>
                @if($user->avatar)
                <label class="inline-flex items-center gap-1.5 mt-2 text-xs font-medium text-rose-600 cursor-pointer">
                    <input type="checkbox" name="remove_avatar" value="1" class="rounded border-ink-300 text-rose-600 focus:ring-rose-400"> Hapus foto profil
                </label>
                @endif
            </div>
        </div>

        <div class="border-t border-ink-100"></div>

        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kota</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Alamat</label>
            <textarea name="address" rows="3" class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">{{ old('address', $user->address) }}</textarea>
        </div>

        <button type="submit" class="btn-tactile rounded-md bg-clay-600 text-white font-semibold px-6 py-3.5 text-sm hover:bg-clay-700 hover:brightness-110 transition">Simpan Perubahan</button>
    </form>

    {{-- Form Ganti Kata Sandi --}}
    <form action="{{ route('profile.password.update') }}" method="POST" class="mt-6 rounded-lg border border-ink-100 bg-white shadow-card p-5 sm:p-8 space-y-5">
        @csrf @method('PUT')
        <h2 class="font-serif text-lg font-bold text-ink-900">Ganti Kata Sandi</h2>
        <div>
            <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kata Sandi Saat Ini</label>
            <input type="password" name="current_password" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-md border border-ink-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400 transition-colors">
            </div>
        </div>
        <button type="submit" class="btn-tactile rounded-full bg-ink-900 text-white font-semibold px-6 py-3.5 text-sm shadow-soft hover:brightness-110 transition">Perbarui Kata Sandi</button>
    </form>
</section>

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('avatar-input');
        const preview = document.getElementById('avatar-preview');
        const fallback = document.getElementById('avatar-preview-fallback');
        if (!input) return;
        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.classList.remove('hidden');
            if (fallback) fallback.classList.add('hidden');
        });
    })();
</script>
@endpush
@endsection
