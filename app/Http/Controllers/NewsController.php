<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\News;
use App\Models\CategoryNews;

class NewsController extends Controller
{
    // Метод для отображения всех новостей
    public function news()
    {
        $news = DB::table('news')->get();
        return view('news')->with('news', $news);
    }

    // Метод для отображения формы добавления новости
    public function add()
    {
        $categories = CategoryNews::all();
        return view('news.add')->with('category_news', $categories);
    }

    // Метод для создания новой записи новости
    public function create(Request $request): RedirectResponse
    {
        // Валидация данных
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'filename' => ['nullable', 'image', 'max:2048']
        ]);

        // Загружаем изображение на IPFS, если оно передано
        $img = $request->hasFile('filename') 
            ? $this->uploadToIPFS($request->file('filename')) 
            : 'https://ipfs.sweb.ru/ipfs/QmcBt4UUNPUSUxmH1h2GALvFPZ9FebnKuvirUSsJdHcPjP?filename=daodes.ico';

        // Сохраняем новость в БД
        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category'],
            'user_id' => Auth::id(),
            'img' => $img,
            'views' => 0
        ]);

        // Получаем ID новой записи и перенаправляем пользователя
        $id = $news->id;
        return redirect()->route('good', ['post' => 'news', 'id' => $id, 'action' => 'create']);
    }

    // Приватный метод для загрузки файла на IPFS
    private function uploadToIPFS($file)
    {
        $client = new Client([
            'base_uri' => 'https://daodes.space'
        ]);

        // Загружаем файл на IPFS
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

    // Метод для редактирования новости
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = CategoryNews::all();
        return view('news.edit', compact('news', 'categories'));
    }

    // Метод для обновления новости
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

    // Метод для удаления новости
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('news.index')->with('success', 'Новость удалена');
    }

    // Метод для отображения всех категорий
    public function categoryIndex()
    {
        $categories = CategoryNews::all();
        return view('news.categories.index', compact('categories'));
    }

    // Метод для отображения формы создания категории
    public function categoryCreate()
    {
        return view('news.categories.create');
    }

    // Метод для сохранения новой категории
    public function categoryStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:category_news,category_name',
    ]);

    try {
        CategoryNews::create(['category_name' => $request->name]);
    } catch (\Exception $e) {
        // Записываем ошибку в лог для диагностики
        \Log::error('Ошибка при добавлении категории: ' . $e->getMessage());
        return back()->withErrors(['category_name' => 'Не удалось добавить категорию.']);
    }

    return redirect()->route('newscategories.index')->with('success', 'Категория добавлена');
}


    // Метод для редактирования категории
    public function categoryEdit($id)
    {
        $category = CategoryNews::findOrFail($id);
        return view('news.categories.edit', compact('category'));
    }

    // Метод для обновления категории
    public function categoryUpdate(Request $request, $id)
{
    $category = CategoryNews::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:category_news,category_name,' . $id
    ]);

    try {
        // Обновление категории
        $category->update(['category_name' => $request->name]);
        
        return redirect()->route('newscategories.index')->with('success', 'Категория обновлена');
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
        $category = CategoryNews::findOrFail($id);
        $category->delete();

        return redirect()->route('newscategories.index')->with('success', 'Категория удалена');
    }
}
