<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function showVerifyForm(): View
    {
        $user = Auth::user();

        return view('auth.2fa', [
            'expiresAt' => $user->two_factor_expires_at,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();

        if ($user->verifyTwoFactorCode($request->input('code'))) {
            $user->resetTwoFactorCode();

            $request->session()->put('2fa_passed', true);

            return redirect()->intended(
                route($user->redirectRoute(), absolute: false)
            );
        }

        return back()->withErrors([
            'code' => __('auth.invalid_or_expired_two_factor_code'),
        ]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $user->generateTwoFactorCode();

        return back()->with(
            'status',
            __('auth.two_factor_code_sent')
        );
    }
}