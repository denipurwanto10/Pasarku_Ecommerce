<?php

namespace App\Http\Controllers;

use App\Models\StoreFollow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $user = $request->user();

        if ($user->role !== 'buyer') {
            abort(403, 'Hanya pembeli yang bisa mengikuti toko.');
        }

        if ($user->id === $seller->id) {
            abort(403);
        }

        $existing = StoreFollow::where('user_id', $user->id)
            ->where('seller_id', $seller->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'unfollowed';
            $message = 'Berhenti mengikuti toko ini.';
        } else {
            StoreFollow::create([
                'user_id' => $user->id,
                'seller_id' => $seller->id,
            ]);
            $status = 'followed';
            $message = 'Kamu sekarang mengikuti toko ini.';
        }

        $followerCount = $seller->followers()->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'follower_count' => $followerCount,
            ]);
        }

        return back()->with('success', $message);
    }
}
