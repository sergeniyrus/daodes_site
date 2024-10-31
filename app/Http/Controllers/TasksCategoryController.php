<?php

namespace App\Http\Controllers;

use App\Models\TaskCategory;
use Illuminate\Http\Request;

class TasksCategoryController extends Controller
{
    // Показать список категорий
    public function index()
    {
        $categories = TaskCategory::all();
        return view('tasks.categories.index', compact('categories'));
    }

    // Отобразить форму для создания новой категории
    public function create()
    {
        return view('tasks.categories.create');
    }

    // Сохранить новую категорию
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TaskCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('taskscategories.index')->with('success', 'Категория успешно создана!');
    }

    // Отобразить форму для редактирования категории
    public function edit(TaskCategory $taskCategory)
    {
        return view('tasks.categories.edit', compact('taskCategory'));
    }

    // Обновить категорию
    public function update(Request $request, TaskCategory $taskCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $taskCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('taskscategories.index')->with('success', 'Категория успешно обновлена!');
    }

    // Удалить категорию
    public function destroy(TaskCategory $taskCategory)
    {
        $taskCategory->delete();
        return redirect()->route('taskscategories.index')->with('success', 'Категория успешно удалена!');
    }
}
