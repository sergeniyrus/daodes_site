@extends('template')
@section('title_page', __('tasks.title_page'))
@section('main')

    <style>
        .card {
            border-radius: 10px;
            border: 1px solid #f8f9fa;
            background-color: #2b2c2e;
            margin: 20px 0px;
            padding: 15px;
        }

        .card-title {
            font-size: 1.75rem;
            color: #d7fc09;
            margin-bottom: 15px;
            text-align: center;
        }

        .card-text {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        a {
            color: aqua;
            text-decoration: none;
        }

        a:hover {
            color: rgb(0, 174, 255);
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination .page-item .page-link {
            color: #d7fc09;
        }

        .pagination .page-item.active .page-link {
            background-color: #373af0;
            border-color: #373af0;
        }

        .task-budget,
        .task-deadline,
        .task-category {
            font-size: 0.875rem;
            margin: 0 10px;
        }

        .task-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #d7fc09;
            margin-top: 10px;
        }

        .task-title {
            flex: 1;
            text-align: center;
        }

        .task-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters2 {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filters3 {
            margin-top: 10px;
        }

        .filter-item {
            margin-bottom: 10px;
        }


        /* Основной стиль фильтров */
        .filters {
            margin-bottom: 20px;
            background-color: #2b2c2e;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid gold;

        }

        .filters2 {
            display: flex;
            flex-direction: row;
            text-align: center;
            justify-content: center;
            align-items: center;
            color: gold;
            column-gap: 10px;
            font-size: 0.5rem;
        }

        .filters3 {
            display: flex;
            flex-direction: row;
            text-align: center;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
        }

        /* Элементы фильтров */
        .filter-item {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            /* Убираем отступы снизу */
        }


        /* Стили для выпадающих списков */
        .filters .form-select {
            color: #fff;
            background-color: #333;
            border: 1px solid gold;
            border-radius: 5px;
            padding: 5px;
            width: 100%;
            /* Уменьшаем ширину для компактности */
            font-size: 0.7rem;
            /* Уменьшаем шрифт для выпадающего списка */
            height: 24px;
            /* Уменьшаем высоту */
            line-height: 14px;
            /* Центрируем текст по вертикали */
            appearance: none;
            /* Отключает стрелку в большинстве браузеров */
            -moz-appearance: none;
            /* Для Firefox */
            -webkit-appearance: none;
            /* Для Safari и Chrome */
            background-image: none;
            /* Убирает стрелку в некоторых старых браузерах */
        }



        /* Адаптивность для маленьких экранов */
        @media (max-width: 768px) {
            .filters {
                padding: 10px;
                flex-direction: column;
                /* Размещаем элементы вертикально на маленьких экранах */
                align-items: flex-start;
                /* Выравниваем по левому краю */
            }

            .filter2 {
                flex-wrap: wrap;
                /* Разрешаем обтекание элементов */
                justify-content: flex-start;
                /* Элементы прижимаются к левому краю */
                flex-direction: column;
            }

            .filters2 .form-select {
                width: auto;
                /* Делает select более гибким */
                font-size: 0.75rem;
                /* Еще больше уменьшаем шрифт на маленьких экранах */
                height: 22px;
                /* Уменьшаем высоту */
                line-height: 10px;
            }


        }

        @media (max-width: 768px) {
            .task-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .task-info {
                justify-content: space-between;
                width: 100%;
            }

            .task-info p {
                margin: 5px 0;
            }

            .filters2 {
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (max-width: 380px) {
            .task-info {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>

    <div class="container my-5">
        <div class="text-center mb-4">
            <h1 class="display-4">{{ __('tasks.title_page') }}</h1>
        </div>

        <div class="filters">
            <form action="{{ route('tasks.list') }}" method="GET" class="">
                <div class="filters2">
                    <!-- Sorting -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>{{ __('tasks.sort_new') }}
                            </option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>{{ __('tasks.sort_old') }}
                            </option>
                        </select>
                    </div>

                    <!-- Filter by Category -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">{{ __('tasks.all_categories') }}</option>
                            @foreach ($categories as $categoryItem)
                                <option value="{{ $categoryItem->id }}"
                                    {{ $category == $categoryItem->id ? 'selected' : '' }}>
                                    {{ $categoryItem->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if ($tasks->count() > 0)
                        <!-- Filter by Status -->
                        <div class="filter-item">
                            <label for="status" class="m-0"></label>
                            <select name="status" id="status" class="form-select form-select-sm">
                                <option value="" {{ $status === null ? 'selected' : '' }}>
                                    {{ __('tasks.all_statuses') }}</option>
                                @foreach (__('tasks.statuses') as $key => $label)
                                    @if (isset($statusesWithTasks[$key]))
                                        <option value="{{ $key }}"
                                            {{ (string) $status === (string) $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Number of Records per Page -->
                    <div class="filter-item">
                        <label for="perPage" class="m-0"></label>
                        <select name="perPage" id="perPage" class="form-select form-select-sm">
                            @foreach (__('tasks.per_page') as $value => $label)
                                <option value="{{ $value }}" {{ $perPage == $value ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filters3">
                    <div class="filter-item">
                        <button type="submit" class="des-btn">{{ __('tasks.apply_filters') }}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="pagination">
            {{ $tasks->appends(['sort' => $sort, 'category' => $category, 'status' => $status, 'perPage' => $perPage])->links() }}
        </div>

        @forelse($tasks as $task)
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="task-wrapper">
                        <div class="task-title">
                            <a href="{{ route('tasks.show', $task) }}" class="des-btn">{{ $task->title }}</a>
                        </div>
                        <div class="task-info">
                            <p class="task-category"><i class="fas fa-folder-open"></i>
                                {{ $task->category ? $task->category->name : __('tasks.no_category') }}</p>
                            <p class="task-budget"><i class="fas fa-dollar-sign"></i> {{ $task->budget }} USD</p>
                            <p class="task-deadline"><i class="fas fa-clock"></i> {{ $task->deadline->format('Y-m-d') }}
                            </p>
                            <p class="task-status"><i class="fas fa-info-circle"></i>
                                {{ __('tasks.statuses.' . $task->status) }}</p>

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
                            <a href="{{ route('tasks.show', $task->id) }}"
                                class="btn-link">{{ __('tasks.read_more') }}</a>
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
            {{ $tasks->appends(['sort' => $sort, 'category' => $category, 'status' => $status, 'perPage' => $perPage])->links() }}
        </div>
    </div>

@endsection
