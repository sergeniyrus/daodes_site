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
            'category_id' => 'required|exists:category_tasks,id', // Corrected to 'categories'
        ]);

        // Log all data from the request
        Log::info('Task data:', $request->all());

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

        // Log successful task creation
        Log::info('Task successfully created:', $task->toArray());

        // Redirect with a success message
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
            'category_id' => 'required|numeric:category_id,id',
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

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task successfully deleted!');
    }

    // Submit a bid (only one bid per user)
    public function bid(Request $request, Task $task)
    {
        if ($task->status !== Task::STATUS_OPEN) {
            return redirect()->back()->with('error', 'Bid submission is closed.');
        }

        if ($task->bids()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'You have already submitted a bid for this task.');
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

        return redirect()->route('tasks.show', $task)->with('success', 'Your bid has been successfully submitted.');
    }

    // Accept a bid (only for the author)
    public function acceptBid(Task $task, Bid $bid)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to accept bids.');
        }

        $task->update([
            'accepted_bid_id' => $bid->id,
            'status' => Task::STATUS_NEGOTIATION,
        ]);

        return redirect()->back()->with('success', 'Bid accepted. Please contact the freelancer.');
    }

    // Start work (only for the freelancer)
    public function startWork(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, 'Only the selected freelancer can start work.');
        }

        $task->update([
            'status' => Task::STATUS_IN_PROGRESS,
            'start_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Work started! Timer is running.');
    }

    // Freelancer submits task for review
    public function freelancerComplete(Task $task)
    {
        if ($task->acceptedBid->user_id !== Auth::id()) {
            abort(403, 'Only the selected freelancer can complete the task.');
        }

        $task->update([
            'status' => Task::STATUS_ON_REVIEW,
            'end_time' => now(),
        ]);

        return redirect()->back()->with('success', 'Task submitted for review.');
    }

    // Complete the task (only for the author)
    public function Complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Only the author can complete the task.');
        }

        $task->update([
            'status' => Task::STATUS_COMPLETED,
        ]);

        return redirect()->back()->with('success', 'Task completed!');
    }

    // Continue the task (only for the author) - "Revise" button
    public function continueTask(Task $task)
    {
        // Check if the current user is the task author
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to continue this task.');
        }

        // Check if the task is under review
        if ($task->status !== Task::STATUS_ON_REVIEW) {
            return redirect()->back()->with('error', 'Cannot continue this task.');
        }

        // Return the task to "In Progress" status
        $task->update([
            'status' => Task::STATUS_IN_PROGRESS,
            'start_time' => now(), // Reset the timer
        ]);

        return redirect()->back()->with('success', 'Task returned to work.');
    }

    // Mark the task as failed (only for the author)
    public function fail(Task $task)
    {
        // Check if the current user is the task author
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You do not have permission for this task.');
        }

        // Check if the task is under review
        if ($task->status !== Task::STATUS_ON_REVIEW) {
            return redirect()->back()->with('error', 'Cannot continue this task.');
        }

        $task->update([
            'status' => 'failed',
        ]);

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