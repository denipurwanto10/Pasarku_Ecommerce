<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBuyers = User::where('role', 'buyer')->count();
        $totalSellers = User::where('role', 'seller')->count();
        $pendingStores = User::where('role', 'seller')->where('store_status', 'pending')->count();
        $totalProducts = Product::count();
        $pendingModeration = Product::where('moderation_status', 'rejected')->count();
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<=', Product::LOW_STOCK_THRESHOLD)->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        $totalOrders = Order::count();
        $totalTransaction = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        $recentOrders = Order::with('buyer')->latest()->limit(8)->get();
        $pendingSellers = User::where('role', 'seller')->where('store_status', 'pending')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalBuyers', 'totalSellers', 'pendingStores', 'totalProducts',
            'pendingModeration', 'lowStockCount', 'outOfStockCount',
            'totalOrders', 'totalTransaction', 'recentOrders', 'pendingSellers'
        ));
    }
}
