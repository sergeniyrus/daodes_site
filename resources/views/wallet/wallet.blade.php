@extends('template')

@section('title_page', __('wallet.my_wallet'))

@section('main')
<link  href="{{ asset('css/wallet.css') }}" rel="stylesheet"  type="text/css">

<div class="wallet-container">
    <div class="wallet-header">
        <img src="{{ $UserProfile->avatar_url ?? '/img/main/img_avatar.jpg' }}" alt="Avatar" class="wallet-avatar">
        <h1 class="wallet-title">{{ __('wallet.my_wallet') }}</h1>
    </div>

    @if(session('info'))
        <div class="auth-session-status">
            {{ session('info') }}
        </div>
    @elseif(session('error'))
        <div class="general-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="wallet-balance">
        <p>{{ __('wallet.balance') }}: {{ rtrim(rtrim(number_format($wallet->balance, 8, '.', ''), '0'), '.') }} descoin</p>
    </div>

    <div class="wallet-nav">
        <a href="{{ route('wallet.transfer.form') }}" class="wallet-btn">{{ __('wallet.transfer') }}</a>
        <a href="{{ route('wallet.history') }}" class="wallet-btn">{{ __('wallet.history') }}</a>
    </div>
</div>
@endsection