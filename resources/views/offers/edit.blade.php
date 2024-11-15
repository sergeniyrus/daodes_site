@extends('template')

@section('title_page')
    Редактировать предложение
@endsection

@section('main')
    <style>
        .container {
            padding: 20px;
            margin: 30px auto;
            max-width: 800px;
            background-color: rgba(20, 20, 20, 0.9);
            border-radius: 20px;
            border: 1px solid #d7fc09;
            color: #f8f9fa;
            font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-group label {
            color: #d7fc09;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input_dark,
        textarea {
            background-color: #1a1a1a;
            color: #a0ff08;
            border: 1px solid #d7fc09;
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .input_dark:focus,
        textarea:focus {
            border: 1px solid #a0ff08;
            outline: none;
            box-shadow: 0 0 5px #d7fc09;
        }

        .blue_btn {
            display: inline-block;
            color: #ffffff;
            font-size: 1.2rem;
            background: #0b0c18;
            padding: 12px 25px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
            margin-top: 20px;
        }

        .blue_btn:hover {
            box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
            transform: scale(1.05);
            background: #1a1a1a;
        }

        .file-input-wrapper {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        #preview {
            max-width: 100%;
            margin-top: 10px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            display: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        #file-name {
            font-size: 0.9rem;
            color: #a0ff08;
            text-align: center;
            margin-top: 5px;
        }

        .alert-danger {
            background-color: rgba(255, 0, 0, 0.8);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
    </style>

    <div class="container">
        <h2 class="text-center">Редактировать предложение</h2>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="offer-form" method="POST" action="{{ route('offers.update', ['id' => $offer->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Название предложения</label>
                <input type="text" name="title" class="input_dark" value="{{ old('title', $offer->title) }}">
            </div>

            <div class="form-group">
                <label for="category">Категория</label>
                <select name="category" class="input_dark">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $offer->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="filename">Изображение предложения</label>
                <div class="file-input-wrapper">
                    <!-- Если изображение существует, показываем его, иначе скрываем -->
                    <img id="preview" src="{{ $offer->img ?? '#' }}" alt="Превью изображения"
                        style="display: {{ $offer->img ? 'block' : 'none' }}; max-width: 100px;">
            
                    <div class="file-info">
                        <!-- Если изображение есть, выводим имя файла, иначе пишем "Файл не выбран" -->
                        <span id="file-name" class="file-name">{{ $offer->img ? basename($offer->img) : 'Файл не выбран' }}</span>
                        <button type="button" class="blue_btn" onclick="document.getElementById('file-input').click();">
                            Выберите файл
                        </button>
                        <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                    </div>
                </div>
                <p style="color: red; text-align: center; font-size: 0.9rem;">Изображение должно быть 1:1. Имя файла должно быть на английском.</p>
            </div>
            
            <div class="form-group">
                <label for="content">Содержание предложения</label>
                <textarea id="editor" name="content" rows="10" class="input_dark">{{ old('content', $offer->content) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="blue_btn">Сохранить изменения</button>
            </div>
        </form>
    </div>

    {{-- // Инициализация cropper --}}
    <script src="{{ asset('js/image-cropper.js') }}"></script>
    {{-- // Инициализация CKEditor --}}
    <link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
    <script src="{{ asset('js/ckeditor.js') }}"></script>
@endsection
