@extends('template')
@section('title_page', __('tasks.edit_task'))
@section('main')

<style>
    .container {
        padding: 20px;
        margin: 0 auto;
        max-width: 800px;
        background-color: rgba(20, 20, 20, 0.9);
        border-radius: 20px;
        border: 1px solid #d7fc09;
        color: #f8f9fa;
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
        margin-top: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    .form-group label {
        color: #d7fc09;
        font-size: 1.2rem;
        display: block;
        margin: 10px 0;
        text-align: left;
        font-weight: bold;
    }

    .input_dark, textarea {
        background-color: #1a1a1a;
        color: #a0ff08;
        border: 1px solid #d7fc09;
        border-radius: 5px;
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        transition: border 0.3s ease;
    }

    .input_dark:focus, textarea:focus {
        border: 1px solid #a0ff08;
        outline: none;
        box-shadow: 0 0 5px #d7fc09;
    }

    .des-btn {
        display: inline-block;
        color: #ffffff;
        font-size: 1.2rem;
        background: #0b0c18;
        padding: 12px 25px;
        border: 1px solid #d7fc09;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
        margin-top: 20px;
    }

    .des-btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #1a1a1a;
    }
</style>

<div class="container">
    <div class="text-center mb-4">
        <h1 class="display-4">{{ __('tasks.edit_task') }}</h1>
    </div>

    <!-- Display success flash messages -->
    {{-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if($errors->any()))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <!-- Task editing form -->
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT') <!-- Specify the PUT method for updating -->

        <div class="form-group">
            <label for="title">{{ __('tasks.task_title') }}:</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="input_dark">
        </div>

        <div class="form-group">
            <label for="content">{{ __('tasks.task_description') }}:</label>
            <textarea name="content" id="editor" rows="5" required class="input_dark">{{ old('content', $task->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="deadline">{{ __('tasks.deadline') }}:</label>
            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}" required class="input_dark">
        </div>

        <div class="form-group">
            <label for="budget">{{ __('tasks.budget') }}:</label>
            <input type="number" name="budget" id="budget" step="0.01" value="{{ old('budget', $task->budget) }}" required class="input_dark">
        </div>

        <!-- Add category selection -->
        <div class="form-group">
            <label for="category_id">{{ __('tasks.category') }}:</label>
            <select name="category_id" id="category_id" class="input_dark" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $task->category_id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="des-btn"><i class="fas fa-save"></i> {{ __('tasks.save_changes') }}</button>
        </div>
    </form>
</div>

{{-- Инициализация CKEditor --}}
<link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
<script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection