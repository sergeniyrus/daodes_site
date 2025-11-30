@extends('mailer.admin_layout')
@section('title', 'Контакты')

@section('content')
<a href="{{ route('mailer.recipients.create') }}" class="btn">Добавить</a>
<a href="{{ route('mailer.recipients.import.form') }}" class="btn">Импорт</a>

<table>
<thead><tr><th>ID</th><th>Имя</th><th>Email</th><th>Источник</th><th>Действия</th></tr></thead>
<tbody>
@foreach($recipients as $r)
<tr>
<td>{{ $r->id }}</td><td>{{ $r->name }}</td><td>{{ $r->email }}</td><td>{{ $r->imported_from ?? 'Ручное' }}</td>
<td>
<a href="{{ route('mailer.recipients.edit',$r) }}" class="btn">Редактировать</a>
<form action="{{ route('mailer.recipients.destroy',$r) }}" method="POST" style="display:inline;">
@csrf @method('DELETE')<button class="btn">Удалить</button></form>
</td></tr>
@endforeach
</tbody>
</table>
@endsection
