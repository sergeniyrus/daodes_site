@extends('template')
@section('title_page', 'Создать новое задание')

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

    .form-group label {
        color: #d7fc09;
        font-size: 1.2rem;
        display: block;
        margin: 10px 0;
        text-align: left;
        font-weight: bold;
    }

    .input_dark, textarea {
        background-color: #1a1a1a;
        color: #a0ff08;
        border: 1px solid #d7fc09;
        border-radius: 5px;
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        transition: border 0.3s ease;
    }

    .input_dark:focus, textarea:focus {
        border: 1px solid #a0ff08;
        outline: none;
        box-shadow: 0 0 5px #d7fc09;
    }

    .blue_btn {
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

    .blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #1a1a1a;
    }
    .ck-toolbar {
        background-color: #333333 !important; /* Тёмный фон */
        border-bottom: 1px solid #d7fc09 !important; /* Добавление границы */
    }

    /* Изменение цвета кнопок на панели инструментов */
    .ck-toolbar button {
        color: #f8f9fa !important; /* Светлый цвет текста */
        background-color: #0b0c18 !important; /* Темный фон кнопок */
        border: 1px solid #d7fc09 !important; /* Цвет границ кнопок */
    }

    /* Цвет кнопок при наведении */
    .ck-toolbar button:hover {
        background-color: #d7fc09 !important; /* Желтый фон при наведении */
        color: #1a1a1a !important; /* Тёмный текст при наведении */
    }

    /* Цвет для активных кнопок */
    .ck-toolbar button.ck-on {
        background-color: #d7fc09 !important; /* Желтый фон */
        color: #1a1a1a !important; /* Тёмный текст */
    }

        .ck-editor__editable {
        color: #bbbbbb !important; /* Тёмный цвет текста */
        background-color: #1a1a1a !important; /* Тёмный фон */
        font-size: 16px !important;
    }
</style>

<div class="container my-5">
    <div class="text-center mb-4">
        <h1 class="display-4">Создать новое задание</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Заголовок задачи:</label>
            <input type="text" name="title" id="title" class="input_dark" required>
        </div>

        <div class="form-group">
            <label for="category_id">Категория:</label>
            <select name="category_id" id="category_id" class="input_dark" required>
                <option value="">Выберите категорию</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="content">Описание задачи:</label>
            <textarea name="content" id="editor" class="input_dark" rows="5" required placeholder="Введите задание..."></textarea>
        </div>

        <div class="form-group">
            <label for="deadline">Дедлайн:</label>
            <input type="date" name="deadline" id="deadline" class="input_dark" required>
        </div>

        <div class="form-group">
            <label for="budget">Бюджет:</label>
            <input type="number" name="budget" id="budget" class="input_dark" step="0.01" required>
        </div>

        

        <div class="text-center">
            <button type="submit" class="blue_btn"><i class="fas fa-plus-circle"></i> Создать задачу</button>
        </div>
    </form>
</div>

<script>
    // Инициализация CKEditor
    ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: '{{ route('upload.image') }}',
            options: {
                onError: function(error) {
                    console.error('Error during file upload:', error);
                }
            }
        },
        
    })
    .catch(error => {
        console.error('Editor error:', error);
    });
</script>

@endsection
