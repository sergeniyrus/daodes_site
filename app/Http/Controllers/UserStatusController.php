<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserStatusController extends Controller
{
    /**
     * Отмечаем пользователя online.
     * Вызывается, когда вкладка загружена.
     */
    public function online(Request $request)
    {
        if (Auth::check()) {
            DB::table('users')
                ->where('id', Auth::id())
                ->update([
                    'last_seen_at' => now()
                ]);
        }

        return response()->noContent(); // 204
    }
}
