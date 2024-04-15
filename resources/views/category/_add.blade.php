@extends('template')

@section('title_page')
    Создать тему {{$post}}
@endsection

@section('main')
    <div class="new_post">
        <div class="name_str">
            <h2>Добавление темы
              <?php 
                if ($post == 'news'){echo"новостей.";}
                if ($post == 'offers'){echo"предложений.";}
                ?>
            </h2>
        </div>
        <form method="POST" action={{ route('add_cat') }} enctype="multipart/form-data">
            @csrf
            <div class="verh">
                <div class="fp">Тема<br>
                    <input name="category"  class="dark_text">
                    <input type="hidden" name="page" value="{{ $post }}" />
                  </div>

                
                
          </div>
            <br>
            <button
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Создать</button>
            
        </form>

    </div>
@endsection