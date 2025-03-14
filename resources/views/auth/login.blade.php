@extends('template')

@section('title_page')
    {{ __('login.title_page') }}
@endsection

@section('main')
    <style>
        /* Основные стили для формы */
       

        .input_row {
            background-color: #000000;
            color: #ffffff;
            border: 1px solid #a0ff08;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        .input_row::placeholder {
            color: #ccc;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .link-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .link-buttons a {
            height: 100%;
        }

        .link-buttons a img {
            height: 100%;
        }

        .error-message {
            color: red;
            margin-top: 5px;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #ffffff;
        }

        .submit-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0ff08;
            z-index: 2;
            background: none;
            border: none;
            padding: 0;
            font-size: 16px;
        }

        .toggle-password:focus {
            outline: none;
        }

        /* Стили для сообщений об ошибках и статусах */
        .auth-session-status {
            margin-bottom: 20px;
            color: #a0ff08;
            text-align: center;
        }

        .regwin {
            background-color: #0b0c18;
            border: 1px solid gold;
            border-radius: 20px;
            padding: 20px;
            max-width: 90%;
            min-width: 280px;
            margin: 20px auto 20px auto;
            text-align: center;
        }

        .des-btn {
            display: inline-block;
            color: #000000;
            font-size: xx-large;
            background: none; /* Убрали жёлтый фон */
            border: none; /* Убрали границу */
            border-radius: 10px;
            box-shadow: none; /* Убрали тень */
            transition: transform 0.3s ease;
            border: 1px solid gold;
        }

        .des-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            
        }

        .likebtn {
            background: none;
        }

        .task-line {
            color: #00ccff;
            font-size: xx-large;
            margin-bottom: 10px;
        }
    </style>

    <div class="header">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">

        <div class="regwin">
            <div class="form-container">
                <!-- Сообщение о статусе (например, успешный вход) -->
                @if (session('status'))
                    <div class="auth-session-status">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Форма входа -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Поле для имени пользователя -->
                    <div>
                        <label for="name">{{ __('login.username') }}</label>
                        <input id="name" class="input_row" type="text" name="name" value="{{ old('name') }}"
                            required autofocus autocomplete="username"
                            placeholder="{{ __('login.username_placeholder') }}" />
                        @if ($errors->has('name'))
                            <div class="error-message">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <!-- Поле для пароля -->
                    <div class="mt-4">
                        <label for="password">{{ __('login.password') }}</label>
                        <div class="password-container">
                            <input id="password" class="input_row" type="password" name="password" required
                                autocomplete="current-password" placeholder="{{ __('login.password_placeholder') }}" />
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <br>

                    <!-- Кнопка отправки формы -->
                    <div class="button-container">
                        <button type="submit" class="submit-button" title="{{ __('login.login_button') }}">
                            <img src="img/bottom/login.png" alt="{{ __('login.login_button') }}" class="des-btn">
                        </button>
                    </div>
                    <br>

                    <!-- Ссылки на восстановление пароля и регистрацию -->
                    <div class="link-buttons">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" title="{{ __('login.forgot_password') }}"
                                class="likebtn">
                                <img src="img/bottom/forgot2.png" alt="{{ __('login.forgot_password') }}" class="des-btn">
                            </a>
                        @endif
                        <a href="{{ route('register') }}" title="{{ __('login.register') }}" class="likebtn">
                            <img src="img/bottom/registrat.png" alt="{{ __('login.register') }}" class="des-btn">
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Функция для переключения видимости пароля
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
@endsection