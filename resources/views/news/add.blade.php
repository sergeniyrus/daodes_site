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
            margin-top: 10px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
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
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
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

            <div style="text-align: center;"><button type="submit" class="blue_btn">Создать новость</button></div>
        </form>
    </div>

    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script>
        const fileInput = document.getElementById('file-input');
const previewImage = document.getElementById('preview');
let cropper;

fileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';

            // Инициализация cropper.js после загрузки изображения
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(previewImage, {
                aspectRatio: 1,
                viewMode: 1,
                background: false,
                scalable: false, // Отключение масштабирования
                zoomable: false, // Отключение масштабирования колесом мыши
            });
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.style.display = 'none';
        if (cropper) {
            cropper.destroy();
        }
    }

    document.getElementById('file-name').textContent = file ? file.name : 'Файл не выбран';
});

        // Инициализация CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
