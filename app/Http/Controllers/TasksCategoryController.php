<?php

namespace App\Http\Controllers;

use App\Models\TaskCategory;
use Illuminate\Http\Request;

class TasksCategoryController extends Controller
{
    // Display the list of categories
    public function index()
    {
        $categories = TaskCategory::all();
        return view('tasks.categories.index', compact('categories'));
    }

    // Display the form to create a new category
    public function create()
    {
        return view('tasks.categories.addtask');
    }

    // Save a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TaskCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('taskscategories.index')->with('success', __('message.category_added_success'));
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
