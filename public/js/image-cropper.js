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
                aspectRatio: 1, // Устанавливаем соотношение сторон 1:1 (квадрат)
                viewMode: 1, // Ограничаиваем область обрезки в пределах изображения
                background: true, // Показываем фон вокруг обрезаемой области
                scalable: false, // Отключаем масштабирование
                zoomable: true, // Включаем зумирование с помощью колесика мыши
                autoCropArea: 1, // Устанавливаем область обрезки на 100% изображения
                movable: true, // Разрешаем перемещать обрезаемую область
                cropBoxResizable: true, // Разрешаем изменять размер области обрезки
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