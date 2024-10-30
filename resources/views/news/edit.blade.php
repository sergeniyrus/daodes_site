@extends('template')

@section('title_page')
    Редактировать новость
@endsection

@section('main')

<style>
.new_post {
    width: 90%;
    margin: 20px auto;
    padding-bottom: 20px;
    background-color: rgba(30, 32, 30, 0.753);
    font-size: 20px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    border: 1px solid #fff;
    border-radius: 30px;
    text-align: center;
    display: flex;
    flex-direction: column;
}

.name_str h2 {
    text-align: center;
    font-size: 36px;
}

.blue_btn {
    display: inline-block;
    color: #ffffff;
    font-size: large;
    background: #0b0c18;
    padding: 15px 30px;
    border: 1px solid #d7fc09;
    border-radius: 10px;
    box-shadow: 0 0 20px #000;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    gap: 15px;
}

.blue_btn:hover {
    box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
    transform: scale(1.05);
    color: #ffffff;
    background: #0b0c18;
}

.verh {
    display: flex;
    flex-direction: column;
    gap: 10px;
    text-align: center;
    align-items: center;
    justify-content: center;
}

.fp {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin: 0 20px;
    width: 100px;
}

.dark_text {
    padding: 8px;
    font-size: 16px;
    color: black;
}

.redactor {
    margin: 20px;
    color: black;
}

.varn {
    text-align: center;
    font-size: 14px;
    color: red;
    margin-top: 25px;
}

.file-input-wrapper {
    display: flex;
    flex-direction: column;
    gap: 5px;
    position: relative;
    overflow: hidden;
}

.file-input-wrapper input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 100px;
    cursor: pointer;
    opacity: 0;
}

.custom-file-button {
    display: inline-block;
    padding: 8px 12px;
    cursor: pointer;
    border: 1px solid #ddd;
    background-color: #f8f8f8;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
    transition: background-color 0.3s;
    margin: auto;
}

.custom-file-button:hover {
    background-color: #e0e0e0;
}

#crop-container {
    display: none;
    margin-top: 20px;
}

#preview {
    display: block;
    width: 100px; /* Ограничение по ширине */
    max-width: 100%;
    margin-top: 20px;
}

button.inline-flex {
    margin-top: 20px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
}

@media (min-width: 768px) {
    .verh {
        flex-direction: row;
        justify-content: space-between;
    }
    .fp {
        flex: 1;
    }
    button.inline-flex {
        width: auto;
    }
}

@media (max-width: 767px) {
    .fp input[type="text"], .fp select, .fp input[type="file"] {
        width: 100%;
    }
    #preview {
        width: 80px;
    }
}

@media (max-width: 480px) {
    .dark_text {
        font-size: 14px;
        padding: 6px;
    }
    .varn {
        font-size: 12px;
    }
}

@media (max-width: 320px) {
    .dark_text {
        font-size: 12px;
        padding: 4px;
    }
    .varn {
        font-size: 10px;
    }
}
</style>

<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet">
<script src="https://unpkg.com/cropperjs"></script>

<div class="new_post">
    <div class="name_str">
        <h2>Редактировать новость.</h2>
    </div>
    <form id="news-form" method="POST" action="{{ route('news.update', $news->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="ids" value="{{ $news->id }}"/>
        <div class="verh">
            <div class="fp">Название новости<br>
                <input type="text" name="title" class="dark_text" value="{{ $news->title }}" /><br>
            </div>

            <div class="fp">Тема<br>
                <select name="category" size="1" class="dark_text">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $news->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select><br>
            </div>

            <div class="fp">Картинка новости<br>
                <div class="file-input-wrapper">
                    <button type="button" class="custom-file-button">Выберите файл</button>
                    <input type="file" id="file-input" name="filename" accept="image/*">
                    <div id="crop-container">
                        <img id="crop-image" src="#">
                    </div>
                    <img id="preview" src="{{ $news->img }}" alt="Image Preview" style="max-width: 100px;">
                    <span id="file-name">Файлы не выбраны</span>
                </div>
            </div>
        </div>

        <div class="redactor">
            <textarea id="editor" name="content" rows="20" cols="100">{{ $news->content }}</textarea><br>
        </div>
        <button class="blue_btn" type="submit">Сохранить</button>
    </form>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });

        let cropper;

        document.querySelector('.custom-file-button').addEventListener('click', function() {
            document.getElementById('file-input').click();
        });

        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('crop-container').style.display = 'block';
                    const cropImage = document.getElementById('crop-image');
                    cropImage.src = e.target.result;
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        preview: '#preview',
                    });
                }
                reader.readAsDataURL(file);
            }
            const fileName = this.files.length > 0 ? Array.from(this.files).map(f => f.name).join(', ') : 'Файлы не выбраны';
            document.getElementById('file-name').textContent = fileName;
        });

        document.getElementById('news-form').addEventListener('submit', function(event) {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas();
                canvas.toBlob(function(blob) {
                    const fileInputElement = document.getElementById('file-input');
                    const file = new File([blob], 'cropped.png', { type: 'image/png' });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInputElement.files = dataTransfer.files;

                    document.getElementById('news-form').submit();
                });
                event.preventDefault();
            }
        });
    </script>
</div>
@endsection
