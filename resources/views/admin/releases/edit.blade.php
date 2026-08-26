@extends('layouts.app')

@section('content')

<style>
/* =========================================================
   RELEASE FORM
   ========================================================= */

.release-form-container {
    max-width: 900px;
}

.release-form-header {
    text-align: center;
    margin-bottom: 25px;
}

.release-form-subtitle {
    color: #aaa;
    margin: 8px 0 0;
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


/* =========================================================
   ERRORS
   ========================================================= */

.release-errors-list {
    margin: 0;
    padding-left: 20px;
}


/* =========================================================
   FORM SECTION
   ========================================================= */

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


/* =========================================================
   FORM GROUP
   ========================================================= */

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
   CURRENT APK
   ========================================================= */

.release-current-file {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px;
    background: #0b0c18;
    border: 1px solid #444;
    border-radius: 10px;
    margin-bottom: 15px;
}

.release-current-file-icon {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #00d4c7;
    border: 1px solid #00d4c7;
    border-radius: 50%;
    font-size: 1rem;
}

.release-current-file-info {
    min-width: 0;
    flex: 1;
}

.release-current-file-title {
    color: #ddd;
    font-weight: 600;
}

.release-current-file-meta {
    color: #888;
    font-size: 0.8rem;
    margin-top: 4px;
    word-break: break-all;
}

.release-current-file-sha {
    color: #666;
}


/* =========================================================
   APK FILE
   ========================================================= */

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
    line-height: 1.5;
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
   ACTIONS
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


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 600px) {

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

    .release-current-file {
        align-items: flex-start;
    }

}
</style>


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