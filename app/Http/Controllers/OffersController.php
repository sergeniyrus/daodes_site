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

class OffersController extends Controller
{
    public function dao() {        
        $offers = DB::table('offers')->get();
        return view('dao')->with('offers', $offers);
    }


    public function offers() {        
        $offers = DB::table('offers')->get();
        return view('offers')->with('offers', $offers);
    }

    public function offer($id) 
    {
        $offer = DB::table('offers')->where('id', $id)->first(); // Use first() to get a single record
        return view('offer')->with('offer', $offer);
        
    }

    public function add()
    {
        $category = DB::table('category_offers')->get();
        return view('offers._add')->with('category_offers', $category);
    }
    
    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
            'category' => ['required', 'integer'],
            
        ]);

        if ($request->hasFile('filename')) {
            $img = $request->file('filename')->store('public');

        } else {
            $img = 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico'; // или присвоить другое значение по умолчанию
        }

        $user = Auth::user();
        $userName = $user->name;

        DB::table('offers')->insert([
            'created_at' => date("Y-m-d H:i"),
            'title' => $validate['title'],
            'text' => $validate['text'],
            'category_id' => $validate['category'],
            'author' => $userName,
            'img' => $img,
            'views' => 0
        ]);

        $id = DB::table('offers')->latest()->value('id');
        $post = 'offers';
        $action = 'create';

        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
    }
    public function edit($id)
    {
        $offer = DB::table('offers')
        ->where('id', $id)
        ->first();
        return view('offers._edit')->with('offer', $offer);

    }

    public function update(Request $request, $id)
{
    $validate = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'text' => ['required', 'string'],
        'category' => ['required', 'integer'],
    ]);

    DB::table('offers')
        ->where('id', $id)
        ->update([
            'updated_at' => date("Y-m-d H:i"),
            'title' => $validate['title'],
            'text' => $validate['text'],
            'category_id' => $validate['category']
        ]);
        
        $post = 'offers';
        $action = 'edit';
        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
        
    }


}
