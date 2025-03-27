<?php
namespace App\Http\Controllers;

//Подсчет непрочитанных уведомлений в постоянную, индикатор в меню
class NotificationController extends Controller
{
    public function unreadCount()
    {
        if (auth()->check()) {
            $unreadCount = Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();
            return response()->json(['unreadCount' => $unreadCount]);
        }
        return response()->json(['unreadCount' => 0]);
    }
}