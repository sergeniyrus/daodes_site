<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // Функция извлекает данные из базы данных и выводит их на страницу
    public function home()
    {
        $home = DB::table('news')->get();
        return view('home')->with('home', $home);
    }

    // Функция для отображения конкретного поста
    public function good($post, $id, $action)
    {
        return view('good')->with(['post' => $post, 'id' => $id, 'action' => $action]);
    }
}
