<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $intendedUrl = $request->session()->pull('url.intended', route('home'));

        // Добавляем сообщение о успешном входе с использованием перевода
        $request->session()->flash('message', __('login.login'));

        // Перенаправляем пользователя на главную страницу
        return redirect()->route('home');
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Добавляем сообщение о выходе из системы
        $request->session()->flash('message', __('login.logout'));

        return redirect('/');
    }
}
