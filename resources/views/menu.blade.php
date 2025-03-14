<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<!-- Кнопка гамбургерного меню  -->
<div class="mobile-hamburger-header">
    <button id="mobile-hamburger-button" class="mobile-hamburger-button" title="Menu">☰Menu</button>

    <!-- Language Switcher -->
    @auth
        @if (Auth::user()->access_level >= 3)
            <div class="language-switcher">
                <a href="{{ route('language.change', 'en') }}"
                    class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('language.change', 'ru') }}"
                    class="{{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
            </div>
        @endif

        <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}"
            class="mobile-hamburger-button">
            <span class="mobile-logo-name">{{ Auth::user()->name }}</span>
        </a>
        @if ($unreadCount > 0)
            <a href="/notifications" class="mobile-notifications" title="{{ __('chats.notifications_title') }}">
                {{ $unreadCount }}
            </a>
        @endif
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();"
            title="{{ __('menu.logout') }}">
            <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
        <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <a href="/login" class="mobile-hamburger-button">{{ __('menu.login') }}</a>
        <a href="{{ route('register') }}" class="mobile-hamburger-button">{{ __('menu.registration') }}</a>
    @endauth
</div>

<!-- Гамбургер-меню (скрыто по умолчанию) -->
<div class="mobile-hamburger-menu" id="mobile-hamburger-menu">
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/home" class="mobile-chapter-title">{{ __('menu.home') }}</a>
        </div>
    </div>
    <div class="mobile-chapter-container" id="news-mobile-menu">
        <div class="mobile-chapter-header">
            <a href="/news" class="mobile-chapter-title">{{ __('menu.news') }}</a>
        </div>
        <!-- Подменю будет вставлено сюда, если раздел активен -->
    </div>
    <div class="mobile-chapter-container" id="dao-mobile-menu">
        <div class="mobile-chapter-header">
            <a href="/offers" class="mobile-chapter-title">{{ __('menu.decision_making') }}</a>
        </div>
        <!-- Подменю будет вставлено сюда, если раздел активен -->
    </div>
    <div class="mobile-chapter-container" id="tasks-mobile-menu">
        <div class="mobile-chapter-header">
            <a href="/tasks" class="mobile-chapter-title">{{ __('menu.task_marketplace') }}</a>
        </div>
        <!-- Подменю будет вставлено сюда, если раздел активен -->
    </div>

    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/white_paper" class="mobile-chapter-title">{{ __('menu.white_paper') }}</a>
        </div>
    </div>
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/chats" class="mobile-chapter-title">{{ __('menu.deschat') }}</a>
        </div>
    </div>
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/team" class="mobile-chapter-title">{{ __('menu.team') }}</a>
        </div>
    </div>

</div>

@isset($id)
    <script>
        var newsId = {{ $id }};
    </script>
@endisset

@isset($task)
    <script>
        var taskId = {{ $task }};
    </script>
@endisset


<script>
    // Проверка существования переменной newsId
    var newsId = typeof newsId !== 'undefined' ? newsId : null;
    const currentPath = window.location.pathname;

    // Определение путей и подменю
    const submenus = {
            "/news": [{
                    href: "/news",
                    text: "{{ __('menu.back_to_news') }}"
                },
                @auth
                @if (Auth::user()->access_level >= 3)
                    {
                        href: "/news/add",
                        text: "{{ __('menu.add_news') }}"
                    }, {
                        href: "/newscategories",
                        text: "{{ __('menu.manage_categories') }}"
                    },
                @endif
            @endauth
        ],
        "/offers": [{
                href: "/offers",
                text: "{{ __('menu.back_to_offers') }}"
            },
            @auth
            @if (Auth::user()->access_level >= 3)
                {
                    href: "/offers/add",
                    text: "{{ __('menu.add_offer') }}"
                }, {
                    href: "/offerscategories",
                    text: "{{ __('menu.manage_categories') }}"
                },
            @endif
        @endauth
    ],
    "/tasks": [{
            href: "/tasks",
            text: "{{ __('menu.back_to_tasks') }}"
        },
        @auth
        @if (Auth::user()->access_level >= 3)
            {
                href: "/addtask",
                text: "{{ __('menu.create_task') }}"
            }, {
                href: "/taskscategories",
                text: "{{ __('menu.manage_categories') }}"
            },
        @endif
    @endauth
    ],
    };

    // Получение элементов кнопок меню
    const menuButtons = {
        "/news": document.getElementById('news-mobile-menu'),
        "/offers": document.getElementById('dao-mobile-menu'),
        "/tasks": document.getElementById('tasks-mobile-menu')
    };

    // Функция для обновления кнопок меню и подменю
    function updateMenuButton() {
        // Определение активного меню
        Object.keys(menuButtons).forEach((path) => {
            if (currentPath.includes(path)) {
                const menuButton = menuButtons[path];
                if (menuButton) {
                    menuButton.classList.add("active-section-menu");

                    // Изменение текста заголовка на "Меню"
                    const header = menuButton.querySelector('.mobile-chapter-title');
                    if (header) {
                        header.textContent += '{{ __('menu.menu') }}';

                        // Добавление обработчика события для открытия подменю
                        header.addEventListener('click', function(event) {
                            event.preventDefault();
                            const submenuContainer = menuButton.querySelector('.mobile-subchapters');
                            if (submenuContainer) {
                                submenuContainer.classList.toggle('open');
                            }
                        });
                    }

                    // Создание контейнера подменю, если его нет
                    let submenuContainer = menuButton.querySelector('.mobile-subchapters');
                    if (!submenuContainer) {
                        submenuContainer = document.createElement('div');
                        submenuContainer.classList.add('mobile-subchapters');
                        menuButton.appendChild(submenuContainer);
                    } else {
                        submenuContainer.innerHTML = ''; // Очистка существующих элементов подменю
                    }

                    // Заполнение подменю для текущего пути
                    (submenus[path] || []).forEach(function(link) {
                        const anchor = document.createElement('a');
                        anchor.href = link.href;
                        anchor.textContent = link.text;
                        anchor.classList.add('mobile-subchapter-item');
                        submenuContainer.appendChild(anchor);
                    });
                }
            }
        });
    }

    // Вызов функции при загрузке страницы
    window.onload = updateMenuButton;
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerButton = document.getElementById('mobile-hamburger-button');
        const hamburgerMenu = document.getElementById('mobile-hamburger-menu');

        // Управление видимостью гамбургер-меню
        hamburgerButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Предотвращаем всплытие события
            if (hamburgerMenu.style.display === 'flex') {
                hamburgerMenu.style.display = 'none'; // Скрыть меню
            } else {
                hamburgerMenu.style.display = 'flex'; // Показать меню
            }
        });

        // Скрытие меню при клике вне его
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = hamburgerMenu.contains(event.target) || hamburgerButton.contains(
                event.target);
            if (!isClickInsideMenu && hamburgerMenu.style.display === 'flex') {
                hamburgerMenu.style.display = 'none'; // Скрыть меню
            }
        });
    });
</script>

@include('partials.alert')
