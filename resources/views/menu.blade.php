<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<!-- Кнопка гамбургерного меню (видна только на экранах меньше 768px) -->
<div class="mobile-hamburger-header">
    <button id="mobile-hamburger-button" class="mobile-hamburger-button">☰</button>
    @auth
        <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}"
            class="mobile-hamburger-button">
            <span class="logo_name">{{ Auth::user()->name }}</span>
        </a>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();" >
            <span  class="mobile-hamburger-button">{{ __('menu.logout') }}</span>
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
    @if ($unreadCount > 0)
        <div class="mobile-chapter-container">
            <div class="mobile-chapter-header">
                <a href="/notifications" class="mobile-chapter-title">{{ __('menu.notifications') }}
                    ({{ $unreadCount }})</a>
            </div>
        </div>
    @endif
</div>

<!-- Основное меню (видно на экранах больше 768px) -->
<div class="knopkodav">
    <div class="header-menu">
        <a href="/home" title="{{ __('menu.home') }}">
            <span class="logo_name">{{ __('menu.home') }}</span>
        </a>
    </div>
    <div class="header-menu">
        <a href="/news" title="{{ __('menu.news') }}">
            <span class="logo_name">{{ __('menu.news') }}</span></a>
    </div>
    <div class="header-menu">
        <a href="/chats" title="{{ __('menu.deschat') }}">
            <span  class="logo_name">{{ __('menu.deschat') }}</span></a>
    </div>
    <div class="header-menu">
        <a href="/offers" title="{{ __('menu.decision_making') }}">
            <span class="logo_name">{{ __('menu.decision_making') }}</span></a>
    </div>
    <div class="header-menu">
        <a href="/tasks" title="{{ __('menu.task_marketplace') }}">
            <span class="logo_name">{{ __('menu.task_marketplace') }}</span></a>
    </div>
    <div class="header-menu">
        <a href="/white_paper" title="{{ __('menu.white_paper') }}">
            <span  class="logo_name">{{ __('menu.white_paper') }}</span>
        </a>
    </div>
    <div class="header-menu">
        <a href="/team" title="{{ __('menu.team') }}">
            <span  class="logo_name">{{ __('menu.team') }}</span>
        </a>
    </div>
    @if ($unreadCount > 0)
        <a href="/notifications"><span class="notifications">
                {{ $unreadCount }}
            </span></a>
    @endif
    <div class="auth-buttons">
        @auth
            <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}"
                style="border-radius:50%">
                <div class="logo_name">{{ Auth::user()->name }}</div>
            </a>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                title="{{ __('menu.logout') }}">
                <div class="logo_name"><svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg></div>
            </a>
            <form id="logout-form" class="logo_name" action="{{ route('logout') }}" method="POST"
                style="display: none;">
                @csrf
            </form>
        @else
            <a href="https://daodes.space/login" title="{{ __('menu.login') }}">
                <span class="logo_name">{{ __('menu.login') }}</span>
            </a>
            <a href="{{ route('register') }}" title="{{ __('menu.registration') }}">
                <span class="logo_name">{{ __('menu.registration') }}</span>
            </a>
        @endauth
    </div>
</div>
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
