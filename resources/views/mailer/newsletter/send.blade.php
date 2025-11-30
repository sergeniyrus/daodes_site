@extends('mailer.admin_layout')
@section('title', 'Отправка рассылки')
@section('content')
<form method="POST" action="{{ route('mailer.send') }}">
@csrf
<label>Шаблон</label>
<select name="template_id">
@foreach($templates as $t)
<option value="{{ $t->id }}">{{ $t->name }}</option>
@endforeach
</select>

<label>Список контактов</label>
<select name="list_id">
@foreach($lists as $l)
<option value="{{ $l->id }}">{{ $l->name }}</option>
@endforeach
</select>

<button class="btn">Отправить</button>
</form>
@endsection
