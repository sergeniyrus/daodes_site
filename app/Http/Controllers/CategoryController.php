<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    // Отображение списка категорий
    public function index($type)
    {
        $categories = $type == 'news' ? DB::table('category_news')->get() : DB::table('category_offers')->get();
        return view('categories.index', compact('categories', 'type'));
    }

    // Показ формы для создания категории
    public function create($type)
    {
        return view('categories.create', ['type' => $type]);
    }

    // Сохранение новой категории
    public function store(Request $request, $type)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:' . ($type == 'news' ? 'category_news' : 'category_offers') . ',category_name',
        ]);

        DB::table($type == 'news' ? 'category_news' : 'category_offers')->insert([
            'category_name' => $request->input('name'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('categories.index', $type)->with('success', 'Категория успешно добавлена.');
    }

    // Показ формы для редактирования категории
    public function edit($type, $id)
    {
        $category = DB::table($type == 'news' ? 'category_news' : 'category_offers')->find($id);
        return view('categories.edit', compact('category', 'type'));
    }

    // Обновление категории
    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:' . ($type == 'news' ? 'category_news' : 'category_offers') . ',category_name,' . $id,
        ]);

        DB::table($type == 'news' ? 'category_news' : 'category_offers')
            ->where('id', $id)
            ->update([
                'category_name' => $request->input('name'),
                'updated_at' => now(),
            ]);

        return redirect()->route('categories.index', $type)->with('success', 'Категория успешно обновлена.');
    }

    // Удаление категории
    public function destroy($type, $id)
    {
        DB::table($type == 'news' ? 'category_news' : 'category_offers')->where('id', $id)->delete();

        return redirect()->route('categories.index', $type)->with('success', 'Категория успешно удалена.');
    }

    // Сортировка и фильтрация по категориям
    public function categorySort($post, $id)
    {
        $texts = DB::table($post == 'news' ? 'news' : 'offers')
            ->where('category_id', $id)
            ->get();

        return view('category', ['texts' => $texts, 'post' => $post]);
    }
}
