@extends('mailer.admin_layout')
@section('title', 'Редактировать контакт')
@section('content')
<form method="POST" action="{{ route('mailer.recipients.update',$recipient) }}">
@csrf @method('PUT')
<label>Имя</label><input name="name" value="{{ $recipient->name }}" required>
<label>Email</label><input name="email" value="{{ $recipient->email }}" required>
<button class="btn">Обновить</button>
</form>
@endsection
