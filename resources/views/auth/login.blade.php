@extends('template')

@section('title_page')
    {{ __('login.title_page') }}
@endsection

@section('main')
    <style>
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #333333;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

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
        }

        .submit-button {
            width: 100%;
            height: 100px;
            background: none;
            border: 1px solid gold;
        }
    </style>

    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="form-container">
            @csrf

            <!-- Username -->
            <div>
                <x-input-label for="name" :value="__('login.username')" class="task-line" />
                <x-text-input 
                    id="name" 
                    class="input_row" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="{{ __('login.username_placeholder') }}"
                />
                <x-input-error :messages="$errors->get('name')" class="error-message" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('login.password')" class="task-line" />
                <x-text-input 
                    id="password" 
                    class="input_row" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="{{ __('login.password_placeholder') }}"
                />
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>
            <br>

            <!-- Submit Button -->
            <div class="button-container">
                <button type="submit" class="submit-button" title="{{ __('login.login_button') }}">
                    <img src="img/bottom/login.png" alt="{{ __('login.login_button') }}" class="blue_btn">
                </button>
            </div>
            <br>

            <div class="link-buttons">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" title="{{ __('login.forgot_password') }}" class="likebtn">
                        <img src="img/bottom/forgot2.png" alt="{{ __('login.forgot_password') }}" class="blue_btn">
                    </a>
                @endif
                <a href="{{ route('register') }}" title="{{ __('login.register') }}" class="likebtn">
                    <img src="img/bottom/registrat.png" alt="{{ __('login.register') }}" class="blue_btn">
                </a>
            </div>
        </form>
    </x-guest-layout>
@endsection