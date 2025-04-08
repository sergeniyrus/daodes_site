@extends('template')

@section('title_page', __('wallet.transfer_history'))

@section('main')
<link  href="{{ asset('css/wallet.css') }}" rel="stylesheet"  type="text/css">

<div class="wallet-container">
    <div class="wallet-header">
        <img src="{{ $UserProfile->avatar_url ?? '/img/main/img_avatar.jpg' }}" alt="Avatar" class="wallet-avatar">
        <h1 class="wallet-title">{{ __('wallet.transfer_history') }}</h1>
    </div>

    @if(session('success'))
        <div class="wallet-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="wallet-table-responsive">
        <table class="wallet-table">
            <thead>
                <tr>
                    <th>{{ __('wallet.date') }}</th>
                    <th>{{ __('wallet.sender') }}</th>
                    <th>{{ __('wallet.recipient') }}</th>
                    <th>{{ __('wallet.amount') }}</th>
                    <th>{{ __('wallet.fee') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyPays as $pay)
                    <tr>
                        <td data-label="{{ __('wallet.date') }}">{{ $pay->created_at }}</td>
                        <td data-label="{{ __('wallet.sender') }}">
                            {{ optional($pay->fromWallet->user)->name ?? __('Unknown') }}
                        </td>
                        <td data-label="{{ __('wallet.recipient') }}">
                            {{ optional($pay->toWallet->user)->name ?? __('Unknown') }}
                        </td>
                        <td data-label="{{ __('wallet.amount') }}">{{ rtrim(rtrim(number_format($pay->amount, 8, '.', ''), '0'), '.') }} descoin</td>
                        <td data-label="{{ __('wallet.fee') }}">{{ rtrim(rtrim(number_format($pay->fee, 8, '.', ''), '0'), '.') }} descoin</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="wallet-pagination">
        {{ $historyPays->links() }}
        <div class="wallet-pagination-info">
            {{ __('wallet.pagination_info', [
                'first' => $historyPays->firstItem(),
                'last' => $historyPays->lastItem(),
                'total' => $historyPays->total()
            ]) }}
        </div>
    </div>

    <div class="wallet-nav">
        <a href="{{ route('wallet.index') }}" class="wallet-btn">{{ __('wallet.my_wallet') }}</a>
        <a href="{{ route('wallet.transfer.form') }}" class="wallet-btn">{{ __('wallet.transfer') }}</a>
    </div>
</div>
@endsection