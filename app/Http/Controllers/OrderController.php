<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->paginate(8);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.seller', 'items.product');

        $reviewedProductIds = $order->status === 'completed'
            ? Review::where('user_id', auth()->id())
                ->whereIn('product_id', $order->items->pluck('product_id'))
                ->pluck('product_id')
                ->toArray()
            : [];

        return view('orders.show', compact('order', 'reviewedProductIds'));
    }

    /**
     * Pembeli membatalkan pesanannya sendiri selama masih berstatus "pending"
     * (belum diproses penjual). Stok yang sempat dikurangi saat checkout
     * dikembalikan, dan penjual terkait diberi notifikasi.
     */
    public function cancel(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan ini sudah diproses penjual dan tidak bisa dibatalkan sendiri. Hubungi penjual lewat chat jika ada kendala.');
        }

        $order->load('items.product');

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->applyStockChange(
                        $item->quantity,
                        'return',
                        auth()->id(),
                        'Stok dikembalikan karena pesanan '.$order->order_number.' dibatalkan pembeli'
                    );
                    $item->product->decrement('sold_count', min($item->quantity, $item->product->sold_count));
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        $sellers = User::whereIn('id', $order->items->pluck('seller_id')->unique())->get();
        foreach ($sellers as $seller) {
            $seller->notify(new OrderStatusUpdated($order));
        }

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibatalkan. Stok produk sudah dikembalikan.');
    }

    /**
     * "Beli Lagi": masukkan ulang semua produk dari pesanan lama ke keranjang,
     * sejumlah yang dulu dibeli (dibatasi stok yang tersedia sekarang).
     * Produk yang sudah dihapus/nonaktif/habis dilewati dan dilaporkan ke pembeli.
     */
    public function reorder(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        $added = 0;
        $unavailable = 0;

        foreach ($order->items as $item) {
            $product = $item->product;

            if (! $product || ! $product->is_active || $product->moderation_status !== 'approved' || $product->stock < 1) {
                $unavailable++;
                continue;
            }

            $cartItem = CartItem::firstOrNew([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);

            $qty = min($item->quantity, $product->stock);
            $cartItem->quantity = $cartItem->exists ? $cartItem->quantity + $qty : $qty;
            $cartItem->save();
            $added++;
        }

        if ($added === 0) {
            return back()->with('error', 'Semua produk di pesanan ini sudah tidak tersedia lagi.');
        }

        $message = $added.' produk dari pesanan ini ditambahkan ke keranjang.';
        if ($unavailable > 0) {
            $message .= ' '.$unavailable.' produk lain sudah tidak tersedia dan dilewati.';
        }

        return redirect()->route('cart.index')->with('success', $message);
    }
}
