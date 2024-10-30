@extends('template')
@section('title_page', 'Добавить категорию')
@section('main')

<div class="container my-5">
    <h1>Добавить категорию {{ $type == 'news' ? 'Новостей' : 'Предложений' }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store', $type) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Добавить категорию
        </button>
    </form>
</div>

@endsection
