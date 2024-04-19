@extends('template')

@section('title_page')
In DAODES
@endsection

@section('main')
<div class="modal-content">
    <div class="imgcontainer">
        <img src="/img/main/img_avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <div class="regwin">
            
                {{ $slot }}
            
        </div>    
    </div>
</div>        
@endsection