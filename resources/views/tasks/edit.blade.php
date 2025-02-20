@extends('template')
@section('title_page', 'Редактировать задание')
@section('main')

<style>
    .container {
    padding: 20px; /* Увеличил отступы для более комфортного восприятия */
    margin: 0 auto;
    max-width: 800px;
    background-color: rgba(20, 20, 20, 0.9); /* Сделал фон темнее и менее прозрачным */
    border-radius: 20px; /* Увеличил радиус скругления для более мягких углов */
    border: 1px solid #d7fc09; /* Золотая граница для контейнера */
    color: #f8f9fa;
    font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
    margin-top: 30px; /* Верхний отступ для контейнера */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Добавил тень для выделения контейнера */
}

.form-group label {
    color: #d7fc09;
    font-size: 1.2rem;
    display: block;
    margin: 10px 0; /* Убрал автоматические отступы по горизонтали */
    text-align: left; /* Вернул выравнивание текста влево для большей читабельности */
    font-weight: bold; /* Сделал текст более заметным */
}

.input_dark, textarea {
    background-color: #1a1a1a; /* Темнее фон для инпутов */
    color: #a0ff08;
    border: 1px solid #d7fc09; /* Заменил на золотую границу */
    border-radius: 5px;
    width: 100%;
    padding: 12px; /* Увеличил внутренний отступ для удобства */
    margin-top: 5px;
    transition: border 0.3s ease; /* Плавный переход для изменения границы */
}

.input_dark:focus, textarea:focus {
    border: 1px solid #a0ff08; /* Граница при фокусе */
    outline: none; /* Убрал стандартный контур */
    box-shadow: 0 0 5px #d7fc09; /* Эффект свечения при фокусе */
}

.blue_btn {
    display: inline-block;
    color: #ffffff;
    font-size: 1.2rem; /* Увеличил размер шрифта */
    background: #0b0c18;
    padding: 12px 25px; /* Увеличил отступы для кнопки */
    border: 1px solid #d7fc09;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Добавил тень для кнопки */
    cursor: pointer;
    transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease; /* Добавил плавный переход для цвета фона */
    margin-top: 20px;
}

.blue_btn:hover {
    box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09; /* Эффект свечения при наведении */
    transform: scale(1.05);
    background: #1a1a1a; /* Сделал фон кнопки чуть светлее при наведении */
}
</style>

<div class="container">
    <div class="text-center mb-4">
        <h1 class="display-4">Edit Task</h1>
    </div>

    <!-- Display success flash messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Task editing form -->
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT') <!-- Specify the PUT method for updating -->

        <div class="form-group">
            <label for="title">Task Title:</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="input_dark">
        </div>

        <div class="form-group">
            <label for="content">Task Description:</label>
            <textarea name="content" id="editor" rows="5" required class="input_dark">{{ old('content', $task->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="deadline">Deadline:</label>
            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $task->deadline) }}" required class="input_dark">
        </div>

        <div class="form-group">
            <label for="budget">Budget:</label>
            <input type="number" name="budget" id="budget" step="0.01" value="{{ old('budget', $task->budget) }}" required class="input_dark">
        </div>

        <!-- Add category selection -->
        <div class="form-group">
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" class="input_dark" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $task->category_id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="blue_btn"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
{{-- // Инициализация CKEditor --}}
<link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
<script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection
