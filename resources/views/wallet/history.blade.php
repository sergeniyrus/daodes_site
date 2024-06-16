@extends('template')
@section('title_page')
История переводов
@endsection
@section('main')
<style>
    .center-content {
        text-align: center;
    }
    .center-table {
        margin-left: auto;
        margin-right: auto;
    }
    .custom-table {
        background-color: rgba(34, 34, 34, 0.9); /* Темный и прозрачный фон */
        color: white; /* Белый текст для лучшей читаемости */
        width: 100%; /* Для адаптации таблицы под мобильные устройства */
        border-collapse: collapse; /* Чтобы границы ячеек не дублировались */
    }
    .custom-table th, .custom-table td {
        border: 1px solid #444; /* Цвет границ */
        padding: 8px; /* Добавление отступов для улучшения читаемости */
    }

    /* Медиазапросы для мобильных устройств */
    @media (max-width: 768px) {
        .custom-table thead {
            display: none; /* Скрыть заголовки таблицы на маленьких экранах */
        }

        .custom-table, .custom-table tbody, .custom-table tr, .custom-table td {
            display: block;
            width: 100%;
        }

        .custom-table tr {
            margin-bottom: 15px; /* Добавление отступов между строками */
            position: relative;
        }

        .custom-table tr::after {
            content: "";
            display: block;
            height: 10px; /* Высота узкой строки */
            background-color: transparent; /* Прозрачная строка */
        }

        .custom-table td {
            text-align: right;
            padding-left: 50%;
            position: relative;
            font-size: 14px; /* Уменьшение шрифта */
        }

        .custom-table td::before {
            content: attr(data-label); /* Вставка меток ячеек */
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 10px;
            font-weight: bold;
            text-align: left;
            font-size: 14px; /* Уменьшение шрифта */
        }
    }

    @media (max-width: 560px) {
        .custom-table td {
            padding-left: 40%;
            font-size: 12px; /* Еще больше уменьшение шрифта */
        }

        .custom-table td::before {
            width: 40%;
            font-size: 12px; /* Еще больше уменьшение шрифта */
        }
    }

    @media (max-width: 420px) {
        .custom-table td {
            padding-left: 35%;
            font-size: 10px; /* Еще больше уменьшение шрифта */
        }

        .custom-table td::before {
            width: 35%;
            font-size: 10px; /* Еще больше уменьшение шрифта */
        }
    }

    @media (max-width: 380px) {
        .custom-table td {
            padding-left: 30%;
            font-size: 9px; /* Еще больше уменьшение шрифта */
        }

        .custom-table td::before {
            width: 30%;
            font-size: 9px; /* Еще больше уменьшение шрифта */
        }
    }

    @media (max-width: 320px) {
        .custom-table td {
            padding-left: 25%;
            font-size: 8px; /* Еще больше уменьшение шрифта */
        }

        .custom-table td::before {
            width: 25%;
            font-size: 8px; /* Еще больше уменьшение шрифта */
        }
    }
</style>

<div class="container center-content">
    <h1>История переводов</h1><br>
    <table class="custom-table">
        <thead>
            <tr>
                <th class="px-4 py-2">Дата</th>
                <th class="px-4 py-2">Отправитель</th>
                <th class="px-4 py-2">Получатель</th>
                <th class="px-4 py-2">Сумма</th>
                <th class="px-4 py-2">Комиссия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historyPays as $pay)
                <tr>
                    <td class="px-4 py-2" data-label="Дата">{{ $pay->created_at }}</td>
                    <td class="px-4 py-2" data-label="Отправитель">{{ $pay->fromWallet->user->name }}</td>
                    <td class="px-4 py-2" data-label="Получатель">{{ $pay->toWallet->user->name }}</td>
                    <td class="px-4 py-2" data-label="Сумма">{{ rtrim(rtrim(number_format($pay->amount, 8, '.', ''), '0'), '.') }} descoin</td>
                    <td class="px-4 py-2" data-label="Комиссия">{{ rtrim(rtrim(number_format($pay->fee, 8, '.', ''), '0'), '.') }} descoin</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
