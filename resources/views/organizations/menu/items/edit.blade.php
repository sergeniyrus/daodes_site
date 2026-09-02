@extends('template')

@section('title_page')
    Редактирование — {{ $item->name }}
@endsection

@section('main')

<style>

.menu-form-container {
    max-width: 700px;
    margin: 40px auto;
    padding: 25px;

    background:
        linear-gradient(
            145deg,
            #0b0c18,
            #111323,
            #0b0c18
        );

    border: 1px solid rgba(255, 215, 0, 0.55);

    border-radius: 20px;

    color: #fff;
}

.menu-form-title {
    color: gold;

    font-size: 1.5rem;

    margin-bottom: 8px;

    text-align: center;
}

.menu-form-subtitle {
    color: #888;

    text-align: center;

    margin-bottom: 25px;
}

.menu-form-group {
    margin-bottom: 17px;
}

.menu-form-label {
    display: block;

    margin-bottom: 7px;

    color: #aaa;

    font-size: 0.9rem;
}

.menu-form-input,
.menu-form-textarea {
    width: 100%;

    box-sizing: border-box;

    padding: 11px 14px;

    background: #1a1a1a;

    color: #fff;

    border: 1px solid #555;

    border-radius: 10px;

    outline: none;
}

.menu-form-input {
    min-height: 45px;
}

.menu-form-textarea {
    min-height: 110px;

    resize: vertical;
}

.menu-form-input:focus,
.menu-form-textarea:focus {
    border-color: gold;

    box-shadow:
        0 0 8px rgba(255, 215, 0, 0.25);
}

.menu-form-row {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 12px;
}

.menu-form-checkbox {
    display: flex;

    align-items: center;

    gap: 8px;

    color: #ccc;
}

.menu-form-actions {
    display: flex;

    gap: 10px;

    justify-content: center;

    margin-top: 25px;
}

.menu-form-button {
    min-height: 43px;

    padding: 0 20px;

    background: #0b0c18;

    color: gold;

    border: 1px solid gold;

    border-radius: 22px;

    cursor: pointer;

    font-weight: 600;
}

.menu-form-button:hover {
    background: gold;

    color: #0b0c18;
}

.menu-form-back {
    display: inline-flex;

    align-items: center;

    padding: 0 20px;

    color: #00e5ff;

    border: 1px solid #00e5ff;

    border-radius: 22px;

    text-decoration: none;
}

@media (max-width: 600px) {

    .menu-form-container {
        margin: 20px 10px;

        padding: 18px;
    }

    .menu-form-row {
        grid-template-columns: 1fr;
    }

    .menu-form-actions {
        flex-direction: column;
    }

    .menu-form-button,
    .menu-form-back {
        width: 100%;

        box-sizing: border-box;

        justify-content: center;
    }
}

</style>


<div class="menu-form-container">

    <div class="menu-form-title">
        Редактирование блюда
    </div>

    <div class="menu-form-subtitle">
        {{ $organization->name }} → {{ $section->name }} → {{ $item->name }}
    </div>


    <form
        method="POST"
        action="{{ route('organizations.menu.items.update', [
            'organization' => $organization,
            'section' => $section,
            'item' => $item,
        ]) }}"
    >

        @csrf
        @method('PUT')


        <div class="menu-form-group">

            <label class="menu-form-label">
                Название блюда
            </label>

            <input
                type="text"
                name="name"
                class="menu-form-input"
                value="{{ old('name', $item->name) }}"
                required
                maxlength="255"
            >

        </div>


        <div class="menu-form-row">

            <div class="menu-form-group">

                <label class="menu-form-label">
                    Вес
                </label>

                <input
                    type="number"
                    step="0.001"
                    min="0"
                    name="weight"
                    class="menu-form-input"
                    value="{{ old('weight', $item->weight) }}"
                >

            </div>


            <div class="menu-form-group">

                <label class="menu-form-label">
                    Единица
                </label>

                <input
                    type="text"
                    name="unit"
                    class="menu-form-input"
                    value="{{ old('unit', $item->unit) }}"
                    maxlength="50"
                    placeholder="г / мл / шт"
                >

            </div>

        </div>


        <div class="menu-form-group">

            <label class="menu-form-label">
                Цена
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                name="price"
                class="menu-form-input"
                value="{{ old('price', $item->price) }}"
                required
            >

        </div>


        <div class="menu-form-group">

            <label class="menu-form-label">
                Описание
            </label>

            <textarea
                name="description"
                class="menu-form-textarea"
            >{{ old('description', $item->description) }}</textarea>

        </div>


        <div class="menu-form-group">

            <label class="menu-form-label">
                Порядок
            </label>

            <input
                type="number"
                name="sort_order"
                class="menu-form-input"
                value="{{ old('sort_order', $item->sort_order) }}"
                min="0"
            >

        </div>


        <div class="menu-form-group">

            <label class="menu-form-checkbox">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $item->is_active))
                >

                Блюдо активно

            </label>

        </div>


        <div class="menu-form-actions">

            <a
                href="{{ route('organizations.menu.index', $organization) }}"
                class="menu-form-back"
            >
                Назад
            </a>

            <button
                type="submit"
                class="menu-form-button"
            >
                Сохранить
            </button>

        </div>

    </form>

</div>

@endsection