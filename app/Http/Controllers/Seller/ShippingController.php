<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ShippingCityRate;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user();
        $cityRates = $seller->shippingCityRates()->orderBy('city')->get();

        return view('seller.shipping.index', compact('seller', 'cityRates'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shipping_type' => ['required', 'in:flat,per_city'],
            'shipping_flat_rate' => ['required', 'integer', 'min:0'],
        ]);

        $request->user()->update($data);

        return back()->with('success', 'Pengaturan ongkir toko berhasil disimpan.');
    }

    public function storeCityRate(Request $request)
    {
        $data = $request->validate([
            'city' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'integer', 'min:0'],
        ]);

        ShippingCityRate::updateOrCreate(
            ['seller_id' => $request->user()->id, 'city' => $data['city']],
            ['rate' => $data['rate']]
        );

        return back()->with('success', 'Tarif ongkir untuk kota "'.$data['city'].'" berhasil disimpan.');
    }

    public function destroyCityRate(ShippingCityRate $cityRate)
    {
        if ($cityRate->seller_id !== auth()->id()) {
            abort(403);
        }

        $cityRate->delete();

        return back()->with('success', 'Tarif kota berhasil dihapus.');
    }
}
