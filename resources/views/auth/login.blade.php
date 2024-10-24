@extends('template')

@section('title_page')
    Вход в систему
@endsection

@section('main')
    <style>
        .input_row {
            background-color: #000000;
            color: #fff;
            border: 1px solid #a0ff08;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            margin: 10px auto;
        }

        .button-container {
            text-align: center;
            background: none;
        }

        .blue_btn {
            display: inline-block;
            color: #000000;
            font-size: large;
            background-color: #d7fc09;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px #fcfbfb;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            color: #fff;
        }

        .likebtn {
            background: none;
        }
    </style>
    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="container my-5">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Вход -->
                <div class="">
                    <label  for="name">Логин</label>
                    <input class="input_row" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="username">
                    @error('name')
                        <br><span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="">
                    <label for="password">Пароль</label>
                    <input class="input_row" id="password" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <br><span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>   
            
            <br>
            <!-- Submit button -->
            <div class="button-container">
                <button type="submit" class="likebtn" title="Войти">
                    <img src="img/bottom/login.png" alt="Войти" class="blue_btn">
                </button>
            </div>
        </form>
            <br>
            <!-- Links -->
            <div class="button-container">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" title="Забыли пароль?" class="likebtn">
                        <img src="img/bottom/forgot2.png" alt="Забыли пароль?" class="blue_btn" >
                    </a>
                @endif
                <a href="{{ route('register') }}" title="Регистрация" class="likebtn">
                    <img src="img/bottom/registrat.png" alt="Регистрация" class="blue_btn">
                </a>
            </div>
        </div>
    </x-guest-layout>
@endsection
