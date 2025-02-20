{{-- @extends('template')

@section('title_page', 'Предложения и решения')

@section('main')
    <link rel="stylesheet" href="{{ asset('css/offers.css') }}">

    <div class="container my-5">
        <div class="header-title">
            <h1>Предложения и решения</h1>
        </div>

        <div class="filters">
            <form action="{{ route('offers.index') }}" method="GET" class="">
                <div class="filters2">
                    <!-- Сортировка -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>Новые&nbsp;☰</option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>Старые ☰</option>
                        </select>
                    </div>
        
                    <!-- Фильтр по категориям -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">Все категории ☰</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" {{ $category == $id ? 'selected' : '' }}>
                                    {{ $name }} ☰</option>
                            @endforeach
                        </select>
                    </div>
        
                    @if ($offers->count() > 0)
    <!-- Фильтр по статусу -->
    <div class="filter-item">
        <label for="state" class="m-0"></label>
        <select name="state" id="state" class="form-select form-select-sm">
            <option value="" {{ $state === null ? 'selected' : '' }}>Все статусы &nbsp;&nbsp;☰</option>
            @foreach ([0 => 'На модерации', 1 => 'Обсуждении', 2 => 'Голосование', 3 => 'В работе', 4 => 'Принятые', 5 => 'Отклонённые'] as $key => $label)
                @if (isset($statesWithOffers[$key]))
                    <option value="{{ $key }}" {{ (string) $state === (string) $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
@endif


        
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
            {{ $offers->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
        
        
        <div>
            @forelse($offers as $offersItem)
                <div class="card shadow-sm">
                    <div class=""><div class="task-title">
                        <a href="{{ route('offers.show', $offersItem->id) }}" class="btn blue_btn">
                            <h5>{{ $offersItem->title }}</h5>
                        </a>
                    </div>
                        <div class="offers-header">
                            
                            <div class="img_post">
                                <img src="{{ $offersItem->img }}" alt="Image for {{ $offersItem->title }}" />
                            </div>
                            <div class="rows-title">
                                
                                <div class="task-info">
                                    <p class="task-category"><i class="fas fa-folder-open"></i>
                                        @php
                                            $categoryName = DB::table('category_offers')
                                                ->where('id', $offersItem->category_id)
                                                ->value('name');
                                        @endphp
                                        {{ $categoryName ?? 'Без категории' }}
                                    </p>
                                </div>
                                <div class="task-info2">
                                    <p class="task-data"><i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($offersItem->created_at)->format('d/m/y') }}</p>
                                    <p class="task-views"><i class="fas fa-eye"></i> {{ $offersItem->views }}</p>
                                    <p class="task-comment"><i class="fa fa-comments" aria-hidden="true"></i>
                                        {{ $commentCount[$offersItem->id] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            {!! Str::limit($offersItem->content, 260) !!}
                            <a href="{{ route('offers.show', $offersItem->id) }}" class="btn btn-link">Читать дальше <i
                                    class="fa fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </p>
                    </div>
                </div>
            @empty
                <p>Нет новостей для отображения.</p>
            @endforelse
        </div>



        <div class="pagination">
          {{ $offers->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
      </div>



    </div>
@endsection --}}

@extends('template')

@section('title_page', 'Suggestions and Solutions')

@section('main')
    <link rel="stylesheet" href="{{ asset('css/offers.css') }}">

    <div class="container my-5">
        <div class="header-title">
            <h1>Suggestions and Solutions</h1>
        </div>

        <div class="filters">
            <form action="{{ route('offers.index') }}" method="GET" class="">
                <div class="filters2">
                    <!-- Sorting -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>New&nbsp;☰</option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>Old ☰</option>
                        </select>
                    </div>
        
                    <!-- Filter by Category -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">All Categories ☰</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}" {{ $category == $id ? 'selected' : '' }}>
                                    {{ $name }} ☰</option>
                            @endforeach
                        </select>
                    </div>
        
                    @if ($offers->count() > 0)
                        <!-- Filter by Status -->
                        <div class="filter-item">
                            <label for="state" class="m-0"></label>
                            <select name="state" id="state" class="form-select form-select-sm">
                                <option value="" {{ $state === null ? 'selected' : '' }}>All Statuses &nbsp;&nbsp;☰</option>
                                @foreach ([0 => 'Under Moderation', 1 => 'In Discussion', 2 => 'Voting', 3 => 'In Progress', 4 => 'Accepted', 5 => 'Rejected'] as $key => $label)
                                    @if (isset($statesWithOffers[$key]))
                                        <option value="{{ $key }}" {{ (string) $state === (string) $key ? 'selected' : '' }}>
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
            {{ $offers->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
        
        <div>
            @forelse($offers as $offersItem)
                <div class="card shadow-sm">
                    <div class="">
                        <div class="task-title">
                            <a href="{{ route('offers.show', $offersItem->id) }}" class="btn blue_btn">
                                <h5>{{ $offersItem->title }}</h5>
                            </a>
                        </div>
                        <div class="offers-header">
                            <div class="img_post">
                                <img src="{{ $offersItem->img }}" alt="Image for {{ $offersItem->title }}" />
                            </div>
                            <div class="rows-title">
                                <div class="task-info">
                                    <p class="task-category"><i class="fas fa-folder-open"></i>
                                        @php
                                            $categoryName = DB::table('category_offers')
                                                ->where('id', $offersItem->category_id)
                                                ->value('name');
                                        @endphp
                                        {{ $categoryName ?? 'No Category' }}
                                    </p>
                                </div>
                                <div class="task-info2">
                                    <p class="task-data"><i class="fas fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($offersItem->created_at)->format('d/m/y') }}</p>
                                    <p class="task-views"><i class="fas fa-eye"></i> {{ $offersItem->views }}</p>
                                    <p class="task-comment"><i class="fa fa-comments" aria-hidden="true"></i>
                                        {{ $commentCount[$offersItem->id] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            {!! Str::limit($offersItem->content, 260) !!}
                            <a href="{{ route('offers.show', $offersItem->id) }}" class="btn btn-link">Read More <i
                                    class="fa fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </p>
                    </div>
                </div>
            @empty
                <p>No news to display.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $offers->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
@endsection