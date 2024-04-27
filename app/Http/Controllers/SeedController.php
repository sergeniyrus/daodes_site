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

class NewsController extends Controller
{
//     public function news()
//     {
//         $news = DB::table('news')->get();
//         return view('news')->with('news', $news);
//     }

//     public function add()
//     {
//         $category = DB::table('category_news')->get();
//         return view('news._add')->with('category_news', $category);
//     }

//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         //
//     }

//     /**
//      * Show the form for creating a new resource.
//      */

//     public function create(Request $request): RedirectResponse
//     {
//         $validate = $request->validate([
//             'title' => ['required', 'string', 'max:255'],
//             'text' => ['required', 'string'],
//             'category' => ['required', 'integer'],
            
//         ]);

//         if ($request->hasFile('filename')) {
//             $img = $request->file('filename')->store('public');

//         } else {
//             $img = 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico'; // или присвоить другое значение по умолчанию
//         }

//         $user = Auth::user();
//         $userName = $user->name;

//         DB::table('news')->insert([
//             'created_at' => date("Y-m-d H:i"),
//             'title' => $validate['title'],
//             'text' => $validate['text'],
//             'category_id' => $validate['category'],
//             'author' => $userName,
//             'img' => $img,
//             'views' => 0
//         ]);

//         $id = DB::table('news')->latest()->value('id');
//         $post = 'news';
//         $action = 'create';

//         return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
//     }


//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         //
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(string $id)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit($id)
//     {
//         $new = DB::table('news')
//         ->where('id', $id)
//         ->first();
//         return view('news._edit')->with('new', $new);

//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, $id)
// {
//     $validate = $request->validate([
//         'title' => ['required', 'string', 'max:255'],
//         'text' => ['required', 'string'],
//         'category' => ['required', 'integer'],
//     ]);

//     DB::table('news')
//         ->where('id', $id)
//         ->update([
//             'updated_at' => date("Y-m-d H:i"),
//             'title' => $validate['title'],
//             'text' => $validate['text'],
//             'category_id' => $validate['category']
//         ]);
        
//         $post = 'news';
//         $action = 'edit';
//         return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
        
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(string $id)
//     {
//         //
//     }
}
