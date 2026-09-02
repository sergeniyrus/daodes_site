@extends('template')

@section('title_page')
    Управление меню — {{ $organization->name }}
@endsection

@section('main')

<style>

.menu-admin-container {
    max-width: 1100px;
    margin: 35px auto 50px;
    padding: 25px;

    background:
        radial-gradient(
            circle at top right,
            rgba(255, 215, 0, 0.06),
            transparent 35%
        ),
        radial-gradient(
            circle at bottom left,
            rgba(0, 229, 255, 0.04),
            transparent 35%
        ),
        linear-gradient(
            145deg,
            #0b0c18 0%,
            #111323 50%,
            #0b0c18 100%
        );

    border: 1px solid rgba(255, 215, 0, 0.65);
    border-radius: 22px;

    color: #fff;

    box-shadow:
        0 14px 45px rgba(0, 0, 0, 0.55);

    overflow: hidden;
}


/* =========================================================
   HEADER
   ========================================================= */

.menu-admin-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 5px 5px 18px;

    margin-bottom: 20px;

    border-bottom: 1px solid rgba(255, 215, 0, 0.20);
}

.menu-admin-header-title {
    min-width: 0;
}

.menu-admin-title-small {
    color: #888;

    font-size: 0.85rem;

    margin-bottom: 5px;
}

.menu-admin-title {
    color: gold;

    font-size: 1.7rem;
    font-weight: 700;

    text-shadow:
        0 0 10px rgba(255, 215, 0, 0.25);
}

.menu-admin-header-actions {
    display: flex;
    gap: 10px;

    flex-shrink: 0;
}


/* =========================================================
   ALERT
   ========================================================= */

.menu-admin-alert {
    padding: 11px 15px;

    margin-bottom: 15px;

    border-radius: 12px;

    text-align: center;
}

.menu-admin-alert.success {
    color: #4caf50;

    border: 1px solid #4caf50;

    background: rgba(76, 175, 80, 0.08);
}

.menu-admin-alert.error {
    color: #ff6b6b;

    border: 1px solid #ff6b6b;

    background: rgba(255, 107, 107, 0.08);
}


/* =========================================================
   BUTTONS
   ========================================================= */

.menu-admin-button {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    min-height: 42px;

    padding: 0 17px;

    background: #0b0c18;

    color: gold;

    border: 1px solid gold;

    border-radius: 21px;

    text-decoration: none;

    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;
}

.menu-admin-button:hover {
    background: gold;

    color: #0b0c18;

    box-shadow:
        0 0 12px rgba(255, 215, 0, 0.35);
}

.menu-admin-button.cyan {
    color: #00e5ff;

    border-color: #00e5ff;
}

.menu-admin-button.cyan:hover {
    background: #00e5ff;

    color: #0b0c18;

    box-shadow:
        0 0 12px rgba(0, 229, 255, 0.35);
}

.menu-admin-button.danger {
    color: #ff6b6b;

    border-color: #ff6b6b;
}

.menu-admin-button.danger:hover {
    background: #ff5252;

    color: #fff;
}


/* =========================================================
   SECTION
   ========================================================= */

.menu-section {
    margin-bottom: 18px;

    padding: 18px;

    background:
        linear-gradient(
            145deg,
            rgba(255, 255, 255, 0.025),
            rgba(255, 255, 255, 0.01)
        );

    border: 1px solid rgba(255, 215, 0, 0.25);

    border-radius: 16px;

    transition: border-color 0.3s;
}

.menu-section:last-child {
    margin-bottom: 0;
}

.menu-section.open {
    border-color: rgba(0, 229, 255, 0.45);
}


/* =========================================================
   SECTION HEADER
   ========================================================= */

.menu-section-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 15px;

    cursor: pointer;

    user-select: none;

    transition: 0.2s;
}

.menu-section.collapsed .menu-section-header {
    margin-bottom: 0;
}

.menu-section-header:hover .menu-section-title {
    text-shadow: 0 0 12px rgba(255, 215, 0, 0.45);
}

/* Название слева, мета справа — одинаково в обоих состояниях */
.menu-section-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
}

.menu-section-title {
    color: gold;

    font-size: 1.25rem;
    font-weight: 700;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.menu-section-meta {
    color: #888;

    font-size: 0.8rem;

    white-space: nowrap;

    margin-left: auto; /* прижимает мета к правому краю */
}

.menu-section-actions {
    display: flex;

    gap: 8px;

    flex-wrap: wrap;

    justify-content: flex-end;

    flex-shrink: 0;
}


/* =========================================================
   SECTION BODY (аккордеон)
   ========================================================= */

.menu-section-body {
    overflow: hidden;

    max-height: 5000px;

    opacity: 1;

    transition:
        max-height 0.45s ease,
        opacity 0.35s ease,
        margin-top 0.35s ease;

    margin-top: 15px;
}

.menu-section.collapsed .menu-section-body {
    max-height: 0;
    opacity: 0;
    margin-top: 0;
}


/* =========================================================
   ITEM
   ========================================================= */

.menu-item {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 13px 14px;

    margin-bottom: 8px;

    background: #2B2C2E;

    border: 1px solid #444;

    border-radius: 12px;

    transition: 0.2s;
}

.menu-item:last-child {
    margin-bottom: 0;
}

.menu-item:hover {
    border-color: #00e5ff;

    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.25);
}

.menu-item-main {
    min-width: 0;

    flex: 1;
}

.menu-item-name {
    color: #fff;

    font-weight: 600;

    margin-bottom: 5px;
}

.menu-item-description {
    color: #999;

    font-size: 0.82rem;

    margin-bottom: 6px;
}

.menu-item-data {
    display: flex;

    flex-wrap: wrap;

    gap: 7px;
}

.menu-item-badge {
    display: inline-block;

    padding: 3px 8px;

    border-radius: 10px;

    border: 1px solid #555;

    color: #bbb;

    font-size: 0.75rem;
}

.menu-item-badge.price {
    color: gold;

    border-color: gold;
}

.menu-item-badge.active {
    color: #4caf50;

    border-color: #4caf50;
}

.menu-item-badge.inactive {
    color: #ffb74d;

    border-color: #ffb74d;
}

.menu-item-actions {
    display: flex;

    flex-direction: column;

    gap: 7px;

    align-items: flex-end;

    flex-shrink: 0;
}

.menu-item-meta {
    display: flex;

    gap: 7px;
}

.menu-item-buttons {
    display: flex;

    gap: 7px;
}


/* =========================================================
   EMPTY
   ========================================================= */

.menu-empty {
    padding: 20px;

    text-align: center;

    color: #888;

    border: 1px dashed #444;

    border-radius: 12px;
}


/* =========================================================
   FOOTER
   ========================================================= */

.menu-admin-footer {
    margin-top: 20px;

    text-align: center;
}

.menu-admin-back {
    color: #00e5ff;

    text-decoration: none;

    font-size: 0.9rem;
}

.menu-admin-back:hover {
    color: gold;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 750px) {

    .menu-admin-container {
        margin: 20px 10px 35px;

        padding: 15px;

        border-radius: 17px;
    }

    .menu-admin-header {
        display: block;

        text-align: center;
    }

    .menu-admin-header-actions {
        justify-content: center;

        margin-top: 15px;

        flex-wrap: wrap;
    }

    .menu-section-header {
        display: block;
    }

    .menu-section-title-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }

    .menu-section-meta {
        margin-left: 0;
    }

    .menu-section.collapsed .menu-section-actions {
        display: none;
    }

    .menu-section-actions {
        margin-top: 12px;

        justify-content: flex-start;
    }

    .menu-item {
        display: block;
    }

    .menu-item-name {
        font-size: 1.1rem;
        text-align: center;
    }

    .menu-item-description {
        font-size: 0.95rem;
        text-align: center;
    }

    .menu-item-data {
        justify-content: center;
    }

    .menu-item-badge {
        font-size: 0.85rem;
        padding: 4px 10px;
    }

    .menu-item-actions {
        margin-top: 12px;
        align-items: stretch;
    }

    .menu-item-meta {
        justify-content: center;
    }

    .menu-item-buttons {
        justify-content: center;
    }
}

@media (max-width: 500px) {

    .menu-admin-title {
        font-size: 1.35rem;
    }

    .menu-admin-header-actions,
    .menu-section-actions,
    .menu-item-buttons {
        flex-direction: column;
    }

    .menu-admin-button {
        width: 100%;

        box-sizing: border-box;
    }
}

</style>


<div class="menu-admin-container">

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <div class="menu-admin-header">

        <div class="menu-admin-header-title">

            <div class="menu-admin-title-small">
                Управление меню
            </div>

            <div class="menu-admin-title">
                {{ $organization->name }}
            </div>

        </div>

        <div class="menu-admin-header-actions">

            <a
                href="{{ route('organizations.menu.sections.create', $organization) }}"
                class="menu-admin-button"
            >
                + Добавить раздел
            </a>

        </div>

    </div>


    {{-- =====================================================
         УВЕДОМЛЕНИЯ
         ===================================================== --}}

    @if(session('success'))

        <div class="menu-admin-alert success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="menu-admin-alert error">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="menu-admin-alert error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    {{-- =====================================================
         РАЗДЕЛЫ МЕНЮ
         ===================================================== --}}

    @forelse($sections as $section)

        <div
            class="menu-section collapsed"
            data-section
        >

            <div class="menu-section-header" data-section-toggle>

                <div class="menu-section-title-wrapper">

                    <div class="menu-section-title">
                        {{ $section->name }}
                    </div>

                    <div class="menu-section-meta">
                        Порядок: {{ $section->sort_order }}
                        ·
                        @if($section->is_active)
                            <span style="color:#4caf50;">активен</span>
                        @else
                            <span style="color:#ffb74d;">выключен</span>
                        @endif
                        ·
                        {{ $section->items->count() }}
                        {{ $section->items->count() == 1 ? 'блюдо' : 'блюд' }}
                    </div>

                </div>


                <div class="menu-section-actions">

                    <a
                        href="{{ route('organizations.menu.items.create', [
                            'organization' => $organization,
                            'section' => $section,
                        ]) }}"
                        class="menu-admin-button cyan"
                        data-stop-propagation
                    >
                        + Блюдо
                    </a>

                    <a
                        href="{{ route('organizations.menu.sections.edit', [
                            'organization' => $organization,
                            'section' => $section,
                        ]) }}"
                        class="menu-admin-button"
                        data-stop-propagation
                    >
                        Изменить
                    </a>

                    <form
                        method="POST"
                        action="{{ route('organizations.menu.sections.destroy', [
                            'organization' => $organization,
                            'section' => $section,
                        ]) }}"
                        onsubmit="return confirm('Удалить раздел и все его блюда?');"
                        data-stop-propagation
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="menu-admin-button danger"
                        >
                            Удалить
                        </button>

                    </form>

                </div>

            </div>


            {{-- =================================================
                 БЛЮДА
                 ================================================= --}}

            <div class="menu-section-body">

                @forelse($section->items as $item)

                    <div class="menu-item">

                        <div class="menu-item-main">

                            <div class="menu-item-name">
                                {{ $item->name }}
                            </div>

                            @if($item->description)

                                <div class="menu-item-description">
                                    {{ $item->description }}
                                </div>

                            @endif

                            <div class="menu-item-data">

                                @if($item->weight !== null)

                                    <span class="menu-item-badge">
                                        {{ fmod($item->weight, 1) == 0 ? (int)$item->weight : $item->weight }}
                                        {{ $item->unit }}
                                    </span>

                                @endif

                                <span class="menu-item-badge price">
                                    {{ fmod($item->price, 1) == 0 ? (int)$item->price : $item->price }} ₽
                                </span>

                            </div>

                        </div>


                        <div class="menu-item-actions">

                            <div class="menu-item-meta">

                                <span class="menu-item-badge">
                                    порядок: {{ $item->sort_order }}
                                </span>

                                @if($item->is_active)

                                    <span class="menu-item-badge active">
                                        активно
                                    </span>

                                @else

                                    <span class="menu-item-badge inactive">
                                        выключено
                                    </span>

                                @endif

                            </div>

                            <div class="menu-item-buttons">

                                <a
                                    href="{{ route('organizations.menu.items.edit', [
                                        'organization' => $organization,
                                        'section' => $section,
                                        'item' => $item,
                                    ]) }}"
                                    class="menu-admin-button"
                                >
                                    Изменить
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('organizations.menu.items.destroy', [
                                        'organization' => $organization,
                                        'section' => $section,
                                        'item' => $item,
                                    ]) }}"
                                    onsubmit="return confirm('Удалить это блюдо?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="menu-admin-button danger"
                                    >
                                        Удалить
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="menu-empty">
                        В этом разделе пока нет блюд.
                    </div>

                @endforelse

            </div>

        </div>

    @empty

        <div class="menu-empty">
            Меню пока пустое.
            Создайте первый раздел.
        </div>

    @endforelse


    {{-- =====================================================
         НАЗАД
         ===================================================== --}}

    <div class="menu-admin-footer">

        <a
            href="{{ route('organizations.manage', $organization) }}"
            class="menu-admin-back"
        >
            ← Вернуться к управлению организацией
        </a>

    </div>

</div>


<script>
(function () {
    const sections = document.querySelectorAll('[data-section]');

    sections.forEach(section => {
        const header = section.querySelector('[data-section-toggle]');
        if (!header) return;

        header.addEventListener('click', function (e) {
            if (e.target.closest('[data-stop-propagation]')) {
                return;
            }

            const isCollapsed = section.classList.contains('collapsed');

            sections.forEach(other => {
                if (other !== section) {
                    other.classList.remove('open');
                    other.classList.add('collapsed');
                }
            });

            if (isCollapsed) {
                section.classList.remove('collapsed');
                section.classList.add('open');
            } else {
                section.classList.remove('open');
                section.classList.add('collapsed');
            }
        });
    });
})();
</script>

@endsection