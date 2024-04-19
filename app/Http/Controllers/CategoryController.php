<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function category_sort($post, $id)
    {
        if ($post == 'news') {
            $texts = DB::table('news')
            ->where('category_id', $id)
            ->get();
            

        }

        if ($post == 'offers') {
            $texts = DB::table('offers')
            ->where('category_id', $id)
            ->get();
            }
            return view('category')->with(['texts' => $texts, 'post' => $post]);

    }
}
