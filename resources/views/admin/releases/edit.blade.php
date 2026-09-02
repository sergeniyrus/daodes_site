@extends('layouts.app')

@section('content')

@vite(['resources/css/admin_releases.css'])


<div class="organization-container release-form-container">

    {{-- =========================================================
         HEADER
         ========================================================= --}}

    <div class="release-form-header">

        <div>

            <h1 class="organization-title">
                Редактирование релиза
            </h1>

            <p class="release-form-subtitle">

                {{ $release->version }}

                <span class="release-header-separator">
                    —
                </span>

                {{ $release->title }}

            </p>

        </div>

    </div>


    {{-- =========================================================
         ERRORS
         ========================================================= --}}

    @if($errors->any())

        <div class="organization-alert error">

            <ul class="release-errors-list">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         FORM
         ========================================================= --}}

    <form method="POST"
          action="{{ route('admin.releases.update', $release) }}"
          class="release-form"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')


        {{-- =====================================================
             CATEGORY
             ===================================================== --}}

        <div class="release-form-section">

            <div class="release-form-section-title">

                <i class="fas fa-layer-group"></i>

                Категория релиза

            </div>


            <div class="release-form-group">

                <label class="release-form-label">
                    Категория
                </label>


                <select name="category_id"
                        class="release-form-control"
                        required>

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            @selected(
                                old(
                                    'category_id',
                                    $release->category_id
                                ) == $category->id
                            )>

                            {{ $category->name }}
                            ({{ $category->slug }})

                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        {{-- =====================================================
             VERSION
             ===================================================== --}}

        <div class="release-form-section">

            <div class="release-form-section-title">

                <i class="fas fa-code-branch"></i>

                Версия

            </div>


            <div class="release-form-grid">

                <div class="release-form-group">

                    <label class="release-form-label">
                        Версия
                    </label>


                    <input type="text"
                           name="version"
                           class="release-form-control"
                           value="{{ old('version', $release->version) }}"
                           placeholder="root-v2"
                           required>

                </div>


                <div class="release-form-group">

                    <label class="release-form-label">
                        Version Code
                    </label>


                    <input type="number"
                           name="version_code"
                           class="release-form-control"
                           value="{{ old('version_code', $release->version_code) }}"
                           min="1"
                           required>

                </div>

            </div>


            <div class="release-form-group">

                <label class="release-form-label">
                    Название
                </label>


                <input type="text"
                       name="title"
                       class="release-form-control"
                       value="{{ old('title', $release->title) }}"
                       placeholder="Ёлки Иголки root-v2"
                       required>

            </div>


            <div class="release-form-group">

                <label class="release-form-label">
                    Описание
                </label>


                <textarea name="description"
                          class="release-form-control release-form-textarea"
                          rows="4"
                          placeholder="Описание изменений">{{ old('description', $release->description) }}</textarea>

            </div>

        </div>


        {{-- =====================================================
             APK
             ===================================================== --}}

        <div class="release-form-section">

            <div class="release-form-section-title">

                <i class="fas fa-mobile-alt"></i>

                APK

            </div>


            {{-- =================================================
                 CURRENT APK
                 ================================================= --}}

            @if($release->apk_url)

                <div class="release-form-group">

                    <label class="release-form-label">
                        Текущий APK
                    </label>


                    <div class="release-current-file">

                        <div class="release-current-file-icon">

                            <i class="fas fa-file-alt"></i>

                        </div>


                        <div class="release-current-file-info">

                            <div class="release-current-file-title">
                                APK уже загружен
                            </div>


                            @if($release->apk_size)

                                <div class="release-current-file-meta">

                                    Размер:

                                    {{ number_format(
                                        $release->apk_size / 1024 / 1024,
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                    MB

                                </div>

                            @endif


                            @if($release->apk_sha256)

                                <div class="release-current-file-meta release-current-file-sha">

                                    SHA-256:

                                    {{ $release->apk_sha256 }}

                                </div>

                            @endif


                            <a href="{{ $release->apk_url }}"
                               target="_blank"
                               rel="noopener"
                               class="release-apk-link">

                                <i class="fas fa-external-link-alt"></i>

                                Открыть текущий APK

                            </a>

                        </div>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 NEW APK
                 ================================================= --}}

            <div class="release-form-group">

                <label class="release-form-label">
                    Загрузить новый APK
                </label>


                <input type="file"
                       name="apk_file"
                       class="release-form-control release-file-input"
                       accept=".apk,application/vnd.android.package-archive">


                <div class="release-form-hint">

                    Выберите новый APK-файл с устройства.

                    Если файл не выбран, текущий APK останется
                    без изменений.

                    SHA-256 и размер нового файла будут определены
                    автоматически.

                </div>

            </div>

        </div>


        {{-- =====================================================
             STATUS
             ===================================================== --}}

        <div class="release-form-section">

            <div class="release-form-section-title">

                <i class="fas fa-toggle-on"></i>

                Настройки релиза

            </div>


            {{-- REQUIRED --}}

            <label class="release-switch-row">

                <input type="checkbox"
                       name="is_required"
                       value="1"
                       class="release-switch-input"
                       @checked(
                           old(
                               'is_required',
                               $release->is_required
                           )
                       )>


                <span class="release-switch"></span>


                <span class="release-switch-content">

                    <span class="release-switch-title">
                        Обновление обязательно
                    </span>


                    <span class="release-switch-description">
                        Пользователь должен установить эту версию.
                    </span>

                </span>

            </label>


            {{-- ACTIVE --}}

            <label class="release-switch-row">

                <input type="checkbox"
                       name="is_active"
                       value="1"
                       class="release-switch-input"
                       @checked(
                           old(
                               'is_active',
                               $release->is_active
                           )
                       )>


                <span class="release-switch"></span>


                <span class="release-switch-content">

                    <span class="release-switch-title">
                        Релиз активен
                    </span>


                    <span class="release-switch-description">
                        Активный релиз доступен приложению для обновления.
                    </span>

                </span>

            </label>

        </div>


        {{-- =====================================================
             ACTIONS
             ===================================================== --}}

        <div class="release-form-actions">

            <button type="submit"
                    class="organization-button">

                <i class="fas fa-save"></i>

                Сохранить изменения

            </button>


            <a href="{{ route('admin.releases.index') }}"
               class="organization-button release-button-secondary">

                <i class="fas fa-arrow-left"></i>

                Отмена

            </a>

        </div>

    </form>

</div>

@endsection