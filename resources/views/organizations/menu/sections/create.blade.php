@extends('template')

@section('title_page')
    Новый раздел меню — {{ $organization->name }}
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

    margin-bottom: 20px;

    text-align: center;
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

.menu-form-input {
    width: 100%;

    box-sizing: border-box;

    min-height: 45px;

    padding: 0 14px;

    background: #1a1a1a;

    color: #fff;

    border: 1px solid #555;

    border-radius: 10px;

    outline: none;
}

.menu-form-input:focus {
    border-color: gold;

    box-shadow:
        0 0 8px rgba(255, 215, 0, 0.25);
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

</style>


<div class="menu-form-container">

    <div class="menu-form-title">
        Новый раздел меню
    </div>

    <form
        method="POST"
        action="{{ route('organizations.menu.sections.store', $organization) }}"
    >

        @csrf

        <div class="menu-form-group">

            <label class="menu-form-label">
                Название раздела
            </label>

            <input
                type="text"
                name="name"
                class="menu-form-input"
                value="{{ old('name') }}"
                required
                maxlength="255"
            >

        </div>


        <div class="menu-form-group">

            <label class="menu-form-label">
                Порядок
            </label>

            <input
                type="number"
                name="sort_order"
                class="menu-form-input"
                value="{{ old('sort_order', 0) }}"
                min="0"
            >

        </div>


        <div class="menu-form-group">

            <label class="menu-form-checkbox">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', true))
                >

                Раздел активен

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
                Создать раздел
            </button>

        </div>

    </form>

</div>

@endsection