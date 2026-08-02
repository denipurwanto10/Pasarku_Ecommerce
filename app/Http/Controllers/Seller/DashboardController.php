<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user();

        $productCount = $seller->products()->count();
        $activeProductCount = $seller->products()->where('is_active', true)->count();

        $orderItems = OrderItem::where('seller_id', $seller->id);

        $totalRevenue = (clone $orderItems)
            ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->sum('subtotal');

        $totalSold = (clone $orderItems)
            ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->sum('quantity');

        $pendingOrders = (clone $orderItems)
            ->whereHas('order', fn ($q) => $q->where('status', 'pending'))
            ->distinct('order_id')
            ->count('order_id');

        $recentItems = OrderItem::where('seller_id', $seller->id)
            ->with(['order.buyer', 'product'])
            ->latest()
            ->limit(6)
            ->get();

        $topProducts = $seller->products()->orderByDesc('sold_count')->limit(5)->get();

        $lowStockProducts = $seller->products()
            ->where('stock', '<=', \App\Models\Product::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->limit(8)
            ->get();
        $lowStockCount = $seller->products()->where('stock', '<=', \App\Models\Product::LOW_STOCK_THRESHOLD)->count();

        $followerCount = $seller->followers()->count();
        $unreadChatCount = $seller->conversationsAsSeller()->get()->sum(fn ($c) => $c->unreadCountFor($seller));

        $revenueTrend = collect(range(6, 0))->map(function ($daysAgo) use ($orderItems) {
            $date = now()->subDays($daysAgo);
            $sum = (clone $orderItems)
                ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
                ->whereDate('created_at', $date->toDateString())
                ->sum('subtotal');

            return ['label' => $date->translatedFormat('d M'), 'value' => (float) $sum];
        });

        return view('seller.dashboard', compact(
            'productCount', 'activeProductCount', 'totalRevenue',
            'totalSold', 'pendingOrders', 'recentItems', 'topProducts', 'revenueTrend',
            'followerCount', 'unreadChatCount', 'lowStockProducts', 'lowStockCount'
        ));
    }
}
