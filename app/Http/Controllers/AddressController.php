<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->orderByDesc('is_default')->latest()->get();

        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateAddress($request);
        $data['user_id'] = $request->user()->id;

        $this->handleDefault($request, $data);

        Address::create($data);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit(Address $address)
    {
        $this->authorizeOwner($address);

        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeOwner($address);

        $data = $this->validateAddress($request);
        $this->handleDefault($request, $data, $address);

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Address $address)
    {
        $this->authorizeOwner($address);
        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(Address $address)
    {
        $this->authorizeOwner($address);

        $address->user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama diperbarui.');
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'detail' => ['required', 'string', 'max:500'],
        ]);
    }

    private function handleDefault(Request $request, array &$data, ?Address $ignore = null): void
    {
        $isFirstAddress = $request->user()->addresses()->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))->doesntExist();
        $data['is_default'] = $request->boolean('is_default') || $isFirstAddress;

        if ($data['is_default']) {
            $request->user()->addresses()->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))->update(['is_default' => false]);
        }
    }

    private function authorizeOwner(Address $address): void
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
