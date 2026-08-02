<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->cartItems()->with(['product.seller', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        $stockError = $this->findStockError($items);
        if ($stockError) {
            return redirect()->route('cart.index')->with('error', $stockError);
        }

        $total = $items->sum(fn ($item) => $item->quantity * (int) $item->unit_price);

        $coupon = null;
        $discount = 0;

        if ($request->session()->has('checkout_coupon_id')) {
            $sessionCoupon = Coupon::find($request->session()->get('checkout_coupon_id'));

            if ($sessionCoupon) {
                [$coupon, $discount, $error] = $this->resolveCoupon($sessionCoupon->code, $items);

                if ($error) {
                    $request->session()->forget(['checkout_coupon_id', 'checkout_coupon_code']);
                    $coupon = null;
                    $discount = 0;
                }
            }
        }

        $addresses = $request->user()->addresses()->orderByDesc('is_default')->latest()->get();
        $selectedCity = optional($addresses->firstWhere('is_default', true) ?? $addresses->first())->city
            ?? $request->user()->city;

        $shipping = $this->calculateShipping($items, $selectedCity);

        $grandTotal = max(0, $total - $discount) + $shipping['total'];

        return view('checkout.index', compact(
            'items', 'total', 'coupon', 'discount', 'grandTotal', 'addresses', 'shipping', 'selectedCity'
        ));
    }

    public function calculateShippingForCity(Request $request)
    {
        $request->validate(['city' => ['required', 'string', 'max:100']]);

        $items = $request->user()->cartItems()->with(['product.seller', 'variant'])->get();
        $shipping = $this->calculateShipping($items, $request->city);

        return response()->json($shipping);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'max:30']]);

        $items = $request->user()->cartItems()->with(['product.seller', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        [$coupon, $discount, $error] = $this->resolveCoupon($request->code, $items);

        if ($error) {
            return back()->with('error', $error);
        }

        $request->session()->put('checkout_coupon_id', $coupon->id);
        $request->session()->put('checkout_coupon_code', $coupon->code);

        return back()->with('success', 'Kupon "'.$coupon->code.'" berhasil diterapkan, kamu hemat Rp'.number_format($discount, 0, ',', '.').'.');
    }

    public function removeCoupon(Request $request)
    {
        $request->session()->forget(['checkout_coupon_id', 'checkout_coupon_code']);

        return back()->with('success', 'Kupon dibatalkan.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address_id' => ['nullable', 'exists:addresses,id'],
            'shipping_address' => ['required_without:address_id', 'nullable', 'string', 'max:500'],
            'shipping_phone' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'in:transfer_bank,cod,e_wallet'],
            'courier' => ['required', 'in:jne_reg,jnt_express,sicepat,grab_instant'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $items = $user->cartItems()->with(['product', 'variant'])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu masih kosong.');
        }

        $stockError = $this->findStockError($items);
        if ($stockError) {
            return redirect()->route('cart.index')->with('error', $stockError);
        }

        $address = null;
        if (! empty($data['address_id'])) {
            $address = Address::where('id', $data['address_id'])->where('user_id', $user->id)->first();

            if (! $address) {
                return back()->with('error', 'Alamat yang dipilih tidak valid.');
            }
        }

        $shippingAddressText = $address ? $address->detail : $data['shipping_address'];
        $shippingPhone = $address ? $address->phone : $data['shipping_phone'];
        $shippingCity = $address ? $address->city : $data['shipping_city'];

        $coupon = null;
        $discount = 0;
        $couponCode = null;

        if ($request->session()->has('checkout_coupon_id')) {
            $sessionCoupon = Coupon::find($request->session()->get('checkout_coupon_id'));

            if ($sessionCoupon) {
                [$coupon, $discount, $error] = $this->resolveCoupon($sessionCoupon->code, $items);

                if (! $error) {
                    $couponCode = $coupon->code;
                }
            }
        }

        $shipping = $this->calculateShipping($items, $shippingCity);

        $order = DB::transaction(function () use ($data, $user, $items, $coupon, $discount, $couponCode, $address, $shippingAddressText, $shippingPhone, $shippingCity, $shipping) {
            $total = $items->sum(fn ($item) => $item->quantity * (int) $item->unit_price);
            $grandTotal = max(0, $total - $discount) + $shipping['total'];

            $order = Order::create([
                'buyer_id' => $user->id,
                'address_id' => $address?->id,
                'order_number' => 'PSK-'.strtoupper(Str::random(8)),
                'total_amount' => $grandTotal,
                'discount_amount' => $discount,
                'shipping_amount' => $shipping['total'],
                'shipping_breakdown' => $shipping['breakdown'],
                'status' => 'pending',
                'shipping_address' => $shippingAddressText,
                'shipping_phone' => $shippingPhone,
                'shipping_city' => $shippingCity,
                'payment_method' => $data['payment_method'],
                'coupon_code' => $couponCode,
                'courier' => $data['courier'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $unitPrice = (int) $item->unit_price;
                $product = $item->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'seller_id' => $product->seller_id,
                    'product_name' => $product->name,
                    'variant_name' => $item->variant?->name,
                    'price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $unitPrice,
                ]);

                if ($item->variant) {
                    // Produk dengan varian: kurangi stok varian secara langsung.
                    $item->variant->decrement('stock', min($item->quantity, $item->variant->stock));
                } else {
                    $product->applyStockChange(
                        -$item->quantity,
                        'order',
                        null,
                        'Terjual melalui pesanan '.$order->order_number
                    );
                }

                // Flash sale: catat jumlah terjual selama diskon berbatas waktu masih aktif,
                // supaya kuota flash sale ikut berkurang.
                if ($product->isOnDiscount()) {
                    $product->increment('flash_sale_sold', $item->quantity);
                }

                $product->increment('sold_count', $item->quantity);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $user->cartItems()->delete();

            return $order;
        });

        $request->session()->forget(['checkout_coupon_id', 'checkout_coupon_code']);

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat! Terima kasih sudah berbelanja.');
    }

    /**
     * Pastikan seluruh item keranjang masih memiliki stok yang cukup sebelum checkout diproses.
     */
    private function findStockError($items): ?string
    {
        foreach ($items as $item) {
            $label = $item->product->name.($item->variant ? ' ('.$item->variant->name.')' : '');
            $available = $item->available_stock;

            if ($available <= 0) {
                return 'Stok "'.$label.'" sudah habis. Silakan hapus dari keranjang.';
            }

            if ($item->quantity > $available) {
                return 'Stok "'.$label.'" tersisa '.$available.', tetapi kamu meminta '.$item->quantity.'.';
            }
        }

        return null;
    }

    /**
     * Hitung ongkir per penjual berdasarkan kota tujuan, lalu jumlahkan totalnya.
     *
     * @return array{total: int, breakdown: array<int, array{seller_id:int, seller_name:string, rate:int}>}
     */
    private function calculateShipping($items, ?string $city): array
    {
        $bySeller = $items->groupBy(fn ($item) => $item->product->seller_id);

        $breakdown = [];
        $total = 0;

        foreach ($bySeller as $sellerId => $sellerItems) {
            $seller = $sellerItems->first()->product->seller;
            $rate = $seller->shippingRateForCity($city);

            $breakdown[] = [
                'seller_id' => $seller->id,
                'seller_name' => $seller->store_name ?? $seller->name,
                'rate' => $rate,
            ];

            $total += $rate;
        }

        return ['total' => $total, 'breakdown' => $breakdown];
    }

    /**
     * Validate a coupon code against the current cart items.
     *
     * @return array{0: ?Coupon, 1: int, 2: ?string} [$coupon, $discount, $errorMessage]
     */
    private function resolveCoupon(string $code, $items): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (! $coupon) {
            return [null, 0, 'Kode kupon tidak ditemukan.'];
        }

        if (! $coupon->isRedeemable()) {
            return [null, 0, 'Kupon ini sudah tidak berlaku atau kuotanya habis.'];
        }

        $eligibleSubtotal = (int) $items
            ->filter(fn ($item) => (int) $item->product->seller_id === (int) $coupon->seller_id)
            ->sum(fn ($item) => $item->quantity * (int) $item->unit_price);

        if ($eligibleSubtotal <= 0) {
            return [null, 0, 'Kupon ini hanya berlaku untuk produk dari toko tertentu yang belum ada di keranjangmu.'];
        }

        if ($coupon->min_purchase && $eligibleSubtotal < $coupon->min_purchase) {
            return [null, 0, 'Minimal belanja Rp'.number_format($coupon->min_purchase, 0, ',', '.').' dari toko terkait untuk memakai kupon ini.'];
        }

        $discount = $coupon->calculateDiscount($eligibleSubtotal);

        return [$coupon, $discount, null];
    }
}
