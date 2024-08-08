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
        $id_offer = $request->input('offer_id');

        // Проверка на существующий голос
        $hasVoted = DB::table('vote_users')
            ->where('id_offer', $id_offer)
            ->where('id_user', $user_id)
            ->exists();

        if (!$hasVoted) {
            DB::table('vote_users')->insert([
                'id_offer' => $id_offer,
                'id_user' => $user_id,
                'vote' => $request->input('vote'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Перенаправление на указанный URL после голосования
        return redirect()->to('https://daodes.space/page/offers/' . $id_offer);
    }

    
}
