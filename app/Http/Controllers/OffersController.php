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
    $categories = CategoryOffers::all(); // Используем модель CategoryOffers
    return view('offers.categories.index', compact('categories'));
}

// Метод для отображения формы создания категории
public function categoryCreate()
{
    return view('offers.categories.create');
}

// Метод для сохранения новой категории
public function categoryStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:category_offers,category_name',
    ]);

    try {
        CategoryOffers::create(['category_name' => $request->name]); // Используем модель CategoryOffers
    } catch (\Exception $e) {
        // Записываем ошибку в лог для диагностики
        \Log::error('Ошибка при добавлении категории: ' . $e->getMessage());
        return back()->withErrors(['name' => 'Не удалось добавить категорию.']);
    }

    return redirect()->route('offerscategories.index')->with('success', 'Категория добавлена');
}

// Метод для редактирования категории
public function categoryEdit($id)
{
    $category = CategoryOffers::findOrFail($id); // Используем модель CategoryOffers
    return view('offers.categories.edit', compact('category'));
}

// Метод для обновления категории
public function categoryUpdate(Request $request, $id)
{
    $category = CategoryOffers::findOrFail($id); // Используем модель CategoryOffers

    $request->validate([
        'name' => 'required|string|max:255|unique:category_offers,category_name,' . $id,
    ]);

    try {
        // Обновление категории
        $category->update(['category_name' => $request->name]);
        return redirect()->route('offerscategories.index')->with('success', 'Категория обновлена');
    } catch (\Exception $e) {
        // Запись ошибки в лог
        \Log::error('Ошибка при обновлении категории: ' . $e->getMessage());
        // Сообщение об ошибке
        return redirect()->back()->withErrors(['name' => 'Не удалось обновить категорию. Пожалуйста, попробуйте еще раз.']);
    }
}

// Метод для удаления категории
public function categoryDestroy($id)
{
    $category = CategoryOffers::findOrFail($id); // Используем модель CategoryOffers
    $category->delete();

    return redirect()->route('offerscategories.index')->with('success', 'Категория удалена');
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
