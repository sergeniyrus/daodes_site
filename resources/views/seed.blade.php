@extends('template')

@section('title_page', 'SeedPhrase')

@section('main')
<div class="modal-content">
    <div class="imgcontainer">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        @if(isset($message))
            <p style="font-size: 22px; color:red; text-align: center;">{{ $message }}</p>
        @else
            <form method="post" action="{{ route('saveSeed') }}">
                @csrf
                <div class="tabseed">
                    <h2 style="color: rgb(0, 255, 0);">Ваша сид-фраза:</h2><br>
                        <div id="seedPhrase">
                        @foreach($words as $index => $word)
                        <span> {{ $word }} </span> <input type="hidden" name="word{{ $index }}" value="{{ $word }}">
                        @endforeach
                        <span> {{ $keyword }} </span> <input type="hidden" name="word23" value="{{ $keyword }}">
                    </div>
                    <br><p style="color: red;">ОБЯЗАТЕЛЬНО СОХРАНИТЕ ЕЁ В НАДЁЖНОМ МЕСТЕ</p>
                </div>
                <div class="flex items-center justify-center mt-4">
                    <x-primary-button class="ms-4">
                        {{ __('Я Сохранил сид-фразу') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="flex items-center justify-center mt-4">
                <button onclick="copyToClipboard()" class="ms-4">
                    Копировать сид-фразу
                </button>
            </div>
            <div id="copyMessage" style="display: none; font-size: 22px; color: green; text-align: center;">
                Сид-фраза скопирована в буфер обмена.
            </div>
        @endif

        @if (session('checkpoint'))
            <p style="font-size: 22px; color: green; text-align: center;">
                {{ session('checkpoint') }}
            </p>
        @endif

        @if (session('error'))
            <p style="font-size: 22px; color: red; text-align: center;">
                {{ session('error') }}
            </p>
        @endif
    </div>
</div>

<script>
function copyToClipboard() {
    var seedPhrase = document.getElementById('seedPhrase').innerText;
    navigator.clipboard.writeText(seedPhrase).then(function() {
        var copyMessage = document.getElementById('copyMessage');
        copyMessage.style.display = 'block';
        setTimeout(function() {
            copyMessage.style.display = 'none';
        }, 3000); // Показываем сообщение на 3 секунды
    }, function(err) {
        console.error('Ошибка при копировании: ', err);
    });
}
</script>

@endsection
