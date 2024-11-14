@extends('template')

@section('title_page')
    Создать новость
@endsection

@section('main')
    <style>
        .container {
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
            background-color: rgba(20, 20, 20, 0.9);
            border-radius: 20px;
            border: 1px solid #d7fc09;
            color: #f8f9fa;
            font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
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
        }

        /* Стили для превью изображения */
        #preview {
            max-width: 100%;
            height: auto;
            margin: 10px auto;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            object-fit: contain;
            display: none;
            /* Скрыт по умолчанию */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        /* Стили для названия файла */
        #file-name {
            font-size: 0.9rem;
            color: #a0ff08;
            text-align: center;
            margin-top: 5px;
        }

        .ck-toolbar {
            background-color: #333333 !important;
            /* Тёмный фон */
            border-bottom: 1px solid #d7fc09 !important;
            /* Добавление границы */
        }

        /* Изменение цвета кнопок на панели инструментов */
        .ck-toolbar button {
            color: #f8f9fa !important;
            /* Светлый цвет текста */
            background-color: #0b0c18 !important;
            /* Темный фон кнопок */
            border: 1px solid #d7fc09 !important;
            /* Цвет границ кнопок */
        }

        /* Цвет кнопок при наведении */
        .ck-toolbar button:hover {
            background-color: #d7fc09 !important;
            /* Желтый фон при наведении */
            color: #1a1a1a !important;
            /* Тёмный текст при наведении */
        }

        /* Цвет для активных кнопок */
        .ck-toolbar button.ck-on {
            background-color: #d7fc09 !important;
            /* Желтый фон */
            color: #1a1a1a !important;
            /* Тёмный текст */
        }

        .ck-editor__editable {
            color: #bbbbbb !important;
            /* Тёмный цвет текста */
            background-color: #1a1a1a !important;
            /* Тёмный фон */
            font-size: 16px !important;
        }
    </style>

    <div class="container">
        <h2 class="text-center">Создать новость</h2>

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

        <form id="news-form" method="POST" action="{{ route('news.create') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Название новости</label>
                <input type="text" name="title" class="input_dark" value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="category">Тема</label>
                <select name="category" class="input_dark">
                    <option value="0" selected>Выберите категорию</option>
                    @foreach (DB::table('category_news')->get() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="filename">Картинка новости</label>
                <div class="file-input-wrapper">
                    <!-- Превью изображения -->
                    <img id="preview" src="#" alt="Превью изображения">

                    <!-- Кнопка для загрузки изображения -->
                    <button type="button" class="blue_btn"
                        onclick="document.getElementById('file-input').click();">Выберите файл</button>

                    <!-- Поле загрузки файла (скрыто) -->
                    <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">

                    <!-- Название выбранного файла -->
                    <div id="file-name">Файл не выбран</div>
                </div>
                <p style="color: red; text-align: center; margin-top: 20px; font-size:0.9rem;">Изображение должно быть 1:1.
                    Имя файла должно быть на английском.</p>
            </div>

            <div class="form-group">
                <label for="content">Содержание новости</label>
                <textarea id="editor" name="content" rows="10" placeholder="Введите текст новости">{{ old('content') }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="blue_btn" style="input_dark">Создать новость</button>
            </div>
        </form>
    </div>

{{-- // Инициализация cropper --}}
    <script src="{{ asset('js/image-cropper.js') }}"></script>

    {{-- // Инициализация CKEditor --}}
    <script src="{{ asset('js/ckeditor.js') }}"></script>

@endsection
