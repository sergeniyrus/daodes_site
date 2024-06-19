<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NewsController extends Controller
{
    public function news()
    {
        $news = DB::table('news')->get();
        return view('news')->with('news', $news);
    }

    public function add()
    {
        $category = DB::table('category_news')->get();
        return view('news._add')->with('category_news', $category);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048'] // Добавлено правило валидации для изображения
        ]);

        $img = 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico'; // значение по умолчанию

        if ($request->hasFile('filename')) {
            $img = $this->uploadToIPFS($request->file('filename'));
        }

        DB::table('news')->insert([
            'created_at' => now(),
            'title' => $validate['title'],
            'content' => $validate['content'],
            'category_id' => $validate['category'],
            'user_id' => Auth::id(),
            'img' => $img,
            'views' => 0
        ]);

        $id = DB::table('news')->latest()->value('id');
        $post = 'news';
        $action = 'create';

        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $new = DB::table('news')
            ->where('id', $id)
            ->first();
        return view('news._edit')->with('new', $new);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048'] // Добавлено правило валидации для изображения
        ]);

        if ($request->hasFile('filename')) {
            $img = $this->uploadToIPFS($request->file('filename'));
        } else {
            $img = DB::table('news')->where('id', $id)->value('img'); // Сохранить старое значение, если новое не загружено
        }

        DB::table('news')
            ->where('id', $id)
            ->update([
                'updated_at' => now(),
                'title' => $validate['title'],
                'content' => $validate['content'],
                'category_id' => $validate['category'],
                'user_id' => Auth::id(), // если нужно обновлять user_id
                'img' => $img
            ]);

        $post = 'news';
        $action = 'edit';
        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Upload file to IPFS
     */
    private function uploadToIPFS($file)
    {
        $client = new Client(['base_uri' => 'http://localhost:5001']);
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
        return 'http://localhost:8080/ipfs/' . $data['Hash'];
    }
}
