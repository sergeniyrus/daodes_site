<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\CategoryOffers;

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
        return view('offers.add')->with('category_offers', $category);
    }

    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        $img = $request->hasFile('filename')
            ? $this->uploadToIPFS($request->file('filename'))
            : 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico';

        DB::table('offers')->insert([
            'created_at' => now(),
            'title' => $validate['title'],
            'content' => $validate['content'],
            'category_id' => $validate['category'],
            'user_id' => Auth::id(),
            'img' => $img,
            'views' => 0
        ]);

        $id = DB::table('offers')->latest()->value('id');
        return redirect()->route('good', ['post' => 'offers', 'id' => $id, 'action' => 'create']);
    }

    public function edit($id)
    {
        $offer = DB::table('offers')->where('id', $id)->first();
        return view('offers.edit')->with('offer', $offer);
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

        DB::table('offers')->where('id', $id)->update([
            'updated_at' => now(),
            'title' => $validate['title'],
            'content' => $validate['content'],
            'category_id' => $validate['category'],
            'user_id' => Auth::id(),
            'img' => $img,
        ]);

        return redirect()->route('good', ['post' => 'offers', 'id' => $id, 'action' => 'edit']);
    }

    
    public function categoryIndex()
    {
        $categories = DB::table('category_offers')->get();
        return view('offers.categories.index')->with('categories', $categories);
    }

    public function categoryCreate()
    {
        return view('offers.categories.create');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_offers',
        ]);

        DB::table('category_offers')->insert(['name' => $request->name]);

        return redirect()->route('offers.categories.index')->with('success', 'Категория добавлена');
    }

    public function categoryEdit($id)
    {
        $category = DB::table('category_offers')->where('id', $id)->first();
        return view('offers.categories.edit')->with('category', $category);
    }

    public function categoryUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_offers,name,' . $id,
        ]);

        DB::table('category_offers')->where('id', $id)->update(['name' => $request->name]);

        return redirect()->route('offers.categories.index')->with('success', 'Категория обновлена');
    }

    public function categoryDestroy($id)
    {
        DB::table('category_offers')->where('id', $id)->delete();

        return redirect()->route('offers.categories.index')->with('success', 'Категория удалена');
    }

    private function uploadToIPFS($file)
    {
        $client = new Client(['base_uri' => 'https://daodes.space']);

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
