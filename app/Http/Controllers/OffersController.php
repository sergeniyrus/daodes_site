<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Offers; 
use App\Models\CategoryOffers; 
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OffersController extends Controller
{
    
    public function list(Request $request)
{
    // Получаем параметры фильтрации и сортировки
    $sort = $request->input('sort', 'new'); // new (по умолчанию) или old
    $category = $request->input('category', null); // ID категории или null
    $perPage = $request->input('perPage', 5); // Количество записей на страницу
    $state = $request->input('state', null); // Фильтр по статусу

    // Запрос к базе данных с учетом фильтров
    $query = DB::table('offers');

    if ($category) {
        $query->where('category_id', $category);
    }

    if ($state !== null) {
        $query->where('state', $state);
    }

    // Обработка сортировки
    if ($sort === 'new') {
        $query->orderBy('created_at', 'desc');
    } elseif ($sort === 'old') {
        $query->orderBy('created_at', 'asc');
    }

    // Получаем пагинированный результат
    $offers = $query->paginate($perPage);

    // Получаем категории для фильтра
    $categories = DB::table('category_offers')->pluck('name', 'id');

    // Получаем список статусов, по которым есть предложения
    $statesWithOffers = DB::table('offers')
        ->select('state', DB::raw('COUNT(*) as count'))
        ->groupBy('state')
        ->pluck('count', 'state');

    // Количество комментариев для каждой новости
    $commentCount = [];
    foreach ($offers as $item) {
        $commentCount[$item->id] = DB::table('comments_offers')
            ->where('offer_id', $item->id)
            ->count();
    }

    // Передаем данные в представление
    return view('offers.list', compact('offers', 'commentCount', 'categories', 'sort', 'category', 'perPage', 'state', 'statesWithOffers'));
}




    // Метод для отображения конкретного предложения по ID
    // Метод для отображения страницы (для страницы/контента)
    public function show($id)
{
    // Получаем новость по ID
    $offers = DB::table('offers')->where('id', $id)->first();

    if ($offers) {
        // Увеличиваем количество просмотров на 1
        DB::table('offers')->where('id', $id)->increment('views', 1);

        // Получаем название категории по category_id
        $categoryName = DB::table('category_offers')
            ->where('id', $offers->category_id)
            ->value('name'); // Получаем значение поля 'category_name'
        
        // Получаем комментарии для этой новости
        $comments = DB::table('comments_offers')
            ->where('offer_id', $offers->id)
            ->orderBy('created_at', 'desc') // Сортировка по дате (от новых к старым)
            ->get();

        // Получаем количество комментариев
        $commentCount = $comments->count();

        // Возвращаем представление с данными
        return view('offers.show', compact('offers', 'categoryName', 'comments', 'commentCount'));
    } else {
        // Если новость не найдена, возвращаем ошибку или пустой результат
        return abort(404);
    }
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
