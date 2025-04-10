@extends('template')

@section('title_page', __('admin_news.create_news_title'))

@section('main')
<link rel="stylesheet" href="{{ asset('css/news.css') }}">

<div class="container">
    <h2 class="text-center">{{ __('admin_news.create_news_title') }}</h2>

    <form id="news-form" method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Title fields (RU and EN) -->
        <div class="form-group">
            <label for="title_ru">{{ __('admin_news.news_title_ru') }}</label>
            <input type="text" name="title_ru" class="input_dark" value="{{ old('title_ru') }}" required>
        </div>
        <div class="form-group">
            <label for="title_en">{{ __('admin_news.news_title_en') }}</label>
            <input type="text" name="title_en" class="input_dark" value="{{ old('title_en') }}">
        </div>

        <!-- Category fields (RU and EN names) -->
        <div class="form-group">
            <label for="category">{{ __('admin_news.category') }}</label>
            <div class="category-container">
                <div class="category-select-wrapper">
                    <select name="category_id" class="input_dark" id="category-select" required>
                        <option value="" selected disabled>{{ __('admin_news.select_category') }}</option>
                        @foreach ($category_news as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ru }} / {{ $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="des-btn add-category-btn" id="open-category-modal">
                    <i class="fas fa-plus-circle"></i> {{ __('admin_news.add_category') }}
                </button>
            </div>
        </div>

        <!-- Image upload -->
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

        <!-- Content fields (RU and EN) with CKEditor -->
        <div class="form-group">
            <label for="content_ru">{{ __('admin_news.news_content_ru') }}</label>
            <textarea id="editor-ru" name="content_ru">{{ old('content_ru') }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="content_en">{{ __('admin_news.news_content_en') }}</label>
            <textarea id="editor-en" name="content_en">{{ old('content_en') }}</textarea>
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
                <label for="category-name_ru">{{ __('admin_news.category_name_ru') }}</label>
                <input type="text" name="name_ru" id="category-name_ru" class="input_dark" placeholder=" {{ __('admin_offers.name_regex') }}" required>
            </div>
            <div class="form-group">
                <label for="category-name_en">{{ __('admin_news.category_name_en') }}</label>
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
<script src="{{ asset('js/ckeditor-init.js') }}"></script>
<script src="{{ asset('js/form-validation.js') }}"></script>
<script src="{{ asset('js/category-modal.js') }}"></script>
<script src="{{ asset('js/category-submit.js') }}"></script>
<script src="{{ asset('js/cropper-init.js') }}"></script>

@endsection
