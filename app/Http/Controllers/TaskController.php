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
            return redirect()->route('tasks.list')->withErrors('Task author not found.');
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

        return redirect()->route('tasks.index')->with('success', 'Task successfully added!');
    }

    // Edit a task (only for the author)
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to edit this task.');
        }

        $categories = TaskCategory::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    // Update a task (only for the author)
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to edit this task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'deadline' => 'required|date',
            'budget' => 'required|numeric',
            'category_id' => 'required|exists:category_tasks,id',
        ]);

        $task->update($request->only(['title', 'content', 'deadline', 'budget', 'category_id']));

        return redirect()->route('tasks.show', $task)->with('success', 'Task successfully updated!');
    }

    // Delete a task (only for the author)
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to delete this task.');
        }

        if (!$task->canBeDeleted()) {
            return redirect()->back()->with('error', 'Cannot delete this task.');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task successfully deleted!');
    }

    // Submit a bid (only one bid per user)
    public function bid(Request $request, Task $task)
    {
        Log::info('Attempting to submit a bid for task ID: ' . $task->id, ['user_id' => Auth::id()]);

        if (!$task->isOpen()) {
            Log::warning('Bid submission is closed for task ID: ' . $task->id);
            return redirect()->back()->with('error', 'Bid submission is closed.');
        }

        if ($task->user_id === Auth::id()) {
            Log::warning('User tried to bid on their own task ID: ' . $task->id);
            return redirect()->back()->with('error', 'You cannot bid on your own task.');
        }

        if ($task->bids()->where('user_id', Auth::id())->exists()) {
            Log::warning('User already submitted a bid for task ID: ' . $task->id);
            return redirect()->back()->with('error', 'You have already submitted a bid for this task.');
        }

        Validator::extend('min_words', function ($attribute, $value, $parameters, $validator) {
            $wordCount = str_word_count($value);
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
            'comment.min_words' => 'The comment must contain at least 3 words.',
            'comment.min_chars' => 'The comment must be at least 20 characters long.',
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

            return redirect()->route('tasks.show', $task)->with('success', 'Your bid has been successfully submitted.');
        } catch (\Exception $e) {
            Log::error('Error creating bid for task ID: ' . $task->id, ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while submitting your bid. Please try again.');
        }
    }

    // Accept a bid (only for the author)
    public function acceptBid(Task $task, Bid $bid)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to accept bids.');
        }

        if (!$task->canUpdateStatus(Task::STATUS_NEGOTIATION)) {
            return redirect()->back()->with('error', 'Cannot update task status.');
        }

        $task->setStatus(Task::STATUS_NEGOTIATION)->save();
        $task->update(['accepted_bid_id' => $bid->id]);

        return redirect()->back()->with('success', 'Bid accepted. Please contact the freelancer.');
    }

    // Start work (only for the freelancer)
    public function startWork(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, 'Only the selected freelancer can start work.');
        }

        if (!$task->canUpdateStatus(Task::STATUS_IN_PROGRESS)) {
            return redirect()->back()->with('error', 'Cannot update task status.');
        }

        $task->setStatus(Task::STATUS_IN_PROGRESS)->save();
        $task->update(['start_time' => now()]);

        return redirect()->back()->with('success', 'Work started! Timer is running.');
    }

    // Freelancer submits task for review
    public function freelancerComplete(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, 'Only the selected freelancer can complete the task.');
        }

        if (!$task->canUpdateStatus(Task::STATUS_ON_REVIEW)) {
            return redirect()->back()->with('error', 'Cannot update task status.');
        }

        $task->setStatus(Task::STATUS_ON_REVIEW)->save();
        $task->update(['end_time' => now()]);

        return redirect()->back()->with('success', 'Task submitted for review.');
    }

    // Complete the task (only for the author)
    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Only the author can complete the task.');
        }

        if (!$task->canUpdateStatus(Task::STATUS_COMPLETED)) {
            return redirect()->back()->with('error', 'Cannot update task status.');
        }

        $task->setStatus(Task::STATUS_COMPLETED)->save();

        return redirect()->back()->with('success', 'Task completed!');
    }

    // Continue the task (only for the author) - "Revise" button
    public function continueTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to continue this task.');
        }

        if (!$task->isOnReview()) {
            return redirect()->back()->with('error', 'Cannot continue this task.');
        }

        $task->setStatus(Task::STATUS_IN_PROGRESS)->save();
        $task->update(['start_time' => now()]);

        return redirect()->back()->with('success', 'Task returned to work.');
    }

    // Mark the task as failed (only for the author)
    public function fail(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission for this task.');
        }

        if (!$task->isOnReview()) {
            return redirect()->back()->with('error', 'Cannot mark this task as failed.');
        }

        $task->setStatus(Task::STATUS_FAILED)->save();

        return redirect()->back()->with('success', 'Task marked as failed.');
    }

    // Rate the task (only for the author)
    public function rate(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Only the author can rate the task.');
        }

        $request->validate([
            'rating' => 'required|integer|between:-10,10',
        ]);

        $task->update(['rating' => $request->rating]);

        return redirect()->back()->with('success', 'Rating saved.');
    }
}