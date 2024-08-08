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
            'id_offer' => 'required|exists:offers,id',
        ]);

        $user = Auth::user();
        $id_user = $user->id;
        $id_offer = $request->input('id_offer');
        $vote = $request->input('vote');

        // Вставляем голос в таблицу discussion
        DB::table('discussion')->updateOrInsert(
            ['id_offer' => $id_offer, 'id_user' => $user->id],
            ['vote' => $vote, 'created_at' => now(), 'updated_at' => now()]
        );

        return redirect()->to('https://daodes.space/page/offers/' . $id_offer);
    }

}

