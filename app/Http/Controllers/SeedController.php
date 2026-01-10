<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;

class SeedController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('pending_user')) {
            return redirect()->route('register');
        }

        $pendingUser = $request->session()->get('pending_user');
        $words = $this->generateRandomWordsFromFile(public_path('base.txt'), 23);

        return view('seed', [
            'keyword' => $pendingUser['keyword'],
            'words' => $words,
        ]);
    }

    public function saveSeed(Request $request)
    {
        if (!$request->session()->has('pending_user')) {
            return redirect()->route('register');
        }

        $pendingUser = $request->session()->get('pending_user');

        // Создаём пользователя — keyword НЕ шифруем и НЕ используем для криптографии
        $user = User::create([
            'name' => $pendingUser['name'],
            'password' => $pendingUser['password'],
        ]);

        Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        // ❌ НЕ логиним автоматически
        $request->session()->forget('pending_user');

        // ✅ Устанавливаем flash-сообщение
    return redirect()->route('login')
        ->with('status', __('auth.registration_complete_message'));
    }

    protected function generateRandomWordsFromFile($filePath, $count)
    {
        $words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        shuffle($words);
        return array_slice($words, 0, $count);
    }
}