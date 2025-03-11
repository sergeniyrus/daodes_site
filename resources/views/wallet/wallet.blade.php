@extends('template')
@section('title_page', __('wallet.my_wallet'))
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

        .balance {
            font-size: 1.5rem;
            color: #d7fc09;
            text-align: center;
            margin-bottom: 30px;
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
        <h1>{{ __('wallet.my_wallet') }}</h1>
    </div>

    @if(session('info'))
        <div class="alert alert-info" style="text-align: center">{{ session('info') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger" style="text-align: center">{{ session('error') }}</div>
    @endif

    <div class="text-center">
        <img src="{{ $UserProfile->avatar_url ?? '/img/main/img_avatar.jpg' }}" alt="Avatar" class="avatar">
    </div>

    <div class="balance">
        <p>{{ __('wallet.balance') }}: {{ rtrim(rtrim(number_format($wallet->balance, 8, '.', ''), '0'), '.') }} descoin</p>
    </div>

    <div style="text-align: center">
        <a href="{{ route('wallet.transfer.form') }}" class="blue_btn">{{ __('wallet.transfer') }}</a>
        <a href="{{ route('wallet.history') }}" class="blue_btn">{{ __('wallet.history') }}</a>
    </div>
</div>
@endsection
