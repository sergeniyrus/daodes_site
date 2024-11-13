<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Offers; // Добавляем модель Offer
use App\Models\CategoryOffers; // Добавляем модель CategoryOffers
use Illuminate\Support\Facades\Log;

class OffersController extends Controller
{
    // Метод для отображения всех предложений в DAO
    public function dao()
    {
        $offers = DB::table('offers')->get();
        return view('dao')->with('offers', $offers);
    }

    // Метод для отображения всех предложений
    public function offers()
    {
        $offers = DB::table('offers')->get();
        return view('offers')->with('offers', $offers);
    }

    // Метод для отображения конкретного предложения по ID
    public function offer($id)
    {
        $offer = DB::table('offers')->where('id', $id)->first();
        return view('offer')->with('offer', $offer);
    }

    // Метод для отображения формы добавления нового предложения
    public function add()
    {
        $category = DB::table('category_offers')->get();
        return view('offers.add')->with('category_offers', $category);
    }

    // Метод для создания нового предложения
    public function create(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        Log::info('$validate');
        
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

    // Метод для отображения формы редактирования предложения
    public function edit($id)
    {
        $offer = Offers::findOrFail($id); // Получаем предложение через модель Offer
        $categories = DB::table('category_offers')->get(); // Получаем категории
        return view('offers.edit', compact('offer', 'categories'));
    }

    // Метод для обновления предложения
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

    // Метод для отображения всех категорий предложений
    public function categoryIndex()
    {
        $categories = CategoryOffers::all(); // Используем модель CategoryOffers
        return view('offers.categories.index', compact('categories'));
    }

    // Метод для отображения формы создания новой категории
    public function categoryCreate()
    {
        return view('offers.categories.create');
    }

    // Метод для сохранения новой категории
    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_offers,name',
        ]);

        try {
            CategoryOffers::create(['name' => $request->name]); // Используем модель CategoryOffers
        } catch (\Exception $e) {
            \Log::error('Ошибка при добавлении категории: ' . $e->getMessage());
            return back()->withErrors(['name' => 'Не удалось добавить категорию.']);
        }

        return redirect()->route('offerscategories.index')->with('success', 'Категория добавлена');
    }

    // Метод для отображения формы редактирования категории
    public function categoryEdit($id)
    {
        $category = CategoryOffers::findOrFail($id); // Используем модель CategoryOffers
        return view('offers.categories.edit', compact('category'));
    }

    // Метод для обновления категории
    public function categoryUpdate(Request $request, $id)
    {
        $category = CategoryOffers::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:category_offers,name,' . $id,
        ]);

        try {
            $category->update(['name' => $request->name]);
            return redirect()->route('offerscategories.index')->with('success', 'Категория обновлена');
        } catch (\Exception $e) {
            \Log::error('Ошибка при обновлении категории: ' . $e->getMessage());
            return redirect()->back()->withErrors(['name' => 'Не удалось обновить категорию. Пожалуйста, попробуйте еще раз.']);
        }
    }

    // Метод для удаления категории
    public function categoryDestroy($id)
    {
        $category = CategoryOffers::findOrFail($id);
        $category->delete();

        return redirect()->route('offerscategories.index')->with('success', 'Категория удалена');
    }

    // Метод для загрузки изображений на IPFS
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
