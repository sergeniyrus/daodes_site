<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Забыли пароль? Без проблем. Введите ваше имя пользователя и ключевое слово для сброса пароля.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">{{ __('Что-то пошло не так.') }}</div>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <h1>Восстановление пароля</h1>
        <form method="POST" action="{{ route('custom.password.submit') }}">
            @csrf

            <!-- Username -->
            <div>
                <x-input-label for="username" :value="__('Имя пользователя')" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
                {{-- <x-input-error :messages="$errors->get('username')" class="mt-2" /> --}}
            </div>

            <!-- Keyword -->
            <div class="mt-4">
                <x-input-label for="keyword" :value="__('Ключевое слово')" />
                <x-text-input id="keyword" class="block mt-1 w-full" type="text" name="keyword" :value="old('keyword')" required />
                {{-- <x-input-error :messages="$errors->get('keyword')" class="mt-2" /> --}}
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-primary-button>
                    {{ __('Отправить') }}
                </x-primary-button>
            </div>

            <!-- Remaining Attempts -->
            @if (session('attempts'))
                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Осталось попыток до блокировки: :attempts', ['attempts' => session('attempts')]) }}
                </div>
            @endif

            <!-- Lockout Message -->
            @if (session('lockout'))
                <div class="mt-4 text-sm text-red-600 dark:text-red-400">
                    {{ __('Повторите попытку через :time', ['time' => session('lockout')]) }}
                </div>
            @endif
        </form>
    </div>
</x-guest-layout>
