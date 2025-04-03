@extends('template')

@section('title_page')
{{ __('admin_news.edit_news_title') }}
@endsection

@section('main')
    <style>
        /* Основные стили контейнера и формы */
        .container {
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
            background-color: rgba(20, 20, 20, 0.9);
            border-radius: 20px;
            border: 1px solid gold;
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
            color: gold;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input_dark,
        textarea {
            background-color: #1a1a1a;
            color: gold;
            border: 1px solid gold;
            border-radius: 5px;
            padding: 12px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .input_dark:focus,
        textarea:focus {
            border: 1px solid gold;
            outline: none;
            box-shadow: 0 0 5px gold;
        }

        .des-btn {
            display: inline-block;
            color: #ffffff;
            font-size: 1.2rem;
            background: #0b0c18;
            padding: 10px 15px;
            border: 1px solid gold;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
        }

        .des-btn:hover {
            box-shadow: 0 0 10px gold, 0 0 30px gold, 0 0 30px gold;
            transform: scale(1.05);
            background: #1a1a1a;
        }

        .file-input-wrapper {
            text-align: left;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        #preview {
            max-width: 100%;
            max-height: 300px;
            margin-top: 10px;
            border: 1px solid gold;
            border-radius: 10px;
            display: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        #file-name {
            font-size: 0.9rem;
            color: gold;
            text-align: left;
            margin: 10px 0 0 10px;
        }

        .alert-danger {
            background-color: rgba(255, 0, 0, 0.8);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background-color: rgba(20, 20, 20, 0.95);
            margin: 5% auto;
            padding: 20px;
            border: 1px solid gold;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .close {
            color: gold;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: goldenrod;
        }

        .error-message {
            color: #ff6b6b;
            margin: 10px 0;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Cropper specific styles */
        #crop-container-wrapper {
            width: 100%;
            height: 60vh;
            margin: 20px auto;
            position: relative;
            background-color: #1a1a1a;
            overflow: hidden;
        }

        #crop-container {
            width: 100%;
            height: 100%;
        }

        .cropper-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .cropper-view-box {
            outline: 2px solid gold !important;
            box-shadow: 0 0 0 9999px rgba(26, 26, 26, 0.5);
        }

        .cropper-line {
            background-color: gold !important;
        }

        .cropper-point {
            background-color: gold !important;
            width: 12px !important;
            height: 12px !important;
            opacity: 1 !important;
        }

        .cropper-point.point-se {
            right: -6px !important;
            bottom: -6px !important;
            cursor: se-resize !important;
        }
    </style>

    <div class="container">
        <h2 class="text-center">{{ __('admin_news.edit_news_title') }}</h2>

        <!-- Validation Errors -->
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <form id="news-form" method="POST" action="{{ route('news.update', $news->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">{{ __('admin_news.news_title') }}</label>
                <input type="text" name="title" class="input_dark" value="{{ old('title', $news->title) }}" placeholder="{{ __('admin_news.news_title_placeholder') }}">
            </div>

            <div class="form-group">
                <label for="category">{{ __('admin_news.category') }}</label>
                <select name="category" class="input_dark">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $news->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="filename">{{ __('admin_news.news_image') }}</label>
                <div class="file-input-wrapper">
                    <!-- Если изображение существует, показываем его, иначе скрываем -->
                    <img id="preview" src="{{ $news->img ?? '#' }}" alt="Превью изображения"
                        style="display: {{ $news->img ? 'block' : 'none' }}; max-width: 100px;">

                    <div class="file-info">
                        <!-- Если изображение есть, выводим имя файла, иначе пишем "Файл не выбран" -->
                        <span id="file-name" class="file-name">{{ $news->img ? basename($news->img) : __('admin_news.no_file_selected') }}</span>
                        <button type="button" class="des-btn" onclick="document.getElementById('file-input').click();">
                            {{ __('admin_news.choose_file') }}
                        </button>
                        <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                    </div>
                </div>
                <p style="color: red; text-align: center; font-size: 0.9rem;">{{ __('admin_news.image_requirements') }}</p>
            </div>

            <div class="form-group">
                <label for="content">{{ __('admin_news.news_content') }}</label>
                <textarea id="editor" name="content" rows="10" class="input_dark">{{ old('content', $news->content) }}</textarea>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="des-btn">{{ __('admin_news.save_changes_button') }}</button>
            </div>
        </form>
    </div>

    <!-- Modal Structure -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="crop-container-wrapper">
                <div id="crop-container"></div>
            </div>
            <div class="cropper-actions">
                <button type="button" class="des-btn" id="crop-button">{{ __('admin_news.crop_image') }}</button>
                <button type="button" class="des-btn" id="cancel-crop-button">{{ __('admin_news.cancel') }}</button>
            </div>
        </div>
    </div>

    {{-- // Инициализация cropper --}}
    <script src="{{ asset('js/image-upload.js') }}"></script>
    {{-- // Инициализация CKEditor --}}
    <link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
    <script src="{{ asset('js/ckeditor.js') }}"></script>

    <script>
        // JavaScript для обработки модального окна и cropper
        const modal = document.getElementById('modal');
        const cropButton = document.getElementById('crop-button');
        const cancelCropButton = document.getElementById('cancel-crop-button');
        const closeModal = document.querySelector('.close');
        const fileInput = document.getElementById('file-input');
        const preview = document.getElementById('preview');
        const fileName = document.getElementById('file-name');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    fileName.textContent = file.name;
                    modal.style.display = 'block';
                    // Инициализация cropper
                    initializeCropper(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        cancelCropButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        function initializeCropper(imageSrc) {
            // Инициализация cropper с заданным изображением
            const image = document.createElement('img');
            image.src = imageSrc;
            document.getElementById('crop-container').innerHTML = '';
            document.getElementById('crop-container').appendChild(image);

            const cropper = new Cropper(image, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                cropBoxResizable: false,
                background: false,
                autoCropArea: 1
            });

            cropButton.addEventListener('click', function() {
                const croppedCanvas = cropper.getCroppedCanvas();
                croppedCanvas.toBlob(function(blob) {
                    const croppedFile = new File([blob], fileName.textContent, { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    fileInput.files = dataTransfer.files;
                    modal.style.display = 'none';
                });
            });
        }
    </script>
@endsection
