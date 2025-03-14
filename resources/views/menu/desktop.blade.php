<style>
  .knopkodav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10px;
  width: 100%;
  box-sizing: border-box;
  flex-wrap: wrap;
  position: fixed;
}

.header-menu {
  text-align: center;
  position: relative;
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
}

.header-menu a {
  display: flex;
  align-items: center;
  padding: 5px;
  text-decoration: none;
  color: #fff;
  transition: transform 0.3s ease;
}

.auth-buttons {
  display: flex;
  gap: 10px;
}

.notifications {
  background-color: red;
  border-radius: 5px;
  font-size: clamp(12px, 1.5vw, 36px);
  cursor: pointer;
  padding: 0 2px;
}

/* Стили для кнопки подменю */
.submenu-button {
  background-color: rgba(11, 12, 24, 0.9);
  padding: 25px 0;
  color: gold;
  font-size: clamp(12px, 1.5vw, 36px);
  font-weight: bold;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s ease;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  border-radius: 10px;
  margin: 0;
  padding: 5px;
}

.submenu-button:hover {
  background-color: goldenrod;
  color: black;
}

.active-section-menu {
  line-height: 1.2;
}

.logout-icon {
  width: 1em;
  height: 1em;
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
  background-color: rgba(0, 0, 0, 0.8);
}

.modal-content {
  background-color: #2b2c2e;
  margin: 15% auto;
  padding: 20px 30px;
  border-radius: 8px;
  border: 2px solid rgb(255, 0, 255);
  max-width: 720px;
  text-align: center;
}

.modal-content a {
  display: block;
  margin: 10px 0;
  background-color: #0b0c18;
  color: #00ffff;
  text-decoration: none;
  border: 1px solid gold;
  padding: 10px;
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

.modal-content a:hover {
  background-color: #131b7c;
  border: 1px solid goldenrod;
  box-shadow: 0 0 10px 5px gold;
  color: gold;
}

.close {
  color: red;
  float: right;
  font-size: 36px;
  font-weight: bold;
  margin-top: -32px;
  margin-right: -28px;
}

.close:hover,
.close:focus {
  color: #00ffff;
  text-decoration: none;
  cursor: pointer;
}


</style>

<!-- Основное меню (видно на экранах больше 800px) -->

<div class="knopkodav">
  <div class="submenu-button" id="home-menu">
      <a href="/home" title="{{ __('menu.home') }}">
          <span class="submenu-button">{{ __('menu.home') }}</span>
      </a>
  </div>
  <div class="submenu-button  has-submenu" id="news-menu">
      <a href="/news" title="{{ __('menu.news') }}">
          <span class="submenu-button">{{ __('menu.news') }}</span></a>
  </div>
  <div class="submenu-button has-submenu">
      <a href="/chats" title="{{ __('menu.deschat') }}">
          <span class="submenu-button">{{ __('menu.deschat') }}</span></a>
  </div>
  <div class="submenu-button has-submenu" id="dao-menu">
      <a href="/offers" title="{{ __('menu.decision_making') }}">
          <span class="submenu-button">{{ __('menu.decision_making') }}</span></a>
  </div>
  <div class="submenu-button has-submenu" id="tasks-menu">
      <a href="/tasks" title="{{ __('menu.task_marketplace') }}">
          <span class="submenu-button">{{ __('menu.task_marketplace') }}</span></a>
  </div>
  <div class="submenu-button">
      <a href="/white_paper" title="{{ __('menu.white_paper') }}">
          <span class="submenu-button">{{ __('menu.white_paper') }}</span>
      </a>
  </div>
  <div class="submenu-button">
      <a href="/team" title="{{ __('menu.team') }}">
          <span  class="submenu-button">{{ __('menu.team') }}</span>
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
              <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
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
          const isClickInsideMenu = hamburgerMenu.contains(event.target) || hamburgerButton.contains(event.target);
          if (!isClickInsideMenu && hamburgerMenu.style.display === 'flex') {
              hamburgerMenu.style.display = 'none'; // Скрыть меню
          }
      });

      // Проверка существования переменной newsId
      var newsId = typeof newsId !== 'undefined' ? newsId : null;
      const currentPath = window.location.pathname;
      console.log('Current Path:', currentPath); // Отладочная информация

      // Определение путей и подменю
      const submenus = {
          "/news": [{
                  href: "/news",
                  text: "Вернуться к новостям"
              },
              @auth
              @if (Auth::user()->access_level >= 3)
                  {
                      href: "/news/add",
                      text: "Добавить новость"
                  }, {
                      href: "/newscategories",
                      text: "Управление категориями"
                  },
              @endif
          @endauth
          ],
          "/offers": [{
                  href: "/offers",
                  text: "Вернуться к предложениям"
              },
              @auth
              @if (Auth::user()->access_level >= 3)
                  {
                      href: "/offers/add",
                      text: "Добавить предложение"
                  }, {
                      href: "/offerscategories",
                      text: "Редактировать категории"
                  },
              @endif
          @endauth
          ],
          "/tasks": [{
                  href: "/tasks",
                  text: "Вернуться к задачам"
              },
              @auth
              @if (Auth::user()->access_level >= 3)
                  {
                      href: "/addtask",
                      text: "Создать задачу"
                  }, {
                      href: "/taskscategories",
                      text: "Редактировать категории"
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
              if (currentPath.includes(path)) {
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
                      (submenus[path] || []).forEach(function(link) {
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
      updateMenuButton();
  });
</script>