@extends('template')
@section('title_page')
    Панель управления
@endsection

@section('main')
    <div id="header">
        {{-- <div class="imgcontainer"> --}}
            <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
        {{-- </div> --}}
        <div class="container">

            {{ $slot }}
        </div>
    </div>
@endsection
