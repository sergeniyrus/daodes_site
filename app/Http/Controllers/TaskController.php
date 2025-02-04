<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bid;
use App\Models\TaskVote;
use App\Models\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // Отображение страницы создания задачи
    public function create()
    {
        $categories = TaskCategory::all();
        return view('tasks.create', compact('categories'));
    }

    // Отображение списка задач
    public function list()
    {
        $tasks = Task::with('category')->paginate(10);
        $categories = TaskCategory::all();
        return view('tasks.list', compact('tasks', 'categories'));
    }

    // Отображение конкретной задачи
    public function show(Task $task)
    {
        $task->load('category', 'user', 'bids');

        if (!$task->user) {
            return redirect()->route('tasks.list')->withErrors('Автор задачи не найден.');
        }

        $task->deadline = Carbon::parse($task->deadline);

        $bids = $task->bids()
            ->orderBy('price', 'asc')
            ->orderBy('days', 'asc')
            ->orderBy('hours', 'asc')
            ->get();

        return view('tasks.show', compact('task', 'bids'));
    }

    // Создание новой задачи
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
            'category_id' => 'required|exists:task_categories,id',
        ]);

        Task::create([
            'title' => $request->title,
            'content' => $request->content,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => Task::STATUS_OPEN,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Задание успешно добавлено!');
    }

    // Редактирование задачи (только для автора)
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав на редактирование этой задачи.');
        }

        $categories = TaskCategory::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    // Обновление задачи (только для автора)
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав на редактирование этой задачи.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
            'category_id' => 'required|exists:task_categories,id',
        ]);

        $task->update($request->only(['title', 'content', 'deadline', 'budget', 'category_id']));

        return redirect()->route('tasks.show', $task)->with('success', 'Задание успешно обновлено!');
    }

    // Удаление задачи (только для автора)
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав на удаление этой задачи.');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Задание успешно удалено!');
    }

    // Подача предложения (только одно предложение от пользователя)
    public function bid(Request $request, Task $task)
    {
        if ($task->status !== Task::STATUS_OPEN) {
            return redirect()->back()->with('error', 'Прием предложений закрыт.');
        }

        if ($task->bids()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'Вы уже подали предложение на это задание.');
        }

        $request->validate([
            'price' => 'required|numeric',
            'days' => 'required|integer|min:0',
            'hours' => 'required|integer|min:0|max:23',
            'comment' => 'nullable|string|max:255',
        ]);

        $task->bids()->create([
            'user_id' => Auth::id(),
            'price' => $request->price,
            'days' => $request->days,
            'hours' => $request->hours,
            'comment' => $request->comment,
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Ваше предложение успешно подано.');
    }

    // Принятие предложения (только для автора)
    public function acceptBid(Task $task, Bid $bid)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'У вас нет прав на принятие предложений.');
        }

        $task->update([
            'accepted_bid_id' => $bid->id,
            'status' => Task::STATUS_NEGOTIATION,
        ]);

        return redirect()->back()->with('success', 'Предложение принято. Свяжитесь с фрилансером.');
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



    // Начало работы (только для фрилансера)
    public function startWork(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, 'Только выбранный фрилансер может начать работу.');
        }

        $task->update([
            'status' => Task::STATUS_IN_PROGRESS,
            'start_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Работа начата! Таймер запущен.');
    }

//фрилансер на проверку
// Завершение работы (только для фрилансера)
public function freelancerComplete(Task $task)
{
    if ($task->acceptedBid->user_id !== Auth::id()) {
        abort(403, 'Только выбранный фрилансер может завершить задачу.');
    }

    $task->update([
        'status' => Task::STATUS_ON_REVIEW,
        'end_time' => now(),
    ]);

    return redirect()->back()->with('success', 'Задача отправлена на проверку.');
}

    // Завершение работы (только для Автора)
    public function Complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Только Автор может завершить задачу.');
        }

        $task->update([
            'status' => Task::STATUS_COMPLETED,
            
        ]);

        return redirect()->back()->with('success', 'Задача выполнена!');
    }

// Продолжение задачи (только для автора) кнопка доработать
public function continueTask(Task $task)
{
    // Проверка, что текущий пользователь — это автор задачи
    if ($task->user_id !== Auth::id()) {
        abort(403, 'У вас нет прав для продолжения этого задания.');
    }

    // Проверка, что задача находится на проверке
    if ($task->status !== Task::STATUS_ON_REVIEW) {
        return redirect()->back()->with('error', 'Невозможно продолжить это задание.');
    }

    // Возвращаем задачу в статус "В работе"
    $task->update([
        'status' => Task::STATUS_IN_PROGRESS,
        'start_time' => now(), // Сбрасываем таймер
    ]);

    return redirect()->back()->with('success', 'Задание возвращено в работу.');
}

// Задача провалена только для автора
public function fail(Task $task)
{

// Проверка, что текущий пользователь — это автор задачи
if ($task->user_id !== Auth::id()) {
    abort(403, 'У вас нет прав для этого задания.');
}

// Проверка, что задача находится на проверке
if ($task->status !== Task::STATUS_ON_REVIEW) {
    return redirect()->back()->with('error', 'Невозможно продолжить это задание.');
}

    $task->update([
        'status' => 'failed',
        
    ]);
    return redirect()->back()->with('success', 'Задание помечено как проваленное');
}

    // Оценка задачи (только для автора)
    public function rate(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Только автор может оценить задачу.');
        }

        $request->validate([
            'rating' => 'required|integer|between:-10,10',
        ]);

        $task->update(['rating' => $request->rating]);

        return redirect()->back()->with('success', 'Оценка сохранена.');
    }
}