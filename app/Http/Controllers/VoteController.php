<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vote' => 'required|in:1,2',
            'offer_id' => 'required|exists:offers,id',
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        $offer_id = $request->input('offer_id');

        // Проверка на существующий голос
        $hasVoted = DB::table('offer_votes')
            ->where('offer_id', $offer_id)
            ->where('user_id', $user_id)
            ->exists();

        if (!$hasVoted) {
            DB::table('offer_votes')->insert([
                'offer_id' => $offer_id,
                'user_id' => $user_id,
                'vote' => $request->input('vote'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Перенаправление на указанный URL после голосования
        return redirect()->to('https://daodes.space/offers/' . $offer_id);
    }

    
}
