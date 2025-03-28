@extends('template')

@section('title_page', __('offers.title_page'))

@section('main')
    <link rel="stylesheet" href="{{ asset('css/offers.css') }}">

    <div class="container my-5">
        <div class="header-title">
            <h1>{{ __('offers.header_title') }}</h1>
        </div>

        <div class="filters">
            <form action="{{ route('offers.index') }}" method="GET" class="">
                <div class="filters2">
                    <!-- Sorting -->
                    <div class="filter-item">
                        <label for="sort" class="m-0"></label>
                        <select name="sort" id="sort" class="form-select form-select-sm">
                            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>{{ __('offers.sort_new') }}</option>
                            <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>{{ __('offers.sort_old') }}</option>
                        </select>
                    </div>
        
                    <!-- Filter by Category -->
                    <div class="filter-item">
                        <label for="category" class="m-0"></label>
                        <select name="category" id="category" class="form-select form-select-sm">
                            <option value="">{{ __('offers.all_categories') }}</option>
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
                                <option value="" {{ $state === null ? 'selected' : '' }}>{{ __('offers.all_statuses') }}</option>
                                @foreach (__('offers.statuses') as $key => $label)
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
                            @foreach (__('offers.per_page') as $value => $label)
                                <option value="{{ $value }}" {{ $perPage == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filters3">
                    <div class="filter-item">
                        <button type="submit" class="des-btn">{{ __('offers.apply_filters') }}</button>
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
                            <a href="{{ route('offers.show', $offersItem->id) }}" class="des-btn2">
                                <h5>{{ $offersItem->title }}</h5>
                            </a>
                        </div>
                        <div class="offers-header">
                            <div class="img_post">
                                <img src="{{ $offersItem->img }}" alt="Image for {{ $offersItem->title }}" />
                            </div>
                            <div class="rows-title">
                                <div class="task-info">
                                    <p class="task-category">{!! __('offers.category_label') !!}
                                        @php
                                            $categoryName = DB::table('category_offers')
                                                ->where('id', $offersItem->category_id)
                                                ->value('name');
                                        @endphp
                                        {{ $categoryName ?? __('offers.no_category') }}
                                    </p>
                                </div>
                                <div class="task-info2">
                                    <p class="task-data">{!! __('offers.date_label') !!}
                                        {{ \Carbon\Carbon::parse($offersItem->created_at)->format('d/m/y') }}</p>
                                    <p class="task-views">{!! __('offers.views_label') !!} {{ $offersItem->views }}</p>
                                    <p class="task-comment">{!! __('offers.comments_label') !!}
                                        {{ $commentCount[$offersItem->id] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            {!! Str::limit($offersItem->content, 260) !!}
                            <a href="{{ route('offers.show', $offersItem->id) }}" class="btn btn-link">{!! __('offers.read_more') !!}</a>
                        </p>
                    </div>
                </div>
            @empty
                <p style="text-align: center">{{ __('offers.no_offers') }}</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $offers->appends(['sort' => $sort, 'category' => $category, 'perPage' => $perPage])->links('vendor.pagination.simple-tailwind') }}
        </div>
    </div>
@endsection