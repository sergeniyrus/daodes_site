<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<!-- Кнопка гамбургерного меню  -->
<div class="mobile-hamburger-header">
    <button id="mobile-hamburger-button" class="mobile-hamburger-button" title="Menu">☰ Menu</button>

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

        <div class="profile-link">
            <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}">
                {{ Auth::user()->name }}
            </a>
        </div>
        @if ($unreadCount > 0)
            <div class="mobile-notifications">
                <a href="/notifications" title="{{ __('chats.notifications') }}">
                    {{ $unreadCount }}
                </a>
            </div>
        @endif
        <div class="mobile-hamburger-button log-link">
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
        </div>
    @else
        <div class="log-link">
            <a href="/login">{{ __('menu.login') }}</a>
        </div>
        <div class="log-link">
            <a href="{{ route('register') }}">{{ __('menu.registration') }}</a>
        </div>
    @endauth
</div>

<!-- Гамбургер-меню (скрыто по умолчанию) -->
<div class="mobile-hamburger-menu" id="mobile-hamburger-menu">
    <div class="mobile-chapter-container">
        <a href="/home" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.home') }}</span>
        </a>
    </div>
    <div class="mobile-chapter-container" id="news-mobile-menu">
        <a href="/news" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.news') }}</span>
        </a>
        <!-- Подменю будет вставлено сюда, если раздел активен -->
    </div>
    <div class="mobile-chapter-container" id="dao-mobile-menu">
        <a href="/offers" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.decision_making') }}</span>
        </a>
        <!-- Подменю будет вставлено сюда, если раздел активен -->
    </div>
    <div class="mobile-chapter-container" id="tasks-mobile-menu">
        <a href="/tasks" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.task_marketplace') }}</span>
        </a>
        <!-- Подменю будет вставлено сюда, если раздел активен -->
    </div>

    <div class="mobile-chapter-container">
        <a href="/white_paper" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.white_paper') }}</span>
        </a>
    </div>
    <div class="mobile-chapter-container">
        <a href="/chats" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.deschat') }}</span>
        </a>
    </div>
    <div class="mobile-chapter-container">
        <a href="/team" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.team') }}</span>
        </a>
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

document.addEventListener('DOMContentLoaded', function() {
    const hamburgerButton = document.getElementById('mobile-hamburger-button');
    const hamburgerMenu = document.getElementById('mobile-hamburger-menu');
    const currentPath = window.location.pathname;

    if (!hamburgerButton || !hamburgerMenu) {
        console.error('Hamburger button or menu not found');
        return;
    }

    // Определение путей и подменю
    const submenus = {
        "/news": [
            { href: "/news", text: "{{ __('menu.back_to_news') }}" },            
            @auth
            @if (Auth::user()->access_level >= 3)   
            { href: "/news/create", text: "{{ __('menu.add_news') }}" },         
            { href: "/newscategories", text: "{{ __('menu.manage_categories') }}" },
            @endif
            @endauth
        ],
        "/offers": [
            { href: "/offers", text: "{{ __('menu.back_to_offers') }}" },
            { href: "/offers/create", text: "{{ __('menu.add_offer') }}" },
            @auth
            @if (Auth::user()->access_level >= 3)            
            { href: "/offerscategories", text: "{{ __('menu.manage_categories') }}" },
            @endif
            @endauth
        ],
        "/tasks": [
            { href: "/tasks", text: "{{ __('menu.back_to_tasks') }}" },
            { href: "/addtask", text: "{{ __('menu.create_task') }}" },
            @auth
            @if (Auth::user()->access_level >= 3)            
            { href: "/taskscategories", text: "{{ __('menu.manage_categories') }}" },
            @endif
            @endauth
        ],
        "/chats": [
            { href: "/chats", text: "{{ __('chats.your_chats') }}" },
            { href: "/chats/create", text: "{{ __('chats.create_chat') }}" },
            { href: "/notifications", text: "{{ __('chats.notifications') }}" },
        ],
    };

    // Управление видимостью гамбургер-меню
    hamburgerButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Предотвращаем всплытие события
        if (hamburgerMenu.style.display === 'flex') {
            hamburgerMenu.style.display = 'none'; // Скрыть меню
        } else {
            hamburgerMenu.style.display = 'flex'; // Показать меню
            updateActiveMenu();
        }
    });

    // Скрытие меню при клике вне его
    document.addEventListener('click', function(event) {
        const isClickInsideMenu = hamburgerMenu.contains(event.target) || hamburgerButton.contains(event.target);
        if (!isClickInsideMenu && hamburgerMenu.style.display === 'flex') {
            hamburgerMenu.style.display = 'none'; // Скрыть меню
        }
    });

    // Функция для обновления активного меню
    function updateActiveMenu() {
        const menuHeaders = document.querySelectorAll('.mobile-chapter-header');
        menuHeaders.forEach(header => {
            const headerPath = header.getAttribute('href');
            if (currentPath.includes(headerPath)) {
                header.classList.add('active');
            } else {
                header.classList.remove('active');
            }
            createSubmenu(header.parentElement);
        });
    }

    // Функция для создания подменю
    function createSubmenu(menuContainer) {
        const header = menuContainer.querySelector('.mobile-chapter-header');
        const headerPath = header.getAttribute('href');
        let submenuContainer = menuContainer.querySelector('.mobile-subchapters');

        // Если подменю уже существует, удаляем его
        if (submenuContainer) {
            submenuContainer.remove();
        }

        // Страницы, для которых подменю не нужно
        const noSubmenuPaths = ["/home", "/white_paper", "/team"];
        if (noSubmenuPaths.includes(headerPath)) {
            return; // Не создаем подменю для этих страниц
        }

        // Создаем подменю только для пользователей с access_level >= 3 или для /chats
        @auth
        @if (Auth::user()->access_level >= 0 || headerPath === "/chats")
        submenuContainer = document.createElement('div');
        submenuContainer.classList.add('mobile-subchapters');

        // Добавляем ссылки в подменю
        if (submenus[headerPath]) {
            // Если подменю определено в коде
            submenus[headerPath].forEach(function(link) {
                const anchor = document.createElement('a');
                anchor.href = link.href;
                anchor.textContent = link.text;
                anchor.classList.add('mobile-subchapter-item');
                submenuContainer.appendChild(anchor);
            });
        }

        menuContainer.appendChild(submenuContainer);

        // Добавление обработчика события для открытия подменю
        header.addEventListener('click', function(event) {
            event.preventDefault(); // Предотвращаем переход по ссылке
            event.stopPropagation(); // Останавливаем всплытие события
            submenuContainer.classList.toggle('open'); // Переключаем класс .open
            console.log('Подменю открыто/закрыто'); // Для отладки
        });
        @endif
        @endauth
    }

    // Вызов функции при загрузке страницы
    updateActiveMenu();
});

</script>

@include('partials.alert')
