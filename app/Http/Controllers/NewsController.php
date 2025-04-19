<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\News;
use App\Models\CategoryNews;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{

    public function list(Request $request)
    {
        // Получаем параметры фильтрации и сортировки
        $sort = $request->input('sort', 'new'); // new (по умолчанию) или old
        $category = $request->input('category', null); // ID категории или null
        $perPage = $request->input('perPage', 5); // Количество записей на страницу

        // Определяем текущую локаль
        $locale = app()->getLocale(); // 'ru' или 'en'

        // Определяем поля заголовка и контента в зависимости от локали
        $titleField = $locale === 'ru' ? 'title_ru' : 'title_en';
        $contentField = $locale === 'ru' ? 'content_ru' : 'content_en';

        // Запрос к базе данных с учетом фильтров
        $query = DB::table('news')
            ->select('id', 'category_id', 'created_at', 'img', $titleField . ' as title', $contentField . ' as content', 'views')
            ->when($category, function ($query) use ($category) {
                return $query->where('category_id', $category);
            })
            ->orderBy('created_at', $sort === 'new' ? 'desc' : 'asc');

        // Получаем пагинированный результат
        $news = $query->paginate($perPage);

        // Получаем категории с переводом по локали
        $categoryNameField = $locale === 'ru' ? 'name_ru' : 'name_en';
        $categories = DB::table('category_news')->pluck($categoryNameField, 'id');

        // Количество комментариев для каждой новости
        $commentCount = [];
        foreach ($news as $item) {
            $commentCount[$item->id] = DB::table('comments_news')
                ->where('news_id', $item->id)
                ->count();
        }

        // Передаём данные в представление
        return view('news.list', compact('news', 'commentCount', 'categories', 'sort', 'category', 'perPage'));
    }



    public function show($id)
    {
        // Определяем текущую локаль
        $locale = app()->getLocale();
        $titleField = $locale === 'ru' ? 'title_ru' : 'title_en';
        $contentField = $locale === 'ru' ? 'content_ru' : 'content_en';

        $news = DB::table('news')
            ->select(
                'id',
                'category_id',
                'created_at',
                'img',
                'views',
                $titleField . ' as title',
                $contentField . ' as content'
            )
            ->where('id', $id)
            ->first();

        if ($news) {
            // Увеличиваем количество просмотров на 1
            DB::table('news')->where('id', $id)->increment('views', 1);

            // Получаем название категории по category_id
            $categoryName = DB::table('category_news')
                ->where('id', $news->category_id)
                ->value($locale === 'ru' ? 'name_ru' : 'name_en'); // Получаем значение категории на основе локали

            // Получаем комментарии для этой новости
            $comments = DB::table('comments_news')
                ->where('news_id', $news->id)
                ->orderBy('created_at', 'desc') // Сортировка по дате (от новых к старым)
                ->get();

            // Получаем количество комментариев
            $commentCount = $comments->count();

            // Возвращаем представление с данными
            return view('news.show', compact('news', 'categoryName', 'comments', 'commentCount'));
        } else {
            // Если новость не найдена, возвращаем ошибку или пустой результат
            return abort(404);
        }
    }


    public function create()
    {
        $category = DB::table('category_news')->get();
        return view('news.create')->with('category_news', $category);
    }

    public function store(Request $request): RedirectResponse
    {
        // Валидация данных
        $validated = $request->validate([
            'title_ru'    => ['required', 'string', 'max:255'],
            'content_ru'  => ['required', 'string'],
            'title_en'    => ['required', 'string', 'max:255'],
            'content_en'  => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:category_news,id'],
            'filename'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
        ]);

        try {
            // Загрузка изображения на IPFS или использование дефолтного
            $imageUrl = $request->hasFile('filename')
                ? $this->uploadToIPFS($request->file('filename'))
                : 'https://daodes.space/ipfs/QmdAFB8mUSW5zBCKAuJpSBMwYkFSo4apApN9Y6V8iRW2o6';

            // Создание новости с использованием новых полей
            $news = News::create([
                'title_ru'   => $validated['title_ru'],
                'content_ru' => $validated['content_ru'],
                'title_en'   => $validated['title_en'],
                'content_en' => $validated['content_en'],
                'category_id' => $validated['category_id'],
                'user_id'    => Auth::id(),
                'img'        => $imageUrl,
                'views'      => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Перенаправление с сообщением об успехе
            // Редирект на просмотр оффера
            return redirect()->route('news.show', $news->id)
                ->with('success', __('message.news_created_success'));
        } catch (\Exception $e) {
            Log::error('News creation error: ' . $e->getMessage());
            return back()->withErrors(['error' => __('message.news_creation_failed')]);
        }
    }



    public function edit($id)
    {
        // Поиск новости по ID
        $news = News::findOrFail($id);

        // Загрузка всех категорий новостей
        $category_news = CategoryNews::all();

        // Передаем в представление URL изображения
        return view('news.edit', compact('news', 'category_news'))->with('existingImageUrl', $news->img);
    }




    public function update(Request $request, $id): RedirectResponse
    {
        // Валидация данных
        $validated = $request->validate([
            'title_ru'    => ['required', 'string', 'max:255'],
            'content_ru'  => ['required', 'string'],
            'title_en'    => ['required', 'string', 'max:255'],
            'content_en'  => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:category_news,id'],
            'filename'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
        ]);

        try {
            // Поиск новости по ID
            $news = News::findOrFail($id);

            // Загрузка изображения на IPFS, если оно было загружено
            $imageUrl = $request->hasFile('filename')
                ? $this->uploadToIPFS($request->file('filename'))
                : $news->img; // Используем старое изображение, если новое не загружено

            // Обновление данных новости
            $news->update([
                'title_ru'   => $validated['title_ru'],
                'content_ru' => $validated['content_ru'],
                'title_en'   => $validated['title_en'],
                'content_en' => $validated['content_en'],
                'category_id' => $validated['category_id'],
                'img'        => $imageUrl,
                'updated_at' => now() // Обновляем дату изменения
            ]);

            // Перенаправление с сообщением об успехе
            return redirect()->route('news.show', ['id' => $news->id])
                ->with('success', __('admin_news.news_updated_success'));
        } catch (\Exception $e) {
            Log::error('News update error: ' . $e->getMessage());
            return back()->withErrors(['error' => __('message.news_update_failed')]);
        }
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('news.index')->with('success', __('message.news_deleted'));
    }


    private function uploadToIPFS($file)
    {
        $client = new Client([
            'base_uri' => 'https://daodes.space'
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

        // Получаем URL-адрес файла на IPFS
        $data = json_decode($response->getBody()->getContents(), true);
        return 'https://daodes.space/ipfs/' . $data['Hash'];
    }





    public function categoryIndex()
    {
        $categories = CategoryNews::all();
        return view('news.categories.index', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('news.categories.create');
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ru' => [
                'required',
                'string',
                'max:255',
                'unique:category_news,name_ru',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ],
            'name_en' => [
                'required',
                'string',
                'max:255',
                'unique:category_news,name_en',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ]
        ], [
            'name_ru.required' => __('admin_news.validation.name_required'),
            'name_en.required' => __('admin_news.validation.name_required'),
            'name_ru.string' => __('admin_news.validation.name_string'),
            'name_en.string' => __('admin_news.validation.name_string'),
            'name_ru.max' => __('admin_news.validation.name_max'),
            'name_en.max' => __('admin_news.validation.name_max'),
            'name_ru.unique' => __('admin_news.validation.name_taken'),
            'name_en.unique' => __('admin_news.validation.name_taken'),
            'name_ru.regex' => __('admin_news.validation.name_regex'),
            'name_en.regex' => __('admin_news.validation.name_regex'),
        ]);

        if ($validator->fails()) {
            // Возвращаем ошибки в формате JSON
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Сохраняем категорию с новыми полями
            $category = CategoryNews::create([
                'name_ru' => $request->name_ru,
                'name_en' => $request->name_en
            ]);

            // Возвращаем успешный ответ с сообщением
            return response()->json([
                'success' => true,
                'message' => __('message.category_added_success'),
                'category' => $category
            ]);
        } catch (\Exception $e) {
            // В случае ошибки, возвращаем ответ с ошибкой
            return response()->json([
                'success' => false,
                'message' => __('message.category_add_failed')
            ], 500); // 500 Internal Server Error
        }
    }




    public function categoryEdit($id)
    {
        $category = CategoryNews::findOrFail($id);
        return view('news.categories.edit', compact('category'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = CategoryNews::findOrFail($id);

        $request->validate([
            'name_ru' => 'required|string|max:255|unique:category_news,name_ru,' . $id,
            'name_en' => 'required|string|max:255|unique:category_news,name_en,' . $id,
        ]);

        try {
            $category->update([
                'name_ru' => $request->name_ru,
                'name_en' => $request->name_en,
            ]);

            return redirect()->route('newscategories.index')->with('success', __('message.category_updated_success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => __('message.category_update_failed')]);
        }
    }


    public function categoryDestroy($id)
    {
        $category = CategoryNews::findOrFail($id);
        $category->delete();

        return redirect()->route('newscategories.index')->with('success', __('message.category_deleted_success'));
    }
}
