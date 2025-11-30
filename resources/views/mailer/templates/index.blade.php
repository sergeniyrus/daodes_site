@extends('mailer.admin_layout')
@section('title', 'Шаблоны писем')

@section('content')
<a href="{{ route('mailer.templates.create') }}" class="btn">Создать шаблон</a>
<table>
    <thead><tr><th>ID</th><th>Название</th><th>Тема</th><th>Действия</th></tr></thead>
    <tbody>
    @foreach($templates as $t)
    <tr>
        <td>{{ $t->id }}</td><td>{{ $t->name }}</td><td>{{ $t->subject }}</td>
        <td>
            <a href="{{ route('mailer.templates.edit', $t) }}" class="btn">Редактировать</a>
            <a href="{{ route('mailer.templates.show', $t) }}" class="btn">Просмотр</a>

            <form action="{{ route('mailer.templates.destroy', $t) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn">Удалить</button>
            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection
