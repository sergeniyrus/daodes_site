<?php

namespace App\Http\Controllers;

use App\Models\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        return view('tasks.categories.create');
    }



    // Save a new category
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:category_tasks,name',
            'regex:/^[\p{L}\p{N}\s\-.,;!?€£\$₽]+$/u'
        ],
    ], [
        'name.required' => __('tasks.validation.name_required'),
        'name.string' => __('tasks.validation.name_string'),
        'name.max' => __('tasks.validation.name_max'),
        'name.unique' => __('tasks.validation.name_taken'),
        'name.regex' => __('tasks.validation.name_regex'),
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
            'message' => __('message.validation_error')
        ], 422);
    }

    try {
        $category = TaskCategory::create(['name' => $request->name]);
        
        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name
            ],
            'message' => __('tasks.messages.category_added_success')
        ]);

    } catch (\Exception $e) {
        \Log::error('Category creation error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => __('tasks.messages.category_add_failed'),
            'error' => $e->getMessage()
        ], 500);
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
