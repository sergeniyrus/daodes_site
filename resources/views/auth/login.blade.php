@extends('template')

@section('title_page')
    Вход в систему
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
            justify-content: center; /* Центрирование кнопок */
            align-items: center;
            margin: 20px 0; /* Отступы между кнопками и формой */
        }

        .link-buttons {
            display: flex;
            justify-content: center; /* Центрирование кнопок */
            gap: 20px; /* Отступ между кнопками */
            margin-top: 20px; /* Отступ сверху */
        }

        .link-buttons a {
            height: 100%;
           /* width: 100px;  Ширина для ссылок */
        }

        .link-buttons a img {
            /*width:auto;  Ширина изображений */
            height:  100%; /* Автоматическая высота */
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
    </style>

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="form-container">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="name" :value="__('Username')" class="task-line" />
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

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="task-line" />
            <x-text-input 
                id="password" 
                class="input_row" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="Enter password"
            />
            <x-input-error :messages="$errors->get('password')" class="error-message" />
        </div>
        <br>

        <!-- Submit Button -->
        <div class="button-container">
            <button type="submit" class="submit-button" title="Login">
                <img src="img/bottom/login.png" alt="Login" class="blue_btn">
            </button>
        </div>
        <br>

        <div class="link-buttons">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" title="Forgot Password?" class="likebtn">
                    <img src="img/bottom/forgot2.png" alt="Forgot Password?" class="blue_btn">
                </a>
            @endif
            <a href="{{ route('register') }}" title="Register" class="likebtn">
                <img src="img/bottom/registrat.png" alt="Register" class="blue_btn">
            </a>
        </div>
    </form>
</x-guest-layout>
@endsection
