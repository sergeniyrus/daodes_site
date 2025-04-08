<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\EncryptionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class KeywordResetPasswordController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    // Показать форму для сброса пароля по ключевому слову
    public function showKeywordForm()
    {
        return view('auth.forgot-password');
    }

    // Обработка отправки ключевого слова
    public function submitKeyword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'keyword' => 'required|string',
        ]);

        $user = User::where('name', $request->username)->first();

        if (!$user) {
            Log::warning('Password reset attempt for non-existent user', ['username' => $request->username]);
            return back()->withErrors(['general' => __('message.incorrect_credentials')]);
        }

        try {
            $decryptedKeyword = $this->encryptionService->decrypt($user->keyword);
            
            if (!hash_equals($decryptedKeyword, $request->keyword)) {
                Log::warning('Incorrect keyword for user', ['user_id' => $user->id]);
                return back()->withErrors(['general' => __('message.incorrect_credentials')]);
            }

            // Генерируем и сохраняем токен
            $token = Str::random(60);
            Session::put('password_reset_token', $token);
            Session::put('password_reset_user_id', $user->id);

            $user->forceFill([
                'remember_token' => Hash::make($token),
            ])->save();

            Log::info('Keyword verified for password reset', ['user_id' => $user->id]);

            return redirect()->route('password.reset')
                ->with('status', __('message.keyword_verified'));

        } catch (\Exception $e) {
            Log::error('Keyword decryption failed', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['general' => __('message.decryption_error')]);
        }
    }

    // Показать форму для нового пароля
    public function showResetForm()
    {
        if (!Session::has('password_reset_token')) {
            return redirect()->route('password.keyword')
                ->withErrors(['general' => __('message.invalid_token')]);
        }

        return view('auth.reset-password');
    }

    // Обновление пароля
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $token = Session::get('password_reset_token');
        $userId = Session::get('password_reset_user_id');

        if (!$token || !$userId) {
            return back()->withErrors(['general' => __('message.invalid_token')]);
        }

        $user = User::find($userId);

        if (!$user || !Hash::check($token, $user->remember_token)) {
            Log::warning('Invalid password reset token attempted');
            return back()->withErrors(['general' => __('message.invalid_token')]);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => null,
        ])->save();

        Session::forget(['password_reset_token', 'password_reset_user_id']);

        Log::info('Password reset successful', ['user_id' => $user->id]);

        return redirect()->route('login')
            ->with('status', __('message.password_reset_success'));
    }
}