<!-- resources/views/wallet/wallet.blade.php -->
@extends('template')
@section('title_page')
Кошелёк
@endsection
@section('main')

<div class="modal-content" style="text-align: center;">
    <div class="imgcontainer">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <h1>Мой кошелек</h1>
        <p>Баланс: {{ rtrim(rtrim(number_format($wallet->balance, 8, '.', ''), '0'), '.') }} descoin</p>
        <br>
        <a href="{{ route('wallet.transfer.form') }}" >
            {{ __('Перевести') }}
        </a>
        <a href="{{ route('wallet.history') }}" >
            {{ __('История') }}
        </a>
    </div>
</div>
@endsection