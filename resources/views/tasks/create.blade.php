@extends('template')
@section('title_page', __('tasks.create_task'))
@section('main')
    @vite(['resources/css/redactor.css'])

    <div class="container">
        <h2 class="text-center">{{ __('tasks.create_task') }}</h2>

        <form id="task-form" method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Title fields (RU and EN) -->
            <div class="form-group">
                <label for="title_ru">{{ __('admin_offers.offer_title_ru') }}</label>
                <input type="text" name="title_ru" class="input_dark" value="{{ old('title_ru') }}" required>
            </div>
            <div class="form-group">
                <label for="title_en">{{ __('admin_offers.offer_title_en') }}</label>
                <input type="text" name="title_en" class="input_dark" value="{{ old('title_en') }}" required>
            </div>

            <!-- Category selection -->
            <div class="form-group">
                <label for="category_id">{{ __('tasks.category') }}</label>
                <div class="category-container">
                    <div class="category-select-wrapper">
                        <select name="category_id" class="input_dark" id="category-select" required>
                            <option value="" selected disabled>{{ __('tasks.select_category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ru }} / {{ $category->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="des-btn add-category-btn" id="open-category-modal">
                        <i class="fas fa-plus-circle"></i> {{ __('tasks.add_category') }}
                    </button>
                </div>
            </div>

            <!-- Deadline field -->
            <div class="form-group">
                <label for="deadline">{{ __('tasks.deadline') }}</label>
                <input type="date" name="deadline" class="input_dark" value="{{ old('deadline') }}" required>
            </div>

            <!-- Budget field -->
            <div class="form-group">
                <label for="budget">{{ __('tasks.budget') }}</label>
                <div class="input-with-currency">
                    <input type="number" name="budget" class="input_dark" step="0.01" value="{{ old('budget') }}"
                        required>
                    <span class="currency-symbol">USD</span>
                </div>
            </div>

            <!-- File attachment -->
            <div class="form-group">
                <label for="filename">{{ __('tasks.attachments') }}</label>
                <div class="file-input-wrapper">
                    <img id="preview" src="#" alt="Image preview" style="display: none;">
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
            <!-- Content fields (RU and EN) -->
            <div class="form-group">
                <label for="content_ru">{{ __('admin_offers.offer_content_ru') }}</label>
                <textarea id="editor-ru" name="content_ru">{{ old('content_ru') }}</textarea>
            </div>

            <div class="form-group">
                <label for="content_en">{{ __('admin_offers.offer_content_en') }}</label>
                <textarea id="editor-en" name="content_en">{{ old('content_en') }}</textarea>
            </div>



            <div style="text-align: center;">
                <button type="submit" class="des-btn">{{ __('tasks.create_task_button') }}</button>
            </div>
        </form>
    </div>

    <!-- Modal for adding new category -->
    <div id="category-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>{{ __('tasks.add_category') }}</h2>
            <form id="category-form" method="POST" action="{{ route('taskscategories.store') }}">
                @csrf
                <div class="form-group">
                    <label for="category-name_ru">{{ __('admin_offers.category_name_ru') }}</label>
                    <input type="text" name="name_ru" id="category-name_ru" class="input_dark" placeholder=" {{ __('admin_offers.name_regex') }}"  required>
                </div>
                <div class="form-group">
                    <label for="category-name_en">{{ __('admin_offers.category_name_en') }}</label>
                    <input type="text" name="name_en" id="category-name_en" class="input_dark" placeholder=" {{ __('admin_offers.name_regex') }}" required>
                </div>
                <div id="category-error" class="error-message"></div>
                <div style="text-align: center;">
                    <button type="submit" class="des-btn" id="submit-category-btn">
                        <i class="fas fa-plus-circle"></i> {{ __('tasks.add_category') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for image cropping -->
    <div id="crop-modal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <span class="close" id="close-crop-modal">&times;</span>
            <h2>{{ __('tasks.crop_image') }}</h2>
            <div id="crop-container-wrapper">
                <div id="crop-container"></div>
            </div>
            <div class="cropper-actions">
                <button id="cancel-crop" class="des-btn">{{ __('admin_offers.cancel') }}</button>
                <button id="crop-button" class="des-btn">{{ __('admin_offers.crop_image') }}</button>
            </div>
        </div>
    </div>

    <!-- Modal for confirmation -->
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
            <h2 id="confirmation-title">{{ __('tasks.confirmation') }}</h2>
            <p id="confirmation-message"></p>
            <div class="modal-buttons">
                <button id="confirm-cancel" class="des-btn">{{ __('tasks.cancel') }}</button>
                <button id="confirm-ok" class="des-btn">{{ __('tasks.confirm') }}</button>
            </div>
        </div>
    </div>


    <!-- Hidden input for cropped image data -->
    <input type="hidden" id="cropped-image" name="cropped_image">

    <!-- CKEditor CSS and JS -->
    @vite(['resources/css/ckeditor.css'])
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor-init.js') }}"></script>
    <script src="{{ asset('js/form-validation.js') }}"></script>
    <script src="{{ asset('js/category-modal.js') }}"></script>
    <script src="{{ asset('js/category-submit.js') }}"></script>
    <script src="{{ asset('js/cropper-init.js') }}"></script>
@endsection
