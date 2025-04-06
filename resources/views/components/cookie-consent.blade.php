<style>
    /* Основные стили для баннера */
    #cookieConsentBanner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(31, 41, 55, 0.95);
        color: white;
        padding: 16px;
        z-index: 1000;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
    }
    
    .cookie-container {
        width: 70%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    
    @media (min-width: 768px) {
        .cookie-container {
            flex-direction: row;
            justify-content: space-between;
        }
    }
    
    .cookie-buttons {
        display: flex;
        gap: 8px;
    }
    
    /* Стили для модального окна (тёмная тема) */
    #cookieModal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.7);
        display: none;
        z-index: 1050;
        overflow-y: auto;
        padding: 20px;
    }
    
    #cookieModal.modal-open {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: #1f2937;
        border-radius: 8px;
        width: 100%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        color: #f3f4f6;
    }
    
    .modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid #374151;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #374151;
        text-align: right;
    }
    
    /* Стили кнопок */
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #9ca3af;
        cursor: pointer;
    }
    
    .btn-learn {
        background: none;
        border: none;
        color: #39FF14;
        text-decoration: none;
        font-family: inherit;
        font-size: inherit;
        cursor: pointer;
        padding: 0;
        margin: 0;
        text-align: inherit;
    }
    
    .btn-learn:hover {
        text-decoration: underline;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
        border: none;
    }
    
    .btn-secondary {
        background-color: #4b5563;
        color: white;
        border: none;
    }
    
    /* Иконки */
    .icon {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        vertical-align: middle;
    }
    
    /* Текст и заголовки */
    .text-gray-500 {
        color: #9ca3af;
    }
    
    .text-gray-700 {
        color: #d1d5db;
    }
    
    .text-blue-500 {
        color: #3b82f6;
    }
    
    /* Кастомный скроллбар */
    .modal-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-content::-webkit-scrollbar-track {
        background: #374151;
        border-radius: 4px;
    }
    
    .modal-content::-webkit-scrollbar-thumb {
        background: #6b7280;
        border-radius: 4px;
    }
    
    /* Отключение прокрутки body при открытом модальном окне */
    body.modal-open {
        overflow: hidden;
    }
</style>
</head>
<body>
<!-- Cookie Consent Banner -->
<div id="cookieConsentBanner" @if($checkLocalStorage ?? false) data-check-storage="true" @endif>
    <div class="cookie-container">
        <p class="text-sm md:text-base">
            {{ __('cookie.cookies_definition') }}
            <button onclick="openCookieModal()" class="btn btn-learn">
                {{ __('cookie.learn more') }}
            </button>
        </p>
        <div class="cookie-buttons">
            <form method="POST" action="{{ route('cookies.reject') }}" id="rejectForm">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    {{ __('Reject') }}
                </button>
            </form>
            <form method="POST" action="{{ route('cookies.accept') }}" id="acceptForm">
                @csrf
                <button type="submit" class="btn btn-primary">
                    {{ __('Accept') }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для политики cookies -->
<div id="cookieModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('cookie.title') }}</h3>
            <button onclick="closeCookieModal()" class="btn-close">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <div class="mb-8">
                <h4 class="text-xl font-semibold mb-3">{{ __('cookie.what_are_cookies') }}</h4>
                <p class="text-gray-700 mb-4">{{ __('cookie.cookies_definition') }}</p>
            </div>
            
            <div class="mb-8">
                <h4 class="text-xl font-semibold mb-3">{{ __('cookie.types_title') }}</h4>
                <ul class="space-y-3">
                    @foreach(__('cookie.types') as $type => $description)
                    <li class="flex items-start">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700">{{ $description }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="mb-8">
                <h4 class="text-xl font-semibold mb-3">{{ __('cookie.manage_title') }}</h4>
                <p class="text-gray-700 mb-4">{{ __('cookie.manage_text') }}</p>
                <div class="bg-gray-800 p-4 rounded-md">
                    <h5 class="font-medium mb-2">{{ __('Browser instructions') }}:</h5>
                    <ul class="list-disc pl-5 space-y-1 text-gray-400">
                        <li><a href="https://support.google.com/chrome/answer/95647" target="_blank">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences" target="_blank">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" target="_blank">Safari</a></li>
                        <li><a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank">Microsoft Edge</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mb-8">
                <h4 class="text-xl font-semibold mb-3">{{ __('cookie.changes_title') }}</h4>
                <p class="text-gray-700">{{ __('cookie.changes_text') }}</p>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeCookieModal()" class="btn btn-secondary">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>

<script>
    // Проверяем выбор при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        // Проверка localStorage
        if (localStorage.getItem('cookie_choice')) {
            document.getElementById('cookieConsentBanner').style.display = 'none';
        }
        
        // Проверка атрибута data-check-storage
        if (document.getElementById('cookieConsentBanner').dataset.checkStorage) {
            if (localStorage.getItem('cookie_choice')) {
                document.getElementById('cookieConsentBanner').style.display = 'none';
            }
        }
        
        // Настройка обработчиков форм
        setupCookieForms();
        
        // Обработчики модального окна
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeCookieModal();
        });
        
        document.getElementById('cookieModal').addEventListener('click', function(e) {
            if (e.target === this) closeCookieModal();
        });
    });

    // Обработка форм через AJAX с сохранением в localStorage
    function setupCookieForms() {
        document.querySelectorAll('form[action*="cookies/"]').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    });
                    
                    if (response.ok) {
                        // 1. Скрываем баннер
                        document.getElementById('cookieConsentBanner').style.display = 'none';
                        
                        // 2. Сохраняем выбор в localStorage
                        const choice = this.action.includes('accept') ? 'accepted' : 'rejected';
                        localStorage.setItem('cookie_choice', choice);
                        
                        // 3. Синхронизация между вкладками
                        window.dispatchEvent(new Event('storage'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    }
    
    // Синхронизация между вкладками
    window.addEventListener('storage', function(e) {
        if (e.key === 'cookie_choice') {
            document.getElementById('cookieConsentBanner').style.display = 'none';
        }
    });

    // Функции модального окна
    function openCookieModal() {
        document.getElementById('cookieModal').classList.add('modal-open');
        document.body.classList.add('modal-open');
    }
    
    function closeCookieModal() {
        document.getElementById('cookieModal').classList.remove('modal-open');
        document.body.classList.remove('modal-open');
    }
</script>