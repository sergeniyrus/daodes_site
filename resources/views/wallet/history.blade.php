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
        background-color: rgba(34, 34, 34, 0.8);
        color: white;
    }
    .custom-table th, .custom-table td {
        border: 1px solid #444;
    }
    @media (max-width: 768px) {
        .custom-table td {
            border-bottom: 1px solid rgba(68, 68, 68, 0.5);
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
</style>

<div class="container center-content">
    <h1>История переводов</h1>
    <table class="table-auto w-3/4 text-left center-table custom-table">
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
                    <td class="px-4 py-2">{{ $pay->created_at }}</td>
                    <td class="px-4 py-2">
                        {{ optional($pay->fromWallet->user)->name ?? 'Неизвестно' }}
                    </td>
                    <td class="px-4 py-2">
                        {{ optional($pay->toWallet->user)->name ?? 'Неизвестно' }}
                    </td>
                    <td class="px-4 py-2">{{ rtrim(rtrim(number_format($pay->amount, 8, '.', ''), '0'), '.') }} descoin</td>
                    <td class="px-4 py-2">{{ rtrim(rtrim(number_format($pay->fee, 8, '.', ''), '0'), '.') }} descoin</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-center">
        {{ $historyPays->links() }}
        <div class="pagination-info">
            Showing {{ $historyPays->firstItem() }} to {{ $historyPays->lastItem() }} of {{ $historyPays->total() }} results
        </div>
    </div>
</div>
@endsection
