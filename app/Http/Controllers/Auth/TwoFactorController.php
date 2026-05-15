<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function showVerifyForm()
    {
        $user = Auth::user();

        // timestamp de l'expiration
        $expiresAt = $user->two_factor_expires_at;

        return view('auth.2fa', compact('expiresAt'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($user->verifyTwoFactorCode($request->code)) {
            $user->resetTwoFactorCode();
            $request->session()->put('2fa_passed', true);

            return redirect()->intended(route($user->redirectRoute(), absolute: false));
        }

        return back()->withErrors(['code' => 'Code 2FA invalide ou expiré.']);
    }

    public function resend(Request $request)
    {
        $user = Auth::user();

        // Regénère le code et envoie par email
        $user->generateTwoFactorCode();

        return back()->with('status', 'Un nouveau code a été envoyé à votre email.');
    }
}
