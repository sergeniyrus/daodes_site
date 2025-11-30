@extends('mailer.admin_layout')
@section('title', 'Редактировать шаблон')

@section('content')
<form method="POST" action="{{ route('mailer.templates.update', $template) }}">
@csrf @method('PUT')
<label>Название</label><input name="name" value="{{ $template->name }}" required>
<label>Тема письма</label><input name="subject" value="{{ $template->subject }}" required>
<label>HTML тело письма</label><textarea name="body" rows="10" required>{{ $template->body }}</textarea>
<button class="btn">Обновить</button>
</form>
@endsection
