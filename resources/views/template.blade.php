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
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script> --}}

    <!-- смайлики значки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



    <!-- Подключите библиотеки Cropper.js и IPFS HTTP Client -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@43.3.1/build/ckeditor.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/ipfs-http-client/dist/index.min.js"></script>

    {{-- <!-- Скрипт раскрытия блоков -->
    <script src="js/block.js" type="text/javascript"></script>
    <!-- кнопка вверх -->
    <script src="js/bt_top.js" type="text/javascript"></script> --}}

    {{-- <link rel="stylesheet" href="{{ asset('resources/css/main.css') }}"> --}}
    <!-- * Вставляем стили -->
    {{-- <link rel="stylesheet" href="css/main.css"> --}}
    @vite(['resources/css/app.css', 'resources/css/main.css', 'resources/js/app.js', 'resources/js/block.js', 'resources/js/bt_top.js'])

</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'StandardPoster';
            src: url('/public/fonts/StandardPoster.eot');
            src: local('☺'), url('/public/fonts/StandardPoster.woff') format('woff'), url('/public/fonts/StandardPoster.ttf') format('truetype'), url('/public/fonts/StandardPoster.svg') format('svg');
            font-weight: normal;
            font-style: normal;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: #ffffff;
            /* background-color: #b3b2d3; */
            background-image: url("https://ipfs.sweb.ru/ipfs/QmTpCH5y3AkDhVBgDLUKuTRbkKV69EEvGzzgwLhQMtsWZ3?filename=bg01.jpg");
            opacity: 1;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-size: min(max(100%, 2vw), 200%);
        }

        img {
            max-width: 100%;
        }

        /* Header */
        .header {
            width: 97%;
            height: auto;
            margin: 5px auto;
            display: flex;
        }
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
            color: #ffffff;
            animation: marquee 15s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
/* блок вход регистрация */
        .box-content {
            background-color: #000000cf;
            margin: 20px auto;
            border: 1px solid rgb(255, 255, 255);
            max-width: 80%;
            min-width: 300px;
            border-radius: 15px;
            
        }

        .imgcontainer {
            text-align: center;
            border-radius: 50%;
            
        }
        img.avatar {
            border-radius: 50%;
            border: 2px solid #fff;
            margin: 25px auto 15px auto;
        }

    </style>

    @include('partials.infotrade')

    <div class="marquee">
        <p>Сайт находится в доработке, не обращайте внимание на некоторые недостатки, кривости, а обо всех найденных неполадках сообщайте в телеграм @sergeniyrus</p>
    </div>
    <!-- Кнопочное меню -->

    @include('menu')



    <!-- вставка всего в шаблон -->


    @yield('main')

    <!-- Подвал-->
    @include('footer')


</body>

</html>
