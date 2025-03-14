@extends('template')
@section('title_page', __('team.title'))
@section('main')
    <style>
        .team-section {
            padding: 20px;
        }

        .team-members {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .team-member {
            text-align: center;
            margin: 10px;
            width: 250px;
            border: 1px solid gold;
            border-radius: 10px;
            padding: 15px;
            background-color: #1a1a1a;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
        }

        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 10px auto;
            border: 2px solid gold;
        }

        .team-member h3 {
            font-size: 1.5rem;
            margin: 15px 0 10px;
            color: gold;
        }

        .team-member p {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #f8f9fa;
        }

        .team-member p.contact {
            font-size: 0.9rem;
            color: #cccccc;
        }

        /* Стили для карусели */
        .slick-slide {
            padding: 10px;
        }

        .slick-prev:before,
        .slick-next:before {
            color: gold;
        }

        .slick-dots li button:before {
            color: gold;
        }

        .slick-dots li.slick-active button:before {
            color: gold;
        }

        /* Стили для блока с подробной информацией */
        .details {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background-color: #1a1a1a;
            border: 1px solid gold;
            border-radius: 10px;
            color: #f8f9fa;
        }

        .details.active {
            display: block;
        }

        /* Убираем маркеры для всех списков в details */
        .details-list {
            list-style-type: none;
            /* Убираем маркеры */
            padding-left: 0;
            /* Убираем отступ слева */
        }

        /* Возвращаем маркеры для списков внутри About me */
        .details-list .about-me-list ul {
            list-style-type: disc;
            /* Возвращаем маркеры */
            margin-left: 20px;
            /* Добавляем отступ для маркеров */
        }

        .details-list .about-me-list ul li {
            margin-bottom: 10px;
            /* Добавляем отступ между элементами списка */
        }
    </style>
    <main>
        <div class="container">
            <h6>{{ __('team.decentralized_ecosystems') }}</h6>
            <h1 class="big">{{ __('team.title') }}</h1>
            <div class="content">
                <div class="team-section">
                    <div class="team-members slick-carousel">
                        @foreach (trans('team.team_members') as $index => $member)
                            <div class="team-member" data-index="{{ $index }}">
                                <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}">
                                <h3>{{ $member['name'] }}</h3>
                                <p style="text-align:center">{{ $member['description'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Блоки с подробной информацией -->
                    @foreach (trans('team.team_members') as $index => $member)
                        <div class="details" data-index="{{ $index }}">
                            <h3>Information about: <strong style="color: gold">{{ $member['name'] }}</strong></h3>
                            <ul class="details-list">
                                @foreach ($member['details'] as $key => $value)
                                    <li>
                                        <strong style="color: gold">{{ $key }}:</strong>
                                        @if ($key === 'Contact' && !empty($value))
                                            <a href="mailto:{{ $value }}" style="color: #0379ff">{{ $value }}</a>
                                        @else
                                            @if ($key === 'About me')
                                                <div class="about-me-list">{!! $value !!}</div>
                                            @else
                                                {!! $value !!}
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
            <h6>{{ __('team.click_for_details') }}</h6>
        </div>
    </main>
    <!-- Подключение jQuery и Slick Carousel -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            // Инициализация карусели
            $('.slick-carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });

            // Обработка клика по карточке
            $('.team-member').on('click', function() {
                const index = $(this).data('index'); // Получаем индекс карточки
                $('.details').removeClass('active'); // Скрываем все блоки с информацией
                $(`.details[data-index="${index}"]`).addClass('active'); // Показываем нужный блок
            });

            // Скрываем блок с подробностями при перелистывании карусели
            $('.slick-carousel').on('afterChange', function(event, slick, currentSlide) {
                $('.details').removeClass('active'); // Скрываем все блоки с информацией
            });
        });
    </script>
@endsection
