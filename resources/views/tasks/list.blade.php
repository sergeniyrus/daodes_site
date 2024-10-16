@extends('template')
@section('title_page')
    Биржа заданий
@endsection
@section('main')
<style>
    .card {
        border-radius: 10px;
        border-color: #f8f9fa;
        background-color: #2b2c2e;
        text-align: center; /* Центрирование текста */
        color: #fff; /* Белый цвет текста для контраста */
    }

    .card-title {
        font-size: 1.5rem;
    }

    .card-text {
        font-size: 1rem;
    }

    .btn-outline-primary {
        font-size: 0.875rem;
        padding: 5px 10px;
    }

    a {
        color: #373af0; /* Белый цвет для ссылок */
    }

    a:hover {
        color: #f8f9fa; /* Легкое изменение цвета при наведении */
    }
</style>
<div class="modal-content" style="text-align: center;">
<div class="container my-5">
    <div class="text-center mb-4">
        <h1 class="display-4">Биржа заданий</h1>
    </div>

    @foreach($tasks as $task)
        <div class="card mb-4 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title"><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></h2>
                <p class="card-text">
                    @if (strlen($task->description) > 260)
                        {{ Str::limit($task->description, 260) }}...
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-primary btn-sm">Читать дальше</a>
                    @else
                        {{ $task->description }}
                    @endif
                </p>
                <p>Категория: {{ $task->category ? $task->category->name : 'Без категории' }}</p>
<!-- Добавлено отображение категории -->
                <p class="card-text"><strong>Бюджет:</strong> {{ $task->budget }} руб.</p>
                <p class="card-text"><strong>Срок:</strong> {{ $task->deadline }}</p>
            </div>
        </div>
    @endforeach
</div>
<!-- Пагинация -->
<div class="pagination">
    {{ $tasks->links() }}
</div>
</div>
@endsection
