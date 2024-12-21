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
        // Perform local authentication
        $request->authenticate();

        $request->session()->regenerate();

        // Send a login request to the first project to get the token
        $response = Http::post('http://daodes.space/api/login', [
            'name' => $request->input('name'),
            'password' => $request->input('password'),
        ]);

        if ($response->successful()) {
            // Store the access token in the session
            $accessToken = $response->json()['access_token'];
            $request->session()->put('access_token', $accessToken);
        } else {
            return back()->withErrors(['message' => 'Failed to get access token from the first project']);
        }

        // Retrieve the previous URL from the session
        $intendedUrl = $request->session()->pull('url.intended', route('dashboard'));

        // Check if the intended URL is the home page
        if ($intendedUrl === route('home')) {
            // Redirect to dashboard if it's the home page
            return redirect()->route('dashboard');
        }

        // Otherwise, redirect to the intended page
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