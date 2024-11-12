<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'text' => 'required|string|max:255',
        'offer_id' => 'required|exists:offers,id',
    ]);

    // Логируем данные запроса для отладки
    Log::info($request->all());

    // Добавляем новый комментарий с user_id текущего пользователя
    Comment::create([
        'offer_id' => $request->offer_id,
        'user_id' => Auth::id(),
        'text' => $request->text,
    ]);

    return redirect()->back()->with('success', 'Комментарий добавлен.');
}

}

