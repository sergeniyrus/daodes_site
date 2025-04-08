<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:15', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'keyword' => ['required', 'string', 'min:5', 'max:15'],
            'password' => ['required', 'min:6', 'max:12', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->session()->put('pending_user', [
            'name' => $validated['name'],
            'keyword' => $validated['keyword'], // Незашифрованный keyword
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('seed.index');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            // другие правила
            'agreeToTerms' => ['required', 'accepted'],
        ], [
            'agreeToTerms.required' => __('registration.agree_required'),
            'agreeToTerms.accepted' => __('registration.agree_required'),
        ]);
    }
}
