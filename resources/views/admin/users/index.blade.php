@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / email..."
            class="flex-1 min-w-[200px] rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
        <select name="role" onchange="this.form.submit()" class="rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            <option value="">Semua Peran</option>
            <option value="buyer" {{ request('role')=='buyer'?'selected':'' }}>Pembeli</option>
            <option value="seller" {{ request('role')=='seller'?'selected':'' }}>Penjual</option>
            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
        </select>
        <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700">Cari</button>
    </form>

    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-soft">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-semibold px-4 py-3">Nama</th>
                    <th class="text-left font-semibold px-4 py-3">Email</th>
                    <th class="text-left font-semibold px-4 py-3">Peran</th>
                    <th class="text-left font-semibold px-4 py-3">Status</th>
                    <th class="text-right font-semibold px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach($users as $user)
                <tr class="hover:bg-clay-50/50 transition-colors">
                    <td class="px-4 py-3"><a href="{{ route('admin.users.show', $user) }}" class="font-medium text-ink-800 hover:text-clay-600">{{ $user->name }}</a></td>
                    <td class="px-4 py-3 text-ink-500">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-ink-500 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $user->is_suspended ? 'bg-rose-100 text-rose-600' : 'bg-forest-100 text-forest-700' }}">
                            {{ $user->is_suspended ? 'Nonaktif' : 'Aktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($user->role !== 'admin')
                            @if($user->is_suspended)
                            <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-xs font-semibold text-forest-600 hover:underline">Aktifkan</button>
                            </form>
                            @else
                            <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan akun ini?')">
                                @csrf @method('PATCH')
                                <button class="text-xs font-semibold text-rose-500 hover:underline">Nonaktifkan</button>
                            </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $users->links() }}</div>
</section>
@endsection
