<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('q'), fn ($q) => $q->where(function ($w) use ($request) {
                $w->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount(['products', 'orders']);

        return view('admin.users.show', compact('user'));
    }

    public function suspend(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin tidak bisa dinonaktifkan.');
        }

        $user->update(['is_suspended' => true]);

        return back()->with('success', 'Akun "'.$user->name.'" telah dinonaktifkan.');
    }

    public function activate(User $user)
    {
        $user->update(['is_suspended' => false]);

        return back()->with('success', 'Akun "'.$user->name.'" telah diaktifkan kembali.');
    }
}
