@extends('template')
@section('title_page', 'Биржа заданий')
@section('main')

<style>
    .container {
        padding: 15px;
        margin: 0 auto;
        max-width: 800px;
        background-color: rgba(30, 32, 30, 0.753);
        border-radius: 15px;
        border: 1px solid #d7fc09; /* Золотая граница для контейнера */
        color: #f8f9fa;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        margin-top: 30px; /* Верхний отступ для контейнера */
    }

    .text-center {
        text-align: center; /* Центрирование текста */
    }

    .card {
        border-radius: 10px;
        border: 1px solid #f8f9fa; /* Легкая граница для карточки */
        background-color: #2b2c2e;
        margin-bottom: 20px; /* Отступ между карточками */
        padding: 15px; /* Внутренние отступы для карточки */
    }

    .card-title {
        font-size: 1.75rem; /* Увеличенный размер заголовка */
        color: #d7fc09; /* Золотой цвет заголовка */
        margin-bottom: 15px; /* Отступ снизу */
        text-align: center; /* Центрирование заголовка */
    }

    .card-text {
        font-size: 1rem;
        margin-bottom: 10px; /* Отступ снизу */
    }

    .btn.blue_btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1rem;
        background: #0b0c18;
        padding: 10px 20px;
        border: 1px solid #d7fc09; /* Золотая граница для кнопки */
        border-radius: 10px;
        box-shadow: 0 0 20px #000;
        cursor: pointer;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        text-decoration: none; /* Убираем подчеркивание для кнопки */
    }

    .btn.blue_btn:hover {
        box-shadow: 0 0 20px #d7fc09, 0 0 40px #d7fc09, 0 0 60px #d7fc09;
        transform: scale(1.05);
        background: #0b0c18;
    }

    a {
        color: aqua; /* Цвет для ссылок */
        text-decoration: none; /* Убираем подчеркивание */
    }

    a:hover {
        color: aqua; /* Легкое изменение цвета при наведении */
    }

    .pagination {
        margin-top: 20px; /* Отступ сверху для пагинации */
        display: flex;
        justify-content: center; /* Центрирование пагинации */
    }

    .pagination .page-item .page-link {
        color: #d7fc09; /* Цвет ссылок пагинации */
    }

    .pagination .page-item.active .page-link {
        background-color: #373af0; /* Цвет активной ссылки */
        border-color: #373af0; /* Цвет границы активной ссылки */
    }

    .task-budget,
    .task-deadline,
    .task-category {
        font-size: 0.875rem; /* Уменьшенный размер для бюджета и срока */
        margin: 0 10px; /* Отступы между элементами */
    }

    .task-info {
        display: flex;
        justify-content: space-between; /* Выравнивание элементов по краям */
        align-items: center; /* Центрирование по вертикали */
        color: #d7fc09; /* Золотой цвет для информации */
        margin-top: 10px; /* Отступ сверху для информации */
    }

    .task-title {
        flex: 1; /* Занимает оставшееся пространство */
        text-align: center; /* Центрирование заголовка */
    }

    .task-wrapper {
        display: flex;
        justify-content: space-between; /* Размещаем элементы по краям */
        align-items: center; /* Центрирование по вертикали */
        margin-bottom: 10px; /* Отступ между заголовком и информацией */
    }

    @media (max-width: 768px) {
        .task-wrapper {
            flex-direction: column; /* Вертикальное выравнивание для маленьких экранов */
            align-items: center; /* Центрирование элементов */
        }

        .task-info {
            justify-content: space-between; /* Выровнять информацию по строке */
            width: 100%; /* Занять всю ширину */
        }

        .task-info p {
            margin: 5px 0; /* Отступы между элементами информации */
        }
    }

    @media (max-width: 480px) {
        .task-info {
            flex-direction: column; /* Вертикальное выравнивание для очень маленьких экранов */
            align-items: center; /* Центрирование элементов */
        }
    }
</style>

<div class="container my-5">
    <div class="text-center mb-4">
        <h1 class="display-4">Биржа заданий</h1>
    </div>

    @forelse($tasks as $task)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="task-wrapper">
                <div class="task-title">
                    <a href="{{ route('tasks.show', $task) }}" class="btn blue_btn">{{ $task->title }}</a> 
                </div>    
                <!-- Информация о категории, бюджете и сроке -->
                <div class="task-info">    
                    <p class="task-category"><i class="fas fa-folder-open"></i> {{ $task->category ? $task->category->name : 'Без категории' }}</p>
                    <p class="task-budget"><i class="fas fa-dollar-sign"></i> {{ $task->budget }} руб.</p>
                    <p class="task-deadline"><i class="fas fa-clock"></i> {{ $task->deadline }}</p>
                </div>
            </div>

            <!-- Заголовок как кнопка -->
            <p class="card-text">
                @if (strlen($task->content) > 260)
                {!! Str::limit($task->content, 260) !!}
                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-link">→ Читать дальше ←</a>
                @else
                    {{ $task->content }}
                @endif
            </p>
        </div>
    </div>
@empty
    <div style="text-align: center">
        <p>Заданий нет, можете <a href="{{ route('addtask') }}">создать задание</a>.</p>
    </div>
@endforelse
<?php
Log::info('List читаем file');
?>

    <!-- Пагинация -->
    <div class="pagination">
        {{ $tasks->links() }}
    </div>
</div>

@endsection
