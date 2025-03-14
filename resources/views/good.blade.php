@extends('template')

@section('title_page', __('good.title_page'))

<style>
    .good {
        max-width: 500px;
        height: auto;
        margin: 50px auto;
        color: rgb(255, 255, 255);
        background-color: rgba(30, 32, 30, 0.753);
        font-size: min(max(50%, 7vw), 100%);
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        border: 1px solid #fff;
        border-radius: 30px;
        text-align: center;
        vertical-align: auto;
    }

    .des-btn {
        display: inline-block;
        color: #ffffff;
        font-size: large;
        background: #0b0c18;
        padding: 15px 30px;
        border: 1px solid #d7fc09;
        border-radius: 10px;
        box-shadow: 0 0 20px #000;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        gap: 15px;
        margin-bottom: 25px;
    }

    .des-btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        color: #ffffff;
        background: #0b0c18;
    }
</style>

@section('main')
    <div class="good">
        <br>
        <h2>
            @if ($action == 'edit')
                {{ __('good.edit') }}
            @elseif ($action == 'create')
                {{ __('good.create') }}
            @endif

            @if ($post == 'news')
                {{ __('good.news') }}
            @elseif ($post == 'offers')
                {{ __('good.offers') }}
            @endif
        </h2>
        <br>
        <h2>{{ __('good.success_message') }}</h2>
        <br>
        <a href="/{{ $post }}/{{ $id }}">
            <h2 class="des-btn">{{ __('good.view_button') }}</h2>
        </a>
        <br>
    </div>
@endsection