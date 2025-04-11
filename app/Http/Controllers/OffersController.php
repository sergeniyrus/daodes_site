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
use Illuminate\Support\Facades\Validator;

class OffersController extends Controller
{
    public function index(Request $request)
    {
        $sort     = $request->input('sort', 'new');
        $category = $request->input('category');
        $perPage  = $request->input('perPage', 5);
        $state    = $request->input('state');

        // Локаль и выбор нужных полей
        $locale = app()->getLocale();
        $titleField   = $locale === 'ru' ? 'title_ru' : 'title_en';
        $contentField = $locale === 'ru' ? 'content_ru' : 'content_en';

        // Сборка запроса
        $query = DB::table('offers')
            ->select('id', 'category_id', 'created_at', 'img', 'views', $titleField . ' as title', $contentField . ' as content')
            ->when($category, fn($q) => $q->where('category_id', $category))
            ->when($state !== null, fn($q) => $q->where('state', $state))
            ->orderBy('created_at', $sort === 'new' ? 'desc' : 'asc');

        $offers = $query->paginate($perPage);

        // Категории с переводом
        $categoryNameField = $locale === 'ru' ? 'name_ru' : 'name_en';
        $categories = DB::table('category_offers')->pluck($categoryNameField, 'id');

        // Статусы, по которым есть предложения
        $statesWithOffers = DB::table('offers')
            ->select('state', DB::raw('COUNT(*) as count'))
            ->groupBy('state')
            ->pluck('count', 'state');

        // Кол-во комментариев
        $commentCount = [];
        foreach ($offers as $item) {
            $commentCount[$item->id] = DB::table('comments_offers')
                ->where('offer_id', $item->id)
                ->count();
        }

        return view('offers.index', compact(
            'offers',
            'commentCount',
            'categories',
            'sort',
            'category',
            'perPage',
            'state',
            'statesWithOffers'
        ));
    }


    public function show($id)
    {
        $locale = app()->getLocale();
        $titleField   = $locale === 'ru' ? 'title_ru' : 'title_en';
        $contentField = $locale === 'ru' ? 'content_ru' : 'content_en';

        $offers = DB::table('offers')
            ->select('id', 'title_ru', 'title_en', 'content_ru', 'content_en', 'category_id', 'user_id', 'img', 'views', 'created_at', 'state')
            ->where('id', $id)
            ->first();

        if ($offers) {
            // Увеличение просмотров
            DB::table('offers')->where('id', $id)->increment('views', 1);

            // Название категории по локали
            $categoryName = DB::table('category_offers')
                ->where('id', $offers->category_id)
                ->value($locale === 'ru' ? 'name_ru' : 'name_en');

            // Комментарии
            $comments = DB::table('comments_offers')
                ->where('offer_id', $offers->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $commentCount = $comments->count();

            return view('offers.show', compact('offers', 'categoryName', 'comments', 'commentCount',));
        }

        return abort(404);
    }


    public function create()
    {
        $category = DB::table('category_offers')->get();
        return view('offers.create')->with('category_offers', $category);
    }

    public function store(Request $request): RedirectResponse
    {
        // Лог: начало выполнения метода
        Log::info('Создание оффера: начало выполнения метода store()');

        // Валидация данных
        $validated = $request->validate([
            'title_ru'    => ['required', 'string', 'max:255'],
            'content_ru'  => ['required', 'string'],
            'title_en'    => ['required', 'string', 'max:255'],
            'content_en'  => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:category_offers,id'],
            'filename'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
        ]);

        // Лог: данные прошли валидацию
        Log::info('Создание оффера: данные прошли валидацию', $validated);

        try {
            // Лог: начало обработки изображения
            Log::info('Создание оффера: начало обработки изображения');

            // Загрузка изображения на IPFS или использование дефолтного
            $imageUrl = $request->hasFile('filename')
                ? $this->uploadToIPFS($request->file('filename'))
                : 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico';

            // Лог: результат обработки изображения
            Log::info('Создание оффера: URL изображения установлен', ['imageUrl' => $imageUrl]);

            // Создание оффера
            $offers = Offers::create([
                'title_ru'   => $validated['title_ru'],
                'content_ru' => $validated['content_ru'],
                'title_en'   => $validated['title_en'],
                'content_en' => $validated['content_en'],
                'category_id' => (int)$validated['category_id'],
                'user_id'    => Auth::id(),
                'img'        => $imageUrl,
                'views'      => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Лог: оффер успешно создан
            Log::info('Создание оффера: успешно создан', ['offersId' => $offers->id]);

            // Редирект с успехом
            return redirect()->route('good', [
                'post' => 'offers',
                'id' => $offers->id,
                'action' => 'create'
            ])->with('success', __('message.offers_created_success'));
        } catch (\Exception $e) {
            // Лог: ошибка при создании
            Log::error('Ошибка при создании оффера: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => __('message.offers_creation_failed')]);
        }
    }





    public function edit($id)
    {
        // Поиск оффера по ID
        $offer = Offers::findOrFail($id);

        // Загрузка всех категорий офферов
        $categories = CategoryOffers::all();

        // Передаём оффер, категории и существующий URL изображения
        return view('offers.edit', compact('offer', 'categories'))
            ->with('existingImageUrl', $offer->img);
    }


    public function update(Request $request, $id): RedirectResponse
    {
        // Валидация данных
        $validated = $request->validate([
            'title_ru'    => ['required', 'string', 'max:255'],
            'content_ru'  => ['required', 'string'],
            'title_en'    => ['required', 'string', 'max:255'],
            'content_en'  => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:category_offers,id'],
            'filename'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
        ]);

        try {
            // Поиск оффера по ID
            $offer = Offers::findOrFail($id);

            // Загрузка изображения на IPFS, если новое загружено, иначе используем старое
            $imageUrl = $request->hasFile('filename')
                ? $this->uploadToIPFS($request->file('filename'))
                : $offer->img;

            // Обновление данных оффера
            $offer->update([
                'title_ru'    => $validated['title_ru'],
                'content_ru'  => $validated['content_ru'],
                'title_en'    => $validated['title_en'],
                'content_en'  => $validated['content_en'],
                'category_id' => $validated['category_id'],
                'img'         => $imageUrl,
                'updated_at'  => now()
            ]);

            // Перенаправление с сообщением об успехе
            return redirect()->route('offers.show', ['id' => $offer->id])
                ->with('success', __('message.offers_updated_success'));
        } catch (\Exception $e) {
            Log::error('Offers update error: ' . $e->getMessage());
            return back()->withErrors(['error' => __('message.offers_update_failed')]);
        }
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




    public function categoryIndex()
    {
        $categories = CategoryOffers::all();
        return view('offers.categories.index', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('offers.categories.create');
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ru' => [
                'required',
                'string',
                'max:255',
                'unique:category_offers,name_ru',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ],
            'name_en' => [
                'required',
                'string',
                'max:255',
                'unique:category_offers,name_en',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ]
        ], [
            'name_ru.required' => __('admin_offers.validation.name_required'),
            'name_en.required' => __('admin_offers.validation.name_required'),
            'name_ru.string' => __('admin_offers.validation.name_string'),
            'name_en.string' => __('admin_offers.validation.name_string'),
            'name_ru.max' => __('admin_offers.validation.name_max'),
            'name_en.max' => __('admin_offers.validation.name_max'),
            'name_ru.unique' => __('admin_offers.validation.name_taken'),
            'name_en.unique' => __('admin_offers.validation.name_taken'),
            'name_ru.regex' => __('admin_offers.validation.name_regex'),
            'name_en.regex' => __('admin_offers.validation.name_regex'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 422);
        }

        try {
            $category = CategoryOffers::create([
                'name_ru' => $request->name_ru,
                'name_en' => $request->name_en
            ]);

            return response()->json([
                'success' => true,
                'message' => __('message.category_added_success'),
                'category' => $category
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('message.category_add_failed')
            ], 500);
        }
    }


    public function categoryStoreState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ru' => [
                'required',
                'string',
                'max:255',
                'unique:category_offers,name_ru',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ],
            'name_en' => [
                'required',
                'string',
                'max:255',
                'unique:category_offers,name_en',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ]
        ], [
            'name_ru.required' => __('admin_offers.validation.name_required'),
            'name_en.required' => __('admin_offers.validation.name_required'),
            'name_ru.string' => __('admin_offers.validation.name_string'),
            'name_en.string' => __('admin_offers.validation.name_string'),
            'name_ru.max' => __('admin_offers.validation.name_max'),
            'name_en.max' => __('admin_offers.validation.name_max'),
            'name_ru.unique' => __('admin_offers.validation.name_taken'),
            'name_en.unique' => __('admin_offers.validation.name_taken'),
            'name_ru.regex' => __('admin_offers.validation.name_regex'),
            'name_en.regex' => __('admin_offers.validation.name_regex'),
        ]);
    
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin_offers.validation.validation_error'))
                ->withErrors($validator);
        }
    
        try {
            CategoryOffers::create([
                'name_ru' => $request->name_ru,
                'name_en' => $request->name_en
            ]);
            
            Log::info('Создание категории оффера статично');

            return redirect()
                ->route('offerscategories.index')
                ->with('success', __('admin_offers.category.created'));
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin_offers.category.create_error'));
        }
    }


    public function categoryEdit($id)
    {
        $category = Categoryoffers::findOrFail($id);
        return view('offers.categories.edit', compact('category'));
    }


    
    public function categoryUpdate(Request $request, $id)
    {
        $category = CategoryOffers::findOrFail($id);

        $request->validate([
            'name_ru' => 'required|string|max:255|unique:category_offers,name_ru,' . $id,
            'name_en' => 'required|string|max:255|unique:category_offers,name_en,' . $id,
        ]);

        try {
            $category->update([
                'name_ru' => $request->name_ru,
                'name_en' => $request->name_en,
            ]);

            return redirect()->route('offerscategories.index')->with('success', __('message.category_updated_success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => __('message.category_update_failed')]);
        }
    }


    public function categoryDestroy($id)
    {
        $category = Categoryoffers::findOrFail($id);
        $category->delete();

        return redirect()->route('offerscategories.index')->with('success', __('message.category_deleted_success'));
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
