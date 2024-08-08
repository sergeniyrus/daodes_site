@extends('template')

@section('title_page')
    Создать новость
@endsection

@section('main')
    <div class="new_post">
        <div class="name_str">
            <h2>Редактировать предложение.</h2>
        </div>
        <form method="POST" action="/edit_offers/{{ $offer->id }}" enctype="multipart/form-data">
            @csrf
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