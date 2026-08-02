<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * $roles bisa berupa satu role ("seller") atau beberapa role dipisah "|" (mis. "seller|admin").
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        $allowed = explode('|', $roles);

        if (! $user || ! in_array($user->role, $allowed, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($user->is_suspended) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Akun kamu telah dinonaktifkan. Hubungi admin untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
}
