@extends('template')

@section('title_page', __('news.title_page'))

@section('main')
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">

    <div class="container my-5">
        <div class="header-title">
            <h1>{{ __('news.header_title') }}</h1>
        </div>

        <div class="filters">
            <form action="{{ route('news.index') }}" method="GET">
                <div class="filters2">
                    <!-- Sorting -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>{{ __('news.sort_new') }}
                            </option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>{{ __('news.sort_old') }}
                            </option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">{{ __('news.all_categories') }}</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" {{ $category == $id ? 'selected' : '' }}>
                                    {{ $name }} ☰</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Number of Records per Page -->
                    <div class="filter-item">
                        <label for="perPage" class="m-0"></label>
                        <select name="perPage" id="perPage" class="form-select form-select-sm">
                            @foreach (__('news.per_page') as $value => $label)
                                <option value="{{ $value }}" {{ $perPage == $value ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filters3">
                    <div class="filter-item">
                        <button type="submit" class="des-btn">{{ __('news.apply_filters') }}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="pagination">
            {{ $news->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>

        <div>
            @forelse($news as $newsItem)
                <div class="card shadow-sm">
                    <div class="">
                        <div class="task-title">
                            <a href="{{ route('news.show', $newsItem->id) }}" class="des-btn2">
                                <h5>{{ $newsItem->title }}</h5>
                            </a>
                        </div>
                        <div class="news-header">
                            <div class="img_post">
                                <img src="{{ $newsItem->img }}" alt="Image for {{ $newsItem->title }}" />
                            </div>
                            <div class="rows-title">
                                <div class="task-info">
                                    <p class="task-category">{!! __('news.category_label') !!}
                                        {{ $categories[$newsItem->category_id] ?? __('news.uncategorized') }}
                                    </p>
                                </div>
                                <div class="task-info2">
                                    <p class="task-data">{!! __('news.date_label') !!}
                                        {{ \Carbon\Carbon::parse($newsItem->created_at)->format('d/m/y') }}</p>
                                    <p class="task-views">{!! __('news.views_label') !!} {{ $newsItem->views }}</p>
                                    <p class="task-comment">{!! __('news.comments_label') !!}
                                        {{ $commentCount[$newsItem->id] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-text">
                            <div class="news-text">
                                {!! Str::limit($newsItem->content, 260) !!}
                            </div>
                            <div class="btn-link">
                                <a href="{{ route('news.show', $newsItem->id) }}"
                                    class="btn-link">{!! __('news.read_more') !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center">{{ __('news.no_news') }}</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $news->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
@endsection
