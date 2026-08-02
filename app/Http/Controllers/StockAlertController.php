<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAlert;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    /**
     * Toggle langganan "Beri tahu saya" untuk produk yang sedang habis stok.
     * Hanya relevan saat produk memang habis; jika sudah tersedia, langsung tolak.
     */
    public function toggle(Request $request, Product $product)
    {
        $existing = StockAlert::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->whereNull('notified_at')
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Kamu tidak akan diberi tahu lagi untuk produk ini.';
        } else {
            if (! $product->isOutOfStock()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'unavailable'], 422);
                }

                return back()->with('error', 'Produk ini masih tersedia.');
            }

            StockAlert::updateOrCreate(
                ['user_id' => $request->user()->id, 'product_id' => $product->id, 'notified_at' => null],
                []
            );
            $status = 'added';
            $message = 'Oke, kami akan beri tahu kamu saat stok tersedia lagi.';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $message);
    }
}
