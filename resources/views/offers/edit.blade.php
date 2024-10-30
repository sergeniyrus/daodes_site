@extends('template')

@section('title_page')
    Создать новость
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
    <div class="new_post">
        <div class="name_str">
            <h2>Редактировать предложение.</h2>
        </div>
        <form method="POST" action="{{ route('offers.update', ['id' => $offer->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="ids" value="{{ $offer->id }}"/>
            <div class="verh">
                <div class="fp">Название предложения<br>
                    <input type="text" name="title" class="dark_text" value="{{ $offer->title }}" /><br>
                </div>

                <div class="fp">Тема<br>
                    <select name="category" size="1" class="dark_text">
                        


                        <?php
                    // получаем категории
                    $categories = DB::table('category_offers')->get();
                    foreach ($categories as $category) : ?>

                        <option value="<?php echo $category->id;
                        if (($category->id)==($offer->category_id)){
                        echo '" selected="selected';
                        }
                        ?>" label="<?php echo $category->category_name; ?>"></option>
                        <?php endforeach; ?>
                    </select><br>
                </div>
            </div>
            <div class="redactor">
                <textarea id="editor" name="content" rows="20" cols="100"
                    >{{ $offer->content }}</textarea><br>
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
            </script>
            
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Сохранить</button>
        </form>
        <div class="varn">
            <p>Изображение должно иметь размер 1:1 (200*200px)</p>
            <p> Имя файла <b>только</b> цифры и английский язык ! </p>
        </div>
        <br>
    </div>
@endsection