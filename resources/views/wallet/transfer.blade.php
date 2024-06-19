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
        <form method="post" action="{{ route('wallet.transfer') }}">
            @csrf
            <div>
                <x-input-label for="recipient" :value="__('Получатель')" />
                <x-text-input id="recipient" class="block mt-1 w-full" type="text" name="recipient" :value="old('recipient')" required autofocus />
                @error('recipient')
                    <div class="font-medium text-red-600">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mt-4">
                <x-input-label for="amount" :value="__('Сумма')" />
                <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount')" required />
                @error('amount')
                    <div class="font-medium text-red-600">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mt-4">
                <x-input-label for="seed_phrase" :value="__('Сид-фраза')" />
                <x-text-input id="seed_phrase" class="block mt-1 w-full" type="text" name="seed_phrase" :value="old('seed_phrase')" required />
                @error('seed_phrase')
                    <div class="font-medium text-red-600">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="ms-3">
                    {{ __('Перевести') }}
                </x-primary-button>
            </div>
        </form>
        
        
    </div>
</div>
@endsection