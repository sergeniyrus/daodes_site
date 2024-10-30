@extends('template')
@section('title_page', 'Добавить категорию')
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
        text-align: center;
    }

    label {
        font-size: 1.2rem;
        color: #d7fc09;
        display: block;
        margin-bottom: 10px;
    }

    .input_dark {
        background-color: #000000;
        color: #a0ff08;
        border: 1px solid #a0ff08;
        border-radius: 5px;
        width: 100%;
        padding: 10px;
        margin: 10px auto 15px auto;
        font-size: 1rem;
    }

    .blue_btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1rem;
        background: #0b0c18;
        padding: 10px 20px;
        border: 1px solid #d7fc09;
        border-radius: 10px;
        box-shadow: 0 0 20px #000;
        cursor: pointer;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .blue_btn i {
        margin-right: 8px;
        font-size: 1.2rem;
    }

    .blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #0b0c18;
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
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" name="name" id="name" class="input_dark" required>
        </div>

        <!-- Кнопка с иконкой для добавления -->
        <button type="submit" class="blue_btn">
            <i class="fas fa-plus-circle"></i> Добавить категорию
        </button>
    </form>
</div>
@endsection
