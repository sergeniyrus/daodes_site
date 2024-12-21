<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
<div class="knopkodav">
    <div class="header-menu" id="home-menu">
        <a href="https://daodes.space/home" title="Главная">
            <span class="logo_name"><img src="/img/bottom/home.png" alt="Главная"></span>
        </a>
    </div>
    <div class="header-menu has-submenu" id="news-menu">
        <a href="https://daodes.space/news" title="Новости">
            <span class="logo_name"><img src="/img/bottom/dn.webp" alt="Новости"></span></a>
    </div>
    <div class="header-menu has-submenu" id="dao-menu">
        <a href="https://daodes.space/offers" title="Принятие решений">
            <span class="logo_name"><img src="/img/bottom/dd.jpg" alt="Принятие решений"></span></a>
    </div>
    <div class="header-menu has-submenu" id="tasks-menu">
        <a href="https://daodes.space/tasks" title="Биржа заданий">
            <span class="logo_name"><img src="/img/bottom/tasks2.png" alt="Биржа заданий"></span></a>
    </div>
    <div class="header-menu has-submenu" id="wallet-menu">
        <a href="https://deschat.daodes.space/chatify" title="DESChat">
            <span><img src="/img/bottom/deschat.png" alt="DESChat"></span></a>
    </div>
    <div class="header-menu" id="paper-menu">
        <a href="https://1drv.ms/w/c/5a7adc7e00e1bf69/EWm_4QB-3HoggFq07AEAAAABQpjB8fDjRd_X53NmjBrYfw" title="White paper" target="_blank">
            <span><img src="/img/bottom/paper.png" alt="White paper"></span>
        </a>
    </div>


    <div class="auth-buttons">
        @if (Auth::check())
            <a href="{{ route('user_profile.show', ['id' => Auth::id()]) }}" title="Profile">
                <span class="logo_name">
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->avatar_url ? Auth::user()->profile->avatar_url : 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj' }}"
                        alt="Profile">
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

<!-- Модальное окно для подменю -->
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
    // Проверка существования переменной newsId
    var newsId = typeof newsId !== 'undefined' ? newsId : null;
    const currentPath = window.location.pathname;

    // Определяем пути и подменю
    const submenus = {
        "/news": [
            { href: "/news", text: "Вернуться в новости" },
            @auth
                @if (Auth::user()->access_level >= 3)
                    { href: "/news/add", text: "Добавить новость" },
                    { href: "/newscategories", text: "Управление категориями" },
                @endif
            @endauth
        ],
        "/offers": [
            { href: "/offers", text: "Вернуться в список предложений" },
            @auth
                @if (Auth::user()->access_level >= 3)
                    { href: "/offers/add", text: "Добавить предложение" },
                    { href: "/offerscategories", text: "Редактировать категории" },
                @endif
            @endauth
        ],
        "/tasks": [
            { href: "/tasks", text: "Вернуться к заданиям" },
            @auth
                @if (Auth::user()->access_level >= 3)
                    { href: "/addtask", text: "Создать задание" },
                    { href: "/taskscategories", text: "Редактировать категории" },
                @endif
            @endauth
        ],
    };

    // Получаем элементы подменю и кнопок
    const submenuModal = document.getElementById('submenuModal');
    const submenuLinksContainer = document.getElementById('submenuLinks');
    const closeModalButton = document.getElementsByClassName('close')[0];
    const menuButtons = {
        "/news": document.getElementById('news-menu'),
        "/offers": document.getElementById('dao-menu'),
        "/tasks": document.getElementById('tasks-menu'),
        "/wallet": document.getElementById('wallet-menu')
    };

    // Закрытие модального окна подменю
    closeModalButton.onclick = function() {
        submenuModal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === submenuModal) {
            submenuModal.style.display = 'none';
        }
    };

    // Функция для обновления кнопок и подменю
    function updateMenuButton() {
        // Определяем активное меню
        Object.keys(menuButtons).forEach((path) => {
            if (currentPath.includes(path) || (currentPath.includes("/news/") && path === "/news/$id") || (
                    currentPath.includes("/offers/") && path === "/offers/$id")) {
                menuButtons[path].classList.add("active-section-menu");

                // Создаем кнопку подменю
                menuButtons[path].innerHTML = '';
                const submenuButton = document.createElement('div');
                submenuButton.classList.add('submenu-button', 'active-section-menu');
                submenuButton.innerHTML = 'Меню<br>раздела';

                // Открытие подменю
                submenuButton.onclick = function() {
                    submenuModal.style.display = 'block';
                    submenuLinksContainer.innerHTML = '';

                    // Заполнение подменю для текущего пути
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

    // Вызов функции при загрузке страницы
    window.onload = updateMenuButton;
</script>
