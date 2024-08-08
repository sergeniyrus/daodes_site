<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'id_offer' => 'required|exists:offers,id',
        ]);

        Comment::create([
            'id_offer' => $request->id_offer,
            'id_user' => Auth::id(),
            'text' => $request->text,
        ]);

        return redirect()->back()->with('success', 'Комментарий добавлен.');
    }
}

