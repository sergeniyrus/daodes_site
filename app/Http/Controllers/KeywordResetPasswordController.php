<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class KeywordResetPasswordController extends Controller
{
    /**
     * Show the form to request a password reset using a keyword.
     */
    public function showKeywordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the submission of the keyword reset form.
     */
    public function submitKeyword(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string|exists:users,name',
            'keyword' => 'required|string',
        ]);

        // Find the user by username
        $user = User::where('name', $request->username)->first();

        // Check if the keyword matches
        if ($user && $user->keyword === $request->keyword) {
            // Generate a token for password reset
            $token = Str::random(60);
            $user->forceFill([
                'remember_token' => Hash::make($token),
            ])->save();

            // Redirect to the password reset form with the token
            return redirect()->route('password.reset', ['token' => $token]);
        }

        // If keyword doesn't match, return with an error
        return back()->withErrors(['keyword' => 'The provided keyword is incorrect.']);
    }

    /**
     * Show the form to reset the password.
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
{
    // Validate the request
    $request->validate([
        'token' => 'required', // Token for verification
        'password' => 'required|string|min:8|confirmed', // New password
    ]);

    // Find the user by the token
    $user = User::whereNotNull('remember_token')->first();

    // Check if the token is valid
    if ($user && Hash::check($request->token, $user->remember_token)) {
        // Update the password
        $user->forceFill([
            'password' => Hash::make($request->password), // Hash the new password
            'remember_token' => null, // Clear the token after use
        ])->save();

        // Redirect to login with a success message
        return redirect()->route('login')->with('status', 'Your password has been reset successfully.');
    }

    // If token is invalid, return with an error
    return back()->withErrors(['token' => 'Invalid token.']);
}
}