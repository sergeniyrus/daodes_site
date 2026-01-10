<!DOCTYPE html>
{{-- <html lang="ru"> --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"> <!-- Устанавливает кодировку символов на UTF-8 -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-public-key" content="{{ auth()->user()->public_key ?? '' }}">
    <meta name="user-id" content="{{ auth()->check() ? auth()->id() : '' }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Настраивает масштабирование страницы для мобильных устройств -->

    <script src="https://cdn.jsdelivr.net/npm/tweetnacl/nacl.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tweetnacl-util/nacl-util.min.js"></script>

    <!-- иконка сайта -->
    <link rel="icon" href="/../favicon.ico" type="image/x-icon"> <!-- Подключает иконку сайта (favicon) -->

    <!-- Вставляем шрифты -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <!-- Подключает шрифты Google Fonts для ускорения загрузки -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&family=Montserrat:ital,wght@0,400;0,700;1,600&family=Noto+Serif:wght@400;700&display=swap"
        rel="stylesheet" /> <!-- Подключает конкретные шрифты из Google Fonts -->

    <!--  ! Название страницы  -->
    <title>@yield('title_page')</title>
    <!-- Устанавливает заголовок страницы, который будет определен в дочернем шаблоне -->

    <!-- Подключаем скрипты -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Подключает библиотеку jQuery -->
    <!-- смайлики значки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Подключает иконки Font Awesome -->
    <!-- Подключите библиотеки Cropper.js и IPFS HTTP Client -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css">
    <!-- Подключает библиотеку Cropper.js для обрезки изображений -->
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@43.3.1/build/ckeditor.js"></script> <!-- Подключает редактор CKEditor -->
    <script src="https://cdn.jsdelivr.net/npm/ipfs-http-client/dist/index.min.js"></script> <!-- Подключает клиент IPFS для работы с децентрализованным хранилищем -->



    {{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}
    @vite(['resources/css/main.css'])
    @vite(['resources/css/ckeditor.css'])
    <script src="{{ asset('js/bt_top.js') }} " type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <!-- Подключает стили для карусели Slick -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    {{-- <!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();
    for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
    k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
 
    ym(100649778, "init", {
         clickmap:true,
         trackLinks:true,
         accurateTrackBounce:true
    });
 </script>
 <noscript><div><img src="https://mc.yandex.ru/watch/100649778" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
 <!-- /Yandex.Metrika counter --> --}}
</head>
@yield('scripts')

<body>
    <style>
        /*бегущая строка */
        .marquee {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .marquee p {
            display: inline-block;
            font-size: 24px;
            color: gold;
            animation: marquee 20s linear infinite;
            /* font-family: 'StandardPoster'; */
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid gold;
        }
    </style>
    {{-- @include('partials.infotrade') --}}
    @include('menu')
    <!-- вставка всего в шаблон -->
    @yield('main')
    <!-- Подвал-->
    @include('footer')
    @include('components.cookie-consent')

    {{-- Скрипт определения онлайн --}}
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content') || ''
                        }
                    }).catch(() => {
                        // Игнорируем ошибки — не критично
                    });
                }

                function startHeartbeat() {
                    sendOnline(); // сразу
                    heartbeatInterval = setInterval(sendOnline, 25_000);
                }

                function stopHeartbeat() {
                    if (heartbeatInterval) {
                        clearInterval(heartbeatInterval);
                        heartbeatInterval = null;
                    }
                }

                // Запуск при загрузке
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', startHeartbeat);
                } else {
                    startHeartbeat();
                }

                // Остановка при сворачивании/переключении вкладки
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        stopHeartbeat();
                    } else {
                        startHeartbeat();
                    }
                });

            })();
        </script>
    @endif


  {{-- Проверка ключей — НЕ ЗАПУСКАТЬ на страницах setup-keys --}}
@if (auth()->check())
    <script>
        // 🔒 Не запускать проверку, если это страница ввода сид-фразы
        if (document.getElementById('setup-keys-page')) {
            console.log("[KEYCHECK] На странице setup-keys — пропуск.");
        } else {
            document.addEventListener("DOMContentLoaded", () => {
                console.log("%c[KEYCHECK] Запуск проверки ключей...", "color: gold");

                const CURRENT_USER_ID = {{ auth()->id() }};
                const privateKey = localStorage.getItem(`userPrivateKey_${CURRENT_USER_ID}`);

                if (!privateKey) {
                    console.warn("%c[KEYCHECK] Приватный ключ отсутствует → редирект", "color: orange; font-weight: bold;");
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
                .catch(err => {
                    console.error("[KEYCHECK ERROR]", err);
                });
            });
        }
    </script>

@endif




</body>

</html>
