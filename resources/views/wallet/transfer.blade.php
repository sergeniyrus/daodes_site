<!-- resources/views/wallet/transfer.blade.php -->
@extends('template')
@section('title_page')
Трансфер
@endsection
@section('main')


<div class="modal-content" style="text-align: center;">
    <div class="imgcontainer">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <h1>Перевод средств</h1>
        @if (session('error'))
            <div class="font-medium text-red-600 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="font-medium text-red-600 text-sm">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <br>
        <form action="{{ route('wallet.transfer') }}" method="POST">
            @csrf
            <div class="mb-3">
                <x-input-label for="to_username" :value="__('Кому (имя пользователя)')" />
                <x-text-input id="to_username" class="form-control" type="text" name="to_username" required />
            </div>
            <div class="mb-3">
                <x-input-label for="amount" :value="__('Сумма')" />
                <x-text-input id="amount" class="form-control" type="number" step="0.00000001" name="amount" required />
            </div>
            <div class="mb-3">
                <x-input-label for="user_seed" :value="__('Сид-фраза')" />
                <x-text-input id="user_seed" class="form-control" type="text" name="user_seed" required />
            </div>
            <br>
            <x-primary-button class="ms-3">
                {{ __('Перевести') }}
            </x-primary-button>
        </form>
    </div>
</div>
@endsection