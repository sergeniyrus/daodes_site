@extends('template')
@section('title_page')
    Редактировать категорию
@endsection
@section('main')
<style>
    .form-group {
        margin-bottom: 20px;
    }

    label {
        color: #f8f9fa;
    }

    input {
        background-color: #494a4b;
        color: #fff;
        border: 1px solid #6c757d;
        border-radius: 5px;
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
    }

    button {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 1rem;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<div class="container my-5">
    <h1>Редактировать категорию</h1>

    <!-- Вывод ошибок валидации -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Форма редактирования категории -->
    <form action="{{ route('task_categories.update', $taskCategory) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $taskCategory->name) }}" style="color: #000" required>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
</div>
@endsection
