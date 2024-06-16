@extends('template')

@section('title_page')
    Создать новость
@endsection

@section('main')
<style>
    .new_post {
        padding: 20px;
        margin: auto;
    }
    .name_str h2 {
        text-align: center;
    }
    .verh {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .fp {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .dark_text {
        padding: 8px;
        font-size: 16px;
    }
    .redactor {
        margin-top: 20px;
    }
    .varn {
        text-align: center;
        font-size: 14px;
        color: red;
    }
    .file-input-wrapper {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .file-input-wrapper span {
        font-size: 14px;
        color: #555;
    }
    button.inline-flex {
        margin-top: 20px;
        width: 100%;
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

<div class="new_post">
    <div class="name_str">
        <h2>Добавление новости.</h2>
    </div>
    <form method="POST" action="{{ route('add_news') }}" enctype="multipart/form-data">
        @csrf
        <div class="verh">
            <div class="fp">Название новости<br>
                <input type="text" name="title" class="dark_text" /><br>
            </div>

            <div class="fp">Тема<br>
                <select name="category" size="1" class="dark_text">
                    <option value="0" selected>Жми и выбирай</option>

                    @foreach (DB::table('category_news')->get() as $category)
                        <option value="{{ $category->id }}" label="{{ $category->category_name }}"></option>
                    @endforeach
                </select><br>
            </div>

            <div class="fp">Картинка новости<br>
                <div class="file-input-wrapper">
                    <input type="file" id="file-input" name="filename" multiple accept="image/*">
                    <span id="file-name">Файлы не выбраны</span>
                </div>
            </div>
        </div>

        <div class="redactor">
            <textarea id="editor" name="text" rows="20" cols="100" placeholder="Введите текст новости, возможно использование html тегов"></textarea><br>
        </div>
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
                })
                .then(editor => {
                    window.editor = editor;
                })
                .catch(err => {
                    console.error(err.stack);
                });

            document.getElementById('file-input').addEventListener('change', function() {
                const fileName = this.files.length > 0 ? Array.from(this.files).map(f => f.name).join(', ') : 'Файлы не выбраны';
                document.getElementById('file-name').textContent = fileName;
            });
        </script>
        <br>
        <button
            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Создать</button>
    </form><br>
    <div class="varn">
        <p>Изображение должно иметь размер 1:1 (200*200px)</p>
        <p> Имя файла <b>только</b> цифры и английский язык ! </p>
    </div>
    <br>
</div>
@endsection
