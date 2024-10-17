<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bid;
use App\Models\TaskVote;
use App\Models\TaskCategory; // Модель для категорий
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class TaskController extends Controller
{
    // Отображение страницы создания задачи
    public function create()
    {
        $categories = TaskCategory::all(); // Загружаем категории для выбора
        return view('tasks.create', compact('categories')); // Передаем категории в представление
    }

    // Отображение списка открытых задач
    public function list()
    {
        // Логика получения задач с фильтрацией по категориям (если применимо)
        $tasks = Task::with('category')->paginate(10); // Пример с пагинацией
        $categories = TaskCategory::all();
        return view('tasks.list', compact('tasks', 'categories'));
    }

    public function show(Task $task)
{
    // Загружаем связанные данные: категория и предложения
    $task->load('category');

    // Сортируем предложения по цене, дням и часам выполнения
    $bids = $task->bids()
        ->orderBy('price', 'asc') // сортировка по цене
        ->orderBy('days', 'asc') // сортировка по количеству дней
        ->orderBy('hours', 'asc') // сортировка по количеству часов
        ->get();

    // Преобразуем срок выполнения задачи в объект Carbon
    $task->deadline = Carbon::parse($task->deadline);

    return view('tasks.show', compact('task', 'bids'));
}

    // Создание новой задачи
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
            'category_id' => 'required|exists:task_category,id', // Проверка существования категории
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => 'open',
            'user_id' => Auth::id(),
            'category_id' => $request->category_id, // Добавляем категорию
        ]);

        return redirect()->route('tasks.index')->with('success', 'Задание успешно добавлено!');
    }

    // Подать предложение на задачу
    public function bid(Request $request, Task $task)
{
    $request->validate([
        'price' => 'required|numeric',
        'days' => 'required|integer|min:0',
        'hours' => 'required|integer|min:0|max:23',
        'comment' => 'nullable|string|max:255',
    ]);

    // Проверка, есть ли уже предложение от этого фрилансера
    $existingBid = $task->bids()->where('user_id', Auth::id())->first();

    if ($existingBid) {
        return redirect()->back()->with('error', 'Вы уже подали предложение на это задание.');
    }

    // Создаем новое предложение
    $task->bids()->create([
        'user_id' => Auth::id(),
        'price' => $request->price,
        'days' => $request->days,
        'hours' => $request->hours,
        'comment' => $request->comment,
    ]);

    return redirect()->route('tasks.show', $task)->with('success', 'Ваше предложение успешно подано.');
}



    // Лайк задачи
    public function like(Task $task)
    {
        $userId = Auth::id();
        $existingVote = TaskVote::where('task_id', $task->id)
                                ->where('user_id', $userId)
                                ->first();

        if ($existingVote) {
            if (!$existingVote->is_like) {
                // Если был дизлайк, меняем на лайк
                $existingVote->update(['is_like' => true]);
            } else {
                // Если уже стоит лайк, убираем его
                $existingVote->delete();
            }
        } else {
            // Если голос еще не был добавлен
            TaskVote::create([
                'task_id' => $task->id,
                'user_id' => $userId,
                'is_like' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Ваш голос учтен!');
    }

    // Дизлайк задачи
    public function dislike(Task $task)
    {
        $userId = Auth::id();
        $existingVote = TaskVote::where('task_id', $task->id)
                                ->where('user_id', $userId)
                                ->first();

        if ($existingVote) {
            if ($existingVote->is_like) {
                // Если был лайк, меняем на дизлайк
                $existingVote->update(['is_like' => false]);
            } else {
                // Если уже стоит дизлайк, убираем его
                $existingVote->delete();
            }
        } else {
            // Если голос еще не был добавлен
            TaskVote::create([
                'task_id' => $task->id,
                'user_id' => $userId,
                'is_like' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Ваш голос учтен!');
    }

    // Редактирование задачи
    public function edit(Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }

        $categories = TaskCategory::all(); // Получаем все категории для выбора при редактировании
        return view('tasks.edit', compact('task', 'categories'));
    }

    // Удаление задачи
    public function destroy(Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Задание успешно удалено!');
    }

    // Обновление задачи
    public function update(Request $request, Task $task)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric|min:0',
            'category_id' => 'required|exists:task_category,id', // Категория должна существовать
        ]);

        // Обновление задачи
        $task->update($validatedData);

        // Перенаправляем с сообщением об успехе
        return redirect()->route('tasks.index')->with('success', 'Задание успешно обновлено!');
    }

    public function complete(Task $task)
{
    // Убедитесь, что пользователь — это автор задания
    if (Auth::id() == $task->user_id && $task->in_progress && !$task->completed) {
        $task->completed = true;
        $task->save();
    }
    return redirect()->back();
}

public function rate(Request $request, Task $task)
{
    // Убедитесь, что пользователь — это автор задания и задание завершено
    if (Auth::id() == $task->user_id && $task->completed && !$task->rating) {
        $rating = $request->input('rating');
        if ($rating >= 1 && $rating <= 10) {
            $task->rating = $rating;
            $task->save();
        }
    }
    return redirect()->back();
}

public function startWork(Task $task)
{
    // Проверяем, может ли текущий пользователь начать работу над задачей
    if (Auth::id() !== $task->user_id || $task->accepted_bid_id === null) {
        return redirect()->back()->withErrors('Вы не можете начать работу над этой задачей.');
    }

    // Устанавливаем текущее время в UTC как время начала работы
    $task->start_time = now()->setTimezone('UTC'); // Указываем таймзону UTC
    $task->in_progress = true;
    $task->save();

    // Перенаправляем на страницу с задачей с сообщением об успехе
    return redirect()->back()
        ->with('success', 'Задача теперь в работе.')
        ->with('start_time', $task->start_time); // Передаём start_time через сессию
}





    public function fail(Task $task)
{
    // Проверка, что только владелец задания может пометить его как проваленное
    if (auth()->id() !== $task->user_id) {
        return redirect()->back()->withErrors(['error' => 'У вас нет прав на это действие.']);
    }

    // Логика обработки проваленного задания
    $task->in_progress = false;
    $task->completed = false;
    $task->accepted_bid_id = null;
    $task->save();

    return redirect()->back()->with('status', 'Задание помечено как проваленное.');
}

}
