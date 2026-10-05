@extends('layouts.app')

@section('content')

    @vite(['resources/css/admin_releases.css'])


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

        @if (session('success'))
            <div class="organization-alert success">
                {{ session('success') }}
            </div>
        @endif


        @if (session('error'))
            <div class="organization-alert error">
                {{ session('error') }}
            </div>
        @endif


        {{-- =========================================================
         ORGANIZATIONS
         ========================================================= --}}

        @if ($organizations->isEmpty())
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

                @foreach ($organizations as $organization)
                    @php

                        $categories = $organization->releaseCategories;

                        $releaseCount = $categories->sum(fn($category) => $category->releases->count());

                    @endphp


                    {{-- =================================================
                     ORGANIZATION
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

                            @if ($organization->isActive())
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


                                <a href="{{ route('admin.releases.create', $organization->id) }}"
                                    class="organization-button">

                                    <i class="fas fa-plus"></i>

                                    Новый релиз

                                </a>

                            </div>


                            {{-- =================================================
                             NO CATEGORIES
                             ================================================= --}}

                            @if ($categories->isEmpty())
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

                                    @foreach ($categories as $category)
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


                                                @if ($category->is_active)
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


                                            {{-- DESCRIPTION --}}

                                            @if ($category->description)
                                                <div class="release-category-description">

                                                    {{ $category->description }}

                                                </div>
                                            @endif


                                            {{-- =================================================
                                             RELEASES
                                             ================================================= --}}

                                            @if ($category->releases->isEmpty())
                                                <div class="release-category-empty">

                                                    Релизов пока нет.

                                                </div>
                                            @else
                                                <div class="release-list">

                                                    @foreach ($category->releases as $release)
                                                        @php

                                                            /*
                                                             * Публичная ссылка для скачивания.
                                                             *
                                                             * ВАЖНО:
                                                             * IPFS URL пользователю больше не показываем.
                                                             */

                                                            $downloadUrl = route('releases.download', $release);

                                                        @endphp


                                                        <div class="release-card">

                                                            <div class="release-main">


                                                                {{-- =================================================
                                                                 TITLE
                                                                 ================================================= --}}

                                                                <div class="release-title">

                                                                    {{ $release->title }}

                                                                </div>


                                                                {{-- VERSION --}}

                                                                <div class="release-version">

                                                                    {{ $release->version }}

                                                                </div>


                                                                {{-- =================================================
                                                                 META
                                                                 ================================================= --}}

                                                                <div class="release-meta">


                                                                    {{-- ID --}}

                                                                    <span class="release-meta-item">

                                                                        <span class="release-meta-label">
                                                                            ID
                                                                        </span>

                                                                        <span class="release-meta-value">
                                                                            {{ $release->id }}
                                                                        </span>

                                                                    </span>


                                                                    {{-- VERSION CODE --}}

                                                                    <span class="release-meta-item">

                                                                        <span class="release-meta-label">
                                                                            Код
                                                                        </span>

                                                                        <span class="release-meta-value">
                                                                            {{ $release->version_code }}
                                                                        </span>

                                                                    </span>


                                                                    {{-- DATE --}}

                                                                    @if ($release->released_at)
                                                                        <span class="release-meta-item">

                                                                            <span class="release-meta-label">
                                                                                Дата
                                                                            </span>

                                                                            <span class="release-meta-value">

                                                                                {{ $release->released_at->format('d.m.Y H:i') }}

                                                                            </span>

                                                                        </span>
                                                                    @endif


                                                                    {{-- APK SIZE --}}

                                                                    @if ($release->apk_size)
                                                                        <span class="release-meta-item">

                                                                            <span class="release-meta-label">
                                                                                APK
                                                                            </span>

                                                                            <span class="release-meta-value">

                                                                                {{ number_format($release->apk_size / 1024 / 1024, 2, ',', ' ') }}

                                                                                MB

                                                                            </span>

                                                                        </span>
                                                                    @endif

                                                                </div>


                                                                {{-- =================================================
     SHA-256 (РАСКРЫВАЮЩИЙСЯ, В ОДНУ СТРОКУ)
     ================================================= --}}
@if($release->apk_sha256)
    <div class="release-sha256-wrapper">
        
        {{-- Заголовок-переключатель --}}
        <div class="release-sha256-toggle" onclick="toggleSha256({{ $release->id }})">
            <div class="release-sha256-title">
                <i class="fas fa-fingerprint"></i>
                <span>SHA-256 хеш файла</span>
            </div>
            <i class="fas fa-chevron-down release-sha256-arrow" id="sha256-arrow-{{ $release->id }}"></i>
        </div>

        {{-- Скрываемый контент (в одну строку) --}}
        <div class="release-sha256-content" id="sha256-content-{{ $release->id }}">
            <code class="release-sha256-text">
                {{ $release->apk_sha256 }}
            </code>
            
            <button 
                type="button" 
                class="release-sha256-btn" 
                id="sha256-btn-{{ $release->id }}"
                onclick="copySha256({{ $release->id }}, @js($release->apk_sha256))" 
                title="Скопировать SHA-256"
            >
                <i class="fas fa-copy btn-icon-copy"></i>
                <i class="fas fa-check btn-icon-check"></i>
                <span class="btn-text">Копировать</span>
            </button>
        </div>
        
    </div>
@endif


                                                                {{-- =================================================
                                                                 STATUS + ACTIONS
                                                                 ================================================= --}}

                                                                <div class="release-bottom-row">


                                                                    {{-- STATUS --}}

                                                                    <div class="release-status-block">

                                                                        @if ($release->is_active)
                                                                            <span class="organization-status active">

                                                                                <span
                                                                                    class="organization-status-dot"></span>

                                                                                Активен

                                                                            </span>
                                                                        @else
                                                                            <span class="organization-status inactive">

                                                                                <span
                                                                                    class="organization-status-dot"></span>

                                                                                Отключён

                                                                            </span>
                                                                        @endif


                                                                        @if ($release->is_required)
                                                                            <span class="release-required">
                                                                                Обязательный
                                                                            </span>
                                                                        @else
                                                                            <span class="release-optional">
                                                                                Необязательный
                                                                            </span>
                                                                        @endif

                                                                    </div>


                                                                    {{-- ACTIONS --}}

                                                                    <div class="release-actions">


                                                                        {{-- =================================================
                                                                         DOWNLOAD
                                                                         ================================================= --}}

                                                                        @if ($release->apk_url)
                                                                            <a href="{{ $downloadUrl }}"
                                                                                class="release-action-button view"
                                                                                title="Скачать APK">

                                                                                <i class="fas fa-download"></i>

                                                                                <span>
                                                                                    Скачать APK
                                                                                </span>

                                                                            </a>


                                                                            {{-- =================================================
                                                                             COPY DOWNLOAD URL
                                                                             ================================================= --}}

                                                                            <a href="#"
                                                                                class="release-action-button copy"
                                                                                title="Копировать ссылку на скачивание"
                                                                                onclick="copyReleaseUrl(
                                                                                {{ $release->id }},
                                                                                @js($downloadUrl)
                                                                            ); return false;">

                                                                                <i class="fas fa-copy"></i>

                                                                                <span>
                                                                                    Ссылка
                                                                                </span>

                                                                            </a>
                                                                        @endif


                                                                        {{-- EDIT --}}

                                                                        <a href="{{ route('admin.releases.edit', $release) }}"
                                                                            class="release-action-button edit"
                                                                            title="Изменить">

                                                                            <i class="fas fa-pen"></i>

                                                                            <span>
                                                                                Изменить
                                                                            </span>

                                                                        </a>

                                                                    </div>

                                                                </div>

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


    {{-- =========================================================
     COPY SCRIPTS
     ========================================================= --}}

    <script>
        /*
    |--------------------------------------------------------------------------
    | COPY DOWNLOAD URL
    |--------------------------------------------------------------------------
    */

        function copyReleaseUrl(releaseId, url) {
            if (!url) {
                return;
            }


            if (
                navigator.clipboard &&
                window.isSecureContext
            ) {

                navigator.clipboard
                    .writeText(url)
                    .then(function() {

                        showCopyStatus(
                            releaseId
                        );

                    })
                    .catch(function(error) {

                        console.error(
                            'Ошибка копирования:',
                            error
                        );

                        copyReleaseUrlFallback(
                            releaseId,
                            url
                        );

                    });

                return;
            }


            copyReleaseUrlFallback(
                releaseId,
                url
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK URL
        |--------------------------------------------------------------------------
        */

        function copyReleaseUrlFallback(
            releaseId,
            url
        ) {

            const textarea =
                document.createElement(
                    'textarea'
                );


            textarea.value =
                url;


            textarea.style.position =
                'fixed';

            textarea.style.left =
                '-9999px';

            textarea.style.top =
                '0';

            textarea.style.opacity =
                '0';


            document.body.appendChild(
                textarea
            );


            textarea.focus();
            textarea.select();


            try {

                document.execCommand(
                    'copy'
                );

                showCopyStatus(
                    releaseId
                );

            } catch (error) {

                console.error(
                    'Ошибка копирования:',
                    error
                );

            }


            document.body.removeChild(
                textarea
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SHOW URL COPY STATUS
        |--------------------------------------------------------------------------
        */

        function showCopyStatus(
            releaseId
        ) {

            const status =
                document.getElementById(
                    'copy-status-' + releaseId
                );


            if (!status) {
                return;
            }


            status.style.display =
                'inline';


            setTimeout(function() {

                status.style.display =
                    'none';

            }, 2000);
        }


       /*
|--------------------------------------------------------------------------
| TOGGLE SHA256 VISIBILITY
|--------------------------------------------------------------------------
*/
function toggleSha256(releaseId) {
    const content = document.getElementById('sha256-content-' + releaseId);
    const arrow = document.getElementById('sha256-arrow-' + releaseId);

    if (content.classList.contains('show')) {
        content.classList.remove('show');
        arrow.classList.remove('rotated');
    } else {
        content.classList.add('show');
        arrow.classList.add('rotated');
    }
}

/*
|--------------------------------------------------------------------------
| COPY SHA256 (Оставляем вашу рабочую версию)
|--------------------------------------------------------------------------
*/
function copySha256(releaseId, sha256) {
    if (!sha256) return;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(sha256).then(function () {
            showSha256Success(releaseId);
        }).catch(function () {
            copySha256Fallback(releaseId, sha256);
        });
        return;
    }
    copySha256Fallback(releaseId, sha256);
}

function copySha256Fallback(releaseId, sha256) {
    const textarea = document.createElement('textarea');
    textarea.value = sha256;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.focus();
    textarea.select();
    
    try {
        document.execCommand('copy');
        showSha256Success(releaseId);
    } catch (error) {
        console.error('Ошибка копирования SHA-256:', error);
    }
    
    document.body.removeChild(textarea);
}

function showSha256Success(releaseId) {
    const btn = document.getElementById('sha256-btn-' + releaseId);
    const textSpan = btn.querySelector('.btn-text');
    
    if (!btn) return;

    const originalText = textSpan.innerText;

    btn.classList.add('copied');
    textSpan.innerText = 'Скопировано';

    setTimeout(function () {
        btn.classList.remove('copied');
        textSpan.innerText = originalText;
    }, 2000);
}

/*
|--------------------------------------------------------------------------
| VISUAL FEEDBACK FOR SHA256 COPY
|--------------------------------------------------------------------------
*/
function showSha256Success(releaseId) {
    const btn = document.getElementById('sha256-btn-' + releaseId);
    const textSpan = btn.querySelector('.btn-text');
    
    if (!btn) return;

    // Сохраняем оригинальный текст
    const originalText = textSpan.innerText;

    // Применяем класс "copied", который через CSS меняет иконку и цвет
    btn.classList.add('copied');
    textSpan.innerText = 'Скопировано!';

    // Возвращаем в исходное состояние через 2 секунды
    setTimeout(function () {
        btn.classList.remove('copied');
        textSpan.innerText = originalText;
    }, 2000);
}

        /*
        |--------------------------------------------------------------------------
        | SHOW SHA256 STATUS
        |--------------------------------------------------------------------------
        */

        function showSha256Status(
            releaseId
        ) {

            const status =
                document.getElementById(
                    'sha256-status-' + releaseId
                );


            if (!status) {
                return;
            }


            status.style.display =
                'block';


            setTimeout(function() {

                status.style.display =
                    'none';

            }, 2000);
        }
    </script>

@endsection
