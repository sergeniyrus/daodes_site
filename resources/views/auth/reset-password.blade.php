<x-guest-layout>
    @if (session('status'))
        <div class="mb-4 font-medium text-green-600">
            {{ session('status') }}
        </div>
    @else
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Сброс пароля. Пожалуйста, введите новый пароль и его подтверждение.') }}
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
            <h1>Сброс пароля</h1>
            <form method="POST" action="{{ route('custom.password.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="user_id" value="{{ $user_id }}">

                <!-- Новый пароль -->
                <div>
                    <x-input-label for="password" :value="__('Новый пароль')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Подтвердите новый пароль -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Подтвердите новый пароль')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-4">
                    <x-primary-button>
                        {{ __('Сбросить пароль') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    @endif
</x-guest-layout>
