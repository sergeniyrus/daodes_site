<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<!-- Кнопка гамбургерного меню (видна только на экранах меньше 768px) -->
<div class="mobile-hamburger-header">
    <button id="mobile-hamburger-button" class="mobile-hamburger-button" title="Menu">☰</button>
    @auth

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
            <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </a>
        <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <a href="/login" class="mobile-login">{{ __('menu.login') }}</a>
        <a href="{{ route('register') }}" class="mobile-register">{{ __('menu.registration') }}</a>
    @endauth
</div>

<!-- Гамбургер-меню (скрыто по умолчанию) -->
<div class="mobile-hamburger-menu" id="mobile-hamburger-menu">
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/home" class="mobile-chapter-title">{{ __('menu.home') }}</a>
        </div>
    </div>
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/news" class="mobile-chapter-title">{{ __('menu.news') }}</a>
        </div>
        @auth
            @if (Auth::user()->access_level >= 3 && Request::is('news*'))
                <div class="mobile-subchapters">
                    <a href="/news/add" class="mobile-subchapter-item">{{ __('menu.add_news') }}</a>
                    <a href="/newscategories" class="mobile-subchapter-item">{{ __('menu.manage_categories') }}</a>
                </div>
            @endif
        @endauth
    </div>
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/offers" class="mobile-chapter-title">{{ __('menu.decision_making') }}</a>
        </div>
        @auth
            @if (Auth::user()->access_level >= 3 && Request::is('offers*'))
                <div class="mobile-subchapters">
                    <a href="/offers/add" class="mobile-subchapter-item">{{ __('menu.add_offer') }}</a>
                    <a href="/offerscategories" class="mobile-subchapter-item">{{ __('menu.edit_categories') }}</a>
                </div>
            @endif
        @endauth
    </div>
    <div class="mobile-chapter-container">
        <div class="mobile-chapter-header">
            <a href="/tasks" class="mobile-chapter-title">{{ __('menu.task_marketplace') }}</a>
        </div>
        @auth
            @if (Auth::user()->access_level >= 3 && Request::is('tasks*'))
                <div class="mobile-subchapters">
                    <a href="/addtask" class="mobile-subchapter-item">{{ __('menu.create_task') }}</a>
                    <a href="/taskscategories" class="mobile-subchapter-item">{{ __('menu.edit_categories') }}</a>
                </div>
            @endif
        @endauth
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

<!-- Основное меню (видно на экранах больше 768px) -->
<div class="knopkodav">
    <div class="submenu-button" id="home-menu">
        <a href="/home" title="{{ __('menu.home') }}">
            <span>{{ __('menu.home') }}</span>
        </a>
    </div>
    <div class="submenu-button  has-submenu" id="news-menu">
        <a href="/news" title="{{ __('menu.news') }}">
            <span>{{ __('menu.news') }}</span></a>
    </div>
    <div class="submenu-button has-submenu">
        <a href="/chats" title="{{ __('menu.deschat') }}">
            <span>{{ __('menu.deschat') }}</span></a>
    </div>
    <div class="submenu-button has-submenu" id="dao-menu">
        <a href="/offers" title="{{ __('menu.decision_making') }}">
            <span>{{ __('menu.decision_making') }}</span></a>
    </div>
    <div class="submenu-button has-submenu" id="tasks-menu">
        <a href="/tasks" title="{{ __('menu.task_marketplace') }}">
            <span>{{ __('menu.task_marketplace') }}</span></a>
    </div>
    <div class="submenu-button">
        <a href="/white_paper" title="{{ __('menu.white_paper') }}">
            <span>{{ __('menu.white_paper') }}</span>
        </a>
    </div>
    <div class="submenu-button">
        <a href="/team" title="{{ __('menu.team') }}">
            <span>{{ __('menu.team') }}</span>
        </a>
    </div>

    @auth
        <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}">
            <div class="submenu-button">{{ Auth::user()->name }}</div>
        </a>

        @if ($unreadCount > 0)
            <a href="/notifications"><span class="notifications">
                    {{ $unreadCount }}
                </span></a>
        @endif

        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            title="{{ __('menu.logout') }}">
            <div class="submenu-button">
                <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
        </a>
        <form id="logout-form" class="submenu-button" action="{{ route('logout') }}" method="POST"
            style="display: none;">
            @csrf
        </form>
    @else
        <a href="/login" title="{{ __('menu.login') }}">
            <span class="submenu-button">{{ __('menu.login') }}</span>
        </a>
        <a href="{{ route('register') }}" title="{{ __('menu.registration') }}">
            <span class="submenu-button">{{ __('menu.registration') }}</span>
        </a>
    @endauth

</div>

<!-- Modal for submenu -->
<div id="submenuModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="submenuLinks"></div>
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
    // Check if newsId variable exists
    var newsId = typeof newsId !== 'undefined' ? newsId : null;
    const currentPath = window.location.pathname;

    // Define paths and submenus
    const submenus = {
            "/news": [{
                    href: "/news",
                    text: "Back to News"
                },
                @auth
                @if (Auth::user()->access_level >= 3)
                    {
                        href: "/news/add",
                        text: "Add News"
                    }, {
                        href: "/newscategories",
                        text: "Manage Categories"
                    },
                @endif
            @endauth
        ],
        "/offers": [{
                href: "/offers",
                text: "Back to Offers"
            },
            @auth
            @if (Auth::user()->access_level >= 3)
                {
                    href: "/offers/add",
                    text: "Add Offer"
                }, {
                    href: "/offerscategories",
                    text: "Edit Categories"
                },
            @endif
        @endauth
    ],
    "/tasks": [{
            href: "/tasks",
            text: "Back to Tasks"
        },
        @auth
        @if (Auth::user()->access_level >= 3)
            {
                href: "/addtask",
                text: "Create Task"
            }, {
                href: "/taskscategories",
                text: "Edit Categories"
            },
        @endif
    @endauth
    ],
    };

    // Get submenu and button elements
    const submenuModal = document.getElementById('submenuModal');
    const submenuLinksContainer = document.getElementById('submenuLinks');
    const closeModalButton = document.getElementsByClassName('close')[0];
    const menuButtons = {
        "/news": document.getElementById('news-menu'),
        "/offers": document.getElementById('dao-menu'),
        "/tasks": document.getElementById('tasks-menu'),
        "/wallet": document.getElementById('wallet-menu')
    };

    // Close submenu modal
    closeModalButton.onclick = function() {
        submenuModal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === submenuModal) {
            submenuModal.style.display = 'none';
        }
    };

    // Function to update menu buttons and submenus
    function updateMenuButton() {
        // Determine active menu
        Object.keys(menuButtons).forEach((path) => {
            if (currentPath.includes(path) || (currentPath.includes("/news/") && path === "/news/$id") || (
                    currentPath.includes("/offers/") && path === "/offers/$id")) {
                menuButtons[path].classList.add("active-section-menu");

                // Create submenu button
                menuButtons[path].innerHTML = '';
                const submenuButton = document.createElement('div');

                submenuButton.innerHTML = 'Menu';

                // Open submenu
                submenuButton.onclick = function() {
                    submenuModal.style.display = 'block';
                    submenuLinksContainer.innerHTML = '';

                    // Populate submenu for current path
                    (submenus[currentPath] || submenus[path] || []).forEach(function(link) {
                        const anchor = document.createElement('a');
                        anchor.href = link.href;
                        anchor.textContent = link.text;
                        submenuLinksContainer.appendChild(anchor);
                    });
                };
                menuButtons[path].appendChild(submenuButton);
            }
        });
    }

    // Call function on page load
    window.onload = updateMenuButton;
</script>

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
@endauth

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerButton = document.getElementById('mobile-hamburger-button');
        const hamburgerMenu = document.getElementById('mobile-hamburger-menu');

        // Управление видимостью гамбургер-меню
        hamburgerButton.addEventListener('click', function() {
            if (hamburgerMenu.style.display === 'flex') {
                hamburgerMenu.style.display = 'none'; // Скрыть меню
            } else {
                hamburgerMenu.style.display = 'flex'; // Показать меню
            }
        });
    });
</script>

@include('partials.alert')
