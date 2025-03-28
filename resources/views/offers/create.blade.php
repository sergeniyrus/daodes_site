@extends('template')

@section('title_page')
Create an offer
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

        .des-btn {
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

        .des-btn:hover {
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
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #d7fc09;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .close {
            color: #d7fc09;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #a0ff08;
        }

        .error-message {
            color: #ff6b6b;
            margin: 10px 0;
            text-align: center;
            font-size: 0.9rem;
        }
        .loading-spinner {
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

<div class="container">
    <h2 class="text-center">{{ __('admin_offers.create_offer_title') }}</h2>

    <form id="offers-form" method="POST" action="{{ route('offers.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">{{ __('admin_offers.offer_title') }}</label>
            <input type="text" name="title" class="input_dark" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="category">{{ __('admin_offers.category') }}</label>
            <div class="category-container">
                <div class="category-select-wrapper">
                    <select name="category" class="input_dark" id="category-select" required>
                        <option value="" selected disabled>{{ __('admin_offers.select_category') }}</option>
                        @foreach ($category_offers as $category)
                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="des-btn add-category-btn" id="open-category-modal">
                    <i class="fas fa-plus-circle"></i> {{ __('admin_offers.add_category') }}
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="filename">{{ __('admin_offers.image') }}</label>
            <div class="file-input-wrapper">
                <img id="preview" src="#" alt="Image preview">
                <button type="button" class="des-btn" onclick="document.getElementById('file-input').click();">
                    {{ __('admin_offers.choose_file') }}
                </button>
                <input type="file" id="file-input" name="filename" accept="image/*" style="display: none;">
                <div id="file-name">{{ __('admin_offers.no_file_chosen') }}</div>
            </div>
            <p style="color: red; text-align: center; margin-top: 20px; font-size:0.9rem;">
                {{ __('admin_offers.image_requirements') }}
            </p>
        </div>

        <div class="form-group">
            <label for="content">{{ __('admin_offers.offer_content') }}</label>
            <textarea id="editor" name="content" rows="10" class="input_dark" required
                placeholder="{{ __('admin_offers.enter_offer_text') }}">{{ old('content') }}</textarea>
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
        <h2>{{ __('category.categories_title') }}</h2>
        <form id="category-form" method="POST">
            @csrf
            <div class="form-group">
                <label for="category-name">{{ __('category.category_name_label') }}</label>
                <input type="text" name="name" id="category-name" class="input_dark" required>
            </div>
            <div id="category-error" class="error-message"></div>
            <div style="text-align: center;">
                <button type="submit" class="des-btn" id="submit-category-btn">
                    <i class="fas fa-plus-circle"></i> {{ __('admin_offers.add_category') }}
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal handling
        const modal = document.getElementById('category-modal');
        const openBtn = document.getElementById('open-category-modal');
        const closeBtn = document.querySelector('.close');
        const errorDisplay = document.getElementById('category-error');
        const categoryForm = document.getElementById('category-form');
        const submitCategoryBtn = document.getElementById('submit-category-btn');
        const categorySelect = document.getElementById('category-select');
        const categoryNameInput = document.getElementById('category-name');
    
        // Set placeholder from translations
        categoryNameInput.placeholder = '{{ __("admin_offers.name_regex") }}';
    
        // Open modal
        openBtn.addEventListener('click', () => {
            modal.style.display = 'block';
            errorDisplay.textContent = '';
            categoryNameInput.focus();
        });
    
        // Close modal
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            categoryForm.reset();
        });
    
        // Close when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                categoryForm.reset();
            }
        });
    
        // Handle category form submission
        categoryForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            errorDisplay.textContent = '';
            
            // Client-side validation
            const nameRegex = /^[\p{L}\p{N}\s\-\.,;!?€£\$₽]+$/u;
            if (!nameRegex.test(categoryNameInput.value)) {
                errorDisplay.textContent = '{{ __("admin_offers.validation.name_regex") }}';
                return;
            }
    
            const originalBtnContent = submitCategoryBtn.innerHTML;
            submitCategoryBtn.innerHTML = '<i class="fas fa-spinner loading-spinner"></i> {{ __("message.processing") }}';
            submitCategoryBtn.disabled = true;
    
            try {
                const formData = new FormData(this);
                const response = await fetch('{{ route("offerscategories.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
    
                const data = await response.json();
    
                if (!response.ok) {
                    if (data.errors) {
                        let errorMessages = [];
                        for (const [key, messages] of Object.entries(data.errors)) {
                            if (key === 'name' && messages.some(m => m.includes('The name has already been taken'))) {
                                errorMessages.push('{{ __("admin_offers.validation.name_taken") }}');
                            } else {
                                errorMessages.push(...messages);
                            }
                        }
                        errorDisplay.textContent = errorMessages.join(', ');
                    } else {
                        throw new Error(data.message || '{{ __("message.unknown_error") }}');
                    }
                    return;
                }
    
                if (data.success) {
                    const newOption = document.createElement('option');
                    newOption.value = data.category.id;
                    newOption.textContent = data.category.name;
                    categorySelect.appendChild(newOption);
                    categorySelect.value = data.category.id;
                    
                    modal.style.display = "none";
                    this.reset();
                    alert(data.message || '{{ __("message.offer_category_added") }}');
                }
            } catch (error) {
                console.error('Error:', error);
                errorDisplay.textContent = error.message || '{{ __("message.offer_category_add_failed") }}';
            } finally {
                submitCategoryBtn.innerHTML = originalBtnContent;
                submitCategoryBtn.disabled = false;
            }
        });
    
        // Validate main form
        document.getElementById('offers-form').addEventListener('submit', function(e) {
            if (!categorySelect.value) {
                e.preventDefault();
                alert('{{ __("admin_offers.please_select_category") }}');
                categorySelect.focus();
            }
        });
    
        // Image preview
        const fileInput = document.getElementById('file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('preview');
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        document.getElementById('file-name').textContent = file.name;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    </script>
    
    @if(config('app.use_ckeditor'))
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                // CKEditor config options
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    @endif
    
    @endsection