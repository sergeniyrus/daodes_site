<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Seed;


class SeedController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userName = $user->name;
        $user_id = auth()->id(); // Получение ID текущего пользователя

        $keyword = DB::table('users')
            ->where('name', $userName)
            ->value('keyword');

        $onseedExists = DB::table('seed')
            ->where('user_id', $user_id)
            ->exists();

        if ($onseedExists) {
            $message = "The seed phrase has already been received.";
            return view('seed', [
                'keyword' => $keyword,
                'message' => $message,
            ]);
        } else {
            $words = $this->generateRandomWordsFromFile(public_path('base.txt'), 23);
            return view('seed', [
                'keyword' => $keyword,
                'words' => $words,
            ]);
        }
    }

    protected function generateRandomWordsFromFile($filePath, $count)
    {
        $words = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        shuffle($words);
        return array_slice($words, 0, $count);
    }

    // Сохранение сид-фразы
    public function saveSeed(Request $request)
    {
        $user_id = auth()->id(); // Получение ID текущего пользователя
        $user = Auth::user();
        $userName = $user->name;

        $keyword = DB::table('users')
            ->where('name', $userName)
            ->value('keyword');

        $seedWords = $request->only([
            'word0', 'word1', 'word2', 'word3', 'word4',
            'word5', 'word6', 'word7', 'word8', 'word9',
            'word10', 'word11', 'word12', 'word13', 'word14',
            'word15', 'word16', 'word17', 'word18', 'word19',
            'word20', 'word21', 'word22'
        ]);
        $seedWords['word23'] = $keyword; // Добавили $keyword вместо 'word23'

        $seedWords['user_id'] = $user_id;

        $storedSeedExists = Seed::where('user_id', $user_id)->exists();

        if ($storedSeedExists) {
            return redirect()->back()->with('error', 'The seed phrase has been saved.');
        }

        Seed::create($seedWords);
        $success = 'Congratulations on registering!';

        return redirect()->back()->with('success', $success);
    }
}
