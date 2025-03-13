<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<div class="knopkodav">
    <div class="header-menu" id="home-menu">
        <a href="/home" title="Home">
            <span class="logo_name"><img src="/img/bottom/home.png" alt="Home"></span>
        </a>
    </div>
    <div class="header-menu has-submenu" id="news-menu">
        <a href="/news" title="News">
            <span class="logo_name"><img src="/img/bottom/dn.webp" alt="News"></span></a>
    </div>
    <div class="header-menu has-submenu" id="dao-menu">
        <a href="/offers" title="Decision Making">
            <span class="logo_name"><img src="/img/bottom/dd.jpg" alt="Decision Making"></span></a>
    </div>
    <div class="header-menu has-submenu" id="tasks-menu">
        <a href="/tasks" title="Task Marketplace">
            <span class="logo_name"><img src="/img/bottom/tasks2.png" alt="Task Marketplace"></span></a>
    </div>
    {{-- <div class="header-menu has-submenu" id="wallet-menu">
        <a href="https://daodes.space/chatify" title="DESChat">
            <span><img src="/img/bottom/deschat.png" alt="DESChat"></span></a>
    </div> --}}
    <div class="header-menu" id="paper-menu">
        <a href="/white_paper" title="White paper">
            <span><img src="/img/bottom/paper.png" alt="White paper"></span>
        </a>
    </div>
    <div class="header-menu" id="paper-menu">
        <a href="/team" title="Team">
            <span><img src="/img/bottom/team.png" alt="Team"></span>
        </a>
    </div>

    <div class="auth-buttons">
        @if (Auth::check())
            <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="Profile"  style="border-radius:50%">
                <span class="logo_name">
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->avatar_url ? Auth::user()->profile->avatar_url : 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj' }}"
                        alt="Profile"  class="avatar-img">
                </span>
            </a>

            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                <span class="logo_name"><img src="/img/bottom/logout.png" alt="Logout"></span>
            </a>
            <form id="logout-form" class="logo_name" action="{{ route('logout') }}" method="POST"
                style="display: none;">
                @csrf
            </form>
        @else
            <a href="https://daodes.space/login" title="Login">
                <span class="logo_name"><img src="/img/bottom/login.png" alt="Login"></span>
            </a>
            <a href="{{ route('register') }}" title="Registration">
                <span class="logo_name"><img src="/img/bottom/registrat.png" alt="Registration"></span>
            </a>
        @endif
    </div>
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
        "/news": [
            { href: "/news", text: "Back to News" },
            @auth
                @if (Auth::user()->access_level >= 3)
                    { href: "/news/add", text: "Add News" },
                    { href: "/newscategories", text: "Manage Categories" },
                @endif
            @endauth
        ],
        "/offers": [
            { href: "/offers", text: "Back to Offers" },
            @auth
                @if (Auth::user()->access_level >= 3)
                    { href: "/offers/add", text: "Add Offer" },
                    { href: "/offerscategories", text: "Edit Categories" },
                @endif
            @endauth
        ],
        "/tasks": [
            { href: "/tasks", text: "Back to Tasks" },
            @auth
                @if (Auth::user()->access_level >= 3)
                    { href: "/addtask", text: "Create Task" },
                    { href: "/taskscategories", text: "Edit Categories" },
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
                submenuButton.innerHTML = 'Section<br>Menu';

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