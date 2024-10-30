@extends('template')

@section('title_page')
    Редактирование категории
@endsection

@section('main')
<style>
    .new_post {
        width: 90%;
        margin: 20px auto;
        padding-bottom: 20px;
        background-color: rgba(30, 32, 30, 0.753);
        font-size: 20px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        border: 1px solid #fff;
        border-radius: 30px;
        text-align: center;
        display: flex;
        flex-direction: column;
    }
    
    .name_str h2 {
        text-align: center;
        font-size: 36px;
    }

    .blue_btn {
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
    }
    .blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        color: #ffffff;
        background: #0b0c18;
    }
    .verh {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        justify-content: center;
    }
    .dark_text {
        padding: 8px;
        font-size: 16px;
        color: black;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
</style>

<div class="new_post">
    <div class="name_str">
        <h2>Редактирование категории</h2>
    </div>

    <!-- Отображение ошибок валидации -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('news.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="verh">
            <div>Название категории<br>
                <input class="dark_text" type="text" name="name" value="{{ $category->name }}">
            </div>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="blue_btn">Обновить категорию</button>
    </form>
</div>
@endsection
