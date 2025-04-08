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

        .error-message {
            color: #ff4444;
            margin-top: 5px;
            font-size: 0.8em;
            display: none; /* Скрываем ошибки по умолчанию */
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
            background: none;
            border: none;
            padding: 0;
            font-size: 16px;
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

        /* Стили для чекбокса соглашения */
        .agreement-container {
            margin: 20px 0;
            text-align: center;
        }

        .agreement-check {
            display: inline-flex;
            align-items: center;
            position: relative;
        }

        .gold-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #d4af37;
            border-radius: 3px;
            outline: none;
            cursor: pointer;
            margin-right: 8px;
        }

        .gold-checkbox:checked {
            background-color: #d4af37;
            position: relative;
        }

        .gold-checkbox:checked::after {
            content: "";
            position: absolute;
            left: 3px;
            top: 0;
            width: 5px;
            height: 10px;
            border: solid #000;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .agreement-label {
            color: #ffffff;
            cursor: pointer;
            font-size: 14px;
        }

        .agreement-link {
            color: #d4af37;
            text-decoration: underline;
            margin-left: 4px;
            cursor: pointer;
        }

        /* Стили для модального окна */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #0b0c18;
            border: 2px solid gold;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 20px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #d4af37;
            font-size: 24px;
            cursor: pointer;
        }

        .modal-title {
            color: gold;
            text-align: center;
            margin-bottom: 20px;
        }

        .modal-text {
            color: white;
            text-align: left;
            line-height: 1.5;
        }
    </style>

    <div class="header">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">

        <div class="regwin">
            <div class="form-container">
                @if (session('status'))
                    <div class="auth-session-status">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" novalidate id="registrationForm">
                    @csrf

                    <!-- Поле для имени пользователя -->
                    <div>
                        <label for="name">{{ __('registration.username') }}</label>
                        <input id="name" class="input_row" type="text" name="name" value="{{ old('name') }}"
                            required autofocus autocomplete="username"
                            placeholder="{{ __('registration.username_placeholder') }}" />
                        <div class="error-message" id="name-error"></div>
                    </div>

                    <!-- Поле для ключевого слова -->
                    <div class="mt-4">
                        <label for="keyword">{{ __('registration.keyword') }}</label>
                        <input id="keyword" class="input_row" type="text" name="keyword" value="{{ old('keyword') }}"
                            required autocomplete="keyword" placeholder="{{ __('registration.keyword_placeholder') }}" />
                        <div class="error-message" id="keyword-error"></div>
                    </div>

                    <!-- Поле для пароля -->
                    <div class="mt-4">
                        <label for="password">{{ __('registration.password') }}</label>
                        <div class="password-container">
                            <input id="password" class="input_row" type="password" name="password" required
                                autocomplete="new-password" placeholder="{{ __('registration.password_placeholder') }}" />
                            <button type="button" class="toggle-password"
                                onclick="togglePasswordVisibility('password')">👁️</button>
                        </div>
                        <div class="error-message" id="password-error"></div>
                    </div>

                    <!-- Поле для подтверждения пароля -->
                    <div class="mt-4">
                        <label for="password_confirmation">{{ __('registration.confirm_password') }}</label>
                        <div class="password-container">
                            <input id="password_confirmation" class="input_row" type="password" name="password_confirmation"
                                required autocomplete="new-password"
                                placeholder="{{ __('registration.confirm_password_placeholder') }}" />
                            <button type="button" class="toggle-password"
                                onclick="togglePasswordVisibility('password_confirmation')">👁️</button>
                        </div>
                        <div class="error-message" id="password_confirmation-error"></div>
                    </div>

                    <!-- Чекбокс соглашения -->
                    <div class="agreement-container">
                        <div class="agreement-check">
                            <input type="checkbox" required id="agreeToTerms" name="agreeToTerms" class="gold-checkbox">
                            <label for="agreeToTerms" class="agreement-label">
                                {{ __('registration.agree_text') }}
                                <span onclick="openModal()" class="agreement-link">{{ __('registration.agree_link') }}</span>
                            </label>
                        </div>
                        <div class="error-message" id="agreeToTerms-error"></div>
                    </div>

                    <!-- Кнопки формы -->
                    <div class="button-container">
                        <button type="submit" title="{{ __('registration.register_button') }}" class="submit-button" id="registerButton">
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

    <!-- Модальное окно с соглашением -->
    <div id="agreementModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2 class="modal-title">{{ __('agreement.title') }}</h2>
            <div class="modal-text">
                @include('modals.daooffer')
            </div>
        </div>
    </div>

    <script>
        // Функция для переключения видимости пароля
        function togglePasswordVisibility(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }

        // Открытие модального окна
        function openModal() {
            document.getElementById('agreementModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Закрытие модального окна
        function closeModal() {
            document.getElementById('agreementModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Скрыть все сообщения об ошибках
        function hideAllErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
            });
        }

        // Валидация формы
        function validateForm() {
            let isValid = true;
            hideAllErrors(); // Сначала скрываем все ошибки
            
            // Валидация имени пользователя
            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('name-error');
            if (!nameInput.value.trim()) {
                showError(nameError, "{{ __('registration.username_required') }}");
                isValid = false;
            } else if (nameInput.value.length < 3) {
                showError(nameError, "{{ __('registration.name_min', ['min' => 3]) }}");
                isValid = false;
            } else if (nameInput.value.length > 20) {
                showError(nameError, "{{ __('registration.name_max', ['max' => 20]) }}");
                isValid = false;
            } else if (!/^[a-zA-Z0-9_-]+$/.test(nameInput.value)) {
                showError(nameError, "{{ __('registration.name_error') }}");
                isValid = false;
            }

            // Валидация ключевого слова
            const keywordInput = document.getElementById('keyword');
            const keywordError = document.getElementById('keyword-error');
            if (!keywordInput.value.trim()) {
                showError(keywordError, "{{ __('registration.keyword_required') }}");
                isValid = false;
            } else if (keywordInput.value.length < 3) {
                showError(keywordError, "{{ __('registration.keyword_min', ['min' => 3]) }}");
                isValid = false;
            } else if (keywordInput.value.length > 50) {
                showError(keywordError, "{{ __('registration.keyword_max', ['max' => 50]) }}");
                isValid = false;
            }

            // Валидация пароля
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('password-error');
            if (!passwordInput.value.trim()) {
                showError(passwordError, "{{ __('registration.password_required') }}");
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                showError(passwordError, "{{ __('registration.password_min', ['min' => 8]) }}");
                isValid = false;
            } else if (passwordInput.value.length > 30) {
                showError(passwordError, "{{ __('registration.password_max', ['max' => 30]) }}");
                isValid = false;
            }

            // Подтверждение пароля
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const confirmPasswordError = document.getElementById('password_confirmation-error');
            if (passwordInput.value !== confirmPasswordInput.value) {
                showError(confirmPasswordError, "{{ __('registration.password_confirmed') }}");
                isValid = false;
            }

            // Проверка согласия с условиями
            const agreeCheckbox = document.getElementById('agreeToTerms');
            const agreeError = document.getElementById('agreeToTerms-error');
            if (!agreeCheckbox.checked) {
                showError(agreeError, "{{ __('registration.agree_required') }}");
                isValid = false;
            }

            return isValid;
        }

        // Показать ошибку
        function showError(element, message) {
            element.textContent = message;
            element.style.display = 'block';
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const modal = document.getElementById('agreementModal');
            const registerButton = document.getElementById('registerButton');

            // Обработчик отправки формы
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateForm()) {
                        e.preventDefault();
                    }
                });
            }

            // Обработчик клика по кнопке регистрации
            if (registerButton) {
                registerButton.addEventListener('click', function(e) {
                    validateForm();
                });
            }

            // Закрытие модального окна по клику вне области
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeModal();
                });
            }
        });
    </script>
@endsection