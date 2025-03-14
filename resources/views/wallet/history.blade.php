@extends('template')
@section('title_page', __('wallet.transfer_history'))
@section('main')
<style>
    .container {
        padding: 20px; /* Увеличил отступы для более комфортного восприятия */
        margin: 0 auto;
        justify-content: center;
        align-items: center;
        text-align: center;
        max-width: 800px; /* Максимальная ширина для контейнера */
        background-color: rgba(20, 20, 20, 0.9); /* Темный фон для контейнера */
        border-radius: 20px; /* Скругленные углы */
        border: 1px solid #d7fc09; /* Золотая граница для контейнера */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Тень для выделения */
        color: #f8f9fa; /* Цвет текста */
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif; /* Шрифт */
        margin-top: 30px; /* Отступ сверху */
    }

    .table-responsive {
        margin-top: 20px;
    }

    .custom-table {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        background-color: rgba(34, 34, 34, 0.8);
        color: white;
        border-collapse: collapse;
        font-size: 0.875rem; /* Уменьшил размер шрифта для таблицы */
    }

    .custom-table th, .custom-table td {
        border: 1px solid #444;
        padding: 10px;
        font-size: 0.875rem; /* Уменьшил размер шрифта в ячейках */
    }

    .custom-table thead {
        background-color: #444;
    }

    .custom-table tr {
        display: flex;
        flex-direction: column;
        margin-bottom: 1em;
        border-bottom: 2px solid #444;
    }

    .custom-table td::before {
        content: attr(data-label);
        font-weight: bold;
        display: inline-block;
        width: 100px; /* Adjust based on your needs */
    }

    @media (min-width: 768px) {
        .custom-table thead {
            display: table-header-group;
        }

        .custom-table tr {
            display: table-row;
            margin-bottom: 0;
        }

        .custom-table td::before {
            display: none;
        }
    }

    .pagination-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    .pagination-info {
        color: #ccc;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .custom-table th, .custom-table td {
            padding: 8px;
        }

        .pagination-info, .page-link {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .custom-table th, .custom-table td {
            padding: 6px;
            font-size: 12px; /* Уменьшение размера шрифта для мобильных устройств */
        }

        .pagination-info, .page-link {
            font-size: 12px;
        }
    }

    .des-btn {
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

    .des-btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #1a1a1a;
    }

    .alert {
        background-color: rgba(255, 0, 0, 0.8);
        color: #f8f9fa;
        border: 1px solid #d7fc09;
        border-radius: 10px;
        padding: 15px;
        margin: 20px 0;
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
        text-align: left;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    }
</style>

<div class="container">
    <h1>{{ __('wallet.transfer_history') }}</h1>
    
    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table-auto center-table custom-table">
            <thead>
                <tr>
                    <th class="px-4 py-2">{{ __('wallet.date') }}</th>
                    <th class="px-4 py-2">{{ __('wallet.sender') }}</th>
                    <th class="px-4 py-2">{{ __('wallet.recipient') }}</th>
                    <th class="px-4 py-2">{{ __('wallet.amount') }}</th>
                    <th class="px-4 py-2">{{ __('wallet.fee') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyPays as $pay)
                    <tr>
                        <td class="px-4 py-2" data-label="{{ __('wallet.date') }}">{{ $pay->created_at }}</td>
                        <td class="px-4 py-2" data-label="{{ __('wallet.sender') }}">
                            {{ optional($pay->fromWallet->user)->name ?? __('Unknown') }}
                        </td>
                        <td class="px-4 py-2" data-label="{{ __('wallet.recipient') }}">
                            {{ optional($pay->toWallet->user)->name ?? __('Unknown') }}
                        </td>
                        <td class="px-4 py-2" data-label="{{ __('wallet.amount') }}">{{ rtrim(rtrim(number_format($pay->amount, 8, '.', ''), '0'), '.') }} descoin</td>
                        <td class="px-4 py-2" data-label="{{ __('wallet.fee') }}">{{ rtrim(rtrim(number_format($pay->fee, 8, '.', ''), '0'), '.') }} descoin</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-center">
        {{ $historyPays->links() }}
        <div class="pagination-info">
            {{ __('wallet.pagination_info', [
                'first' => $historyPays->firstItem(),
                'last' => $historyPays->lastItem(),
                'total' => $historyPays->total()
            ]) }}
        </div>
    </div>
</div>
@endsection
