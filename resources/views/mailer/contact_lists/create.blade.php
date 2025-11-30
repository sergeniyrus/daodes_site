@extends('mailer.admin_layout')
@section('title', 'Создать список')
@section('content')
<form method="POST" action="{{ route('mailer.lists.store') }}">
@csrf
<label>Название</label><input name="name" required>
<label>Контакты</label>
<select name="recipients[]" multiple style="height:200px;">
@foreach($recipients as $r)
<option value="{{ $r->id }}">{{ $r->name }} ({{ $r->email }})</option>
@endforeach
</select>
<button class="btn">Сохранить</button>
</form>
@endsection
