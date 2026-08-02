<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $stores = User::where('role', 'seller')
            ->withCount('products')
            ->when($request->filled('status'), fn ($q) => $q->where('store_status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where(function ($w) use ($request) {
                $w->where('store_name', 'like', '%'.$request->q.'%')
                    ->orWhere('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.stores.index', compact('stores'));
    }

    public function show(User $store)
    {
        if ($store->role !== 'seller') {
            abort(404);
        }

        $store->loadCount('products');
        $products = $store->products()->latest()->limit(10)->get();

        return view('admin.stores.show', compact('store', 'products'));
    }

    public function approve(User $store)
    {
        $this->authorizeStore($store);

        $store->update(['store_status' => 'approved', 'store_status_note' => null]);

        return back()->with('success', 'Toko "'.$store->store_name.'" telah disetujui dan tampil ke publik.');
    }

    public function suspend(Request $request, User $store)
    {
        $this->authorizeStore($store);

        $data = $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $store->update([
            'store_status' => 'suspended',
            'store_status_note' => $data['note'] ?? null,
        ]);

        return back()->with('success', 'Toko "'.$store->store_name.'" telah dinonaktifkan.');
    }

    private function authorizeStore(User $store): void
    {
        if ($store->role !== 'seller') {
            abort(404);
        }
    }
}
