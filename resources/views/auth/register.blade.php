@extends('template')

@section('title_page')
    {{ __('registration.title_page') }}
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
            padding: 0 10px;
            
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

        .likebtn {
            background: none;
        }

        .task-line {
            color: #00ccff;
            font-size: xx-large;
            margin-bottom: 10px;
        }

        /* Стили для reCAPTCHA */
        .recaptcha-wrapper {
            width: 100%;
            margin: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .g-recaptcha {
            display: inline-block;
            transform: scale(0.9);
        }
    </style>

    <div class="header">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">

        <div class="regwin">
            <div class="form-container">
                <!-- Сообщение о статусе (например, успешная регистрация) -->
                @if (session('status'))
                    <div class="auth-session-status">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Форма регистрации -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Поле для имени пользователя -->
                    <div>
                        <label for="name">{{ __('registration.username') }}</label>
                        <input id="name" class="input_row" type="text" name="name" value="{{ old('name') }}"
                            required autofocus autocomplete="username"
                            placeholder="{{ __('registration.username_placeholder') }}" />
                        @if ($errors->has('name'))
                            <div class="error-message">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <!-- Поле для ключевого слова -->
                    <div class="mt-4">
                        <label for="keyword">{{ __('registration.keyword') }}</label>
                        <input id="keyword" class="input_row" type="text" name="keyword" value="{{ old('keyword') }}"
                            required autocomplete="keyword"
                            placeholder="{{ __('registration.keyword_placeholder') }}" />
                        @if ($errors->has('keyword'))
                            <div class="error-message">{{ $errors->first('keyword') }}</div>
                        @endif
                    </div>

                    <!-- Поле для пароля -->
                    <div class="mt-4">
                        <label for="password">{{ __('registration.password') }}</label>
                        <div class="password-container">
                            <input id="password" class="input_row" type="password" name="password" required
                                autocomplete="new-password" placeholder="{{ __('registration.password_placeholder') }}" />
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password')">👁️</button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <!-- Поле для подтверждения пароля -->
                    <div class="mt-4">
                        <label for="password_confirmation">{{ __('registration.confirm_password') }}</label>
                        <div class="password-container">
                            <input id="password_confirmation" class="input_row" type="password" name="password_confirmation"
                                required autocomplete="new-password"
                                placeholder="{{ __('registration.confirm_password_placeholder') }}" />
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password_confirmation')">👁️</button>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    <!-- reCAPTCHA -->
                    {{-- <div class="recaptcha-wrapper mt-4">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    </div>
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="error-message">
                            {{ $errors->first('g-recaptcha-response') }}
                        </span>
                    @endif
                    <br> --}}

                    <!-- Кнопки отправки формы и перехода к входу -->
                    <div class="button-container">
                        <button type="submit" title="{{ __('registration.register_button') }}" class="submit-button">
                            <img src="img/bottom/registrat.png" alt="{{ __('registration.register_button') }}" class="des-btn">
                        </button>
                        <a href="{{ route('login') }}" class="submit-button" title="{{ __('registration.login_button') }}">
                            <img src="img/bottom/login.png" alt="{{ __('registration.login_button') }}" class="des-btn">
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Подключение скрипта reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?nl=en" async defer></script>

    <script>
        // Функция для переключения видимости пароля
        function togglePasswordVisibility(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
@endsection