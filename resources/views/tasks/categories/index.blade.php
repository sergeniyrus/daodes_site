@extends('template')
@section('title_page', 'Управление категориями')
@section('main')

<style>
    .container {
        padding: 20px; /* Увеличил отступы для более комфортного восприятия */
        margin: 0 auto;
        justify-content: center;
        align-items: center;
        text-align: center;
        max-width: 800px; /* Максимальная ширина для контейнера */
        background-color: rgba(20, 20, 20, 0.9); /* Темный фон для контейнера */
        border-radius: 20px; /* Скругленные углы */
        border: 1px solid #d7fc09; /* Золотая граница для контейнера */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Тень для выделения */
        color: #f8f9fa; /* Цвет текста */
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif; /* Шрифт */
        margin-top: 30px; /* Отступ сверху */
    }

    .new_post {
        width: 90%;
        height: auto;
        margin: 20px auto;
        padding-bottom: 20px;
        color: #fff;
        background-color: rgba(30, 30, 30, 0.9); /* Сделал фон менее прозрачным */
        border: 1px solid #fff;
        font-size: 20px;
        border-radius: 30px; /* Скругление углов для блока */
        display: flex;
        flex-direction: column;
        align-items: center; /* Центрирование содержимого */
    }

    .table {
        width: 90%;
        margin: 30px auto; /* Выровнять таблицу по центру */
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid #ccc; /* Светлый цвет границ */
        padding: 10px;
        text-align: center;
        color: #fff; /* Цвет текста в ячейках */
    }

    .table th {
        background-color: #444; /* Цвет фона заголовка */
    }

    .blue_btn {
        display: inline-block;
        color: #ffffff;
        font-size: large;
        background: #0b0c18;
        padding: 10px 20px; /* Увеличил отступы для кнопки */
        border: 1px solid #d7fc09; /* Золотая граница */
        border-radius: 10px; /* Скругленные углы для кнопки */
        box-shadow: 0 0 20px #000; /* Тень для кнопки */
        transition: box-shadow 0.3s ease, transform 0.3s ease; /* Плавные переходы */
        margin-top: 10px; /* Отступ сверху для кнопок */
        cursor: pointer; /* Указатель при наведении */
    }

    .blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09; /* Эффект свечения при наведении */
        transform: scale(1.05); /* Увеличение кнопки при наведении */
        background: #1a1a1a; /* Смена фона кнопки при наведении */
    }
    .alert {
        background-color: rgba(255, 0, 0, 0.8); /* Полупрозрачный красный фон */
        color: #f8f9fa; /* Цвет текста */
        border: 1px solid #d7fc09; /* Золотая граница */
        border-radius: 10px; /* Скругленные углы */
        padding: 15px; /* Отступы внутри */
        margin: 20px 0; /* Отступы сверху и снизу */
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif; /* Шрифт */
        text-align: left; /* Выровнять текст влево */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Тень для выделения */
    }
</style>

<div class="container">
    <h1>Категории Биржи заданий</h1>
    <div class="new_post">
        @if(session('success'))
            <div class="alert alert-success">
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
                    <td>{{ $category->name }}</td>
                    <td>
                        <!-- Кнопка редактирования с классом blue_btn -->
                        <a href="{{ route('taskscategories.edit', $category) }}" class="blue_btn" title="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Кнопка удаления с классом blue_btn -->
                        <form action="{{ route('taskscategories.destroy', $category) }}" method="POST" style="display:inline;">
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
        
        <!-- Кнопка добавления категории с классом blue_btn -->
        <form action="{{ route('taskscategories.create') }}" method="GET" style="display:inline;">
            <button type="submit" class="blue_btn"> <i class="fas fa-plus-circle"></i> Добавить категорию</button>
        </form>
    </div>
</div>
@endsection
