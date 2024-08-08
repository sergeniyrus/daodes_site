@extends('template')

@section('title_page')
    Обозреватель
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
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        background-color: rgba(34, 34, 34, 0.8);
        color: white;
        border-collapse: collapse;
    }
    .custom-table th, .custom-table td {
        border: 1px solid #444;
        padding: 8px;
    }
    .custom-table thead {
        display: none;
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
            font-size: 12px;
        }
        .pagination-info, .page-link {
            font-size: 12px;
        }
    }
</style>

<div class="container center-content">
    <h1>История переводов</h1>
    <div class="table-responsive">
        <table class="table-auto center-table custom-table">
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
                        <td class="px-4 py-2" data-label="Отправитель">
                            {{ optional($pay->fromWallet->user)->name ?? 'Неизвестно' }}
                        </td>
                        <td class="px-4 py-2" data-label="Получатель">
                            {{ optional($pay->toWallet->user)->name ?? 'Неизвестно' }}
                        </td>
                        <td class="px-4 py-2" data-label="Сумма">{{ rtrim(rtrim(number_format($pay->amount, 8, '.', ''), '0'), '.') }} descoin</td>
                        <td class="px-4 py-2" data-label="Комиссия">{{ rtrim(rtrim(number_format($pay->fee, 8, '.', ''), '0'), '.') }} descoin</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-center">
        {{ $historyPays->links('vendor.pagination.custom') }}
        <div class="pagination-info">
            Showing {{ $historyPays->firstItem() }} to {{ $historyPays->lastItem() }} of {{ $historyPays->total() }} results
        </div>
    </div>
</div>
@endsection
