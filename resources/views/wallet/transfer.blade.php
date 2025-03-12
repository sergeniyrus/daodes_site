@extends('template')
@section('title_page', __('wallet.transfer')) 
@section('main')
    <style>
        .container {
            padding: 15px;
            margin: 0 auto;
            max-width: 800px;
            background-color: rgba(30, 32, 30, 0.753);
            border-radius: 15px;
            border: 1px solid #d7fc09;
            color: #f8f9fa;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-top: 30px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #d7fc09;
            margin-bottom: 20px;
        }

        h1 {
            color: #ffffff;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
        }

        .blue_btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
            background: #0b0c18;
            padding: 10px 20px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            text-decoration: none;
            margin-top: 10px;
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            background: #0b0c18;
        }
    </style>

<div class="container">
    <div class="text-center mb-4">
        <h1>{{ __('wallet.fund_transfer') }}</h1> <!-- Перевод заголовка -->
    </div>

    {{-- @if(session('info'))
        <div class="alert alert-info" style="text-align: center">{{ session('info') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger" style="text-align: center">{{ session('error') }}</div>
    @endif --}}

    <div class="text-center">
        <img src="{{ e($UserProfile->avatar_url ?? '/img/main/img_avatar.jpg') }}" alt="Avatar" class="avatar">
    </div>

    <div class="container">
        <!-- ... (остальная логика отображения ошибок без изменений) ... -->

        <form action="{{ route('wallet.transfer') }}" method="post">
            @csrf

            <div>
                <x-input-label for="recipient" :value="__('wallet.recipient_username')" />
                <x-text-input id="recipient" class="block mt-1 w-full" type="text" name="recipient" value="{{ old('recipient') }}" required autofocus />
                @error('recipient')
                    <div class="font-medium text-red-600">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mt-4">
                <x-input-label for="amount" :value="__('wallet.amount_excluding')" />
                <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" value="{{ old('amount') }}" required />
                @error('amount')
                    <div class="font-medium text-red-600">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mt-4">
                <x-input-label for="seed_phrase" :value="__('wallet.your_seed_phrase')" />
                <x-text-input id="seed_phrase" class="block mt-1 w-full" type="text" name="seed_phrase" value="{{ old('seed_phrase') }}" required />
                @error('seed_phrase')
                    <div class="font-medium text-red-600">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="blue_btn">
                    {{ __('wallet.transfer_btn') }} <!-- Перевод кнопки -->
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
