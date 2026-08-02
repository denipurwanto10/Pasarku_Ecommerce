<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $items = OrderItem::where('seller_id', $request->user()->id)
            ->with(['order.buyer', 'product'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->whereHas('order', fn ($o) => $o->where('status', $request->status));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.orders.index', compact('items'));
    }

    public function updateStatus(Request $request, \App\Models\Order $order)
    {
        $hasItem = $order->items()->where('seller_id', $request->user()->id)->exists();

        if (! $hasItem) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,completed,cancelled'],
            'courier' => ['nullable', 'in:jne_reg,jnt_express,sicepat,grab_instant'],
            'tracking_number' => ['nullable', 'string', 'max:50'],
        ]);

        $statusChanged = $order->status !== $data['status'];

        $order->update([
            'status' => $data['status'],
            'courier' => $data['courier'] ?? $order->courier,
            'tracking_number' => $data['tracking_number'] ?? $order->tracking_number,
        ]);

        if ($statusChanged) {
            $order->buyer->notify(new OrderStatusUpdated($order));
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
