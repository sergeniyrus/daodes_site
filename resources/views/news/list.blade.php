{{-- @extends('template')

@section('title_page', 'Новости')

@section('main')
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">

    <div class="container my-5">
        <div class="header-title">
            <h1>Новости</h1>
        </div>

        <div class="filters">
            <form action="{{ route('news.index') }}" method="GET" class="">
                <div class="filters2">
                    <!-- Сортировка -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>Новые &nbsp;&nbsp;☰</option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>Старые ☰</option>
                        </select>
                    </div>

                    <!-- Фильтр по категориям -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">Все категории &nbsp;&nbsp;&nbsp;☰
                            </option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" {{ $category == $id ? 'selected' : '' }}>
                                    {{ $name }} ☰</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Количество записей на странице -->
                    <div class="filter-item">
                        <label for="perPage" class="m-0"></label>
                        <select name="perPage" id="perPage" class="form-select form-select-sm">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>по 5 &nbsp;&nbsp;☰</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>по 10 ☰</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>по 20 ☰</option>
                        </select>
                    </div>
                    </div>
                    <div class="filters3">
                      <div class="filter-item">
                        <button type="submit" class="blue_btn">Применить сортировку</button>
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
                            <a href="{{ route('news.show', $newsItem->id) }}" class="btn blue_btn">
                                <h5>{{ $newsItem->title }}</h5>
                            </a>
                        </div>
                        <div class="news-header">
                            <div class="img_post">
                                <img src="{{ $newsItem->img }}" alt="Image for {{ $newsItem->title }}" />
                            </div>
                            <div class="rows-title">
                                
                                <div class="task-info">
                                    <p class="task-category"><i class="fas fa-folder-open"></i>
                                        @php
                                            $categoryName = DB::table('category_news')
                                                ->where('id', $newsItem->category_id)
                                                ->value('name');
                                        @endphp
                                        {{ $categoryName ?? 'Без категории' }}
                                    </p>
                                </div>
                                <div class="task-info2">
                                    <p class="task-data"><i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($newsItem->created_at)->format('d/m/y') }}</p>
                                    <p class="task-views"><i class="fas fa-eye"></i> {{ $newsItem->views }}</p>
                                    <p class="task-comment"><i class="fa fa-comments" aria-hidden="true"></i>
                                        {{ $commentCount[$newsItem->id] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            {!! Str::limit($newsItem->content, 260) !!}
                            <a href="{{ route('news.show', $newsItem->id) }}" class="btn btn-link">Читать дальше <i
                                    class="fa fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </p>
                    </div>
                </div>
            @empty
                <p style="text-align: center">Нет новостей для отображения.</p>
            @endforelse
        </div>



        <div class="pagination">
          {{ $news->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
      </div>



    </div>
@endsection --}}
@extends('template')

@section('title_page', 'News')

@section('main')
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">

    <div class="container my-5">
        <div class="header-title">
            <h1>News</h1>
        </div>

        <div class="filters">
            <form action="{{ route('news.index') }}" method="GET" class="">
                <div class="filters2">
                    <!-- Sorting -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>New &nbsp;&nbsp;☰</option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>Old ☰</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">All Categories &nbsp;&nbsp;&nbsp;☰</option>
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
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 per page &nbsp;&nbsp;☰</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page ☰</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 per page ☰</option>
                        </select>
                    </div>
                </div>
                <div class="filters3">
                    <div class="filter-item">
                        <button type="submit" class="blue_btn">Apply Filters</button>
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
                            <a href="{{ route('news.show', $newsItem->id) }}" class="btn blue_btn">
                                <h5>{{ $newsItem->title }}</h5>
                            </a>
                        </div>
                        <div class="news-header">
                            <div class="img_post">
                                <img src="{{ $newsItem->img }}" alt="Image for {{ $newsItem->title }}" />
                            </div>
                            <div class="rows-title">
                                <div class="task-info">
                                    <p class="task-category"><i class="fas fa-folder-open"></i>
                                        @php
                                            $categoryName = DB::table('category_news')
                                                ->where('id', $newsItem->category_id)
                                                ->value('name');
                                        @endphp
                                        {{ $categoryName ?? 'Uncategorized' }}
                                    </p>
                                </div>
                                <div class="task-info2">
                                    <p class="task-data"><i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($newsItem->created_at)->format('d/m/y') }}</p>
                                    <p class="task-views"><i class="fas fa-eye"></i> {{ $newsItem->views }}</p>
                                    <p class="task-comment"><i class="fa fa-comments" aria-hidden="true"></i>
                                        {{ $commentCount[$newsItem->id] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            {!! Str::limit($newsItem->content, 260) !!}
                            <a href="{{ route('news.show', $newsItem->id) }}" class="btn btn-link">Read more <i
                                    class="fa fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </p>
                    </div>
                </div>
            @empty
                <p style="text-align: center">No news to display.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $news->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
@endsection