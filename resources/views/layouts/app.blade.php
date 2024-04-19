@extends('template')
@section('title_page')
Панель управления
@endsection

@section('main') 
<div class="modal-content">
    <div class="imgcontainer">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        
    {{ $slot }}
        </div></div>
@endsection