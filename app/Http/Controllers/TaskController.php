<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Отображение страницы создания задачи
    public function create()
    {
        return view('tasks.create'); // представление, где будет форма
    }

    // Отображение списка открытых задач
    public function list()
    {
        $tasks = Task::where('status', 'open')->get();
        return view('tasks.list', compact('tasks'));
    }

    // Отображение конкретной задачи
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    // Создание новой задачи
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => 'open',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tasks.list')->with('success', 'Задание успешно добавлено!');
    }

    // Подать предложение на задачу
    public function bid(Request $request, Task $task)
    {
        $request->validate([
            'price' => 'required|numeric',
            'deadline' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        Bid::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'price' => $request->price,
            'deadline' => $request->deadline,
            'comment' => $request->comment,
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Ваше предложение успешно подано!');
    }

    // Добавьте другие методы, если это необходимо
}
