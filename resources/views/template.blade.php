<!DOCTYPE html>
{{-- <html lang="ru"> --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- иконка сайта -->
    <link rel="icon" href="/../favicon.ico" type="image/x-icon">
    {{-- <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> --}}
    <!-- Вставляем шрифты -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&family=Montserrat:ital,wght@0,400;0,700;1,600&family=Noto+Serif:wght@400;700&display=swap"
        rel="stylesheet" />


    <!--  ! Название страницы  -->
    <title>@yield('title_page')</title>

    <!-- Подключаем скрипты -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
    <!-- смайлики значки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Подключите библиотеки Cropper.js и IPFS HTTP Client -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@43.3.1/build/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ipfs-http-client/dist/index.min.js"></script>

    {{-- <!-- Скрипт раскрытия блоков -->
    <script src="js/block.js" type="text/javascript"></script>
    <!-- кнопка вверх -->
    <script src="js/bt_top.js" type="text/javascript"></script> --}}

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- * Вставляем стили -->
    {{-- <link rel="stylesheet" href="css/main.css"> --}}
    @vite(['resources/css/app.css', 'resources/css/main.css', 'resources/js/app.js', 'resources/js/block.js', 'resources/js/bt_top.js'])
    
    <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="DAODES_Robot" data-size="large" data-auth-url="https://daodes.space/" data-request-access="write"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>

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
            font-family: 'StandardPoster';
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

    @include('partials.infotrade')

    {{-- <div class="marquee">
        <p>Ведётся доработка, не обращайте внимание на некоторые недостатки, кривости, а обо всех найденных неполадках сообщайте в телеграм @sergeniyrus</p>
    </div> --}}
    <!-- Кнопочное меню -->

    @include('menu')



    <!-- вставка всего в шаблон -->


    @yield('main')

    <!-- Подвал-->
    @include('footer')

    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
</body>

</html>
