<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function page_sort($post, $id)
    {
        if ($post == 'news') {
            $text = DB::table('news')
            ->where('id', $id)
            ->first();  
            // +1 просмотр к новости
            DB::table('news')->where('id', $id)->increment('views');

        }

        if ($post == 'offers') {
            $text = DB::table('offers')
            ->where('id', $id)
            ->first();
            // +1 просмотр к оферу
            DB::table('offers')->where('id', $id)->increment('views');

        }

    return view('page')->with(['text' => $text, 'post' => $post, 'id' => $id]);

    }
}
