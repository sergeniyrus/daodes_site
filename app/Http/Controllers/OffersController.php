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
use Illuminate\Support\Facades\Validator;

class OffersController extends Controller
{
    public function index(Request $request)
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

        // Количество комментариев для предложения
        $commentCount = [];
        foreach ($offers as $item) {
            $commentCount[$item->id] = DB::table('comments_offers')
                ->where('offer_id', $item->id)
                ->count();
        }

        // Передаем данные в представление
        return view('offers.index', compact('offers', 'commentCount', 'categories', 'sort', 'category', 'perPage', 'state', 'statesWithOffers'));
    }

    public function show($id)
    {
        // Получаем предложение по ID
        $offers = DB::table('offers')->where('id', $id)->first();

        if ($offers) {
            // Увеличиваем количество просмотров на 1
            DB::table('offers')->where('id', $id)->increment('views', 1);

            // Получаем название категории по category_id
            $categoryName = DB::table('category_offers')
                ->where('id', $offers->category_id)
                ->value('name'); // Получаем значение поля 'category_name'
            
            // Получаем комментарии для этой предложение
            $comments = DB::table('comments_offers')
                ->where('offer_id', $offers->id)
                ->orderBy('created_at', 'desc') // Сортировка по дате (от новых к старым)
                ->get();

            // Получаем количество комментариев
            $commentCount = $comments->count();

            // Возвращаем представление с данными
            return view('offers.show', compact('offers', 'categoryName', 'comments', 'commentCount'));
        } else {
            // Если предложение не найдена, возвращаем ошибку или пустой результат
            return abort(404);
        }
    }

    public function create()
    {
        $category = DB::table('category_offers')->get();
        return view('offers.create')->with('category_offers', $category);
    }

    public function store(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        // Log::info('$validate');
        
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
        $offer = Offers::findOrFail($id); // Получаем предложение через модель Offer
        $categories = DB::table('category_offers')->get(); // Получаем категории
        return view('offers.edit', compact('offer', 'categories'));
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

    public function destroy($id): RedirectResponse
{
    // Находим запись
    $offer = DB::table('offers')->where('id', $id)->first();
    
    // Проверяем, существует ли запись
    if (!$offer) {
        return redirect()->back()->with('error', 'Предложение не найдено');
    }
    
    // Проверяем права пользователя
    $user = Auth::user();
    $isAuthor = $user->id == $offer->user_id;
    $isAdminOrModerator = $user->access_level >= 3;
    
    if (!$isAuthor && !$isAdminOrModerator) {
        return redirect()->back()->with('error', 'У вас нет прав на удаление');
    }
    
    // Удаляем запись
    DB::table('offers')->where('id', $id)->delete();
    
    return redirect()->route('offers.index')->with('success', 'Предложение успешно удалено');
}


public function categoryCreate()
    {
        return view('offers.categories.create');
    }

   public function categoryStore(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:category_offers,name',
            'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
        ],
    ], [
        'name.required' => __('admin_offers.validation.name_required'),
        'name.string' => __('admin_offers.validation.name_string'),
        'name.max' => __('admin_offers.validation.name_max'),
        'name.unique' => __('admin_offers.validation.name_taken'),
        'name.regex' => __('admin_offers.validation.name_regex'),
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
            'message' => __('message.validation_error')
        ], 422);
    }

    try {
        $category = CategoryOffers::create(['name' => $request->name]);
        
        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name
            ],
            'message' => __('message.offer_category_added')
        ]);

    } catch (\Exception $e) {
        \Log::error('Ошибка при добавлении категории: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => __('message.offer_category_add_failed'),
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function categoryEdit($id)
    {
        $category = CategoryOffers::findOrFail($id); // Используем модель CategoryOffers
        return view('offers.categories.edit', compact('category'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = CategoryOffers::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:category_offers,name,' . $id,
        ]);

        try {
            $category->update(['name' => $request->name]);
            return redirect()->route('offerscategories.index')->with('success', __('message.offer_category_updated'));
        } catch (\Exception $e) {
            \Log::error('Ошибка при обновлении категории: ' . $e->getMessage());
            return redirect()->back()->withErrors(['name' => __('message.offer_category_update_failed')]);
        }
    }

    public function categoryDestroy($id)
    {
        $category = CategoryOffers::findOrFail($id);
        $category->delete();

        return redirect()->route('offerscategories.index')->with('success', __('message.offer_category_deleted'));
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
