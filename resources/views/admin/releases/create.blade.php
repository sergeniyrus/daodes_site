@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
   RELEASES
   ========================================================= */

.release-container {
    max-width: 1100px;
}

.release-page-header {
    margin-bottom: 25px;
}

.release-page-header-content {
    text-align: center;
}

.release-page-subtitle,
.release-form-subtitle {
    color: #aaa;
    margin: 8px 0 0;
}

.release-organizations {
    display: flex;
    flex-direction: column;
    gap: 15px;
}


/* =========================================================
   ORGANIZATION RELEASE CARD
   ========================================================= */

.release-organization {
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 15px;
    overflow: hidden;
    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.release-organization:hover {
    border-color: gold;
    box-shadow: 0 0 14px rgba(255, 215, 0, 0.12);
}

.release-organization[open] {
    border-color: gold;
}

.release-organization-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    cursor: pointer;
    list-style: none;
}

.release-organization-card::-webkit-details-marker {
    display: none;
}

.release-organization-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: gold;
    border: 1px solid gold;
    border-radius: 50%;
    background: #0b0c18;
    font-size: 1.15rem;
}

.release-organization-main {
    flex: 1;
    min-width: 0;
}

.release-organization-name {
    color: gold;
    font-size: 1.45rem;
    font-weight: 700;
    line-height: 1.25;
    word-break: break-word;
}

.release-organization-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    color: #999;
    font-size: 0.85rem;
    margin-top: 7px;
}

.release-organization-separator {
    color: #555;
}

.release-organization-arrow {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: gold;
    border: 1px solid #555;
    border-radius: 50%;
    transition: transform 0.2s ease, background 0.2s ease;
}

.release-organization[open] .release-organization-arrow {
    transform: rotate(180deg);
    background: gold;
    color: #0b0c18;
}


/* =========================================================
   ORGANIZATION CONTENT
   ========================================================= */

.release-organization-content {
    padding: 0 20px 20px;
    border-top: 1px solid #333;
}

.release-organization-content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 20px 0;
}

.release-content-title {
    color: gold;
    font-size: 1.15rem;
    font-weight: 700;
}

.release-content-subtitle {
    color: #888;
    font-size: 0.85rem;
    margin-top: 4px;
}


/* =========================================================
   CATEGORIES
   ========================================================= */

.release-categories {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.release-category {
    background: #0b0c18;
    border: 1px solid #444;
    border-radius: 12px;
    padding: 16px;
}

.release-category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.release-category-title-block {
    min-width: 0;
}

.release-category-name {
    color: gold;
    font-size: 1.15rem;
    font-weight: 700;
}

.release-category-slug {
    color: #777;
    font-size: 0.8rem;
    margin-top: 3px;
}

.release-category-description {
    color: #aaa;
    line-height: 1.5;
    margin: 12px 0;
}

.release-category-empty {
    color: #777;
    padding: 15px 0 5px;
}


/* =========================================================
   RELEASE LIST
   ========================================================= */

.release-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 15px;
}

.release-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 12px;
    transition: 0.2s;
}

.release-card:hover {
    border-color: #00d4c7;
    box-shadow: 0 0 10px rgba(0, 212, 199, 0.08);
}

.release-main {
    flex: 1;
    min-width: 0;
}

.release-version {
    color: gold;
    font-size: 1.15rem;
    font-weight: 700;
}

.release-title {
    color: #fff;
    margin-top: 3px;
}

.release-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 9px;
}

.release-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    background: #2b2c2e;
    border: 1px solid #444;
    border-radius: 8px;
    font-size: 0.75rem;
}

.release-meta-label {
    color: #777;
}

.release-meta-value {
    color: #ccc;
}

.release-status-block {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 7px;
    flex-shrink: 0;
}

.release-required,
.release-optional {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 10px;
}

.release-required {
    color: #ff6b6b;
    border: 1px solid #ff6b6b;
    background: rgba(255, 107, 107, 0.08);
}

.release-optional {
    color: #888;
    border: 1px solid #555;
    background: rgba(100, 100, 100, 0.08);
}

.release-action {
    flex-shrink: 0;
}


/* =========================================================
   EMPTY
   ========================================================= */

.release-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #777;
    padding: 20px;
    background: #1a1a1a;
    border: 1px dashed #444;
    border-radius: 12px;
}

.release-empty i {
    color: gold;
}


/* =========================================================
   FORM
   ========================================================= */

.release-form-container {
    max-width: 900px;
}

.release-form-header {
    text-align: center;
    margin-bottom: 25px;
}

.release-header-separator {
    color: #555;
    margin: 0 4px;
}

.release-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.release-form-section {
    background: #1a1a1a;
    border: 1px solid #444;
    border-radius: 12px;
    padding: 20px;
}

.release-form-section:focus-within {
    border-color: rgba(255, 215, 0, 0.55);
}

.release-form-section-title {
    display: flex;
    align-items: center;
    gap: 9px;
    color: gold;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 18px;
}

.release-form-section-title i {
    color: #00d4c7;
}

.release-form-group {
    margin-bottom: 15px;
}

.release-form-group:last-child {
    margin-bottom: 0;
}

.release-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.release-form-label {
    display: block;
    color: #ccc;
    font-size: 0.9rem;
    margin-bottom: 7px;
}

.release-form-control {
    width: 100%;
    min-height: 44px;
    padding: 10px 12px;
    background: #0b0c18;
    color: #fff;
    border: 1px solid #555;
    border-radius: 9px;
    outline: none;
    box-sizing: border-box;
    transition: 0.2s;
}

.release-form-control:focus {
    border-color: #00d4c7;
    box-shadow: 0 0 8px rgba(0, 212, 199, 0.15);
}

.release-form-control::placeholder {
    color: #666;
}

.release-form-textarea {
    resize: vertical;
    min-height: 110px;
}

.release-form-control option {
    background: #0b0c18;
    color: #fff;
}


/* =========================================================
   SWITCHES
   ========================================================= */

.release-switch-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px;
    margin-bottom: 10px;
    background: #0b0c18;
    border: 1px solid #444;
    border-radius: 10px;
    cursor: pointer;
}

.release-switch-row:last-child {
    margin-bottom: 0;
}

.release-switch-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.release-switch {
    position: relative;
    width: 42px;
    height: 22px;
    flex-shrink: 0;
    background: #444;
    border: 1px solid #666;
    border-radius: 20px;
    transition: 0.2s;
}

.release-switch::after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #aaa;
    transition: 0.2s;
}

.release-switch-input:checked + .release-switch {
    background: rgba(0, 212, 199, 0.2);
    border-color: #00d4c7;
}

.release-switch-input:checked + .release-switch::after {
    left: 23px;
    background: #00d4c7;
    box-shadow: 0 0 7px #00d4c7;
}

.release-switch-content {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.release-switch-title {
    color: #ddd;
    font-weight: 600;
}

.release-switch-description {
    color: #777;
    font-size: 0.8rem;
}


/* =========================================================
   FORM ACTIONS
   ========================================================= */

.release-form-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 5px;
}

.release-button-secondary {
    color: #aaa;
    border-color: #555;
}

.release-button-secondary:hover {
    background: #555;
    color: #fff;
    box-shadow: none;
}

.release-errors-list {
    margin: 0;
    padding-left: 20px;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 600px) {

    .release-organization-card {
        padding: 15px;
        gap: 10px;
    }

    .release-organization-icon {
        width: 42px;
        height: 42px;
    }

    .release-organization-name {
        font-size: 1.2rem;
    }

    .release-organization-card > .organization-status {
        display: none;
    }

    .release-organization-content {
        padding: 0 12px 12px;
    }

    .release-organization-content-header {
        flex-direction: column;
        align-items: stretch;
    }

    .release-category {
        padding: 12px;
    }

    .release-category-header {
        align-items: flex-start;
    }

    .release-card {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 12px;
    }

    .release-status-block {
        align-items: flex-start;
        order: 3;
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .release-action {
        margin-left: auto;
    }

    .release-form-container {
        padding: 12px;
    }

    .release-form-section {
        padding: 15px;
    }

    .release-form-grid {
        grid-template-columns: 1fr;
        gap: 0;
        margin-bottom: 0;
    }

    .release-form-actions {
        flex-direction: column;
    }

    .release-form-actions .organization-button {
        width: 100%;
    }
}
    .release-file-input {
    cursor: pointer;
    padding: 12px;
}

.release-file-input::file-selector-button {
    margin-right: 12px;
    padding: 9px 16px;
    background: #0b0c18;
    color: gold;
    border: 1px solid gold;
    border-radius: 20px;
    cursor: pointer;
    transition: 0.2s;
}

.release-file-input::file-selector-button:hover {
    background: gold;
    color: #0b0c18;
}

.release-form-hint {
    margin-top: 8px;
    color: #888;
    font-size: 0.85rem;
}

.release-file-input {
    cursor: pointer;
    padding: 12px;
}

.release-file-input::file-selector-button {
    margin-right: 12px;
    padding: 9px 16px;
    background: #0b0c18;
    color: gold;
    border: 1px solid gold;
    border-radius: 20px;
    cursor: pointer;
    transition: 0.2s;
}

.release-file-input::file-selector-button:hover {
    background: gold;
    color: #0b0c18;
}

.release-form-hint {
    margin-top: 8px;
    color: #888;
    font-size: 0.85rem;
}

</style>

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