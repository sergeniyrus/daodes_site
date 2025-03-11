<x-guest-layout>
    <!-- Сообщение о вводе нового пароля -->
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('forgot.enter_new_password') }}
    </div>

    <!-- Ошибки валидации -->
    @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">{{ __('forgot.something_went_wrong') }}</div>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Контейнер формы -->
    <div class="form-container">
        <h1>{{ __('forgot.title') }}</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT') <!-- Симуляция PUT-запроса -->

            <!-- Скрытое поле для токена -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Новый пароль -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('forgot.new_password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Подтверждение пароля -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('forgot.confirm_password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <!-- Кнопка сброса пароля -->
            <div class="flex items-center justify-center mt-4">
                <x-primary-button>
                    {{ __('forgot.reset_password_button') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>