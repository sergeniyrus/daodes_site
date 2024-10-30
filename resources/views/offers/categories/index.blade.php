@extends('template')

@section('title_page')
    Управление категориями предложений
@endsection

@section('main')
    <h2>Категории предложений</h2>
    <a href="{{ route('offers.categories.create') }}" class="blue_btn">Добавить категорию</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->category_name }}</td> <!-- Обратите внимание на правильное имя поля -->
                    <td>
                        <a href="{{ route('offers.categories.edit', $category->id) }}">Редактировать</a>
                        <form action="{{ route('offers.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
