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
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        $img = 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico';

        if ($request->hasFile('filename')) {
            $img = $this->uploadToIPFS($request->file('filename'));
        }

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
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        $img = DB::table('offers')->where('id', $id)->value('img');

        if ($request->hasFile('filename')) {
            $img = $this->uploadToIPFS($request->file('filename'));
        }

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

    
    private function uploadToIPFS($file)
    {
        $client = new Client([
            'base_uri' => 'https://daodes.space',
            'headers' => [
                // Здесь нет необходимости в авторизации, если вы используете свой собственный сервер
            ]
        ]);

        $response = $client->request('POST', '/api/v0/add', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ]
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return 'https://daodes.space/ipfs/' . $data['Hash'];
    }
}
