{{-- //Главная страница сайта --}}
@extends('template')
@section('title_page', __('menu.home'))
@section('main')
    <main>
        <style>
            .spacer {
                margin-bottom: 50px;
                /* Adds space between paragraphs */
            }

            .pubble-app {
                margin: 0px auto;
            }

            /* Стили для списка внутри .headlines */
            .headlines ul {
                list-style-type: none;
                /* Убираем маркеры списка */
                padding: 0;
                /* Убираем отступы */
                margin-left: 2rem
            }

            .headlines li {
                margin: 5px 0;
                /* Отступы между элементами списка */
            }

            /* Стили для всех ссылок внутри .headlines */
            .headlines a {
                color: aqua;
                /* Цвет текста */
                text-decoration: none;
                /* Убираем подчеркивание */
                transition: font-size 0.3s ease;
                /* Плавное изменение размера шрифта */
            }

            /* Стили для ссылок при наведении */
            .headlines a:hover {
                font-size: 1.1em;
                /* Увеличение шрифта при наведении */
            }

            /* Стили для посещенных ссылок */
            .headlines a:visited {
                color: aqua;
                /* Цвет текста для посещенных ссылок */
            }

            /* Стили для активных ссылок */
            .headlines a:active {
                color: aqua;
                /* Цвет текста для активных ссылок */
            }
        </style>
        <div class="container">
            <div class="">
                <img src="/img/home/1.webp" class="spacer">
                <p class="spacer">
                    {{ __('home.mission_text') }}
                </p>

                <div><h3>{{ __('home.key_advantages') }}</h3></div>
                <hr>
                <div class="headlines">
                    <ul>
                        <li><a href="#custom-blockchain">{{ __('home.custom_blockchain') }}</a></li>
                        <li><a href="#decentralized-messenger">{{ __('home.decentralized_messenger') }}</a></li>
                        <li><a href="#native-coin">{{ __('home.native_coin') }}</a></li>
                        <li><a href="#web3-platform">{{ __('home.web3_platform') }}</a></li>
                        <li><a href="#decision-making">{{ __('home.decision_making') }}</a></li>
                        <li><a href="#task-exchange">{{ __('home.task_exchange') }}</a></li>
                        <li><a href="#reward-system">{{ __('home.reward_system') }}</a></li>
                        <li><a href="#proof-of-time">{{ __('home.proof_of_time') }}</a></li>
                        <li><a href="#activity-tracking">{{ __('home.activity_tracking') }}</a></li>
                        <li><a href="#market-opportunities">{{ __('home.market_opportunities') }}</a></li>
                        <li><a href="#financial-model">{{ __('home.financial_model') }}</a></li>
                        <li><a href="#roadmap">{{ __('home.roadmap') }}</a></li>
                        <li><a href="#team">{{ __('home.team') }}</a></li>
                        <li><a href="#conclusion">{{ __('home.conclusion') }}</a></li>
                    </ul>
                    <hr>
                </div>
                <br>

                <p class="spacer" id="custom-blockchain">
                    <img src="/img/home/2.webp" class="spacer">
                    {{ __('home.custom_blockchain_text') }}
                </p>

                <p class="spacer" id="decentralized-messenger">
                    <img src="/img/home/3.webp" class="spacer">
                    {{ __('home.decentralized_messenger_text') }}
                </p>

                <p class="spacer" id="native-coin">
                    <img src="/img/home/4.webp" class="spacer">
                    {{ __('home.native_coin_text') }}
                </p>

                <p class="spacer" id="web3-platform">
                    <img src="/img/home/5.webp" class="spacer">
                    {{ __('home.web3_platform_text') }}
                </p>

                <p class="spacer" id="decision-making">
                    <img src="/img/home/6.webp" class="spacer">
                    {{ __('home.decision_making_text') }}
                </p>

                <p class="spacer" id="task-exchange">
                    <img src="/img/home/7.webp" class="spacer">
                    {{ __('home.task_exchange_text') }}
                </p>

                <p class="spacer" id="reward-system">
                    <img src="/img/home/8.webp" class="spacer">
                    {{ __('home.reward_system_text') }}
                </p>

                <p class="spacer" id="proof-of-time">
                    <img src="/img/home/9.webp" class="spacer">
                    {{ __('home.proof_of_time_text') }}
                </p>

                <p class="spacer" id="activity-tracking">
                    <img src="/img/home/10.webp" class="spacer">
                    {{ __('home.activity_tracking_text') }}
                </p>

                <p class="spacer" id="market-opportunities">
                    <img src="/img/home/11.webp" class="spacer">
                    {{ __('home.market_opportunities_text') }}
                </p>

                <p class="spacer" id="financial-model">
                    <img src="/img/home/12.webp" class="spacer">
                    {{ __('home.financial_model_text') }}
                </p>

                <p class="spacer" id="roadmap">
                    <img src="/img/home/13.webp" class="spacer">
                    {!! __('home.roadmap_text') !!}
                </p>

                <div class="spacer" id="team">
                    <img src="/img/home/14.webp" class="spacer">
                    <h1>{{ __('home.team') }}</h1><br>
                    <h6>{!! __('home.team_link') !!}</h6>
                    <br>
                    <h6>{{ __('home.team_aim') }}</h6>
                </div>

                <p class="spacer" id="conclusion">
                    <img src="/img/home/15.webp" class="spacer">
                    {{ __('home.conclusion_text') }}
                </p>
                <div  >
                    <h5>{{ __('home.thank_you') }}</h5>
                </div>
            </div>
<br>
            <h6>
                <a href="/register" class="des-btn">
                    <strong>{{ __('home.become_part') }}</strong>
                </a>
            </h6>
        </div>

        {{-- <div class="pubble-app" data-app-id="128664" data-app-identifier="128664"></div>
        <script type="text/javascript" src="https://cdn.chatify.com/javascript/loader.js" defer></script> --}}
    </main>
@endsection
