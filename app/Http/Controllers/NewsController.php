<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\News;

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

    public function index()
    {
        //
    }

    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);
        var_dump($validate); // Для проверки данных, прошедших валидацию
        $img = 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico';

        $news = News::create([
            'title' => $validate['title'],
            'content' => $validate['content'],
            'category_id' => $validate['category'],
            'user_id' => Auth::id(),
            'img' => $img,
            'views' => 0
        ]);

        // DB::table('news')->insert([
        //     'created_at' => now(),
        //     'title' => $validate['title'],
        //     'content' => $validate['content'],
        //     'category_id' => $validate['category'],
        //     'user_id' => Auth::id(),
        //     'img' => $img,
        //     'views' => 0
        // ]);

        $id = DB::table('news')->latest()->value('id');
        $post = 'news';
        $action = 'create';

        

        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $new = DB::table('news')->where('id', $id)->first();
        return view('news._edit')->with('new', $new);
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        if ($request->hasFile('filename')) {
            $img = $this->uploadToIPFS($request->file('filename'));
        } else {
            $img = DB::table('news')->where('id', $id)->value('img');
        }

        DB::table('news')->where('id', $id)->update([
            'updated_at' => now(),
            'title' => $validate['title'],
            'content' => $validate['content'],
            'category_id' => $validate['category'],
            'user_id' => Auth::id(),
            'img' => $img
        ]);

        $post = 'news';
        $action = 'edit';
        return redirect()->route('good', ['post' => $post, 'id' => $id, 'action' => $action]);
    }

    public function destroy(string $id)
    {
        //
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
