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
        }

        .general-error ul {
            list-style-type: none;
            padding: 0;
            margin: 5px 0 0;
        }

        .general-error li {
            margin: 3px 0;
            font-size: 0.9em;
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

        .success-message {
            color: #a0ff08;
            margin-bottom: 15px;
            font-size: 1em;
            padding: 10px;
            border: 1px solid #a0ff08;
            border-radius: 5px;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 2px solid gold;
        }
    </style>

<div class="header">
    <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">

    <div class="regwin">
        <div class="form-container">
            <!-- Статус сессии -->
            {{-- @if (session('status'))
                <div class="success-message">
                    {{ __('message.password_reset_success') }} {{ __('message.can_login_with_new_password') }}
                </div>
            @endif --}}

            <!-- Общая ошибка при неверных данных -->
            @if ($errors->any())
                <div class="general-error">
                    <div>{{ __('forgot.something_went_wrong') }}</div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="form-title">{{ __('forgot.title') }}</h1>
            
            <form method="POST" action="{{ route('password.update') }}" id="passwordResetForm">
                @csrf
                @method('PUT')

                <!-- Новый пароль -->
                <div>
                    <label for="password">{{ __('forgot.new_password') }}</label>
                    <input id="password" class="input_row" type="password" name="password" required 
                           placeholder="{{ __('forgot.enter_new_password') }}" />
                    <div class="error-message" id="password-error"></div>
                </div>

                <!-- Подтверждение пароля -->
                <div class="mt-4">
                    <label for="password_confirmation">{{ __('forgot.confirm_password') }}</label>
                    <input id="password_confirmation" class="input_row" type="password" 
                           name="password_confirmation" required 
                           placeholder="{{ __('forgot.confirm_password') }}" />
                    <div class="error-message" id="password-confirmation-error"></div>
                </div>

                <!-- Кнопка сброса пароля -->
                <div class="button-container mt-4">
                    <button type="submit" title="{{ __('forgot.reset_password_button') }}" class="submit-button">
                        <img src="img/bottom/submit.png" alt="{{ __('forgot.reset_password_button') }}" class="des-btn">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Скрыть все сообщения об ошибках
    function hideAllErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.style.display = 'none';
        });
    }

    // Валидация формы
    function validateForm() {
        let isValid = true;
        hideAllErrors();
        
        // Валидация пароля
        const passwordInput = document.getElementById('password');
        if (!passwordInput.value.trim()) {
            document.getElementById('password-error').textContent = "{{ __('validation.required', ['attribute' => 'password']) }}";
            document.getElementById('password-error').style.display = 'block';
            isValid = false;
        } else if (passwordInput.value.length < 8) {
            document.getElementById('password-error').textContent = "{{ __('validation.min.string', ['attribute' => 'password', 'min' => 8]) }}";
            document.getElementById('password-error').style.display = 'block';
            isValid = false;
        }

        // Валидация подтверждения пароля
        const passwordConfirmInput = document.getElementById('password_confirmation');
        if (passwordInput.value !== passwordConfirmInput.value) {
            document.getElementById('password-confirmation-error').textContent = "{{ __('validation.confirmed', ['attribute' => 'password']) }}";
            document.getElementById('password-confirmation-error').style.display = 'block';
            isValid = false;
        }

        return isValid;
    }

    // Инициализация
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('passwordResetForm');

        // Обработчик отправки формы
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endsection