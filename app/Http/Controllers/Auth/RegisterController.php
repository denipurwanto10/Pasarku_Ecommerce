<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'role' => ['required', 'in:buyer,seller'],
            'phone' => ['required', 'string', 'max:20'],
            'store_name' => ['nullable', 'required_if:role,seller', 'string', 'max:150'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phone' => $data['phone'],
            'store_name' => $data['role'] === 'seller' ? $data['store_name'] : null,
            'store_slug' => $data['role'] === 'seller' ? $this->generateStoreSlug($data['store_name']) : null,
            'store_status' => $data['role'] === 'seller' ? 'pending' : 'approved',
        ]);

        Auth::login($user);

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard')->with('success', 'Selamat datang di Pasarku! Toko kamu sedang menunggu persetujuan admin sebelum tampil ke publik. Kamu tetap bisa menyiapkan produk sekarang.');
        }

        return redirect()->route('home')->with('success', 'Selamat datang di Pasarku!');
    }

    private function generateStoreSlug(string $storeName): string
    {
        $base = Str::slug($storeName);
        $slug = $base;
        $i = 1;

        while (User::where('store_slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
