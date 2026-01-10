<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        // Выполняем аутентификацию
        $request->authenticate();
        Session::regenerate();

        // Получаем текущего пользователя
        $user = Auth::user();

        // Если public_key уже существует — пускаем внутрь
        if ($user->profile?->public_key) {
            return redirect()->intended(route('home'));
        }

        // Иначе — перенаправляем на настройку ключей
        // Сохраняем intended URL, чтобы после setup вернуться туда
        $intended = Session::pull('url.intended', route('home'));
        Session::put('url.intended', $intended);

        return redirect()->route('auth.seed.setup')
            ->with('warning', __('auth.seed_required_to_access'));
    }



    /**
     * Destroy an authenticated session.
     */
    

public function destroy(Request $request): RedirectResponse
{

    
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    $request->session()->flash('message', __('login.logout'));

    return redirect('/');
}


}
