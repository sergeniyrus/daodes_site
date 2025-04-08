@extends('template')

@section('title_page', __('wallet.transfer'))

@section('main')
<link  href="{{ asset('css/wallet.css') }}" rel="stylesheet"  type="text/css">
<div class="wallet-container">
    <div class="wallet-header">
        <img src="{{ e($UserProfile->avatar_url ?? '/img/main/img_avatar.jpg') }}" alt="Avatar" class="wallet-avatar">
        <h1 class="wallet-title">{{ __('wallet.fund_transfer') }}</h1>
    </div>

    <div class="wallet-form-container">
        @if(session('info'))
            <div class="auth-session-status">
                {{ session('info') }}
            </div>
        @elseif(session('error'))
            <div class="general-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('wallet.transfer.submit') }}" method="post" id="transferForm">
            @csrf

            <!-- Получатель -->
            <div>
                <label for="recipient" class="wallet-label">{{ __('wallet.recipient_username') }}</label>
                <input id="recipient" class="input_row" type="text" name="recipient" 
                       value="{{ old('recipient') }}" required autofocus
                       placeholder="{{ __('wallet.recipient_placeholder') }}" />
                <div class="error-message" id="recipient-error"></div>
            </div>

            <!-- Сумма -->
            <div class="mt-4">
                <label for="amount" class="wallet-label">{{ __('wallet.amount_excluding') }}</label>
                <input id="amount" class="input_row" type="number" step="0.01" name="amount" 
                       value="{{ old('amount') }}" required
                       placeholder="{{ __('wallet.amount_placeholder') }}" />
                <div class="error-message" id="amount-error"></div>
            </div>

            <!-- Сид-фраза -->
            <div class="mt-4">
                <label for="seed_phrase" class="wallet-label">{{ __('wallet.your_seed_phrase') }}</label>
                <input id="seed_phrase" class="input_row" type="text" name="seed_phrase" 
                       value="{{ old('seed_phrase') }}" required
                       placeholder="{{ __('wallet.seed_phrase_placeholder') }}" />
                <div class="error-message" id="seed_phrase-error"></div>
            </div>

            <!-- Кнопка отправки -->
            <div class="button-container">
                <button type="submit" title="{{ __('wallet.transfer_btn') }}" class="submit-button">
                    <img src="{{ asset('img/bottom/submit.png') }}" alt="{{ __('wallet.transfer_btn') }}" class="wallet-btn">
                </button>
            </div>
        </form>
    </div>

    <div class="wallet-nav">
        <a href="{{ route('wallet.index') }}" class="wallet-btn">{{ __('wallet.my_wallet') }}</a>
        <a href="{{ route('wallet.history') }}" class="wallet-btn">{{ __('wallet.history') }}</a>
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
        
        // Валидация получателя
        const recipientInput = document.getElementById('recipient');
        if (!recipientInput.value.trim()) {
            document.getElementById('recipient-error').textContent = "{{ __('validation.required', ['attribute' => 'recipient']) }}";
            document.getElementById('recipient-error').style.display = 'block';
            isValid = false;
        }

        // Валидация суммы
        const amountInput = document.getElementById('amount');
        if (!amountInput.value.trim()) {
            document.getElementById('amount-error').textContent = "{{ __('validation.required', ['attribute' => 'amount']) }}";
            document.getElementById('amount-error').style.display = 'block';
            isValid = false;
        } else if (parseFloat(amountInput.value) <= 0) {
            document.getElementById('amount-error').textContent = "{{ __('validation.min.numeric', ['attribute' => 'amount', 'min' => 0.01]) }}";
            document.getElementById('amount-error').style.display = 'block';
            isValid = false;
        }

        // Валидация сид-фразы
        const seedPhraseInput = document.getElementById('seed_phrase');
        if (!seedPhraseInput.value.trim()) {
            document.getElementById('seed_phrase-error').textContent = "{{ __('validation.required', ['attribute' => 'seed phrase']) }}";
            document.getElementById('seed_phrase-error').style.display = 'block';
            isValid = false;
        }

        return isValid;
    }

    // Инициализация
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('transferForm');

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