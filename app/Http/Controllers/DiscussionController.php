<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        
        
        // Проверяем, что vote имеет допустимое значение (0 или 1)
        $request->validate([
            'vote' => 'required|in:1,0',
            'offer_id' => 'required|exists:offers,id',
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        $offer_id = $request->input('offer_id');
        $vote = $request->input('vote');

        // Вставляем голос в таблицу discussions
        DB::table('discussions')->updateOrInsert(
            ['offer_id' => $offer_id, 'user_id' => $user->id],
            ['vote' => $vote, 'created_at' => now(), 'updated_at' => now()]
        );

        return redirect()->to('https://daodes.space/page/offers/' . $offer_id);
    }

}

