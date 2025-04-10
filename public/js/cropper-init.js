
let cropper = null;
let currentImageUrl = null;
let originalFileName = '';

const fileInput = document.getElementById('file-input');
const preview = document.getElementById('preview');
const fileName = document.getElementById('file-name');
const cropModal = document.getElementById('crop-modal');
const cropContainer = document.getElementById('crop-container');

function initCropper(image) {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true,
        cropBoxResizable: true,
        minCropBoxWidth: 256,
        minCropBoxHeight: 256,
        background: false,
        ready() {
            const size = Math.min(
                this.cropper.getContainerData().width * 0.8,
                this.cropper.getContainerData().height * 0.8,
                this.cropper.getImageData().naturalWidth,
                this.cropper.getImageData().naturalHeight
            );
            this.cropper.setCropBoxData({ width: size, height: size });
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

fileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file || !file.type.match('image.*')) {
        alert('Please select an image file');
        return;
    }

    originalFileName = file.name;
    cleanupCropper();

    currentImageUrl = URL.createObjectURL(file);
    cropModal.style.display = 'block';

    cropContainer.innerHTML = `<img id="image-to-crop" src="${currentImageUrl}" style="max-width: 100%;">`;
    document.getElementById('image-to-crop').onload = function () {
        initCropper(this);
    };
});

document.getElementById('crop-button').addEventListener('click', function () {
    if (!cropper) return;

    try {
        const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
        canvas.toBlob(function (blob) {
            const ext = originalFileName.split('.').pop();
            const croppedFileName = `cropped_${originalFileName}`;
            const file = new File([blob], croppedFileName, { type: `image/${ext}` });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            fileName.textContent = croppedFileName;

            cropModal.style.display = 'none';
            cleanupCropper();
        }, `image/${originalFileName.split('.').pop()}`, 0.9);
    } catch (error) {
        console.error(error);
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

window.addEventListener('click', function (event) {
    if (event.target === cropModal) resetFileInput();
});
