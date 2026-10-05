@vite(['resources/css/menu.css'])

<!-- Кнопка гамбургерного меню -->
<div class="mobile-hamburger-header">
    <!-- Левая часть -->
    <div class="mobile-header-left">
        <button id="mobile-hamburger-button" class="mobile-hamburger-button" title="{{ __('menu.open') }}">☰ Menu</button>

        <!-- Переключатель языков -->
        @auth
            @if (Auth::user()->access_level >= 3)
                <div class="language-switcher">
                    <a href="{{ route('language.change', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('language.change', 'ru') }}" class="{{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
                </div>
            @endif
        @endauth
    </div>

    <!-- Правая часть -->
    <div class="mobile-header-right">
        @auth
            @if (isset($unreadCount) && $unreadCount > 0)
                <div class="mobile-notifications">
                    <a href="{{ url('/notifications') }}" title="{{ __('chats.notifications') }}">
                        {{ $unreadCount }}
                    </a>
                </div>
            @endif

            <div class="profile-link">
                <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}">
                    {{ Auth::user()->name }}
                </a>
            </div>

            <div class="log-link">
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
            <div class="auth-buttons-inline">
                <div class="log-link">
                    <a href="{{ route('login') }}">{{ __('menu.login') }}</a>
                </div>
                <div class="log-link">
                    <a href="{{ route('register') }}">{{ __('menu.registration') }}</a>
                </div>
            </div>
        @endauth
    </div>
</div>

<!-- Гамбургер-меню (скрыто по умолчанию) -->
<nav class="mobile-hamburger-menu" id="mobile-hamburger-menu">
    <div class="mobile-chapter-container">
        <a href="{{ url('/home') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.home') }}</span>
        </a>
    </div>

    @auth
        @if (Auth::user()->access_level >= 3)
            <!-- Админка: Рассылка -->
            <div class="mobile-chapter-container">
                <a href="{{ url('/admin/mailer/') }}" class="mobile-chapter-header" target="_blank" rel="noopener noreferrer">
                    <span class="mobile-chapter-title">{{ __('menu.mailer') }}</span>
                </a>
            </div>

            <!-- Админка: Организации -->
            <div class="mobile-chapter-container">
                <a href="{{ url('/organizations') }}" class="mobile-chapter-header">
                    <span class="mobile-chapter-title">{{ __('menu.organizations') }}</span>
                </a>
            </div>
        @endif

        <!-- Почта -->
        <div class="mobile-chapter-container">
            <a href="https://mail.daodes.space/" class="mobile-chapter-header" target="_blank" rel="noopener noreferrer">
                <span class="mobile-chapter-title">{{ __('menu.mail') }}</span>
            </a>
        </div>
    @endauth

    <div class="mobile-chapter-container" id="news-mobile-menu">
        <a href="{{ url('/news') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.news') }}</span>
        </a>
    </div>

    <div class="mobile-chapter-container" id="dao-mobile-menu">
        <a href="{{ url('/offers') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.decision_making') }}</span>
        </a>
    </div>

    <div class="mobile-chapter-container" id="tasks-mobile-menu">
        <a href="{{ url('/tasks') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.task_marketplace') }}</span>
        </a>
    </div>

    <div class="mobile-chapter-container">
        <a href="{{ url('/white_paper') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.white_paper') }}</span>
        </a>
    </div>

    <div class="mobile-chapter-container">
        <a href="{{ url('/chats') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.deschat') }}</span>
        </a>
    </div>

    <div class="mobile-chapter-container">
        <a href="{{ url('/team') }}" class="mobile-chapter-header">
            <span class="mobile-chapter-title">{{ __('menu.team') }}</span>
        </a>
    </div>
</nav>

@isset($id)
    <script>
        const newsId = @json($id);
    </script>
@endisset

@isset($task)
    <script>
        const taskId = @json($task);
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

    // Конфигурация подменю
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

    // Пути, для которых подменю не требуется
    const noSubmenuPaths = ["/home", "/white_paper", "/team"];

    // Управление видимостью гамбургер-меню
    hamburgerButton.addEventListener('click', function(event) {
        event.stopPropagation();
        const isHidden = hamburgerMenu.style.display === 'none' || hamburgerMenu.style.display === '';
        hamburgerMenu.style.display = isHidden ? 'flex' : 'none';
        
        if (isHidden) {
            updateActiveMenu();
        }
    });

    // Скрытие меню при клике вне его области
    document.addEventListener('click', function(event) {
        const isClickInside = hamburgerMenu.contains(event.target) || hamburgerButton.contains(event.target);
        if (!isClickInside && hamburgerMenu.style.display === 'flex') {
            hamburgerMenu.style.display = 'none';
        }
    });

    // Функция обновления активного состояния меню и создания подменю
    function updateActiveMenu() {
        const menuHeaders = document.querySelectorAll('.mobile-chapter-header');
        menuHeaders.forEach(header => {
            const headerPath = header.getAttribute('href');
            const menuContainer = header.parentElement;

            // Подсветка активного пункта
            if (currentPath.includes(headerPath)) {
                header.classList.add('active');
            } else {
                header.classList.remove('active');
            }

            createSubmenu(menuContainer, header, headerPath);
        });
    }

    // Функция создания подменю
    function createSubmenu(menuContainer, header, headerPath) {
        if (noSubmenuPaths.includes(headerPath)) {
            return;
        }

        // Удаляем существующее подменю, если оно есть (защита от дублирования)
        const existingSubmenu = menuContainer.querySelector('.mobile-subchapters');
        if (existingSubmenu) {
            existingSubmenu.remove();
        }

        @auth
            const submenuContainer = document.createElement('div');
            submenuContainer.classList.add('mobile-subchapters');

            const menuItems = submenus[headerPath];
            if (menuItems && menuItems.length > 0) {
                menuItems.forEach(function(link) {
                    const anchor = document.createElement('a');
                    anchor.href = link.href;
                    anchor.textContent = link.text;
                    anchor.classList.add('mobile-subchapter-item');
                    submenuContainer.appendChild(anchor);
                });

                menuContainer.appendChild(submenuContainer);

                // Обработчик клика для открытия/закрытия подменю
                header.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    submenuContainer.classList.toggle('open');
                });
            }
        @endauth
    }

    // Инициализация при загрузке
    updateActiveMenu();
});
</script>

@include('partials.alert')