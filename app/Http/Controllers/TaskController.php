<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Bid;
use App\Models\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Display the task creation page
    public function create()
    {
        $categories = TaskCategory::all();
        return view('tasks.create', compact('categories'));
    }

    // Display the list of tasks
    public function list()
    {
        $tasks = Task::with('category')->paginate(10);
        $categories = TaskCategory::all();
        return view('tasks.list', compact('tasks', 'categories'));
    }

    // Display a specific task
    public function show(Task $task)
    {
        $task->load('category', 'user', 'bids');

        if (!$task->user) {
            return redirect()->route('tasks.list')->withErrors(__('message.task_author_not_found'));
        }

        $task->deadline = Carbon::parse($task->deadline);

        $bids = $task->bids()
            ->orderBy('price', 'asc')
            ->orderBy('days', 'asc')
            ->orderBy('hours', 'asc')
            ->get();

        return view('tasks.show', compact('task', 'bids'));
    }

    // Create a new task
    public function store(Request $request)
    {
        Log::info('Request received:', $request->all());

        // Validate the data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
            'category_id' => 'required|exists:category_tasks,id',
        ]);

        // Create the task
        $task = Task::create([
            'title' => $request->title,
            'content' => $request->content,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => Task::STATUS_OPEN,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);

        Log::info('Task successfully created:', $task->toArray());

        return redirect()->route('tasks.index')->with('success', __('message.task_added'));
    }

    // Edit a task (only for the author)
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        $categories = TaskCategory::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    // Update a task (only for the author)
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, __('message.permission_denied'));
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
            'category_id' => 'required|exists:category_tasks,id',
        ]);

        $task->update($request->only(['title', 'content', 'deadline', 'budget', 'category_id']));

        return redirect()->route('tasks.show', $task)->with('success', __('message.task_updated'));
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
        return redirect()->route('tasks.index')->with('success', __('message.task_deleted'));
    }

    // Submit a bid (only one bid per user)
    public function bid(Request $request, Task $task)
    {
        Log::info('Attempting to submit a bid for task ID: ' . $task->id, ['user_id' => Auth::id()]);

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

        Log::info('Bid request data:', $validatedData);

        try {
            $bid = $task->bids()->create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'price' => $validatedData['price'],
                'days' => $validatedData['days'],
                'hours' => $validatedData['hours'],
                'comment' => $validatedData['comment'],
            ]);

            Log::info('Bid successfully created for task ID: ' . $task->id, ['bid_id' => $bid->id]);

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
}
