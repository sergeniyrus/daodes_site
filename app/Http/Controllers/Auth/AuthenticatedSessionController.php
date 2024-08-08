<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Получаем предыдущий URL из сессии
        $intendedUrl = $request->session()->pull('url.intended', route('dashboard'));

        // Проверяем, является ли предыдущий URL главной страницей
        if ($intendedUrl === route('home')) {
            // Если это главная страница, перенаправляем на роут dashboard
            return redirect()->route('dashboard');
        }

        // В противном случае перенаправляем на страницу, с которой пришли
        return redirect()->intended($intendedUrl);
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
