@extends('template')

@section('title_page')
    {{ __('forgot.title') }}
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

        .error-message {
            color: #ff4444;
            margin-top: 5px;
            font-size: 0.8em;
            display: none;
        }

        .general-error {
            color: #ff4444;
            margin: 10px 0;
            text-align: center;
            display: none;
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

        .regwin {
            background-color: #0b0c18;
            border: 1px solid gold;
            border-radius: 20px;
            padding: 20px;
            max-width: 90%;
            min-width: 280px;
            margin: 20px auto;
            text-align: center;
        }

        .auth-session-status {
            color: #a0ff08;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .form-title {
            color: #a0ff08;
            margin-bottom: 20px;
            font-size: 1.5em;
        }
    </style>

<div class="header">
    <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">

    <div class="regwin">
        <div class="form-container">
            <!-- Статус сессии -->
            @if (session('status'))
                <div class="auth-session-status">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Общая ошибка при неверных данных -->
            <div id="generalError" class="general-error">
                {{ __('forgot.incorrect_data') }}
            </div>

            <h1 class="form-title">{{ __('forgot.title') }}</h1>
            
            <form method="POST" action="{{ route('password.submit') }}" id="forgotForm">
                @csrf

                <!-- Поле для имени пользователя -->
                <div>
                    <label for="username">{{ __('forgot.username') }}</label>
                    <input id="username" class="input_row" type="text" name="username" 
                           value="{{ old('username') }}" required autofocus
                           placeholder="{{ __('forgot.username_placeholder') }}" />
                    <div class="error-message" id="username-error"></div>
                </div>

                <!-- Поле для ключевого слова -->
                <div class="mt-4">
                    <label for="keyword">{{ __('forgot.keyword') }}</label>
                    <input id="keyword" class="input_row" type="text" name="keyword" 
                           value="{{ old('keyword') }}" required
                           placeholder="{{ __('forgot.keyword_placeholder') }}" />
                    <div class="error-message" id="keyword-error"></div>
                </div>

                <!-- Кнопка отправки -->
                <div class="button-container mt-4">
                    <button type="submit" title="{{ __('forgot.submit') }}" class="submit-button">
                        <img src="img/bottom/submit.png" alt="{{ __('forgot.submit') }}" class="des-btn">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Скрыть все сообщения об ошибках
    function hideAllErrors() {
        document.querySelectorAll('.error-message, .general-error').forEach(el => {
            el.style.display = 'none';
        });
    }

    // Валидация формы
    function validateForm() {
        let isValid = true;
        hideAllErrors();
        
        // Валидация имени пользователя
        const usernameInput = document.getElementById('username');
        if (!usernameInput.value.trim()) {
            document.getElementById('username-error').textContent = "{{ __('validation.required', ['attribute' => 'username']) }}";
            document.getElementById('username-error').style.display = 'block';
            isValid = false;
        }

        // Валидация ключевого слова
        const keywordInput = document.getElementById('keyword');
        if (!keywordInput.value.trim()) {
            document.getElementById('keyword-error').textContent = "{{ __('validation.required', ['attribute' => 'keyword']) }}";
            document.getElementById('keyword-error').style.display = 'block';
            isValid = false;
        }

        return isValid;
    }

    // Инициализация
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotForm');

        // Обработчик отправки формы
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });
        }

        // Показать общую ошибку, если она есть
        @if ($errors->any())
            document.getElementById('generalError').style.display = 'block';
        @endif
    });
</script>
@endsection
