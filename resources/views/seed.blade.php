@extends('template')

@section('title_page', 'SeedPhrase')

@section('main')
    <style>
        .modal-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        .save-phrase {
            color: red;
            font-size: 14px;
            /* Начальный размер */
            text-align: center;
        }

        .seed-phrase {
            color: gray;
            font-size: 14px;
            /* Начальный размер */
            text-align: center;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            /* Начальный размер */
            color: rgb(0, 255, 0);
            /* Зеленый цвет */
        }

        .hidden-seed {
            display: none;
        }

        .btn-disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        @media (min-width: 768px) {

            .save-phrase,
            .seed-phrase,
            h2 {
                font-size: 18px;
            }
        }

        @media (min-width: 1024px) {

            .save-phrase,
            .seed-phrase,
            h2 {
                font-size: 20px;
            }
        }
    </style>

    <div class="modal-content">
        <div class="imgcontainer">
            <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
        </div>
        <div class="container">
            @if (isset($message))
                <p style="font-size: 18px; color:red;">{{ $message }}</p>
            @else
                @if (session('checkpoint'))
                    <p style="font-size: 18px; color: green;">
                        {{ session('checkpoint') }}
                    </p>
                @elseif(session('error'))
                    <p style="font-size: 18px; color: red;">
                        {{ session('error') }}
                    </p>
                @else
                    <form method="post" action="{{ route('saveSeed') }}">
                        @csrf
                        <div class="tabseed">
                            <h2>Ваша сид-фраза:</h2><br>
                            <div id="seedPhrase" class="seed-phrase" style="display: inline-block;">
                                @foreach ($words as $index => $word)
                                    <span> {{ $word }} </span> <input type="hidden" name="word{{ $index }}"
                                        value="{{ $word }}">
                                @endforeach
                                <span> {{ $keyword }} </span> <input type="hidden" name="word23"
                                    value="{{ $keyword }}">
                            </div>
                            <br><br>
                            <p class="save-phrase">Обязательно запишите её в надёжном месте! Востановить её будет невозможно!</p>
                        </div>
                        <br>
                        <div class="flex items-center justify-center mt-4">
                            <x-primary-button id="saveButton" class="ms-4 btn-disabled" disabled>
                                {{ __('Я Сохранил сид-фразу') }}
                            </x-primary-button>
                        </div>
                    </form>
<br>
                    <div class="flex items-center justify-center mt-4">
                        <x-primary-button onclick="copytext('#hiddenSeed')" class="ms-4">
                            Копировать сид-фразу
                        </x-primary-button>
                    </div>
                    <br>
                    <div id="copyMessage" style="display: none; font-size: 18px; color: green;">
                        Сид-фраза скопирована в буфер обмена.
                    </div>

                    @if(isset($words) && isset($keyword))
                    <!-- Скрытый блок с сид-фразой в одну строку -->
                    <div id="hiddenSeed" class="hidden-seed">
                        @foreach ($words as $word)
                            {{ $word }}
                        @endforeach
                        {{ $keyword }}
                    </div>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function copytext(el) {
            var $tmp = $("<textarea>");
            $("body").append($tmp);
            $tmp.val($(el).text().trim().replace(/\s+/g, ' ')).select();
            document.execCommand("copy");
            $tmp.remove();
            alert("Сид-фраза скопирована в буфер обмена");

            // Активируем кнопку "Я Сохранил сид-фразу" после копирования
            var saveButton = document.getElementById('saveButton');
            saveButton.disabled = false;
            saveButton.classList.remove('btn-disabled');
        }
    </script>

@endsection
