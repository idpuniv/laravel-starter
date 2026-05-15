<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $rolesRequiring2FA = ['admin', 'root'];
        $requires2FA = in_array($user->role, $rolesRequiring2FA);

        if ($user->is_admin && !$request->session()->get('2fa_passed')) {
            if (!$user->two_factor_code || $user->two_factor_expires_at->isPast()) {
                $user->generateTwoFactorCode();
            }

            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}