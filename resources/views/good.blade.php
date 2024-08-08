@extends('template')

@section('title_page')
    {{-- тайтл --}} Good
@endsection

@section('main')
    <div class="good">
        <br>
        <h2>            
        <?php
        if ( $action == 'edit') {echo 'Редактирование';}
        if ( $action == 'create') {echo 'Создание';}

        if ( $post == 'news'){ echo ' новости'; }
        if ( $post == 'offers'){ echo ' предложения'; }

            ?>   
        </h2><br>
        <h2>прошло успешно!</h2>
        <br>
        <a href="/page/{{ $post }}/{{ $id }}" >
            <h2 class="text-cyan-600">посмотреть</h2>
        </a>
        <br>
    </div>
@endsection
