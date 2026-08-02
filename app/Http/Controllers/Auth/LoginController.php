<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi yang kamu masukkan salah.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->is_suspended) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun kamu telah dinonaktifkan. Hubungi admin untuk informasi lebih lanjut.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'seller') {
            return redirect()->intended(route('seller.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
