@extends('layouts.app')

@section('content')

@vite(['resources/css/admin_releases.css'])

<div class="organization-container release-form-container">

    {{-- HEADER --}}
    <div class="release-form-header">

        <div>
            <h1 class="organization-title">
                Создание релиза
            </h1>

            <p class="release-form-subtitle">
                Добавление новой версии приложения «Ёлки Иголки»
            </p>
        </div>

    </div>


    {{-- ERRORS --}}
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


    {{-- FORM --}}
    <form method="POST"
          action="{{ route('admin.releases.store') }}"
          class="release-form"
          enctype="multipart/form-data">

        @csrf


        {{-- CATEGORY --}}
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

                    <option value="">
                        Выберите категорию
                    </option>

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            @selected(old('category_id') == $category->id)>

                            {{ $category->name }}
                            ({{ $category->slug }})

                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        {{-- VERSION --}}
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
                           value="{{ old('version') }}"
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
                           value="{{ old('version_code') }}"
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
                       value="{{ old('title') }}"
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
                          placeholder="Описание изменений">{{ old('description') }}</textarea>

            </div>

        </div>


        {{-- APK --}}
        <div class="release-form-section">

            <div class="release-form-section-title">
                <i class="fas fa-mobile-alt"></i>
                APK
            </div>

            <div class="release-form-group">

                <label class="release-form-label">
                    APK файл
                </label>

                <input type="file"
                       name="apk_file"
                       class="release-form-control release-file-input"
                       accept=".apk,application/vnd.android.package-archive"
                       required>

                <div class="release-form-hint">
                    Выберите APK-файл с устройства.
                    SHA-256 и размер будут определены автоматически.
                </div>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="release-form-section">

            <div class="release-form-section-title">
                <i class="fas fa-toggle-on"></i>
                Настройки релиза
            </div>


            <label class="release-switch-row">

                <input type="checkbox"
                       name="is_required"
                       value="1"
                       class="release-switch-input"
                       @checked(old('is_required'))>

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


            <label class="release-switch-row">

                <input type="checkbox"
                       name="is_active"
                       value="1"
                       class="release-switch-input"
                       @checked(old('is_active', true))>

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


        {{-- ACTIONS --}}
        <div class="release-form-actions">

            <button type="submit"
                    class="organization-button">

                <i class="fas fa-plus"></i>

                Создать релиз

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