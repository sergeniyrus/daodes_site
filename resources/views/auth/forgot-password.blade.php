<x-guest-layout>
    <!-- Описание формы -->
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('forgot.description') }}
    </div>

    <!-- Статус сессии -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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
        <form method="POST" action="{{ route('password.submit') }}">
            @csrf

            <!-- Поле для имени пользователя -->
            <div>
                <x-input-label for="username" :value="__('forgot.username')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
            </div>

            <!-- Поле для ключевого слова -->
            <div class="mt-4">
                <x-input-label for="keyword" :value="__('forgot.keyword')" />
                <x-text-input id="keyword" class="block mt-1 w-full" type="text" name="keyword" :value="old('keyword')" required />
            </div>

            <!-- Кнопка отправки -->
            <div class="flex items-center justify-center mt-4">
                <x-primary-button>
                    {{ __('forgot.submit') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>