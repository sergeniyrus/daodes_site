@extends('mailer.admin_layout')
@section('title', 'Создать шаблон')

@section('content')
<form method="POST" action="{{ route('mailer.templates.store') }}">
@csrf
<label>Название</label><input name="name" required>
<label>Тема письма</label><input name="subject" required>
<label>HTML тело письма</label><textarea name="body" rows="10" required></textarea>
<button class="btn">Сохранить</button>
</form>
@endsection
