<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bid;
use App\Models\CategoryTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class TasksController extends Controller
{
    public function list(Request $request)
{
    $sort = $request->input('sort', 'new');
    $category = $request->input('category');
    $status = $request->input('status');
    $perPage = $request->input('perPage', 10);

    $locale = app()->getLocale();
    $titleField = $locale === 'ru' ? 'title_ru' : 'title_en';
    $contentField = $locale === 'ru' ? 'content_ru' : 'content_en';
    $categoryNameField = $locale === 'ru' ? 'name_ru' : 'name_en';

    $query = Task::with(['user', 'category'])
        ->select([
            'id', 'user_id', 'category_id', 'img', // 👈 ДОБАВИЛ img
            'created_at', 'status', 'deadline', 'budget',
            DB::raw("$titleField as title"),
            DB::raw("$contentField as content")
        ])
        ->when($category, fn($q) => $q->where('category_id', $category))
        ->when($status !== null, fn($q) => $q->where('status', $status))
        ->orderBy('created_at', $sort === 'new' ? 'desc' : 'asc');

    $tasks = $query->paginate($perPage);

    $categories = CategoryTasks::pluck($categoryNameField, 'id');

    $statusesWithTasks = Task::select('status', DB::raw('COUNT(*) as count'))
        ->groupBy('status')
        ->pluck('count', 'status');

    return view('tasks.list', compact(
        'tasks', 'categories', 'sort', 'category', 'status', 'perPage', 'statusesWithTasks'
    ));
}


        

public function show($id)
{
    $locale = app()->getLocale();
    $titleField = $locale === 'ru' ? 'title_ru' : 'title_en';
    $contentField = $locale === 'ru' ? 'content_ru' : 'content_en';

    $task = Task::with(['category', 'user']) // связи через Eloquent
        ->select('id', 'category_id', 'user_id', 'budget', 'img', 'status', 'created_at', 'deadline', $titleField . ' as title', $contentField . ' as content')
        ->findOrFail($id);

    $task->deadline = Carbon::parse($task->deadline);

    $bids = Bid::where('task_id', $task->id)
        ->orderBy('price')->orderBy('days')->orderBy('hours')
        ->get();

    return view('tasks.show', [
        'task' => $task,
        'bids' => $bids,
        'categoryName' => $task->category->{"name_$locale"} ?? __('tasks.no_category'),
        'user' => $task->user,
    ]);
}




    // Display the task creation page
    public function create()
    {
        $categories = CategoryTasks::all();
        return view('tasks.create', compact('categories'));
    }


    public function store(Request $request): RedirectResponse
    {
        Log::info('HIT TASK STORE');

        Log::info('Starting task creation process', ['user_id' => Auth::id(), 'input' => $request->all()]);
    
        // Валидация данных
        try {
            $validated = $request->validate([
                'title_ru' => ['required', 'string', 'max:255'],
                'title_en' => ['required', 'string', 'max:255'],
                'content_ru' => ['required', 'string'],
                'content_en' => ['required', 'string'],
                'deadline' => ['required', 'date'],
                'budget' => ['required', 'numeric', 'min:0'],
                'category_id' => ['required', 'integer', 'exists:category_tasks,id'],
                'filename' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
            ]);
            Log::debug('Validation passed', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return back()->withErrors($e->errors())->withInput();
        }
    
        try {
            // Логирование загрузки файла
            if ($request->hasFile('filename')) {
                Log::debug('File upload detected', [
                    'filename' => $request->file('filename')->getClientOriginalName(),
                    'size' => $request->file('filename')->getSize(),
                    'mime' => $request->file('filename')->getMimeType()
                ]);
            }
    
            // Загрузка изображения
            $imageUrl = $request->hasFile('filename')
                ? $this->uploadToIPFS($request->file('filename'))
                : 'https://daodes.space/ipfs/QmaXUERRznHoxDE28UNdXsqnq9fb78RUB5senZ2LuAGQsY';
    
            Log::debug('Image URL determined', ['image_url' => $imageUrl]);
    
            // Подготовка данных для создания задачи
            $taskData = [
                'title_ru' => $validated['title_ru'],
                'title_en' => $validated['title_en'],
                'content_ru' => $validated['content_ru'],
                'content_en' => $validated['content_en'],
                'deadline' => $validated['deadline'],
                'budget' => $validated['budget'],
                'status' => Task::STATUS_OPEN,
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'img' => $imageUrl
            ];
    
            Log::debug('Attempting to create task', ['task_data' => $taskData]);
    
            // Создание задачи
            $task = Task::create($taskData);
    
            Log::info('Task successfully created', ['task_id' => $task->id]);
    
            return redirect()->route('tasks.list')->with('success', __('message.task_added'));
    
        } catch (\Exception $e) {
            Log::error('Task creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'user_id' => Auth::id()
            ]);
            return back()->withInput()->withErrors(['error' => __('message.task_creation_failed')]);
        }
    }
    

    // Edit a task (only for the author)
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        $categories = CategoryTasks::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    // Update a task (only for the author)
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'title_ru'    => ['required', 'string', 'max:255'],
            'content_ru'  => ['required', 'string'],
            'title_en'    => ['required', 'string', 'max:255'],
            'content_en'  => ['required', 'string'],
            'deadline'    => ['required', 'date'],
            'budget'      => ['required', 'numeric'],
            'category_id' => ['required', 'integer', 'exists:category_tasks,id'],
            'filename'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);
    
        try {
            $task = Task::findOrFail($id);
    
            if ($task->user_id !== Auth::id()) {
                abort(403, __('message.permission_denied'));
            }
    
            // Загрузка изображения на IPFS (если есть новое)
            $imageUrl = $request->hasFile('filename')
                ? $this->uploadToIPFS($request->file('filename'))
                : $task->img;
    
            $task->update([
                'title_ru'    => $validated['title_ru'],
                'content_ru'  => $validated['content_ru'],
                'title_en'    => $validated['title_en'],
                'content_en'  => $validated['content_en'],
                'deadline'    => $validated['deadline'],
                'budget'      => $validated['budget'],
                'category_id' => $validated['category_id'],
                'img'         => $imageUrl,
                'updated_at'  => now(),
            ]);
    
            return redirect()->route('tasks.show', ['task' => $task->id])
                ->with('success', __('message.task_updated'));
    
        } catch (\Exception $e) {
            Log::error('Task update error: ' . $e->getMessage());
            return back()->withErrors(['error' => __('message.task_update_failed')]);
        }
    }

    // Delete a task (only for the author)
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->canBeDeleted()) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->delete();
        return redirect()->route('tasks.list')->with('success', __('message.task_deleted'));
    }

    // Submit a bid (only one bid per user)
    public function bid(Request $request, Task $task)
    {
        // Log::info('Attempting to submit a bid for task ID: ' . $task->id, ['user_id' => Auth::id()]);

        if (!$task->isOpen()) {
            Log::warning('Bid submission is closed for task ID: ' . $task->id);
            return redirect()->back()->with('error', __('message.bid_submission_closed'));
        }

        if ($task->user_id === Auth::id()) {
            Log::warning('User tried to bid on their own task ID: ' . $task->id);
            return redirect()->back()->with('error', __('message.cannot_bid_own_task'));
        }

        if ($task->bids()->where('user_id', Auth::id())->exists()) {
            Log::warning('User already submitted a bid for task ID: ' . $task->id);
            return redirect()->back()->with('error', __('message.bid_already_submitted'));
        }

        Validator::extend('min_words', function ($attribute, $value, $parameters, $validator) {
            // Удаляем HTML-теги и лишние пробелы
            $text = trim(strip_tags($value));
            // Подсчитываем слова с помощью регулярного выражения
            $wordCount = preg_match_all('/\p{L}[\p{L}\p{Mn}\p{Pd}\'\x{2019}]*/u', $text);
            return $wordCount >= $parameters[0];
        });

        Validator::extend('min_chars', function ($attribute, $value, $parameters, $validator) {
            return strlen($value) >= $parameters[0];
        });

        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'days' => 'required|integer|min:0',
            'hours' => 'required|integer|min:0|max:23',
            'comment' => [
                'required',
                'string',
                'min_words:3',
                'min_chars:20',
                'max:500',
            ],
        ], [
            'comment.min_words' => __('message.comment_min_words'),
            'comment.min_chars' => __('message.comment_min_chars'),
        ]);

        // Log::info('Bid request data:', $validatedData);

        try {
            $bid = $task->bids()->create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'price' => $validatedData['price'],
                'days' => $validatedData['days'],
                'hours' => $validatedData['hours'],
                'comment' => $validatedData['comment'],
            ]);

            // Log::info('Bid successfully created for task ID: ' . $task->id, ['bid_id' => $bid->id]);

            return redirect()->route('tasks.show', $task)->with('success', __('message.bid_submitted'));
        } catch (\Exception $e) {
            Log::error('Error creating bid for task ID: ' . $task->id, ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', __('message.bid_error'));
        }
    }

    // Accept a bid (only for the author)
    public function acceptBid(Task $task, Bid $bid)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->canUpdateStatus(Task::STATUS_NEGOTIATION)) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->setStatus(Task::STATUS_NEGOTIATION)->save();
        $task->update(['accepted_bid_id' => $bid->id]);

        return redirect()->back()->with('success', __('message.bid_accepted'));
    }

    // Start work (only for the freelancer)
    public function startWork(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->canUpdateStatus(Task::STATUS_IN_PROGRESS)) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->setStatus(Task::STATUS_IN_PROGRESS)->save();
        $task->update(['start_time' => now()]);

        return redirect()->back()->with('success', __('message.work_started'));
    }

    // Freelancer submits task for review
    public function freelancerComplete(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->canUpdateStatus(Task::STATUS_ON_REVIEW)) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->setStatus(Task::STATUS_ON_REVIEW)->save();
        $task->update(['end_time' => now()]);

        return redirect()->back()->with('success', __('message.task_submitted_for_review'));
    }

    // Complete the task (only for the author)
    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->canUpdateStatus(Task::STATUS_COMPLETED)) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->setStatus(Task::STATUS_COMPLETED)->save();

        return redirect()->back()->with('success', __('message.task_completed'));
    }

    // Continue the task (only for the author) - "Revise" button
    public function continueTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->isOnReview()) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->setStatus(Task::STATUS_IN_PROGRESS)->save();
        $task->update(['start_time' => now()]);

        return redirect()->back()->with('success', __('message.task_returned_to_work'));
    }

    // Mark the task as failed (only for the author)
    public function fail(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        if (!$task->isOnReview()) {
            return redirect()->back()->with('error', __('message.cannot_update_status'));
        }

        $task->setStatus(Task::STATUS_FAILED)->save();

        return redirect()->back()->with('success', __('message.task_marked_as_failed'));
    }

    // Rate the task (only for the author)
    public function rate(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        $request->validate([
            'rating' => 'required|integer|between:-10,10',
        ]);

        $task->update(['rating' => $request->rating]);

        return redirect()->back()->with('success', __('message.rating_saved'));
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

    
}
