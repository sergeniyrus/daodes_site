@extends('template')
@section('title_page', __('tasks.title_page'))
@section('main')

<style>
    .card {
        border-radius: 10px;
        border: 1px solid #f8f9fa; /* Легкая граница для карточки */
        background-color: #2b2c2e;
        margin: 20px 0px; /* Отступ между карточками */
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

    a {
        color: aqua; /* Цвет для ссылок */
        text-decoration: none; /* Убираем подчеркивание */
    }

    a:hover {
        color: rgb(0, 174, 255); /* Легкое изменение цвета при наведении */
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

    @media (max-width: 380px) {
        .task-info {
            flex-direction: column; /* Вертикальное выравнивание для очень маленьких экранов */
            align-items: center; /* Центрирование элементов */
        }
    }
</style>

<div class="container my-5">
    <div class="text-center mb-4">
        <h1 class="display-4">{{ __('tasks.title_page') }}</h1>
    </div>

    @forelse($tasks as $task)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="task-wrapper">
                <div class="task-title">
                    <a href="{{ route('tasks.show', $task) }}" class="des-btn">{{ $task->title }}</a> 
                </div>    
                <div class="task-info">    
                    <p class="task-category"><i class="fas fa-folder-open"></i> {{ $task->category ? $task->category->name : __('tasks.no_category') }}</p>
                    <p class="task-budget"><i class="fas fa-dollar-sign"></i> {{ $task->budget }} USD</p>
                    <p class="task-deadline"><i class="fas fa-clock"></i> {{ $task->deadline->format('Y-m-d') }}</p>
                
                <div class="task-author">
                    <p><strong>&copy;</strong>
                        <a href="{{ route('user_profile.show', ['id' => $task->user_id]) }}" title="Profile"
                            style="color: #d7fc09; text-decoration: none;">
                            {{ $task->user->name }}
                        </a>
                    </p>
                </div>
            </div>
            </div>

            <p class="card-text">
                @if (strlen($task->content) > 260)
                {!! Str::limit($task->content, 260) !!}
                    <a href="{{ route('tasks.show', $task->id) }}" class="btn-link">{{ __('tasks.read_more') }}</a>
                @else
                    {!! $task->content !!}
                @endif
            </p>
        </div>
    </div>
@empty
    <div style="text-align: center">
        <p>{!! __('tasks.no_tasks', ['link' => '<a href="' . route('addtask') . '">' . __('tasks.create_task') . '</a>']) !!}</p>
    </div>
@endforelse

    <div class="pagination">
        {{ $tasks->links() }}
    </div>
</div>

@endsection
