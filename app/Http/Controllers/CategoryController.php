<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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

    public function add($post)
    {
        $category = DB::table("category_{$post}")->get();
        return view('category._add')->with(['category_offers' => $category, 'post' => $post]);

    }
    
    public function create(Request $request): RedirectResponse
{
    $validatedData = $request->validate([
        'category' => ['required', 'string'],
        'page' => ['string'],
    ]);

    $user = Auth::user();
    $userName = $user->name;

    if ($validatedData['page'] == "offers") {
        $tableName = "category_offers";
        $post = 'offers';
    } elseif ($validatedData['page'] == "news") {
        $tableName = "category_news";
        $post = 'news';
    }

    DB::table($tableName)->insert([
        'category_name' => $validatedData['category'],
        'author' => $userName,
    ]);

    $latestId = DB::table($tableName)->latest('id')->value('id');

    $action = 'createCat';

    return redirect()->route('good', ['post' => $post, 'id' => $latestId, 'action' => $action]);
}

    public function edit($post, $id)
    {
        if ($post == "offers") {
            $tableName = "category_offers";
            
        } elseif ($post == "news") {
            $tableName = "category_news";
            $post = 'news';
        }


        $text = DB::table($tableName)
        ->where('id', $id)
        ->first();
        return view('category._edit')->with(['text'=> $text, 'post'=>$post]);

    }

    public function update(Request $request)
{
    $validatedData = $request->validate([
        'category' => ['required', 'string'],
        'page' => ['string'],
        'ids' => ['integer'],
    ]);

    if ($validatedData['page'] == "offers") {
        $tableName = "category_offers";
        $post = "offers";
    } elseif ($validatedData['page'] == "news") {
        $tableName = "category_news";
        $post = "news";
    }

    $id = $validatedData['ids'];

    DB::table($tableName)
        ->where('id', $id)
        ->update([
            'category_name' => $validatedData['category']
        ]);
        
        $action = 'editCat';
        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
        
    }


}
