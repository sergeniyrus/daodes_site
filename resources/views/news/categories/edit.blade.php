@extends('template')
@section('title_page', 'Редактировать категорию')
@section('main')

<link href="{{ asset('css/category.css') }}" rel="stylesheet">
    <div class="container my-5">
        <h1>{{ __('category.edit_category_title') }}</h1>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('newscategories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name_ru">{{ __('admin_news.category_name_ru') }}</label>
                <input type="text" name="name_ru" id="name_ru" class="input_dark"
                    value="{{ old('name_ru', $category->name_ru) }}" required>
            </div>

            <div class="form-group">
                <label for="name_en">{{ __('admin_news.category_name_en') }}</label>
                <input type="text" name="name_en" id="name_en" class="input_dark"
                    value="{{ old('name_en', $category->name_en) }}" required>
            </div>

            <button type="submit" class="des-btn">
                {!! __('category.save_changes_button') !!}
            </button>
        </form>
    </div>

@endsection
