<style>
    /* Общий стиль */


    .knopkodav {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 8px;
        flex-wrap: wrap;
    }

    .header-menu {
        flex: 1;
        text-align: center;
        padding: 10px;
        position: relative;
        transition: background-color 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .header-menu a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        text-decoration: none;
        color: #fff;
        transition: transform 0.3s ease;
        border: 1px solid gold;
    }

    .header-menu a img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    /* Стиль кнопки подменю */
    .submenu-button {
        width: 100px;
        /* Сделать кнопку такого же размера, как главная */
        height: 100%;
        background-color: #0b0c18;
        border: 1px solid gold;
        padding: 25px 0px;
        color: gold;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .submenu-button:hover {
        background-color: #FFD700;
        /* Светлее при наведении */
        color: black;
    }

    .active-section-menu {
        line-height: 1.2;
        /* Для отображения текста в две строки */
    }


    /* Модальное окно */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #0B0C18;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        border: #eeeeee 2px solid;
        width: 50%;
        text-align: center;
    }

    .modal-content a {
        display: block;
        margin: 10px 0;
        color: #fff;
        text-decoration: none;
        border: 1px solid gold;
        padding: 10px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .modal-content a:hover {
        background-color: #333;
        text-decoration: underline;
    }

    .close {
        color: gold;
        float: right;
        font-size: 36px;
        font-weight: bold;
        margin-top: -30px;
        margin-right: -15px;
    }

    .close:hover,
    .close:focus {
        color: #fff;
        text-decoration: none;
        cursor: pointer;
    }

    .auth-buttons {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        /* Или justify-content: center, если кнопки нужно расположить по центру */
        align-items: center;
    }

    .auth-buttons a {
        margin: 0 10px;
        /* Добавляем отступы между кнопками */
        height: 100px;
        text-decoration: none;
        color: #fff;
        transition: transform 0.3s ease;
        border: 1px solid gold;
    }

    .auth-buttons a img {

        height: 100%;
        object-fit: cover;
    }


    /* Адаптивность */
    @media (max-width: 768px) {
        .modal-content {
            width: 80%;
        }
    }

    @media (max-width: 480px) {
        .modal-content {
            width: 90%;
        }
    }

    @media (max-width: 384px) {
        .knopkodav {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 5px;
        }

        .header-menu {
            width: 30%;
            /* Каждая кнопка займет примерно треть строки */
            padding: 5px;
        }

        .header-menu a {
            width: 80px;
            /* Уменьшаем размеры кнопок */
            height: 80px;
        }

        .header-menu a img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
</style>
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
        <a href="https://daodes.space/dao" title="Принятие решений">
            <span class="logo_name"><img src="/img/bottom/dd.jpg" alt="Принятие решений"></span></a>
    </div>
    <div class="header-menu has-submenu" id="tasks-menu">
        <a href="https://daodes.space/tasks" title="Биржа заданий">
            <span class="logo_name"><img src="/img/bottom/tasks2.png" alt="Биржа заданий"></span></a>
    </div>
    <div class="header-menu has-submenu" id="wallet-menu">
        <a href="https://daodes.space/wallet" title="Кошелёк">
            <span><img src="/img/bottom/wallet2.png" alt="Кошелёк"></span></a>
    </div>
    <div class="header-menu" id="paper-menu">
        <a href="https://goo.su/fwRzC" title="White paper" target="_blank">
            <span><img src="/img/bottom/paper.png" alt="White paper"></span>
        </a>
    </div>
    <div class="auth-buttons">
        @if (Auth::check())
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

<script>
    // Проверка существования переменной newsId
    var newsId = typeof newsId !== 'undefined' ? newsId : null;
    const currentPath = window.location.pathname;

    // Определяем пути и подменю
    const submenus = {
        "/news": [
            { href: "/news/add", text: "Добавить новость" },
            { href: "/news/categories", text: "Управление категориями" },
        ],
        [`/page/news/${newsId}`]: [
            { href: "/news", text: "Вернуться в список новостей" },
            { href: "/news/add", text: "Добавить новость" },
            { href: "/news/categories", text: "Управление категориями" },
        ],
        "/dao": [
            { href: "/offers/add", text: "Добавить предложение" },
            { href: "offers/categories", text: "Редактировать категории" },
        ],
        [`/page/offers/${newsId}`]: [
            { href: "/dao", text: "Вернуться в список предложений" },
            { href: "/offers//add", text: "Добавить предложение" },
            { href: "/offers/categories", text: "Редактировать категории" },
        ],
        "/tasks": [
            { href: "/tasks/create", text: "Создать задание" },
            { href: "/categories/", text: "Управление категориями" },
            
        ],
        [`/tasks/${newsId}`]: [
            { href: "/tasks", text: "Список заданий" },
            { href: "/tasks/create", text: "Создать задание" },
            { href: "/categories", text: "Управление категориями" },
            
        ],
        "/wallet": [
            { href: "/wallet/add", text: "Пополнить кошелёк" },
            { href: "/wallet/edit", text: "Редактировать кошелёк" },
            { href: "/wallet/moderation", text: "Модерация кошельков" }
        ]
    };

    // Получаем элементы подменю и кнопок
    const submenuModal = document.getElementById('submenuModal');
    const submenuLinksContainer = document.getElementById('submenuLinks');
    const closeModalButton = document.getElementsByClassName('close')[0];
    const menuButtons = {
        "/news": document.getElementById('news-menu'),
        "/dao": document.getElementById('dao-menu'),
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
            if (currentPath.includes(path) || (currentPath.includes("/page/news/") && path === "/news") || (currentPath.includes("/page/offers/") && path === "/dao")) {
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



