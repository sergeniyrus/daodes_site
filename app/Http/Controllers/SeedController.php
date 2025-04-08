<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seed;
use App\Services\EncryptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
            'keyword' => $pendingUser['keyword'], // Незашифрованный keyword
            'words' => $words,
        ]);
    }

    public function saveSeed(Request $request)
    {
        if (!$request->session()->has('pending_user')) {
            return redirect()->route('register');
        }
    
        $pendingUser = $request->session()->get('pending_user');
        $encryptionService = app(EncryptionService::class);
    
        // Шифруем и создаём пользователя
        $user = User::create([
            'name' => $pendingUser['name'],
            'keyword' => $encryptionService->encrypt($pendingUser['keyword']),
            'password' => $pendingUser['password'],
        ]);
    
        // Шифруем сид-фразу
        $seedWords = [];
        foreach ($request->only([
            'word0', 'word1', 'word2', 'word3', 'word4',
            'word5', 'word6', 'word7', 'word8', 'word9',
            'word10', 'word11', 'word12', 'word13', 'word14',
            'word15', 'word16', 'word17', 'word18', 'word19',
            'word20', 'word21', 'word22'
        ]) as $key => $word) {
            $seedWords[$key] = $encryptionService->encrypt($word);
        }
    
        // Последнее слово — keyword
        $seedWords['word23'] = $encryptionService->encrypt($pendingUser['keyword']);
        $seedWords['user_id'] = $user->id;
    
        // Сохраняем сид
        Seed::create($seedWords);
    
        // Создаём кошелёк без начисления
        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0] // баланс = 0, если без начисления
        );
    
        // Авторизуем
        Auth::login($user);
    
        // Удаляем временные данные
        $request->session()->forget('pending_user');
    
        // Перенаправляем на создание профиля
        return redirect()->route('user_profile.create')->with('info', __('message.please_complete_profile'));
    }
    

    protected function generateRandomWordsFromFile($filePath, $count)
    {
        $words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        shuffle($words);
        return array_slice($words, 0, $count);
    }
}