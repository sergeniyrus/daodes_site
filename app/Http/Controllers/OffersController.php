<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OffersController extends Controller
{
    public function dao()
    {        
        $offers = DB::table('offers')->get();
        return view('dao')->with('offers', $offers);
    }

    public function offers()
    {        
        $offers = DB::table('offers')->get();
        return view('offers')->with('offers', $offers);
    }

    public function offer($id) 
    {
        $offer = DB::table('offers')->where('id', $id)->first();
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
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
        ]);

        $img = $this->uploadToIPFS($request);

        DB::table('offers')->insert([
            'created_at' => date("Y-m-d H:i"),
            'title' => $validate['title'],
            'content' => $validate['content'],
            'category_id' => $validate['category'],
            'user_id' => Auth::id(),
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
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
        ]);

        $img = $this->uploadToIPFS($request);

        DB::table('offers')
            ->where('id', $id)
            ->update([
                'updated_at' => date("Y-m-d H:i"),
                'title' => $validate['title'],
                'content' => $validate['content'],
                'category_id' => $validate['category'],
                'user_id' => Auth::id(),
                'img' => $img,
            ]);

        $post = 'offers';
        $action = 'edit';
        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
    }

    private function uploadToIPFS($request)
    {
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $client = new Client();
            $response = $client->request('POST', 'http://95.188.118.100:5001/api/v0/add', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName()
                    ]
                ]
            ]);

            $body = json_decode($response->getBody(), true);
            return 'http://95.188.118.100:8080/ipfs/' . $body['Hash'];
        } else {
            return 'http://localhost:8080/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP'; // или присвоить другое значение по умолчанию
        }
    }
}

