@extends('template')
@section('title_page', 'Добавить категорию Новостей')
@section('main')

<style>
    .container {
        padding: 20px;
        margin: 0 auto;
        max-width: 600px;
        background-color: rgba(20, 20, 20, 0.9);
        border: 1px solid #d7fc09;
        border-radius: 20px;
        color: #f8f9fa;
        font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        margin-top: 30px;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: center;
    }

    label {
        font-size: 1.2rem;
        color: #d7fc09;
        display: block;
        margin-bottom: 10px;
    }

    .input_dark {
        background-color: #000000;
        color: #a0ff08;
        border: 1px solid #a0ff08;
        border-radius: 5px;
        width: 100%;
        padding: 10px;
        margin: 10px auto 15px auto;
        font-size: 1rem;
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
        box-shadow: 0 0 20px #000;
        cursor: pointer;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .des-btn i {
        margin-right: 8px;
        font-size: 1.2rem;
    }

    .des-btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #0b0c18;
    }

</style>
<div class="container my-5">
    <h1>{{ __('category.add_category_title') }}</h1>

    {{-- @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <form action="{{ route('newscategories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">{{ __('category.category_name_label') }}</label>
            <input type="text" name="name" id="name" class="input_dark" required>
        </div>

        <button type="submit" class="des-btn">
            {!! __('category.add_category_button') !!}
        </button>
    </form>
</div>
@endsection
