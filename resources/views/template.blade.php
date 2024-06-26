<!DOCTYPE html>
{{-- <html lang="ru"> --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- иконка сайта -->
    <link rel="icon" href="/../favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <!-- Вставляем шрифты -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&family=Montserrat:ital,wght@0,400;0,700;1,600&family=Noto+Serif:wght@400;700&display=swap"
        rel="stylesheet" />

    <!-- * Вставляем стили -->
    @vite(['resources/css/app.css', 'resources/css/main.css', 'resources/js/app.js'])
    <link href="/style/main.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}
    <link href="/style/const.css" rel="stylesheet" />

    <!--  ! Название страницы  -->
    <title>@yield('title_page')</title>

    <!-- Подключаем скрипты -->
        
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>  

<!-- Подключите библиотеки Cropper.js и IPFS HTTP Client -->
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet">
<script src="https://unpkg.com/cropperjs"></script>
<script src="https://cdn.jsdelivr.net/npm/ipfs-http-client/dist/index.min.js"></script>


    <!-- кнопка вверх -->
    <script src="/js/bt_top.js" type="text/javascript"></script>
    
    <!-- Скрипт раскрытия блоков -->
    <script src="/js/block.js" type="text/javascript"></script>
    
</head>

<body>
    <!--Голова -->
    
        @include('layouts.navigation')
        <div class="header">
            <!-- блок с логотипом -->
            <div class="header-logo">
                <a href="/"  ><img src="/img/main/daodes.jpg" alt="" class="border border-solid border-t-red-50 rounded-2xl" /></a>
            </div>                
            <!-- описание сайта рекламный слоган можно сделать динамичный вывод -->
            <div class="header-title">
                <span class="logo_name">DAO DES</span>
            </div>
        </div>     
    @include('partials.infotrade')
    <!-- вставка всего в шаблон -->
    @yield('main')

    <!-- Подвал-->
    <footer>
        <div class="footer">
            <div class="icons">
                <div class="icons-img">
                    <a href="https://t.me/des_info_chat"><img src="/img/icons_ss/Telegram_white.svg"
                            alt="" /></a>
                </div>
                <div class="icons-img">
                    <a href="https://vk.com/daodes_space"><img src="/img/icons_ss/VK_white.svg" alt="" /></a>
                </div>
                <div class="icons-img">
                    <a href="https://www.youtube.com/channel/UCukOsQwfyqApUuFX8UtisTg"><img
                            src="/img/icons_ss/Youtube_white.svg" alt="" /></a>
                </div>
            </div>
            <div class="copy">
                <h4>DAO DES 2024</h4>
            </div>
        </div>
        <a href="#" class="scrollup">Наверх</a>
    </footer>

</body>

</html>
