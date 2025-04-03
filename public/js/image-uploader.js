document.addEventListener('DOMContentLoaded', function() {
    // Modal handling
    const modal = document.getElementById('category-modal');
    const cropModal = document.getElementById('crop-modal');
    const openBtn = document.getElementById('open-category-modal');
    const closeBtns = document.querySelectorAll('.close');
    const errorDisplay = document.getElementById('category-error');
    const categoryForm = document.getElementById('category-form');
    const submitCategoryBtn = document.getElementById('submit-category-btn');
    const categorySelect = document.getElementById('category-select');
    const categoryNameInput = document.getElementById('category-name');
    const fileInput = document.getElementById('file-input');
    const preview = document.getElementById('preview');
    const fileName = document.getElementById('file-name');
    const croppedImageInput = document.getElementById('cropped-image');
    let cropper;

    // Set placeholder from translations
    categoryNameInput.placeholder = '{{ __("admin_news.name_regex") }}';

    // Open category modal
    openBtn.addEventListener('click', () => {
        modal.style.display = 'block';
        errorDisplay.textContent = '';
        categoryNameInput.focus();
    });

    // Close modals
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modalToClose = this.closest('.modal');
            modalToClose.style.display = 'none';
            
            if (modalToClose.id === 'crop-modal' && cropper) {
                cropper.destroy();
            }
            
            if (modalToClose.id === 'category-modal') {
                categoryForm.reset();
            }
        });
    });

    // Close when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            categoryForm.reset();
        }
        if (e.target === cropModal) {
            cropModal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
            }
        }
    });

    // Handle category form submission
    categoryForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorDisplay.textContent = '';
        
        // Client-side validation
        const nameRegex = /^[\p{L}\p{N}\s\-\.,;!?€£\$₽]+$/u;
        if (!nameRegex.test(categoryNameInput.value)) {
            errorDisplay.textContent = '{{ __("admin_news.validation.name_regex") }}';
            return;
        }

        const originalBtnContent = submitCategoryBtn.innerHTML;
        submitCategoryBtn.innerHTML = '<i class="fas fa-spinner loading-spinner"></i> {{ __("message.processing") }}';
        submitCategoryBtn.disabled = true;

        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route("newscategories.store") }}', {
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
                            errorMessages.push('{{ __("admin_news.validation.name_taken") }}');
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
                alert(data.message || '{{ __("message.category_added_success") }}');
            }
        } catch (error) {
            console.error('Error:', error);
            errorDisplay.textContent = error.message || '{{ __("message.category_add_failed") }}';
        } finally {
            submitCategoryBtn.innerHTML = originalBtnContent;
            submitCategoryBtn.disabled = false;
        }
    });

    // Image preview and cropping
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.match('image.*')) {
                    alert('{{ __("admin_news.please_select_image") }}');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    // Show preview
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    fileName.textContent = file.name;
                    
                    // Show crop modal
                    cropModal.style.display = 'block';
                    const cropContainer = document.getElementById('crop-container');
                    cropContainer.innerHTML = '<img id="image-to-crop" src="' + event.target.result + '">';
                    
                    // Initialize cropper
                    const image = document.getElementById('image-to-crop');
                    cropper = new Cropper(image, {
                        aspectRatio: 1, // Square aspect ratio
                        viewMode: 1,
                        autoCropArea: 0.8,
                        responsive: true,
                        preview: '#crop-preview'
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle crop button
    document.getElementById('crop-button').addEventListener('click', function() {
        if (cropper) {
            // Get cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 800,
                height: 800,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 4096,
                maxHeight: 4096,
                fillColor: '#1a1a1a',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            // Convert to data URL
            const croppedImageUrl = canvas.toDataURL('image/jpeg');
            
            // Update preview
            preview.src = croppedImageUrl;
            preview.style.display = 'block';
            
            // Store cropped image data
            croppedImageInput.value = croppedImageUrl;
            
            // Close modal
            cropModal.style.display = 'none';
            cropper.destroy();
        }
    });

    // Handle cancel crop
    document.getElementById('cancel-crop').addEventListener('click', function() {
        cropModal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
        }
        // Reset file input
        fileInput.value = '';
        preview.style.display = 'none';
        fileName.textContent = '{{ __("admin_news.no_file_selected") }}';
    });

    // Validate main form
    document.getElementById('news-form').addEventListener('submit', function(e) {
        if (!categorySelect.value) {
            e.preventDefault();
            alert('{{ __("admin_news.please_select_category") }}');
            categorySelect.focus();
            return;
        }
        
        // Update textarea with CKEditor content before submit
        if (typeof CKEDITOR !== 'undefined') {
            for (let instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        
        // For CKEditor 5
        if (window.editor) {
            document.getElementById('editor-content').value = window.editor.getData();
        }
    });
});