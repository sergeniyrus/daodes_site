@extends('layouts.app')

@section('content')

<div class="organization-container release-container">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="release-page-header">

        <div class="release-page-header-content">

            <h1 class="organization-title">
                Управление версиями приложения
            </h1>

            <p class="release-page-subtitle">
                Выберите организацию для управления её релизами
            </p>

        </div>

    </div>


    {{-- =========================================================
         ALERTS
         ========================================================= --}}

    @if(session('success'))

        <div class="organization-alert success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="organization-alert error">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
         ORGANIZATIONS
         ========================================================= --}}

    @if($organizations->isEmpty())

        <div class="organization-empty">

            <i class="fas fa-building"></i>

            <div class="organization-empty-title">
                Организации отсутствуют
            </div>

            <div class="organization-empty-text">
                У вас нет организаций, доступных для управления релизами.
            </div>

        </div>

    @else

        <div class="release-organizations">

            @foreach($organizations as $organization)

                @php
                    $categories = $organization->releaseCategories;

                    $releaseCount = $categories->sum(
                        fn ($category) => $category->releases->count()
                    );
                @endphp


                {{-- =================================================
                     ORGANIZATION CARD
                     ================================================= --}}

                <details class="release-organization">

                    <summary class="release-organization-card">

                        <div class="release-organization-icon">
                            <i class="fas fa-building"></i>
                        </div>


                        <div class="release-organization-main">

                            <div class="release-organization-name">
                                {{ $organization->name }}
                            </div>

                            <div class="release-organization-info">

                                <span>
                                    {{ $categories->count() }}
                                    {{ $categories->count() === 1 ? 'категория' : 'категорий' }}
                                </span>

                                <span class="release-organization-separator">
                                    •
                                </span>

                                <span>
                                    {{ $releaseCount }}
                                    {{ $releaseCount === 1 ? 'релиз' : 'релизов' }}
                                </span>

                            </div>

                        </div>


                        {{-- STATUS --}}

                        @if($organization->isActive())

                            <span class="organization-status active">
                                <span class="organization-status-dot"></span>
                                Активна
                            </span>

                        @else

                            <span class="organization-status inactive">
                                <span class="organization-status-dot"></span>
                                Отключена
                            </span>

                        @endif


                        {{-- ARROW --}}

                        <span class="release-organization-arrow">
                            <i class="fas fa-chevron-down"></i>
                        </span>

                    </summary>


                    {{-- =================================================
                         ORGANIZATION CONTENT
                         ================================================= --}}

                    <div class="release-organization-content">

                        <div class="release-organization-content-header">

                            <div>

                                <div class="release-content-title">
                                    Релизы организации
                                </div>

                                <div class="release-content-subtitle">
                                    {{ $organization->name }}
                                </div>

                            </div>

                            <a href="{{ route('admin.releases.create') }}"
                               class="organization-button">

                                <i class="fas fa-plus"></i>

                                Новый релиз

                            </a>

                        </div>


                        {{-- =================================================
                             NO CATEGORIES
                             ================================================= --}}

                        @if($categories->isEmpty())

                            <div class="release-empty">

                                <i class="fas fa-box-open"></i>

                                <span>
                                    Категорий релизов пока нет.
                                </span>

                            </div>

                        @else


                            {{-- =================================================
                                 CATEGORIES
                                 ================================================= --}}

                            <div class="release-categories">

                                @foreach($categories as $category)

                                    <div class="release-category">

                                        {{-- CATEGORY HEADER --}}

                                        <div class="release-category-header">

                                            <div class="release-category-title-block">

                                                <div class="release-category-name">
                                                    {{ $category->name }}
                                                </div>

                                                <div class="release-category-slug">
                                                    {{ $category->slug }}
                                                </div>

                                            </div>


                                            @if($category->is_active)

                                                <span class="organization-status active">
                                                    <span class="organization-status-dot"></span>
                                                    Активна
                                                </span>

                                            @else

                                                <span class="organization-status inactive">
                                                    <span class="organization-status-dot"></span>
                                                    Отключена
                                                </span>

                                            @endif

                                        </div>


                                        {{-- CATEGORY DESCRIPTION --}}

                                        @if($category->description)

                                            <div class="release-category-description">
                                                {{ $category->description }}
                                            </div>

                                        @endif


                                        {{-- =================================================
                                             RELEASES
                                             ================================================= --}}

                                        @if($category->releases->isEmpty())

                                            <div class="release-category-empty">
                                                Релизов пока нет.
                                            </div>

                                        @else

                                            <div class="release-list">

                                                @foreach($category->releases as $release)

                                                    <div class="release-card">

                                                        {{-- RELEASE MAIN --}}

                                                        <div class="release-main">

                                                            <div class="release-version">
                                                                {{ $release->version }}
                                                            </div>

                                                            <div class="release-title">
                                                                {{ $release->title }}
                                                            </div>

                                                            <div class="release-meta">

                                                                <span class="release-meta-item">
                                                                    <span class="release-meta-label">
                                                                        ID
                                                                    </span>

                                                                    <span class="release-meta-value">
                                                                        {{ $release->id }}
                                                                    </span>
                                                                </span>


                                                                <span class="release-meta-item">
                                                                    <span class="release-meta-label">
                                                                        Код
                                                                    </span>

                                                                    <span class="release-meta-value">
                                                                        {{ $release->version_code }}
                                                                    </span>
                                                                </span>


                                                                @if($release->released_at)

                                                                    <span class="release-meta-item">
                                                                        <span class="release-meta-label">
                                                                            Дата
                                                                        </span>

                                                                        <span class="release-meta-value">
                                                                            {{ $release->released_at?->format('d.m.Y H:i') }}
                                                                        </span>
                                                                    </span>

                                                                @endif

                                                            </div>

                                                        </div>


                                                        {{-- RELEASE STATUS --}}

                                                        <div class="release-status-block">

                                                            @if($release->is_active)

                                                                <span class="organization-status active">
                                                                    <span class="organization-status-dot"></span>
                                                                    Активен
                                                                </span>

                                                            @else

                                                                <span class="organization-status inactive">
                                                                    <span class="organization-status-dot"></span>
                                                                    Отключён
                                                                </span>

                                                            @endif


                                                            @if($release->is_required)

                                                                <span class="release-required">
                                                                    Обязательный
                                                                </span>

                                                            @else

                                                                <span class="release-optional">
                                                                    Необязательный
                                                                </span>

                                                            @endif

                                                        </div>


                                                        {{-- ACTION --}}

                                                        <div class="release-action">

                                                            <a href="{{ route('admin.releases.edit', $release) }}"
                                                               class="organization-open"
                                                               title="Изменить">

                                                                <i class="fas fa-pen"></i>

                                                            </a>

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                </details>

            @endforeach

        </div>

    @endif

</div>

@endsection