@extends('template')
@section('title_page')
    Добавить категорию
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

    .btn {
        display: inline-flex;
        align-items: center;
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn i {
        margin-right: 8px;
        font-size: 1.2rem;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    /* Дополнительные стили для кнопок */
    .btn-primary {
        background-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #565e64;
    }
</style>

<div class="container my-5">
    <h1>Добавить новую категорию</h1>

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

    <!-- Форма для добавления категории -->
    <form action="{{ route('task_categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" name="name" id="name" class="form-control" style="color: #000" required>
        </div>

        <!-- Кнопка с иконкой для добавления -->
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Добавить категорию
        </button>
    </form>
</div>
@endsection
