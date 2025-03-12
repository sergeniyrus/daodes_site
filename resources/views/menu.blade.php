
<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<div class="knopkodav">
    <div class="header-menu" id="home-menu">
        <a href="/home" title="{{ __('menu.home') }}">
            <span class="logo_name"><img src="/img/bottom/home.png" alt="{{ __('menu.home') }}"></span>
        </a>
    </div>
    <div class="header-menu has-submenu" id="news-menu">
        <a href="/news" title="{{ __('menu.news') }}">
            <span class="logo_name"><img src="/img/bottom/dn.webp" alt="{{ __('menu.news') }}"></span></a>
    </div>
    <div class="header-menu has-submenu" id="dao-menu">
        <a href="/offers" title="{{ __('menu.decision_making') }}">
            <span class="logo_name"><img src="/img/bottom/dd.jpg" alt="{{ __('menu.decision_making') }}"></span></a>
    </div>
    <div class="header-menu has-submenu" id="tasks-menu">
        <a href="/tasks" title="{{ __('menu.task_marketplace') }}">
            <span class="logo_name"><img src="/img/bottom/tasks2.png"
                    alt="{{ __('menu.task_marketplace') }}"></span></a>
    </div>

    <div class="header-menu" id="">
        <a href="/white_paper" title="{{ __('menu.white_paper') }}">
            <span><img src="/img/bottom/paper.png" alt="{{ __('menu.white_paper') }}"></span>
        </a>
    </div>
    <div class="header-menu has-submenu" id="wallet-menu">
        <a href="/chats" title="{{ __('menu.deschat') }}">
            <span><img src="/img/bottom/deschat.png" alt="{{ __('menu.deschat') }}"></span></a>
    </div>
    <div class="header-menu" id="">
        <a href="/team" title="{{ __('menu.team') }}">
            <span><img src="/img/bottom/team.png" alt="{{ __('menu.team') }}"></span>
        </a>
    </div>

    @php
        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    @endphp
    @if ($unreadCount > 0)
        <a href="/notifications"><span class="notifications">
                {{ $unreadCount }}
            </span></a>
    @endif

    <div class="auth-buttons">
        @if (Auth::check())
            <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="{{ __('menu.profile') }}"
                style="border-radius:50%">
                <span class="logo_name">
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->avatar_url ? Auth::user()->profile->avatar_url : 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj' }}"
                        alt="{{ __('menu.profile') }}" class="avatar-img">
                </span>
            </a>

            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                title="{{ __('menu.logout') }}">
                <span class="logo_name"><img src="/img/bottom/logout.png" alt="{{ __('menu.logout') }}"></span>
            </a>
            <form id="logout-form" class="logo_name" action="{{ route('logout') }}" method="POST"
                style="display: none;">
                @csrf
            </form>
        @else
            <a href="https://daodes.space/login" title="{{ __('menu.login') }}">
                <span class="logo_name"><img src="/img/bottom/login.png" alt="{{ __('menu.login') }}"></span>
            </a>
            <a href="{{ route('register') }}" title="{{ __('menu.registration') }}">
                <span class="logo_name"><img src="/img/bottom/registrat.png"
                        alt="{{ __('menu.registration') }}"></span>
            </a>
        @endif
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

@include('partials.alert')

<script>
    // Check if newsId variable exists
    var newsId = typeof newsId !== 'undefined' ? newsId : null;
    const currentPath = window.location.pathname;

    // Define paths and submenus
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
                    text: "{{ __('menu.edit_categories') }}"
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
                text: "{{ __('menu.edit_categories') }}"
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
                submenuButton.classList.add('submenu-button', 'active-section-menu');
                submenuButton.innerHTML = '{{ __('menu.section_menu') }}';

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
