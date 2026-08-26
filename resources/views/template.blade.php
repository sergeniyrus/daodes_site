<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title_page')</title>

<script>
    // Скрываем страницу до применения стилей
    document.documentElement.classList.add('no-js');
    document.documentElement.style.visibility = 'hidden';
    
    document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.style.visibility = 'visible';
    });
    
    // Страховка: если что-то пошло не так, показываем через 3 секунды
    setTimeout(function() {
        document.documentElement.style.visibility = 'visible';
    }, 3000);
</script>


    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Шрифты --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&family=Montserrat:ital,wght@0,400;0,700;1,600&family=Noto+Serif:wght@400;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Cropper.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css">

    {{-- Slick Carousel --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- CDN-скрипты с defer, чтобы не блокировать рендеринг --}}
    <script defer src="https://cdn.jsdelivr.net/npm/tweetnacl/nacl.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/tweetnacl-util/nacl-util.min.js"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@43.3.1/build/ckeditor.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/ipfs-http-client/dist/index.min.js"></script>

    {{-- Vite: все ресурсы одним вызовом --}}
    @vite([
        'resources/css/main.css',
        'resources/css/ckeditor.css',
        'resources/css/organizations.css',
        'resources/css/chat_index.css',
    'resources/css/chat_show.css',
        'resources/js/bt_top.js'
    ])

    {{-- Стили из дочерних шаблонов --}}
    @stack('styles')

</head>

<body>
    @include('menu')
    @yield('main')
    @include('footer')
    @include('components.cookie-consent')

    {{-- Скрипты из дочерних шаблонов (в конце body — правильно!) --}}
    @stack('scripts')

    {{-- Онлайн-статус --}}
    @if (Auth::check())
        <script>
            (function() {
                'use strict';
                const ONLINE_ENDPOINT = "{{ route('user.online') }}";
                let heartbeatInterval = null;

                function sendOnline() {
                    fetch(ONLINE_ENDPOINT, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    }).catch(() => {});
                }

                function startHeartbeat() {
                    sendOnline();
                    heartbeatInterval = setInterval(sendOnline, 25_000);
                }

                function stopHeartbeat() {
                    if (heartbeatInterval) {
                        clearInterval(heartbeatInterval);
                        heartbeatInterval = null;
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', startHeartbeat);
                } else {
                    startHeartbeat();
                }

                document.addEventListener('visibilitychange', () => {
                    document.hidden ? stopHeartbeat() : startHeartbeat();
                });
            })();
        </script>
    @endif

    {{-- Проверка ключей --}}
    @if (auth()->check())
        <script>
            if (document.getElementById('setup-keys-page')) {
                console.log("[KEYCHECK] На странице setup-keys — пропуск.");
            } else {
                document.addEventListener("DOMContentLoaded", () => {
                    const CURRENT_USER_ID = {{ auth()->id() }};
                    const privateKey = localStorage.getItem(`userPrivateKey_${CURRENT_USER_ID}`);

                    if (!privateKey) {
                        sessionStorage.setItem("url.intended", location.href);
                        window.location.href = "/setup-keys?new_device=1";
                        return;
                    }

                    fetch("{{ route('profile.has-public-key') }}", {
                            credentials: "include",
                            headers: { "Accept": "application/json" }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.has_public_key) {
                                sessionStorage.setItem("url.intended", location.href);
                                window.location.href = "/setup-keys";
                            }
                        })
                        .catch(err => console.error("[KEYCHECK ERROR]", err));
                });
            }
        </script>
    @endif
</body>
</html>