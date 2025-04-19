@extends('template')
@section('title_page', __('tasks.title_page'))
@section('main')
    @vite(['resources/css/page.css'])

    <div class="container my-5">
        <div class="header-title">
            <h1>{{ __('tasks.title_page') }}</h1>
        </div>

        <div class="filters">
            <form action="{{ route('tasks.list') }}" method="GET">
                <div class="filters2">
                    <!-- Сортировка -->
                    <div class="filter-item">
                        <select name="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>{{ __('tasks.sort_new') }}
                            </option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>{{ __('tasks.sort_old') }}
                            </option>
                        </select>
                    </div>

                    <!-- Категория -->
                    <div class="filter-item">
                        <select name="category" class="form-select form-select-sm">
                            <option value="">{{ __('tasks.all_categories') }}</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" {{ $category == $id ? 'selected' : '' }}>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($tasks->count() > 0)
                        <!-- Статус -->
                        <div class="filter-item">
                            <select name="state" class="form-select form-select-sm">
                                <option value="">{{ __('tasks.all_statuses') }}</option>
                                @foreach (__('tasks.statuses') as $key => $label)
                                    @if (isset($statesWithTasks[$key]))
                                        <option value="{{ $key }}"
                                            {{ (string) $state === (string) $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Кол-во на страницу -->
                    <div class="filter-item">
                        <select name="perPage" class="form-select form-select-sm">
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
            {{ $tasks->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>

        <div>
            @forelse($tasks as $task)
                <div class="card shadow-sm">
                    <div class="page-header">
                        <div class="img_post">
                            <img src="{{ $task->img }}" alt="Image for {{ $task->title }}" />
                        </div>
                        <div class="rows-title">
                            <div class="page-title">
                                <a href="{{ route('tasks.show', $task->id) }}" class="des-btn2">
                                    <h5>{{ $task->title }}</h5>
                                </a>
                            </div>

                            {{-- ВСТАВКА БЛОКА --}}
                            <div class="info">
                                <p class="category"><i class="fas fa-folder-open"></i>
                                    {{ $task->category ? $task->category->name : __('tasks.no_category') }} </p>
                                <p class="task-author"><strong>&copy;</strong>
                                    <a href="{{ route('user_profile.show', ['id' => $task->user_id]) }}" title="Profile"
                                        style="color: gold; text-decoration: none;">
                                        {{ $task->user->name }}
                                    </a>
                                </p>

                            </div>
                            <div class="info2">
                                <p class="task-budget"><i class="fas fa-dollar-sign"></i> {{ $task->budget }} USD</p>
                                <p class="task-deadline"><i class="fas fa-clock"></i>
                                    {{ $task->deadline->format('Y-m-d') }}</p>
                                <p class="task-status"><i class="fas fa-info-circle"></i>
                                    {{ __('tasks.statuses.' . $task->status) }}</p>

                            </div>

                        </div>
                        <div class="card-text">
                            <div class="news-text">
                                {!! Str::limit($task->content, 260) !!}
                            </div>
                            <div class="btn-link">
                                <a href="{{ route('tasks.show', $task->id) }}"
                                    class="btn btn-link">{!! __('tasks.read_more') !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center">{{ __('tasks.no_tasks') }}</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $tasks->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
@endsection
