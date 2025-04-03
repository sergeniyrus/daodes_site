@extends('template')

@section('title_page', __('admin_news.create_news_title'))

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
        /* padding: 20px; */
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

    .category-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .category-select-wrapper {
        flex-grow: 1;
    }

    .add-category-btn {
        white-space: nowrap;
        padding: 8px 12px;
        font-size: 0.8rem;
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

    /* Responsive adaptations */
    @media (max-width: 320px) {
        .ck-toolbar button {
            font-size: 11px;
            padding: 5px 8px;
        }

        .ck-editor__editable {
            font-size: 12px !important;
            min-height: 120px;
        }
    }

    @media (min-width: 321px) and (max-width: 450px) {
        .ck-toolbar button {
            font-size: 12px;
            padding: 6px 10px;
        }

        .ck-editor__editable {
            font-size: 14px !important;
            min-height: 150px;
        }
    }

    @media (min-width: 451px) and (max-width: 600px) {
        .ck-toolbar button {
            font-size: 13px;
            padding: 7px 12px;
        }

        .ck-editor__editable {
            font-size: 15px !important;
            min-height: 180px;
        }
    }

    @media (min-width: 601px) and (max-width: 900px) {
        .ck-toolbar button {
            font-size: 13px;
            padding: 7px 12px;
        }

        .ck-editor__editable {
            font-size: 15px !important;
            min-height: 180px;
        }
    }

    @media (min-width: 901px) {
        .ck-toolbar button {
            font-size: 14px;
            padding: 8px 12px;
        }

        .ck-editor__editable {
            font-size: 16px !important;
            min-height: 200px;
        }
    }
</style>

<div class="container">
    <h2 class="text-center">{{ __('admin_news.create_news_title') }}</h2>

    <form id="news-form" method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">{{ __('admin_news.news_title') }}</label>
            <input type="text" name="title" class="input_dark" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="category">{{ __('admin_news.category') }}</label>
            <div class="category-container">
                <div class="category-select-wrapper">
                    <select name="category_id" class="input_dark" id="category-select" required>
                        <option value="" selected disabled>{{ __('admin_news.select_category') }}</option>
                        @foreach ($category_news as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="des-btn add-category-btn" id="open-category-modal">
                    <i class="fas fa-plus-circle"></i> {{ __('admin_news.add_category') }}
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="filename">{{ __('admin_news.news_image') }}</label>
            <div class="file-input-wrapper">
                <img id="preview" src="#" alt="Image preview">
                <button type="button" class="des-btn" onclick="document.getElementById('file-input').click();">
                    {{ __('admin_news.choose_file') }}
                </button>
                <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                <div id="file-name">{{ __('admin_news.no_file_selected') }}</div>
            </div>
            <p style="color: red; text-align: left; margin: 10px 0 0 10px; font-size:0.9rem;">
                {{ __('admin_news.image_requirements') }}
            </p>
        </div>

        <div class="form-group">
            <label for="content">{{ __('admin_news.news_content') }}</label>
            <textarea id="editor" name="content" style="display:none;">{{ old('content') }}</textarea>
            <div id="editor-container"></div>
        </div>

        <div style="text-align: center;">
            <button type="submit" class="des-btn">{{ __('admin_news.create_news_button') }}</button>
        </div>
    </form>
</div>

<!-- Modal for adding new category -->
<div id="category-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>{{ __('admin_offers.add_category') }}</h2>
        <form id="category-form" method="POST" action="{{ route('newscategories.categoryStore') }}">
            @csrf
            <div class="form-group">
                <label for="category-name">{{ __('admin_offers.category_name') }}</label>
                <input type="text" name="name" id="category-name" class="input_dark" placeholder=" {{ __('admin_offers.name_regex') }}" required>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="des-btn" id="submit-category-btn">
                    <i class="fas fa-plus-circle"></i> {{ __('admin_offers.add_category') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for image cropping -->
<div id="crop-modal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <span class="close" id="close-crop-modal">&times;</span>
        <h2>{{ __('admin_news.crop_image') }}</h2>
        <div id="crop-container-wrapper">
            <div id="crop-container"></div>
        </div>
        <div class="cropper-actions">
            <button id="cancel-crop" class="des-btn">{{ __('admin_news.cancel') }}</button>
            <button id="crop-button" class="des-btn">{{ __('admin_news.crop_image') }}</button>
        </div>
    </div>
</div>

<!-- Hidden input for cropped image data -->
<input type="hidden" id="cropped-image" name="cropped_image">

<!-- CKEditor CSS and JS -->
<link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
<script src="{{ asset('js/ckeditor.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

// Открытие модального окна для добавления категории
document.getElementById('open-category-modal').addEventListener('click', function() {
        document.getElementById('category-modal').style.display = 'block';
    });

    // Закрытие модального окна
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('category-modal').style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('category-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Обработка отправки формы добавления категории
    document.getElementById('category-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Показываем индикатор загрузки
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    submitBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Добавляем новую категорию в select
            const select = document.getElementById('category-select');
            const option = document.createElement('option');
            option.value = data.category.id;
            option.textContent = data.category.name;
            select.appendChild(option);
            
            // Выбираем новую категорию
            select.value = data.category.id;
            
            // Закрываем модальное окно
            document.getElementById('category-modal').style.display = 'none';
            
            // Очищаем форму
            form.reset();
            
            // Показываем успешное сообщение
            alert(data.message);
        }
    })
    .catch(error => {
        // Показываем ошибки в alert
        const errors = error.errors || [error.message];
        alert(errors.join('\n'));
    })
    .finally(() => {
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    });
});


document.getElementById('news-form').addEventListener('submit', function(e) {
    if (editor) {
        document.getElementById('editor').value = editor.getData();
    }

    const content = document.getElementById('editor').value;
    if (!content || content.trim() === '') {
        e.preventDefault();
        alert('Content is required');
        if (editor) {
            editor.editing.view.focus();
        }
        return false;
    }

    const formData = new FormData(this);
    console.log('Form data:', formData.get('cropped_image'));

    return true;
});

const fileInput = document.getElementById('file-input');
const preview = document.getElementById('preview');
const fileName = document.getElementById('file-name');
const croppedImageInput = document.getElementById('cropped-image');
const cropModal = document.getElementById('crop-modal');
const cropContainer = document.getElementById('crop-container');

let cropper = null;
let currentImageUrl = null;
let originalFileName = '';

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (!file.type.match('image.*')) {
        alert('Please select an image file');
        return;
    }

    // Сохраняем оригинальное имя файла
    originalFileName = file.name;

    cleanupCropper();

    currentImageUrl = URL.createObjectURL(file);

    cropModal.style.display = 'block';

    cropContainer.innerHTML = `<img id="image-to-crop" src="${currentImageUrl}" style="max-width: 100%;">`;
    const image = document.getElementById('image-to-crop');

    image.onload = function() {
        initCropper(image);
    };
});

function initCropper(image) {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        restore: false,
        modal: true,
        guides: true,
        center: true,
        highlight: true,
        background: false,
        movable: true,
        rotatable: false,
        scalable: false,
        zoomable: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        minCropBoxWidth: 256,
        minCropBoxHeight: 256,
        ready: function() {
            console.log('Cropper initialized');
            const container = this.cropper.getContainerData();
            const imageData = this.cropper.getImageData();

            const size = Math.min(
                container.width * 0.8,
                container.height * 0.8,
                imageData.naturalWidth,
                imageData.naturalHeight
            );

            this.cropper.setCropBoxData({
                width: size,
                height: size
            });

            console.log('Crop box size set to:', size);
        }
    });
}

function cleanupCropper() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    if (currentImageUrl) {
        URL.revokeObjectURL(currentImageUrl);
        currentImageUrl = null;
    }
}

document.getElementById('crop-button').addEventListener('click', function() {
    if (!cropper) return;

    try {
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 800,
            minWidth: 256,
            minHeight: 256,
            maxWidth: 4096,
            maxHeight: 4096,
            fillColor: '#1a1a1a',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        if (!canvas) {
            throw new Error('Canvas creation failed');
        }

        // Convert canvas to Blob
        canvas.toBlob(function(blob) {
            // Используем оригинальное расширение файла
            const fileExtension = originalFileName.split('.').pop();
            const croppedFileName = `cropped_${originalFileName}`;
            const file = new File([blob], croppedFileName, { type: `image/${fileExtension}` });

            // Create a DataTransfer to set the file input value
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            fileName.textContent = croppedFileName;

            console.log('Cropped image file:', file);

            cropModal.style.display = 'none';
            cleanupCropper();
        }, `image/${originalFileName.split('.').pop()}`, 0.9);

    } catch (error) {
        console.error('Cropping error:', error);
        alert('Cropping error');
    }
});

document.getElementById('cancel-crop').addEventListener('click', resetFileInput);
document.getElementById('close-crop-modal').addEventListener('click', resetFileInput);

function resetFileInput() {
    cleanupCropper();
    fileInput.value = '';
    preview.style.display = 'none';
    fileName.textContent = 'No file selected';
    cropModal.style.display = 'none';
}

window.addEventListener('click', function(event) {
    if (event.target === cropModal) {
        resetFileInput();
    }
});
});

</script>

@endsection
