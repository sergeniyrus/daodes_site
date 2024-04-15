@extends('template')

@section('title_page')
{{-- тайтл --}}
@endsection

@section('main')
{{-- тело вывода --}}
<!-- Scripts -->



   
        <a href="{{ url('/home') }}">Home</a>
    
        <a href="{{ route('login') }}">Log in</a>

    
            <a href="{{ route('register') }}">Register</a>
    



@endsection