<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CommentOffer; // Модель для таблицы comments_offers
use App\Models\CommentNews;  // Модель для таблицы comments_news

class CommentController extends Controller
{
    public function offers(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'text' => 'required|string|max:255',
            'offer_id' => 'required|exists:offers,id',
        ]);

        // Логируем данные запроса для отладки
        // Log::info($request->all());

        // Добавляем новый комментарий в таблицу comments_offers с user_id текущего пользователя
        CommentOffer::create([
            'offer_id' => $request->offer_id,
            'user_id' => Auth::id(),
            'text' => $request->text,
            'created_at' => now(), // Устанавливаем текущее время
        ]);

        return redirect()->back()->with('success', __('message.comment_added'));
    }

    public function news(Request $request)
{
    // Валидация входящих данных
    $request->validate([
        'text' => 'required|string',
        'news_id' => 'required|exists:news,id',
    ]);

    // Декодируем HTML-сущности из текста комментария
    $text = html_entity_decode($request->text);

    // Добавляем новый комментарий в таблицу comments_news с user_id текущего пользователя
    CommentNews::create([
        'news_id' => $request->news_id,
        'user_id' => Auth::id(),
        'text' => $text,
        'created_at' => now(), // Устанавливаем текущее время
    ]);

    return redirect()->back()->with('success', __('message.comment_added'));
}

}
