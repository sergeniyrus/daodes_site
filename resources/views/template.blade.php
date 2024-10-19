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
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <!-- смайлики значки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



    <!-- Подключите библиотеки Cropper.js и IPFS HTTP Client -->
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet">
    <script src="https://unpkg.com/cropperjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/ipfs-http-client/dist/index.min.js"></script>

    {{-- <!-- Скрипт раскрытия блоков -->
    <script src="js/block.js" type="text/javascript"></script>
    <!-- кнопка вверх -->
    <script src="js/bt_top.js" type="text/javascript"></script> --}}

    {{-- <link rel="stylesheet" href="{{ asset('resources/css/main.css') }}"> --}}
    <!-- * Вставляем стили -->
    {{-- <link rel="stylesheet" href="css/main.css"> --}}
    @vite(['resources/css/app.css', 'resources/css/main.css', 'resources/js/app.js', 'resources/js/block.js', 'resources/js/bt_top.js'])
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
            background-color: #252441;
             background-image: url("https://ipfs.sweb.ru/ipfs/QmTpCH5y3AkDhVBgDLUKuTRbkKV69EEvGzzgwLhQMtsWZ3?filename=bg01.jpg");
  opacity: 0.75;
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

        @media (max-width: 639px) {
            .header {
                display: none;
            }
        }

        .header-logo {
            width: 30%;
            height: auto;
            display: inline-flex;
        }

        .header-title {
            width: 70%;
            height: auto;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background-color: #0b0c18
        }

        .logo_name {
            font-family: StandardPoster;
            font-size: min(max(100%, 5vw), 700%);
            font-weight: 400;
            align-content: center;
            -webkit-text-stroke: 1px #000000c2;
            vertical-align: middle;
            transition: all 0.3s ease;
            letter-spacing: 30px;
            color: #ffffff;
            text-shadow: #9c9c9c 0.006em 0.006em 0.007em, #9c9c9c 1px 1px 1px,
                #9c9c9c 2px 2px 1px, #9c9c9c 3px 3px 1px, #9c9c9c 4px 4px 1px,
                #9c9c9c 5px 5px 1px, #9c9c9c 6px 6px 1px, #9c9c9c 1px 7px 1px,
                rgba(16, 16, 16, 0.4) 1px 18px 6px, rgba(16, 16, 16, 0.2) 1px 22px 10px,
                rgba(16, 16, 16, 0.2) 1px 26px 35px, rgba(16, 16, 16, 0.4) 1px 30px 65px;
        }

        .header-2 {
            display: none;
            width: 50%;
            height: auto;
            align-items: center;
            justify-content: space-between;
        }

        .header-logo-2 {
            width: 55px;
            height: auto;
            align-items: start;
        }

        .header-title-2 {
            height: auto;
            margin-left: 10px;
            justify-content: center;
            align-items: center;
        }

        .logo_name-2 {
            font-family: StandardPoster;
            font-size: min(max(70%, 3vw), 170%);
            font-weight: 400;
            align-content: center;
            -webkit-text-stroke: 1px #000000c2;
            vertical-align: middle;
            transition: all 0.3s ease;
            letter-spacing: 5px;
            color: #ffffff;
        }

        .admin_menu {
            display: flex;
            justify-content: center;
            gap: 4vw;
        }

        @media only screen and (max-width: 639px) {
            .header-2 {
                display: flex;
            }

            .scrollup {
                width: 25px;
                height: 26px;
                bottom: 10px;
                right: 10px;
            }
        }

        /* Админ страницы */
        .new_post {
            width: 90%;
            height: auto;
            margin-top: 20px;
            padding-bottom: 20px;
            color: rgb(0, 0, 0);
            background-color: rgba(30, 32, 30, 0.753);
            font-size: 20px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #fff;
            border-radius: 30px;
            text-align: center;
            vertical-align: auto;
            display: flex;
            flex-direction: column;
        }

        .dark_text {
            color: #141414;
        }

        .name_str {
            color: #fff;
            margin: 20px auto 20px auto;
        }

        .verh {
            display: flex;
            gap: 10px;
            margin: 0 auto;
            justify-content: center;
            align-self: center;
            flex-wrap: wrap;
        }

        .redactor {
            width: auto;
            height: auto;
            margin: 20px 50px;
        }

        .varn {
            margin-top: 2vw;
            font-size: 16px;
            color: orangered;
        }

        .fp {
            display: inline;
            color: #fff;
            margin: 0 auto;
        }

        .header-menu {
            margin: 1% auto 0 auto;
            width: 97%;
            height: auto;
        }

        .header-menu-cont-bord {
            background: rgba(40, 61, 142, 0.78);
            border: 1px solid rgb(187, 246, 242);
            box-shadow: 10px -10px 40px rgb(229, 202, 233);
            border-radius: 20px;
        }

        .header-menu-cont {
            list-style: none;
            display: flex;
            justify-content: space-around;
        }

        .header-menu a:link,
        a:visited {
            font-family: "Crimson Text", serif;
            color: #ffffff;
            text-decoration: none;
            font-size: min(max(70%, 2vw), 200%);
            font-weight: bold;
        }

        .header-menu a:hover {
            color: #dae931;
        }

        .link-name,
        .link-name a {
            margin-right: 0.6em;
            font-family: "Crimson Text", serif;
            color: #ffffff;
            text-decoration: none;
            font-size: min(max(70%, 5vw), 200%);
            font-weight: bold;
            align-items: center;
        }

        .link-hamburg {
            margin-top: 0.6em;
            margin-right: 0.6em;
            font-size: min(max(100%, 7vw), 200%);
        }

        .link-name a:hover {
            color: #dae931;
        }

        .menu-hamburg {
            font-family: "Crimson Text", serif;
            color: #ffffff85;
            text-decoration: none;
            font-size: min(max(80%, 2vw), 100%);
            font-weight: bold;
            margin: 5px 0 0 15px;
        }

        /* Бегущая строка */
        .run-word {
            color: #ffffff;
            font-family: Noto Serif;
            margin-top: 0px;
            background: rgba(0, 0, 0, 0.408);
            border-top: 1px solid #ffffff;
            border-bottom: 1px solid #ffffff;
            box-shadow: 5px 5px 20px rgba(229, 202, 233, 0.78);
        }

        /* main */
        .main-content {
            width: 100%;
            margin: 35px auto 10px auto;
            display: flex;
            justify-content: center;
            align-content: center;
            flex-direction: column;
            align-items: center;
        }

        .windows {
            max-width: 80%;
            min-width: 380px;
            height: 100%;
            background-color: #1f234bce;
            border: 1px solid rgb(255, 255, 255);
            border-radius: 20px;
            margin: 0px auto 20px auto;
            padding: 0 10px 0 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Вертикальное выравнивание */
            align-items: center;
            /* Горизонтальное выравнивание */
        }

        .windows a {
            text-decoration: none;
            font-size: min(max(80%, 1.4vw), 85%);
        }

        .windows-title a {
            color: #ffffff;
        }

        .windows-title a:hover {
            color: #dae931;
        }

        .windows-new-title a {
            color: black;
        }

        .windows-new-title a:hover {
            color: rgb(255, 0, 0);
        }

        .left {
            box-shadow: -10px -10px 10px rgba(229, 202, 233, 0.75);
            margin-left: auto;
            margin-right: 20px;
        }

        .right {
            box-shadow: 10px -10px 10px rgba(229, 202, 233, 0.75);
            margin-right: auto;
            margin-left: 20px;
        }

        .windows-title {
            text-align: center;
            margin: 10px;
            font-size: min(max(70%, 2vw), 100%);
            font-weight: 700;
        }

        #details0,
        #details1,
        #details2,
        #details3,
        #details4,
        #details5,
        #details6 {
            display: none;
        }

        #state0,
        #state1,
        #state2,
        #state3,
        #state4,
        #state5 {
            font-size: min(max(70%, 2vw), 100%);
        }

        /* окно мини новости */
        .windows-new {
            background: rgb(228, 225, 225);
            border-radius: 15px;
            width: 100%;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .windows-string {
            display: block;
            text-align: center;
        }

        .windows-new-title {
            margin: 5px;
            text-align: center;
            color: #fb048c;
            font-size: min(max(70%, 5vw), 90%);
            font-weight: 700;
        }

        .windows-new-images {
            margin: 10px;
            width: 80%;
            max-width: 300px;
            object-fit: cover;
        }

        .windows-new-text {
            margin: 10px;
            color: black;
            font-size: min(max(50%, 12vw), 80%);
            text-align: start;
            margin-bottom: 10px;
        }

        /* footer */
        .footer {
            max-width: 300px;
            margin: 50px auto 0 auto;
            text-align: center;
            height: 150px;
        }

        .icons {
            display: flex;
            justify-content: center;
            align-content: center;
            justify-items: center;
            margin: 0 auto;
        }

        .icons-img {
            width: 100%;
            margin-left: 10px;
            margin-right: 10px;
        }

        .copy {
            margin-top: 30px;
            bottom: 0;
        }

        .scrollup {
            width: 50px;
            height: 52px;
            opacity: 0.7;
            position: fixed;
            bottom: 50px;
            right: 50px;
            text-indent: -9999px;
            background: url("https://ipfs.sweb.ru/ipfs/QmPHW1dLQUytt9rzxAJbpeJXs67Lu9BzK5m3k8hAZh4qr3/public/main/b_top.png") no-repeat;
        }

        .item {
            width: 80%;
            color: #fff;
            margin: 5vw auto;
            padding: 5px;
        }

        .item img {
            width: 100%;
        }

        .item-2 {
            width: 80%;
            height: auto;
            padding: 5px;
        }

        .item-3 {
            color: #fff;
        }

        .shadow {
            position: relative;
            background: linear-gradient(#000, #262626);
        }

        .shadow:before,
        .shadow:after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            bottom: -2px;
            left: -2px;
            background: linear-gradient(45deg, #fb0094, #0000ff, #00ff00, #ffff00, #ff0000, #fb0094, #0000ff, #00ff00, #ffff00, #ff0000);
            background-size: 500%;
            z-index: -1;
            animation: animate 30s ease infinite;
        }

        .shadow:after {
            filter: blur(25px);
        }

        @keyframes animate {
            0% {
                background-position: 0 0;
            }

            50% {
                background-position: 300% 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        .reg_home_btn {
            display: inline-block;
            padding: 15px 35px;
            background: linear-gradient(150deg, rgba(51, 106, 206, 1) 0%, rgba(57, 114, 213, 1) 50%, rgba(35, 90, 191, 1) 50%, rgba(1, 60, 163, 1) 100%);
            font-size: min(max(150%, 12vw), 180%);
            color: #fff;
            border-radius: 35px;
        }

        .reg_home_btn:hover {
            background: linear-gradient(150deg, rgba(1, 60, 163, 1) 0%, rgba(35, 90, 191, 1) 50%, rgba(57, 114, 213, 1) 50%, rgba(51, 106, 206, 1) 100%);
        }

        .good {
            min-width: 60%;
            max-width: 80%;
            height: auto;
            margin: 50px auto;
            color: rgb(255, 255, 255);
            background-color: rgba(30, 32, 30, 0.753);
            font-size: min(max(50%, 7vw), 100%);
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            border: 1px solid #fff;
            border-radius: 30px;
            text-align: center;
            vertical-align: auto;
        }

        .good a:link {
            color: #036efa;
        }

        .good a:hover {
            color: #05ff19;
        }

        .graf {
            display: inline-block;
            width: 90%;
            border: 1px #eeff00 solid;
            height: 16px;
            margin-top: 5px;
        }

        .like_comm img {
            width: 24px;
            height: 24px;
            line-height: 16px;
        }

        .like_comm {
            display: table;
            line-height: 40px;
            vertical-align: middle;
        }

        .left_td {
            width: 20%;
        }

        /* из sign */
        .login {
            width: 100px;
            height: auto;
            text-align: right;
            margin-right: 20px;
            padding: 5px;
            background-color: #0b0c187c;
            border: 1px solid #fff;
            float: right;
            border-radius: 8px;
        }

        .login a {
            font-size: min(max(50%, 2vw), 80%);
            font-family: "Times New Roman", Times, serif;
            color: rgb(20, 181, 255);
            text-decoration: none;
        }

        .login a:hover {
            color: rgb(115, 255, 0);
        }

        .regwin {
            background-color: #0b0c18;
            border: 1px solid #fff;
            border-radius: 20px;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: min(max(50%, 1.5vw), 80%);
        }

        .regwin a {
            color: #f4eb1d;
            text-decoration: none;
        }

        .regwin a:hover {
            color: rgb(115, 255, 0);
        }

        .mini-win {
            background-color: #0b0c18;
            border: 1px solid #fff;
            border-radius: 10px;
            padding: 20px;
        }

        .tablo {
            margin: 20px auto 20px auto;
        }

        /*  окно для входа регистрации сброса пароля */
        .imgcontainer {
            text-align: center;
        }

        img.avatar {
            border-radius: 50%;
            border: 2px solid #fff;
            margin: 25px auto 15px auto;
        }

        .container {
            padding: 15px;
        }

        .modal-content {
            background-color: #000000cf;
            margin: 20px auto 0 auto;
            border: 1px solid rgb(255, 255, 255);
            width: 25%;
            min-width: 300px;
            border-radius: 15px;
        }

        .in_form {
            margin: 0 auto 0 auto;
        }

        input.my_form-control {
            display: block;
            font-weight: 500;
            font-size: 14px;
            color: #4B5563;
            background-color: rgba(17, 24, 39, 1);
            border-radius: 15px;
        }

        /* blog */
        .main-blog {
            width: 100%;
            height: 100%;
            color: rgb(255, 255, 255);
            margin: 25px auto 1px auto;
        }

        .flex-container {
            width: 90%;
            height: 100%;
            background-color: #0b0c18ce;
            border: 1px solid transparent;
            border-color: #fff;
            margin: 1px auto 50px auto;
            border-bottom-right-radius: 100% 100px;
            border-bottom-left-radius: 100% 100px;
            display: flex;
        }

        .item-1 {
            width: 20%;
            height: auto;
            vertical-align: top;
            padding: 10px;
        }

        .item-2 {
            width: 60%;
            vertical-align: top;
        }

        .item-3 {
            width: 20%;
            font-size: min(max(50%, 1vw), 80%);
            font-family: Georgia, "Times New Roman", Times, serif;
            text-decoration: none;
            text-align: justify;
        }


        .opc_head {
            color: rgb(182, 117, 243);
        }

        span .opc_head {
            float: right;
        }

        .opc {
            vertical-align: top;
            font-size: min(max(60%, 1vw), 90%);
            font-family: Georgia, "Times New Roman", Times, serif;
            text-decoration: none;
            text-align: justify;
            color: #f1d11a;
            width: 100%;
        }

        .opc_zag {
            text-align: center;
            font-size: min(max(60%, 1vw), 100%);
            color: rgb(0, 255, 255);
        }

        .img_post {
            width: 100%;
            height: auto;
            border: 1px solid #b4b4b4;
            background-size: cover;
        }

        .title_post {
            width: 100%;
            height: auto;
            font-size: min(max(80%, 2vw), 150%);
            text-align: center;
        }

        .hr_title {
            width: 90%;
            margin: 5px 5% 0 5%;
        }

        .title_post a:active,
        .title_post a:hover {
            text-decoration: none;
            color: #0faa1c;
        }

        .title_post a {
            text-decoration: none;
            color: #f1d11a;
        }

        a.all_post:active,
        a.all_post:hover {
            text-decoration: none;
            color: #2427df;
        }

        a.all_post {
            text-decoration: none;
            color: #2427df;
        }

        .category_post {
            width: 100%;
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: min(max(80%, 3vw), 160%);
            color: rgb(0, 68, 255);
            text-align: center;
        }

        .text_post {
            width: 100%;
            height: auto;
            font-size: min(max(80%, 2vw), 120%);
            padding: 1px 5px 1px 0;
            text-align: justify;
        }

        .text_post a {
            color: #036efa;
        }

        .footer-post {
            justify-self: center;
        }

        .infa_post {
            display: flex;
            /* height: 40px; */
            line-height: 40px;
            /* justify-items: center; */
            align-items: center;
            justify-content: space-between;
            margin: 100px 0 15px 0;
            font-size: min(max(40%, 1vw), 80%);

        }

        .viewes_post,
        .comment_post,
        .data_post {
            width: auto;

        }

        .author_post {
            width: auto;
        }

        .rown {
            height: auto;
            justify-content: space-between;
        }

        .left_box {
            width: 100%;
            font-size: min(max(50%, 1.5vw), 90%);
            color: aqua;
        }


        /* блок формы голосования */
        .vote_box {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-vote {
            width: 100%;
        }

        .tbr {
            border: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        .vote_ratio {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .img_vote {
            position: relative;
            margin: 0 10px;
        }

        .img_vote input[type="radio"] {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .img_vote img {
            display: block;
            width: 100px;
            /* Ширина картинки */
            height: auto;
            border: 3px solid transparent;
            /* Начальный цвет рамки */
            transition: border-color 0.3s;
        }

        .img_vote input[type="radio"]:checked+img {
            border-color: #f00;
            /* Цвет рамки для выбранного элемента */
        }

        .img_bt {
            width: 100px;
            /* Ширина кнопки */
            height: auto;
        }

        .circle {
            border-radius: 50px;
        }


        .tbrv {
            width: 95%;
            margin: 0 5px 0 5px;
        }

        .right_td {
            text-align: right;
        }

        legend {
            margin: 0 auto;
        }

        input.ratio_l {
            position: relative;
            top: -62px;
            right: 40px;
        }

        input.ratio_r {
            position: relative;
            top: -35px;
            left: 40px;
        }

        .img_bt circle {
            border-radius: 15px;
        }


        .sidbar {
            width: 90%;
            height: max-content;
            background-color: #0b0c18ce;
            border: 1px solid #fff;
            margin-top: 1vh;
            padding: 5px;
            border-radius: 15px 0px 15px 5px;
            text-align: center;
        }

        .category_menu {
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: min(max(80%, 4vw), 160%);
            text-align: center;
            margin-left: 1vh;
            border-bottom: 1px solid #0fc51e;
            color: #0fc51e;
        }

        .tab_category {
            margin-top: 1vh;
            text-align: justify;
            font-size: min(max(80%, 1vw), 160%);
        }

        a#tab_category:active,
        a#tab_category:hover {
            text-decoration: none;
            color: #629de0;
        }

        a#tab_category {
            text-decoration: none;
            color: #05ff19;
        }

        .comment {
            width: 70%;
            height: 100%;
            background-color: #0b0c18ce;
            border: 1px solid transparent;
            border-color: #fff;
            margin: 10px auto 2px auto;
            border-top-left-radius: 100% 100px;
            border-top-right-radius: 100% 100px;
            color: #fff;
        }

        .post_com {
            width: 70%;
            margin: 20px auto 0px auto;
            /* Центрирование блока по горизонтали */
            border: 1px solid transparent;
            border-color: #fff;
            /* Цвет границы */
            border-radius: 20px;
            /* Закругленные углы */
            padding: 10px;
            /* Внутренние отступы */
            font-size: min(max(60%, 1.5vw), 80%);
            /* Размер шрифта */

            text-align: center;
            /* Центрирование текста внутри блока */
        }

        .submit-button {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            /* Градиентный фон */
            border: none;
            /* Убираем стандартную рамку */
            color: white;
            /* Цвет текста */
            padding: 10px 20px;
            /* Отступы внутри кнопки */
            border-radius: 25px;
            /* Закругленные углы */
            font-size: 16px;
            /* Размер шрифта */
            cursor: pointer;
            /* Курсор в виде руки */
            transition: background 0.3s, transform 0.2s;
            /* Плавный переход */
        }

        .submit-button:hover {
            background: linear-gradient(135deg, #5a6efb, #8b6fd8);
            /* Изменение фона при наведении */
            transform: scale(1.05);
            /* Увеличение кнопки при наведении */
        }

        .submit-button:focus {
            outline: none;
            /* Убираем стандартный контур */
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
            /* Добавляем тень при фокусе */
        }


        .author-info {
            display: flex;
            justify-content: center;
            /* Центрирование содержимого по горизонтали */
            align-items: center;
            /* Центрирование содержимого по вертикали */
            margin-bottom: 5px;
            /* Отступ снизу между строками */
        }

        .author-name {
            font-weight: bold;
            /* Полужирное начертание для имени автора */
            margin-right: 10px;
            /* Отступ справа между именем и датой */
        }

        .form_com {
            width: 50%;
            margin: 0 auto;
            /* Центрирование блока по горизонтали */
            text-align: center;
            /* Центрирование текста и элементов внутри блока */
        }

        .form_com fieldset {
            border: none;
            /* Убираем стандартную границу */
            padding: 0;
            /* Убираем отступы */
        }

        .form_com legend {
            font-size: min(max(80%, 2vw), 100%);
            margin-bottom: 10px;
            /* Отступ снизу */
        }

        .form_com textarea {
            width: 100%;
            /* Заполняет ширину родительского блока */
            box-sizing: border-box;
            /* Учитывает внутренние отступы и границу */
            padding: 10px;
            /* Внутренние отступы */
            border: 1px solid #ccc;
            /* Цвет границы */
            border-radius: 8px;
            /* Закругленные углы */
            font-size: min(max(70%, 1.5vw), 100%);
        }

        .comment-text {
            /*color: #333;  Цвет текста комментария */
            font-size: 1em;
            /* Размер шрифта для текста комментария */
        }

        .z_com {
            font-size: min(max(60%, 1.5vw), 80%);
            color: #f8fc02;
            margin-top: 25px;
            text-align: center;
        }

        .name_com {
            font-size: min(max(60%, 1.5vw), 80%);
            color: #05ff19;
        }

        .date_com {
            margin-top: 5px;
            font-size: min(max(50%, 1.5vw), 70%);
            color: #f8fc02;
        }

        .text_com {
            font-size: min(max(70%, 2vw), 100%);
            margin: 10px;
            color: #89d0ec;
        }

        .eror_com {
            font-size: min(max(80%, 2vw), 120%);
            text-align: center;
            color: aqua;
            margin: 20px 0 20px 0;
        }

        a.eror_com:after,
        a.eror_com:hover {
            color: #0fc51e;
            text-decoration: none;
        }

        a.eror_com {
            color: #f1d11a;
            text-decoration: none;
        }

        .form_com {
            width: 50%;
            height: auto;
            margin: 0 auto;
            text-align: center;
        }




        .choice1 {
            position: relative;
            /* top: -62px; */
            right: 40px;
        }

        .choice2 {
            position: relative;
            /* top: -62px; */
            right: 40px;
        }

        .msg {
            margin-top: 20px;
            text-align: center;
        }

        .my_input_error {
            font-size: 14px;
            color: #DC2626;
            padding-top: 20px;
            padding-left: 20px;
            list-style-type: none;
        }

        /* index show */
        .razdel {
            box-sizing: border-box;
            width: 95%;
            height: auto;
            border: 2px solid #fff;
            border-radius: 30px;
            margin: 3% auto 0 auto;
            text-align: center;
            padding: 1% 2%;
            background-color: rgba(11, 12, 24, 0.75);
            position: relative;
        }

        .title_razdel {
            text-align: justify;
        }

        .title_razdel p {
            margin: 10px auto;
        }

        .razdel_link {
            bottom: 0;
            display: flex;
            justify-content: space-around;
            font-size: min(max(60%, 2vw), 200%);
        }

        .img_razdel {
            width: 100%;
            opacity: 1;
            margin-top: 10px;
            border: 1px solid #717171;
        }

        .link_bottom {
            float: left;
            width: auto;
            height: 100%;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .link_bottom a {
            color: #0b0c18;
            text-decoration: none;
        }

        .mySlides {
            display: none;
        }

        img {
            vertical-align: middle;
        }

        /* Slideshow container */
        .slideshow-container {
            max-width: 90%;
            position: relative;
            margin: auto;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            width: auto;
            padding: 0px;
            margin-top: -7px;
            color: white;
            font-weight: bold;
            font-size: min(max(80%, 2vw), 200%);
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
        }

        .next {
            opacity: 0.3;
            right: -1%;
            border-radius: 3px 0 0 3px;
        }

        .prev {
            left: -1%;
            opacity: 0.3;
        }

        .prev:hover,
        .next:hover {
            color: rgb(247, 223, 6);
            opacity: 1;
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            position: absolute;
            bottom: 8px;
            width: 100;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active,
        .dot:hover {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @-webkit-keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 360px) {

            .prev,
            .next,
            .text {
                font-size: 11px;
            }

            .windows-string {
                display: block;
                text-align: center;
            }
        }

        @media only screen and (max-width: 640px) {
            .windows-new {
                flex-direction: column;
            }

            .windows-string {
                display: flex;
            }

            .icons-img {
                width: 40px;
            }

            .windows-new-images {
                width: 20%;
            }

        }

        @media only screen and (max-width: 500px) {
            .link-name {
                margin-top: 5px;
            }
        }

        @media only screen and (max-width: 400px) {
            .link-name {
                margin-top: 10px;
            }

            .icons-img {
                width: 30px;
            }
        }

        @media only screen and (max-width: 768px) {
            .header-title {
                font-size: 4vw;
            }

            .good,
            .item,
            .admin_menu,
            .main-blog,
            .flex-container,
            .footer,
            .login,
            .regwin,
            .mini-win {
                width: 95%;
            }

            .windows {
                max-width: 80%;
                padding: 0 5px;
            }

            .header-menu-cont {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media only screen and (max-width: 1024px) {
            .flex-container {
                flex-direction: column;
            }

            .item-1,
            .item-2,
            .item-3 {
                width: 100%;
            }

            .post_com {
                width: 90%;
            }

            .comment {
                width: 90%;
            }

            .form_com {
                width: 80%;
            }
        }

        .input-file-row {
            display: inline-block;
        }

        .input-file {
            position: relative;
            display: inline-block;
        }

        .input-file span {
            position: relative;
            display: inline-block;
            cursor: pointer;
            outline: none;
            text-decoration: none;
            font-size: 14px;
            vertical-align: middle;
            color: rgb(255, 255, 255);
            text-align: center;
            border-radius: 4px;
            background-color: #419152;
            line-height: 22px;
            height: 40px;
            padding: 10px 20px;
            box-sizing: border-box;
            border: none;
            margin: 0;
            transition: background-color 0.2s;
        }

        .input-file input[type=file] {
            position: absolute;
            z-index: -1;
            opacity: 0;
            display: block;
            width: 0;
            height: 0;
        }

        .input-file input[type=file]:focus+span {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .input-file:hover span {
            background-color: #59be6e;
        }

        .input-file:active span {
            background-color: #2E703A;
        }

        .input-file input[type=file]:disabled+span {
            background-color: #eee;
        }

        .input-file-list {
            padding: 10px 0;
        }

        .input-file-list-item {
            display: inline-block;
            margin: 0 15px 15px;
            width: 150px;
            vertical-align: top;
            position: relative;
        }

        .input-file-list-item img {
            width: 150px;
        }

        .input-file-list-name {
            text-align: center;
            display: block;
            font-size: 12px;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .input-file-list-remove {
            color: #fff;
            text-decoration: none;
            display: inline-block;
            position: absolute;
            padding: 0;
            margin: 0;
            top: 5px;
            right: 5px;
            background: #ff0202;
            width: 16px;
            height: 16px;
            text-align: center;
            line-height: 16px;
            border-radius: 50%;
        }

        .knopkodav {
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 10px;
  width: 100%; /* На всю ширину экрана */
  box-sizing: border-box;
  border: 2px solid #ccc; /* Рамка */
  border-radius: 8px; /* Закругление углов */
  flex-wrap: wrap; /* Для переноса элементов на меньших экранах */
}

.header-title {
  flex: 1;
  text-align: center;
  padding: 10px;
}

.header-title a {
  display: inline-block;
  width: 100px; /* Задание размера кнопок */
  height: 100px;
  text-decoration: none;
  color: #fff;
  /*border-radius: 50%;  Округление до круга */
  overflow: hidden; /* Обрезка изображения по кругу */
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
  border: 1px solid #0000ff; /* Синяя рамка */
}

.header-title a img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Обеспечивает покрытие всего элемента изображением */
/*  border-radius: 50%;  Округление изображений */
-webkit-box-shadow: 0px 5px 10px 2px rgba(244, 247, 36, 0.2) inset;
-moz-box-shadow: 0px 5px 10px 2px rgba(244, 247, 36, 0.2) inset;
box-shadow: 0px 5px 10px 2px rgba(244, 247, 36, 0.2) inset;
}

.header-title a:active {
  transform: scale(1.5); /* Немного большее увеличение при нажатии */
}

/* Скрытое меню */
.header-title .dropdown-menu1 {
  display: none;
  position: absolute;
  top: 200px; /* Расположение под кнопкой */
  left: 90%;
  width: auto;
  transform: translateX(-50%);
  background-color: #0B0C18;
  padding: 10px;
  border-radius: 8px;
  border: #eeeeee 2px solid;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  z-index: 10;
  text-align: center; /* Центровка текста внутри меню */
}

.header-title .dropdown-menu a {
  display: inline-block; /* Делаем ссылки блочными элементами */
  margin-bottom: 10px;
  text-align: center; /* Центровка внутри ссылок */
}

.header-title .dropdown-menu a img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  /* border-radius: 50%; */
  border: 1px solid #0000ff;
  -webkit-box-shadow: 0px 5px 10px 2px rgba(244, 247, 36, 0.2) inset;
-moz-box-shadow: 0px 5px 10px 2px rgba(244, 247, 36, 0.2) inset;
box-shadow: 0px 5px 10px 2px rgba(244, 247, 36, 0.2) inset;
}

/* Показать меню при клике */
.header-title.esho.active .dropdown-menu1 {
  display: block;
  
}

/* Адаптивные стили для планшетов */
@media (max-width: 768px) {
  .header-title {
      flex: 0 0 45%; /* Два элемента в строке */
      margin-bottom: 10px;
  }
  
  .header-title a {
      width: 80px; /* Уменьшение размера кнопок */
      height: 80px;
  }
  .dropdown-menu1{
    top: 300px
  }
}

/* Адаптивные стили для мобильных телефонов */
@media (max-width: 480px) {
  .knopkodav {
      justify-content: center; /* Выровнять элементы по центру */
  }

  .header-title {
      flex: 0 0 33%; /* Три элемента в строке на маленьких экранах */
      margin-bottom: 10px;
      top: 300px;
    left: 80%;
  }

  .header-title a {
      width: 70px; /* Ещё большее уменьшение размера кнопок */
      height: 70px;
  }
  .dropdown-menu1{
    top: 300px;
    left: 80%;
  }
}

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

    </style>
</head>

<body>
    <!--Голова -->
{{--
    @include('layouts.navigation')
     <div class="header">
        <!-- блок с логотипом -->
        <div class="header-logo">
            <a href="/"><img src="/img/main/daodes.jpg" alt=""
                    class="border border-solid border-t-red-50 rounded-2xl" /></a>
        </div>

    </div> --}}

    {{-- @include('partials.infotrade') --}}

    <div class="marquee">
        <p>Сайт находится в разработке, не обращайте внимание на некоторые недостатки.</p>
    </div>
    <!-- Кнопочное меню -->

    <div class="knopkodav">
        <div class="header-title">
            <a href="https://daodes.space/home" title="Главная"><span class="logo_name"><img src="/img/bottom/home.png"></span></a>
        </div>
        <div class="header-title">
            <a href="https://daodes.space/news" title="Новости"><span class="logo_name"><img src="/img/bottom/news.jpg"></span></a>
        </div>
        <div class="header-title">
            <a href="https://daodes.space/dao" title="Принятие решений"><span class="logo_name"><img src="/img/bottom/dd.jpg"></span></a>
        </div>
        <div class="header-title">
            <a href="https://daodes.space/tasks" title="Биржа заданий"><span class="logo_name"><img src="/img/bottom/tasks2.png"></span></a>
        </div>

        <div class="header-title">
            <a href="https://daodes.space/wallet" title="Кошелёк"><span ><img src="/img/bottom/wallet2.png"></span></a>
        </div>

        <div class="header-title">
            <a href="https://daodes.space/paper" title="Белая бумага"><span ><img src="/img/bottom/paper.png"></span></a>
        </div>

        <div class="header-title">
            <a href="https://daodes.space/paper" title="Дорожная карта"><span ><img src="/img/bottom/roadmap.png"></span></a>
        </div>

      @if (Route::has('login'))
@auth        
        
        <div class="header-title esho">
            <a href="#" class="menu-button" title="Раскрыть"><span ><img src="/img/bottom/add_post.png"></span></a>
            <div class="dropdown-menu1">
                <div class="header-title1">
                    <a href="https://daodes.space/profile" >
                      <span ><img src="/img/bottom/user.png" title="Профиль">
                      </span>
                    </a>
                </div>
                <div class="header-title1">
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="/img/bottom/exit.png" title="Выход"></a>
                </a>
    @else
    <div class="header-title">
      <a href="https://daodes.space/login" title="Вход"><span ><img src="/img/bottom/enter.png"></span></a>
  </div>   
  <div class="header-title">
    <a href="https://daodes.space/register" title="Регистрация"><span ><img src="/img/bottom/add-user.png"></span></a>
</div>     
@endauth    
@endif                  
                </div>
                {{-- <div class="header-title1">
                    <a href="https://daodes.space/link2" ><span ><img
                                src="/img/bottom/work.png"></span></a>
                </div> --}}

                @auth
                        <?php
                        $rol = DB::table('users')
                            ->where('name', Auth::user()->name)
                            ->select('rang_access')
                            ->first();
                        ?>
                        @if ($rol->rang_access >= 3)
                            <x-nav-link :href="route('add_news')" :active="request()->routeIs('add_news')">
                                {{-- {{ __('Create News') }}--}} <img src="/img/bottom/blog.png" title="Добавить новость"> 
                            </x-nav-link>
                            <x-nav-link :href="route('tasks.create')" :active="request()->routeIs('tasks.create')">
                                <img src="/img/bottom/blog.png" title="Добавить задание">
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('add_offers')" :active="request()->routeIs('add_offers')">
                            {{-- {{ __('Create Offer') }} --}} <img src="/img/bottom/blog.png" title="Добавить предложение">
                        </x-nav-link>

                    @endauth
                
            </div>
        </div>
    </div>



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
