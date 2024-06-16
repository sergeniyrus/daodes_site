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
        <a href="{{ route('wallet.showTransferForm') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ms-3 no-underline text-gray-700 hover:text-gray-700 visited:text-gray-700">
            {{ __('Перевести') }}
        </a>
        <a href="{{ route('wallet.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ms-3 no-underline text-gray-700 hover:text-gray-700 visited:text-gray-700">
            {{ __('История') }}
        </a>
    </div>
</div>
@endsection