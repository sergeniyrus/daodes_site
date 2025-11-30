@extends('mailer.admin_layout')
@section('title', 'Редактировать список')
@section('content')
<form method="POST" action="{{ route('mailer.lists.update',$list) }}">
@csrf @method('PUT')
<label>Название</label><input name="name" value="{{ $list->name }}" required>
<label>Контакты</label>
<select name="recipients[]" multiple style="height:200px;">
@foreach($recipients as $r)
<option value="{{ $r->id }}" @if($list->recipients->contains($r->id)) selected @endif>
{{ $r->name }} ({{ $r->email }})
</option>
@endforeach
</select>
<button class="btn">Обновить</button>
</form>
@endsection
