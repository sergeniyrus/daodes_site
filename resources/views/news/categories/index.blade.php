@extends('template')
@section('title_page', 'Управление категориями новостей')

@section('main')
<style>
    .container {
        padding: 20px;
        margin: 0 auto;
        text-align: center;
        max-width: 800px;
        background-color: rgba(20, 20, 20, 0.9);
        border-radius: 20px;
        border: 1px solid #d7fc09;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        color: #f8f9fa;
        font-family: 'Verdana', sans-serif;
        margin-top: 30px;
    }

    .new_post {
        width: 90%;
        margin: 20px auto;
        padding-bottom: 20px;
        color: #fff;
        background-color: rgba(30, 30, 30, 0.9);
        border: 1px solid #fff;
        font-size: 20px;
        border-radius: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        color: #fff;
    }

    .table th {
        background-color: #444;
    }

    .blue_btn {
        display: inline-block;
        color: #ffffff;
        font-size: large;
        background: #0b0c18;
        padding: 10px 20px;
        border: 1px solid #d7fc09;
        border-radius: 10px;
        box-shadow: 0 0 20px #000;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        margin-top: 10px;
        cursor: pointer;
    }

    .blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #1a1a1a;
    }
    .alert {
        background-color: rgba(255, 0, 0, 0.8); /* Полупрозрачный красный фон */
        color: #f8f9fa; /* Цвет текста */
        border: 1px solid #d7fc09; /* Золотая граница */
        border-radius: 10px; /* Скругленные углы */
        padding: 15px; /* Отступы внутри */
        margin-top: 20px; /* Отступы сверху и снизу */
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif; /* Шрифт */
        text-align: left; /* Выровнять текст влево */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Тень для выделения */
    }

    
</style>

<div class="container">
    <h2>Категории новостей</h2>
    <div class="new_post">
        @if(session('success'))
            <br><div class="alert">
                {{ session('success') }}
            </div>
        @endif

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
                    <td>{{ $category->category_name }}</td> <!-- Убедитесь, что здесь используется корректное поле названия -->
                    <td>
                        <a href="{{ route('newscategories.edit', $category->id) }}" class="blue_btn" title="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('newscategories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="blue_btn" title="Удалить">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <form action="{{ route('newscategories.create') }}" method="GET" style="display:inline;">
            <button type="submit" class="blue_btn">
                <i class="fas fa-plus-circle"></i> Добавить категорию
            </button>
        </form>
    </div>
</div>
@endsection
