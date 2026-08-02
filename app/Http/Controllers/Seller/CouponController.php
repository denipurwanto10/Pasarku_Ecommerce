<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = $request->user()->coupons()->latest()->paginate(10);

        return view('seller.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('seller.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateCoupon($request);

        $data['seller_id'] = $request->user()->id;
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        Coupon::create($data);

        return redirect()->route('seller.coupons.index')->with('success', 'Kupon berhasil dibuat.');
    }

    public function edit(Coupon $coupon)
    {
        $this->authorizeOwner($coupon);

        return view('seller.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->authorizeOwner($coupon);

        $data = $this->validateCoupon($request, $coupon);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        $coupon->update($data);

        return redirect()->route('seller.coupons.index')->with('success', 'Kupon berhasil diperbarui.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorizeOwner($coupon);
        $coupon->delete();

        return back()->with('success', 'Kupon berhasil dihapus.');
    }

    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        return $request->validate([
            'code' => [
                'required', 'string', 'max:30', 'alpha_dash',
                'unique:coupons,code'.($coupon ? ','.$coupon->id : ''),
            ],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1'],
            'max_discount' => ['nullable', 'integer', 'min:0'],
            'min_purchase' => ['nullable', 'integer', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);
    }

    private function authorizeOwner(Coupon $coupon): void
    {
        if ($coupon->seller_id !== auth()->id()) {
            abort(403);
        }
    }
}
