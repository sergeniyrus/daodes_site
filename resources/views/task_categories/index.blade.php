@extends('template')
@section('title_page', 'Управление категориями')
@section('main')

<style>
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

    /* Стили для разных типов кнопок */
    .btn-primary {
        background-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .table {
        width: 100%;
        margin-top: 20px;
        border: 1px solid #ddd;
        background-color: #fff;
        color: #333;
    }

    .table th, .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
</style>

<div class="container">
    <h1>Категории задач</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Кнопка добавления категории с иконкой -->
    <a href="{{ route('task_categories.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i> Добавить категорию
    </a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    <!-- Кнопка редактирования с иконкой -->
                    <a href="{{ route('task_categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                    <!-- Кнопка удаления с иконкой -->
                    <form action="{{ route('task_categories.destroy', $category) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Удалить
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
