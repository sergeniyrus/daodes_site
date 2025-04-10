@extends('template')

@section('title_page')
{{ __('admin_offers.create_offer_title') }}
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
</style>

<div class="container">
    <h2 class="text-center">{{ __('admin_offers.create_offer_title') }}</h2>

    <form id="offers-form" method="POST" action="{{ route('offers.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Title fields (RU and EN) -->
        <div class="form-group">
            <label for="title_ru">{{ __('admin_offers.offer_title_ru') }}</label>
            <input type="text" name="title_ru" class="input_dark" value="{{ old('title_ru') }}" required>
        </div>
        <div class="form-group">
            <label for="title_en">{{ __('admin_offers.offer_title_en') }}</label>
            <input type="text" name="title_en" class="input_dark" value="{{ old('title_en') }}">
        </div>

        <!-- Category fields (RU and EN names) -->
        <div class="form-group">
            <label for="category">{{ __('admin_offers.category') }}</label>
            <div class="category-container">
                <div class="category-select-wrapper">
                    <select name="category_id" class="input_dark" id="category-select" required>
                        <option value="" selected disabled>{{ __('admin_offers.select_category') }}</option>
                        @foreach ($category_offers as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ru }} / {{ $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="des-btn add-category-btn" id="open-category-modal">
                    <i class="fas fa-plus-circle"></i> {{ __('admin_offers.add_category') }}
                </button>
            </div>
        </div>

        <!-- Image upload -->
        <div class="form-group">
            <label for="filename">{{ __('admin_offers.offer_image') }}</label>
            <div class="file-input-wrapper">
                <img id="preview" src="#" alt="Image preview">
                <button type="button" class="des-btn" onclick="document.getElementById('file-input').click();">
                    {{ __('admin_offers.choose_file') }}
                </button>
                <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                <div id="file-name">{{ __('admin_offers.no_file_selected') }}</div>
            </div>
            <p style="color: red; text-align: left; margin: 10px 0 0 10px; font-size:0.9rem;">
                {{ __('admin_offers.image_requirements') }}
            </p>
        </div>

        <!-- Content fields (RU and EN) with CKEditor -->
        <div class="form-group">
            <label for="content_ru">{{ __('admin_offers.offer_content_ru') }}</label>
            <textarea id="editor-ru" name="content_ru">{{ old('content_ru') }}</textarea>
        </div>

        <div class="form-group">
            <label for="content_en">{{ __('admin_offers.offer_content_en') }}</label>
            <textarea id="editor-en" name="content_en">{{ old('content_en') }}</textarea>
        </div>

        <div style="text-align: center;">
            <button type="submit" class="des-btn">{{ __('admin_offers.create_offer_button') }}</button>
        </div>
    </form>
</div>

<!-- Modal for adding new category -->
<div id="category-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>{{ __('admin_offers.add_category') }}</h2>
        <form id="category-form" method="POST" action="{{ route('offerscategories.categoryStore') }}">
            @csrf
            <div class="form-group">
                <label for="category-name_ru">{{ __('admin_offers.category_name_ru') }}</label>
                <input type="text" name="name_ru" id="category-name_ru" class="input_dark" placeholder=" {{ __('admin_offers.name_regex') }}" required>
            </div>
            <div class="form-group">
                <label for="category-name_en">{{ __('admin_offers.category_name_en') }}</label>
                <input type="text" name="name_en" id="category-name_en" class="input_dark" placeholder=" {{ __('admin_offers.name_regex') }}">
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
        <h2>{{ __('admin_offers.crop_image') }}</h2>
        <div id="crop-container-wrapper">
            <div id="crop-container"></div>
        </div>
        <div class="cropper-actions">
            <button id="cancel-crop" class="des-btn">{{ __('admin_offers.cancel') }}</button>
            <button id="crop-button" class="des-btn">{{ __('admin_offers.crop_image') }}</button>
        </div>
    </div>
</div>


<!-- Hidden input for cropped image data -->
<input type="hidden" id="cropped-image" name="cropped_image">

<!-- CKEditor CSS and JS -->
<link rel="stylesheet" href="{{ asset('css/ckeditor.css') }}">
<script src="{{ asset('js/ckeditor.js') }}"></script>
<script src="{{ asset('js/ckeditor-init.js') }}"></script>
<script src="{{ asset('js/form-validation.js') }}"></script>
<script src="{{ asset('js/category-modal.js') }}"></script>
<script src="{{ asset('js/category-submit.js') }}"></script>
<script src="{{ asset('js/cropper-init.js') }}"></script>
@endsection
