@extends('template')
@section('title_page', 'Редактировать категорию')
@section('main')

<style>
    .container {
        padding: 20px; /* Увеличил отступы для более комфортного восприятия */
        margin: 0 auto;
        max-width: 600px; /* Максимальная ширина для контейнера */
        background-color: rgba(20, 20, 20, 0.9); /* Темный фон для контейнера */
        border: 1px solid #d7fc09; /* Золотая граница для контейнера */
        border-radius: 20px; /* Скругленные углы */
        color: #f8f9fa; /* Цвет текста */
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif; /* Шрифт */
        text-align: center; /* Центрирование текста */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Тень для выделения */
        margin-top: 30px; /* Отступ сверху */
    }

    .form-group {
        margin-bottom: 20px;
        text-align: center; /* Центрирование содержимого внутри формы */
    }

    label {
        font-size: 1.2rem; /* Размер шрифта для метки */
        color: #d7fc09; /* Цвет метки */
        display: block; /* Блочный элемент */
        margin-bottom: 10px; /* Отступ снизу */
    }

    .input_dark {
        background-color: #000000; /* Цвет фона */
        color: #a0ff08; /* Цвет текста */
        border: 1px solid #a0ff08; /* Граница */
        border-radius: 5px; /* Скругление углов */
        width: 100%; /* Ширина */
        padding: 10px; /* Отступ внутри */
        margin: 10px auto 15px auto; /* Отступы */
        font-size: 1rem; /* Размер шрифта */
    }

    .des-btn {
        display: inline-flex; /* Гибкая компоновка */
        align-items: center; /* Центрирование по вертикали */
        justify-content: center; /* Центрирование по горизонтали */
        color: #ffffff; /* Цвет текста */
        font-size: 1rem; /* Размер шрифта */
        background: #0b0c18; /* Цвет фона */
        padding: 10px 20px; /* Отступы */
        border: 1px solid #d7fc09; /* Граница */
        border-radius: 10px; /* Скругленные углы */
        box-shadow: 0 0 20px #000; /* Тень */
        cursor: pointer; /* Указатель при наведении */
        transition: box-shadow 0.3s ease, transform 0.3s ease; /* Плавные переходы */
        margin-top: 15px; /* Отступ сверху для кнопки */
    }

    .des-btn i {
        margin-right: 8px; /* Отступ справа для иконки */
        font-size: 1.2rem; /* Размер иконки */
    }

    .des-btn:hover {
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

<div class="container my-5">
    <h1>Редактировать категорию Заданий</h1>

    <!-- Вывод ошибок валидации -->
    @if($errors->any())
        <div class="alert">
            
                @foreach ($errors->all() as $error)
                    >{{ $error }}
                @endforeach
            
        </div>
    @endif

    <!-- Форма редактирования категории -->
    <form action="{{ route('taskscategories.update', $taskCategory) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" name="name" id="name" class="input_dark" value="{{ old('name', $taskCategory->name) }}" required>
        </div>

        <button type="submit" class="des-btn">
            <i class="fas fa-save"></i> Сохранить изменения
        </button>
    </form>
</div>
@endsection
