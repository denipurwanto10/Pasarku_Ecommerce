@extends('layouts.app')
@section('title', 'Kelola Transaksi')
@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-ink-900 mb-2">Panel Admin</h1>
    @include('partials.admin-nav')

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor pesanan..."
            class="flex-1 min-w-[200px] rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
        <select name="status" onchange="this.form.submit()" class="rounded-full border border-ink-200 bg-white shadow-soft px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-clay-400">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Menunggu Konfirmasi</option>
            <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Diproses</option>
            <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Dikirim</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Selesai</option>
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Dibatalkan</option>
        </select>
        <button class="rounded-md bg-clay-600 text-white text-sm font-semibold px-5 py-2.5 hover:bg-clay-700">Cari</button>
    </form>

    <div class="overflow-x-auto rounded-lg border border-ink-100 bg-white shadow-soft">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left font-semibold px-4 py-3">No. Pesanan</th>
                    <th class="text-left font-semibold px-4 py-3">Pembeli</th>
                    <th class="text-left font-semibold px-4 py-3">Total</th>
                    <th class="text-left font-semibold px-4 py-3">Status</th>
                    <th class="text-left font-semibold px-4 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach($orders as $order)
                <tr class="hover:bg-clay-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                    <td class="px-4 py-3 font-medium text-ink-800">{{ $order->order_number }}</td>
                    <td class="px-4 py-3 text-ink-500">{{ $order->buyer->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-ink-800 font-medium">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-ink-100 text-ink-600">{{ $order->statusLabel() }}</span>
                    </td>
                    <td class="px-4 py-3 text-ink-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
</section>
@endsection
