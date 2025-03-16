<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/'],
        'keyword' => ['required', 'string', 'min:8', 'max:12'],
        'password' => ['required',  'min:8', 'max:64', 'confirmed', Rules\Password::defaults()],
    ], [
        'name.regex' => 'The name field may only contain letters, numbers, underscores (_), and hyphens (-). No spaces are allowed.',
    ]);

    $user = User::create([
        'name' => $request->name,
        'keyword' => $request->keyword,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::SEED);
}

    protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:12'],
        'keyword' => ['required', 'string', 'max:255'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        //'g-recaptcha-response' => ['required', 'recaptcha'],
    ]);
}

}
