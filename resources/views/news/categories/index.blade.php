@extends('template')

@section('title_page')
    Управление категориями новостей
@endsection

@section('main')
    <h2>Категории новостей</h2>
    <a href="{{ route('news.categories.create') }}" class="blue_btn">Добавить категорию</a>

    <table>
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
                    <td>{{ $category->category_name }}</td>
                    <td>
                        <a href="{{ route('news.categories.edit', $category->id) }}">Редактировать</a>
                        <form action="{{ route('news.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
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
