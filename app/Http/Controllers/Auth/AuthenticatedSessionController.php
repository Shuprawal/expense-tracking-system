<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }



    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();




        if (!$user->roles()->exists()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('error', 'You are not assigned to any role. Contact admin.');
        }


        if ($user->hasAdminRole()) {
            return redirect()->intended(route('admin.dashboard', [], false));
        } else {
            return redirect()->intended(route('user.dashboard', [], false));
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


}
