<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\AuditService;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $ip = $request->ip();
        
        try {
            $request->authenticate();
        } catch (\Exception $e) {
            AuditService::log('login', null, 'failure', null, null, [
                'email' => $email,
                'ip' => $ip,
                'reason' => 'invalid_credentials'
            ]);
            
            throw $e;
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            AuditService::logFailure('login', $user, 'Compte désactivé');

            return redirect()->route('login')
                ->withErrors([
                    'email' => __('auth.account_disabled')
                ]);
        }

        $request->session()->regenerate();
        
        AuditService::log('login', $user, 'success');
        
        return redirect()->intended(route($user->getDashboardRoute(), absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        
        if ($user) {
            AuditService::log('logout', $user, 'success');
        }
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}