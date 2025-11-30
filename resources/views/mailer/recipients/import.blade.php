@extends('mailer.admin_layout')
@section('title', 'Импорт контактов')
@section('content')
<form method="POST" action="{{ route('mailer.recipients.import') }}" enctype="multipart/form-data">
@csrf
<label>Выберите CSV/XLSX</label><input type="file" name="file" required>
<button class="btn">Импортировать</button>
</form>
@endsection
