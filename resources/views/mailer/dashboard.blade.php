@extends('mailer.admin_layout')
@section('title', 'Панель рассылки')

@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;">
    <div style="background:#141526;border:1px solid gold;border-radius:10px;padding:15px;">
        <h3 style="color:gold;">Шаблоны</h3>
        <p>{{ $templates }}</p>
        <a href="{{ route('mailer.templates.index') }}" class="btn">Открыть</a>
    </div>
    <div style="background:#141526;border:1px solid gold;border-radius:10px;padding:15px;">
        <h3 style="color:gold;">Контакты</h3>
        <p>{{ $recipients }}</p>
        <a href="{{ route('mailer.recipients.index') }}" class="btn">Открыть</a>
    </div>
    <div style="background:#141526;border:1px solid gold;border-radius:10px;padding:15px;">
        <h3 style="color:gold;">Списки</h3>
        <p>{{ $lists }}</p>
        <a href="{{ route('mailer.lists.index') }}" class="btn">Открыть</a>
    </div>
    <div style="background:#141526;border:1px solid gold;border-radius:10px;padding:15px;">
        <h3 style="color:gold;">История писем</h3>
        <p>{{ $logs }}</p>
        <a href="{{ route('mailer.history') }}" class="btn">Открыть</a>
    </div>
</div>
@endsection
