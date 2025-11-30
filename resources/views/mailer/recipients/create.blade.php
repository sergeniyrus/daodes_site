@extends('mailer.admin_layout')
@section('title', 'Добавить контакт')
@section('content')
<form method="POST" action="{{ route('mailer.recipients.store') }}">
@csrf
<label>Имя</label><input name="name" required>
<label>Email</label><input name="email" type="email" required>
<button class="btn">Сохранить</button>
</form>
@endsection
