<?php

namespace App\Http\Controllers;

use App\Models\CategoryTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryTasksController extends Controller
{
    // Display the list of categories
    public function index()
    {
        $categories = CategoryTasks::all();
        return view('tasks.categories.index', compact('categories'));
    }

    // Display the form to create a new category
    public function create()
    {
        return view('tasks.categories.create');
    }



    // Save a new category
    public function store(Request $request)
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
        $category = CategoryTasks::create([
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



    // Display the form to edit a category
    public function edit(TaskCategory $taskCategory)
    {
        return view('tasks.categories.edit', compact('taskCategory'));
    }

    // Update a category
    public function update(Request $request, TaskCategory $taskCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $taskCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('taskscategories.index')->with('success', __('message.category_updated_success'));
    }

    // Delete a category
    public function destroy(TaskCategory $taskCategory)
    {
        $taskCategory->delete();
        return redirect()->route('taskscategories.index')->with('success', __('message.category_deleted_success'));
    }
}
