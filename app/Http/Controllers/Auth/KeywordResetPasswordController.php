<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KeywordResetPasswordController extends Controller
{
    public function showKeywordForm()
    {
        return view('auth.forgot-password');
    }

    public function submitKeyword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'keyword' => 'required|string',
        ]);

        // Проверка имени пользователя и ключевого слова
        $user = User::where('name', $request->username)
                    ->where('keyword', $request->keyword)
                    ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['username' => 'Неправильное имя пользователя или ключевое слово.']);
        }

        session(['user_id' => $user->id]);

        return redirect()->route('custom.password.reset');
    }

    public function showResetForm()
    {
        $user_id = session('user_id');

        if (!$user_id) {
            return redirect()->route('custom.password.keyword')->withErrors(['status' => 'Невозможно продолжить. Попробуйте снова.']);
        }

        return view('auth.reset-password', compact('user_id'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return redirect()->back()->withErrors(['user_id' => 'Неправильный идентификатор пользователя.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('custom.password.reset')->with('status', 'Пароль был успешно сброшен.');
    }
}
