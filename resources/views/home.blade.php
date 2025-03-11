{{-- //Главная страница сайта --}}
@extends('template')
@section('title_page')
    Главная
@endsection
@section('main')
    <main>
        <style>
            .container {
                padding: 15px;
                margin: 20px auto 0 auto;
                max-width: 800px;
                background-color: #000000cf;
                border-radius: 15px;
                border: 1px solid gold;
                color: #f8f9fa;
                font-family: Verdana, Geneva, Tahoma, sans-serif;

            }

            .blue_btn {
                display: inline-block;
                color: #ffffff;
                background: #0b0c18;
                padding: 5px 10px;
                font-size: 1.3rem;
                border: 1px solid gold;
                border-radius: 10px;
                transition: box-shadow 0.3s ease, transform 0.3s ease;
                text-decoration: none;
            }

            .blue_btn:hover {
                box-shadow: 0 0 20px goldenrod;
                transform: scale(1.05);
                color: #ffffff;
            }

            h1,
            h2 {
                text-align: center;
            }

            p {
                text-align: justify;
                width: 100%;
            }

            .spacer {
                margin-bottom: 50px;
                /* Adds space between paragraphs */
            }

            .pubble-app {
                margin: 0px auto;
            }
        </style>
        <div class="container">
            <div class="">
                <img src="/img/home/1.png" class="spacer">
                <p class="spacer">
                    {{ __('home.mission_text') }}
                </p>

                <div align="center"><strong>{{ __('home.key_advantages') }}</strong></div>
                <hr>

                <ul style="color:aqua; margin-left:2rem">
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
                <hr><br>

                <p class="spacer" id="custom-blockchain">
                    <img src="/img/home/2.png" class="spacer">
                    {{ __('home.custom_blockchain_text') }}
                </p>

                <p class="spacer" id="decentralized-messenger">
                    <img src="/img/home/3.png" class="spacer">
                    {{ __('home.decentralized_messenger_text') }}
                </p>

                <p class="spacer" id="native-coin">
                    <img src="/img/home/4.png" class="spacer">
                    {{ __('home.native_coin_text') }}
                </p>

                <p class="spacer" id="web3-platform">
                    <img src="/img/home/5.png" class="spacer">
                    {{ __('home.web3_platform_text') }}
                </p>

                <p class="spacer" id="decision-making">
                    <img src="/img/home/6.png" class="spacer">
                    {{ __('home.decision_making_text') }}
                </p>

                <p class="spacer" id="task-exchange">
                    <img src="/img/home/7.png" class="spacer">
                    {{ __('home.task_exchange_text') }}
                </p>

                <p class="spacer" id="reward-system">
                    <img src="/img/home/8.png" class="spacer">
                    {{ __('home.reward_system_text') }}
                </p>

                <p class="spacer" id="proof-of-time">
                    <img src="/img/home/9.png" class="spacer">
                    {{ __('home.proof_of_time_text') }}
                </p>

                <p class="spacer" id="activity-tracking">
                    <img src="/img/home/10.png" class="spacer">
                    {{ __('home.activity_tracking_text') }}
                </p>

                <p class="spacer" id="market-opportunities">
                    <img src="/img/home/11.png" class="spacer">
                    {{ __('home.market_opportunities_text') }}
                </p>

                <p class="spacer" id="financial-model">
                    <img src="/img/home/12.png" class="spacer">
                    {{ __('home.financial_model_text') }}
                </p>

                <p class="spacer" id="roadmap">
                    <img src="/img/home/13.png" class="spacer">
                    {!! __('home.roadmap_text') !!}
                </p>

                <div class="spacer" id="team">
                    <img src="/img/home/14.png" class="spacer">
                    <h1>{{ __('home.team') }}</h1><br>
                    <h2>{!! __('home.team_link') !!}</h2>
                    <br>
                    <h1>{{ __('home.team_aim') }}</h1>
                </div>

                <p class="spacer" id="conclusion">
                    <img src="/img/home/15.png" class="spacer">
                    {{ __('home.conclusion_text') }}
                </p>
                <div align="center">
                    <p class="spacer" style="text-align: center">{{ __('home.thank_you') }}</p>
                </div>
            </div>

            <div class="" align="center">
                <a href="/register" class="blue_btn">
                    <strong>{{ __('home.become_part') }}</strong>
                </a>
            </div>
        </div>

        <div class="pubble-app" data-app-id="128664" data-app-identifier="128664"></div>
        <script type="text/javascript" src="https://cdn.chatify.com/javascript/loader.js" defer></script>
    </main>
@endsection