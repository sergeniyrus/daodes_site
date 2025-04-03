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

        // Запрос к базе данных с учетом фильтров
        $query = DB::table('news');

        if ($category) {
            $query->where('category_id', $category);
        }

        $query->orderBy('created_at', $sort === 'new' ? 'desc' : 'asc');

        // Получаем пагинированный результат
        $news = $query->paginate($perPage);

        // Получаем категории для фильтра
        $categories = DB::table('category_news')->pluck('name', 'id');

        // Количество комментариев для каждой новости
        $commentCount = [];
        foreach ($news as $item) {
            $commentCount[$item->id] = DB::table('comments_news')
                ->where('news_id', $item->id)
                ->count();
        }

        // Передаем данные в представление
        return view('news.list', compact('news', 'commentCount', 'categories', 'sort', 'category', 'perPage'));
    }

    public function show($id)
    {
        // Получаем новость по ID
        $news = DB::table('news')->where('id', $id)->first();

        if ($news) {
            // Увеличиваем количество просмотров на 1
            DB::table('news')->where('id', $id)->increment('views', 1);

            // Получаем название категории по category_id
            $categoryName = DB::table('category_news')
                ->where('id', $news->category_id)
                ->value('name'); // Получаем значение поля 'category_name'

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
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'category_id' => ['required', 'integer', 'exists:category_news,id'],
        'filename' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
    ]);

    try {
        // Загрузка изображения на IPFS или использование дефолтного
        $imageUrl = $request->hasFile('filename') 
            ? $this->uploadToIPFS($request->file('filename'))
            : 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico';

        // Создание новости
        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'img' => $imageUrl,
            'views' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Перенаправление с сообщением об успехе
        return redirect()->route('good', [
            'post' => 'news', 
            'id' => $news->id, 
            'action' => 'create'
        ])->with('success', __('message.news_created_success'));

    } catch (\Exception $e) {
        Log::error('News creation error: ' . $e->getMessage());
        return back()->withErrors(['error' => __('message.news_creation_failed')]);
    }
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

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = CategoryNews::all();
        return view('news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer', 'exists:category_news,id'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        $news = News::findOrFail($id);
        $img = $request->hasFile('filename')
            ? $this->uploadToIPFS($request->file('filename'))
            : $news->img;

        $news->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category'],
            'img' => $img,
            'user_id' => Auth::id(),
            'updated_at' => now()
        ]);

        return redirect()->route('good', ['post' => 'news', 'id' => $id, 'action' => 'edit']);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('news.index')->with('success', __('message.news_deleted'));
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:category_news,name',
                'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
            ],
        ], [
            'name.required' => __('admin_news.validation.name_required'),
            'name.string' => __('admin_news.validation.name_string'),
            'name.max' => __('admin_news.validation.name_max'),
            'name.unique' => __('admin_news.validation.name_taken'),
            'name.regex' => __('admin_news.validation.name_regex'),
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 422);
        }
    
        try {
            $category = Categorynews::create(['name' => $request->name]);
            
            return response()->json([
                'success' => true,
                'message' => __('message.category_added_success'),
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('message.category_add_failed')
            ], 500);
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
            'name' => 'required|string|max:255|unique:category_news,name,' . $id
        ]);

        try {
            $category->update(['name' => $request->name]);
            return redirect()->route('newscategories.index')->with('success', __('message.category_updated_success'));
        } catch (\Exception $e) {
            // Log::error('Ошибка при обновлении категории: ' . $e->getMessage());
            return redirect()->back()->withErrors(['name' => __('message.category_update_failed')]);
        }
    }

    public function categoryDestroy($id)
    {
        $category = CategoryNews::findOrFail($id);
        $category->delete();

        return redirect()->route('newscategories.index')->with('success', __('message.category_deleted_success'));
    }
}
