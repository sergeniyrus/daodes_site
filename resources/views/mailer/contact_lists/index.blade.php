@extends('mailer.admin_layout')
@section('title', 'Списки контактов')
@section('content')
<a href="{{ route('mailer.lists.create') }}" class="btn">Создать список</a>
<table>
<thead><tr><th>ID</th><th>Название</th><th>Контактов</th><th>Действия</th></tr></thead>
<tbody>
@foreach($lists as $l)
<tr>
<td>{{ $l->id }}</td><td>{{ $l->name }}</td><td>{{ $l->recipients->count() }}</td>
<td>
<a href="{{ route('mailer.lists.edit',$l) }}" class="btn">Редактировать</a>
<form action="{{ route('mailer.lists.destroy',$l) }}" method="POST" style="display:inline;">
@csrf @method('DELETE')<button class="btn">Удалить</button></form>
</td></tr>
@endforeach
</tbody>
</table>
@endsection
