@extends('template')

@section('title_page')
    Регистрация
@endsection

@section('main')
    <style>
        .form-container {
            max-width: 400px; /* Максимальная ширина формы */
            margin: 0 auto; /* Центрирование формы */
            padding: 20px; /* Внутренние отступы */
            background-color: #333333; /* Цвет фона формы */
            border-radius: 10px; /* Скругление углов */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); /* Тень */
            margin-bottom: 40px; /* Нижний отступ */
            display: flex; /* Используем Flexbox для выравнивания */
            flex-direction: column; /* Вертикальное выравнивание */
            align-items: center; /* Центрируем элементы по горизонтали */
        }

        .input_row {
            background-color: #000000; /* Цвет фона полей ввода */
            color: #ffffff; /* Цвет текста */
            border: 1px solid #a0ff08; /* Цвет рамки */
            border-radius: 5px; /* Скругление углов */
            width: 100%; /* Ширина полей ввода */
            padding: 10px; /* Внутренние отступы */
            margin: 10px 0; /* Отступы между полями */
        }

        .input_row::placeholder {
            color: #ccc; /* Цвет текста в плейсхолдере */
        }

        .button-container {
            display: flex;
            justify-content: space-between; /* Размещение кнопок рядом друг с другом */
            align-items: center;
            margin-top: 20px; /* Отступ сверху для кнопок */
            width: 100%;
        }

        .button-container button, .button-container a {
            width: 48%; /* Ширина кнопок, чтобы они располагались рядом */
        }

        .link-buttons {
            display: flex;
            justify-content: center; /* Центрирование кнопок */
            gap: 20px; /* Отступ между кнопками */
            margin-top: 20px; /* Отступ сверху */
        }

        .link-buttons a {
            height: 100%;
        }

        .link-buttons a img {
            height: 100%;
        }

        .error-message {
            color: red; /* Цвет сообщений об ошибках */
            margin-top: 5px; /* Отступ сверху */
            text-align: center; /* Центрирование сообщений об ошибках */
        }

        label {
            display: block; /* Сброс стилей для меток */
            margin: 10px 0 5px; /* Отступы вокруг меток */
        }

        .submit-button {
            width: 100%; /* Ширина кнопки */
            height: 100px; /* Высота кнопки */
            background: none; /* Убираем фон */
            border: 1px solid gold; /* Убираем рамку */
        }

        /* Стиль для кнопки .blue_btn */
        .blue_btn {
            display: inline-block;
            color: #ffffff;
            font-size: large;
            background: #0b0c18;
            padding: 15px 30px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            box-shadow: 0 0 20px #000;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            gap: 15px;
            margin-bottom: 25px;
            width: 100%; /* Убираем фиксированную ширину кнопки */
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            color: #ffffff;
            background: #0b0c18;
        }

        .button-container a img,
        .button-container button img {
            width: 100%; /* Ширина изображения для кнопок */
            height: auto; /* Высота изображения автоматически подстраивается */
        }
    </style>

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="form-container">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="name" :value="__('Username')" class="task-line"/>
            <x-text-input 
                id="name" 
                class="input_row" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="Enter username"
            />
            <x-input-error :messages="$errors->get('name')" class="error-message" />
        </div>

        <!-- Keyword -->
        <div class="mt-4">
            <x-input-label for="keyword" :value="__('Keyword')" class="task-line"/>
            <x-text-input 
                id="keyword" 
                class="input_row" 
                type="text" 
                name="keyword" 
                :value="old('keyword')" 
                required 
                autocomplete="keyword" 
                placeholder="Enter keyword"
            />
            <x-input-error :messages="$errors->get('keyword')" class="error-message" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="task-line" />
            <x-text-input 
                id="password" 
                class="input_row" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password" 
                placeholder="Enter password"
            />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="task-line" />
            <x-text-input 
                id="password_confirmation" 
                class="input_row" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password" 
                placeholder="Confirm password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
        </div>
        <br>
        <!-- Submit and Register Buttons -->
        <div class="button-container">
            <button type="submit" title="Register" class="likebtn">
                <img src="img/bottom/registrat.png" alt="Register" class="blue_btn">
            </button>
            <a href="{{ route('login') }}" class="likebtn" title="Login">
                <img src="img/bottom/login.png" alt="Login" class="blue_btn">
            </a>
        </div>
    </form>
</x-guest-layout>
@endsection
