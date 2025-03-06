@extends('template')
@section('title_page')
    Team
@endsection
@section('main')
    <style>
        .container {
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
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
        h2,
        h3,
        h4 {
            text-align: center;
        }

        p {
            text-align: justify;
            width: 100%;
        }

        .big {
            font-style: bold;
            font-size: 3rem;
        }

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
            <h1>Decentralized EcoSystems</h1>
            <h1 class="big">Team</h1>
            <div class="content">
                <div class="team-section">
                    <div class="team-members slick-carousel">
                        @php
                            $teamMembers = [
                                [
                                    'photo' => 'img/team/2.jpg',
                                    'name' => 'Sergey Nikitin',
                                    'description' => 'CTO Technical Lead.',
                                    'details' => [
                                        'Age' => '42',
                                        'Skills' => 'Knowledge of languages: HTML, CSS, JavaScript, PHP, MySQL, Laravel; ability to manage and organize the work of teams;',
                                        'Experience in industry' => 'Development of interfaces and programs for large companies.',
                                        'About me' => '<strong>Visionary Programmer</strong>
<p>Has been passionate about technology since the age of 12. Over the past 30 years, he has witnessed
and adapted to rapid technological changes, often foreseeing future developments in functionality.</p>
<ul>
<li><strong  style="color: gold">Technical Expertise:</strong> Can disassemble and assemble any computer blindfolded. This hobby also generates income, alongside writing landing pages and websites.</li>
<li><strong style="color: gold">Financial Markets:</strong> Has experience in currency trading since 2013 and cryptocurrency trading since 2019. However, he finds constant market monitoring boring and sought like-minded individuals to create a world-class project.</li>
<li><strong  style="color: gold">Vision for the Future:</strong> Believes humanity will transition to a new technological level where robotic devices replace routine human labor, with a focus on environmental sustainability. He sees decentralized technology as the foundation of this transition, managed by a conscious community for the greater good.</li>
<li><strong  style="color: gold">Continuous Learning:</strong> Invests significant effort in learning and mastering various programming languages to stay ahead of technological advancements.</li>
<li><strong  style="color: gold">Creative Side:</strong> Writes poetry and plays the guitar for friends, preferring to avoid the spotlight of public media.</li>
</ul>',
                                        'Hobbies' => 'Fishing, Outdoor recreation, Aquariums',
                                        'Contact' => 'nikitins@daodes.space',
                                    ],
                                ],
                                [
                                    'photo' => 'img/team/1.jpg',
                                    'name' => 'Valeriy Akimov',
                                    'description' => 'CEO Project Author',
                                    'details' => [
                                        'Age' => '40',
                                        'Skills' => 'Organizational skills, motivation, determination.',
                                        'Experience in industry' => 'Experience in this industry. 5 years old',
                                        'About me' => '<strong>The mastermind</strong><p>Has demonstrated exceptional skills and determination in achieving his goals.</p><strong > His experience spans across some of the largest enterprises in their respective industries, including:</strong>
<ul>
<li><strong style="color: gold">Rosatom State Corporation:</strong> Developed a methodology for calculating the achievement of goals
for the Federal Project of the Russian Federation.</li>
<li><strong  style="color: gold">JSC EuroChem:</strong> Selected into the top 40 highly potential employees (HiPo) out of 27,000 in
the international holding company.</li>
<li><strong style="color: gold">JSC GC EX and LLC KZ Rostselmash:</strong> Gained valuable business-building experience.</li>
</ul>
<p>In his first crypto project, Valery developed a coin burning system that has been successfully implemented. He
also launched an information service that became the most informative and popular news source in a community of
30,000 participants.</p>
<p>Valery is actively involved in social and sports life, having won multiple championships in volleyball and
football.</p>',
                                        'Hobbies' => 'Watch and play soccer and volleyball. Swimming',
                                        'Contact' => 'akimovv@daodes.space',
                                    ],
                                ],
                                [
                                    'photo' => 'img/team/3.jpg',
                                    'name' => 'Denis Bizhko',
                                    'description' => 'CMO Marketing Guru.',
                                    'details' => [
                                        'Age' => '35',
                                        'Skills' => 'Marketing, Attracting traffic, interacting with the audience.',
                                        'Experience in industry' => '5 age',
                                        'About me' => '<p>Believes that blockchain technology should be accessible to everyone.</p> <h3>With over five years of
        experience in the crypto space, he has contributed significantly to various Experience:</h3>
    <ul>
        <li><strong style="color: gold">Artery Project:</strong> Started his crypto journey with the Artery project, where he developed and
            implemented an information resource based on Telegram, gaining over 2,500 subscribers in a week.</li>
        <li><strong style="color: gold">GSP (Waple Rune) Project:</strong> Created the GSP_News information resource, covering Telegram,
            Instagram, VK, and YouTube, helping the project reach the stage of attracting venture capital investments.
        </li>
        <li><strong style="color: gold">Digital Marketing Expertise:</strong> Studied with many advertising gurus, deconstructed Digital
            Marketing, and invented his own promotion formula.</li>
        <li><strong style="color: gold">Current Role:</strong> Serves as the CMO of the DAO DES project, acting as the eyes, ears, and
            mouthpiece of the project.</li>
    </ul>',
                                        'Hobbies' => 'Playing on musical instruments, soccer',
                                        'Contact' => 'bizhkod@daodes.space',
                                    ],
                                ],
                                [
                                    'photo' => 'img/team/4.png',
                                    'name' => 'Olga Korobeynikova',
                                    'description' => 'CFO Project Manager',
                                    'details' => [
                                        'Age' => '?',
                                        'Skills' => '?',
                                        'Experience in industry' => '?',
                                        'About me' => '?',
                                        'Hobbies' => '?',
                                        'Contact' => '?',
                                    ],
                                ],
                                [
                                    'photo' => 'img/team/5.png',
                                    'name' => 'Dmitry Korobeynikov',
                                    'description' => 'VCFO Tester',
                                    'details' => [
                                        'Age' => '?',
                                        'Skills' => '?',
                                        'Experience in industry' => '?',
                                        'About me' => '?',
                                        'Hobbies' => '?',
                                        'Contact' => '?',
                                    ],
                                ],
                            ];
                        @endphp

                        @foreach ($teamMembers as $index => $member)
                            <div class="team-member" data-index="{{ $index }}">
                                <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}">
                                <h3>{{ $member['name'] }}</h3>
                                <p style="text-align:center">{{ $member['description'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Блоки с подробной информацией -->
                    @foreach ($teamMembers as $index => $member)
                        <div class="details" data-index="{{ $index }}">
                            <h3>Information about: <strong style="color: gold">{{ $member['name'] }}</strong></h3>
                            <ul class="details-list">
                                @foreach ($member['details'] as $key => $value)
                                    <li>
                                        <strong style="color: gold">{{ $key }}:</strong>
                                        @if ($key === 'Contact' && !empty($value))
                                            <a href="mailto:{{ $value }}"
                                                style="color: #0379ff">{{ $value }}</a>
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
            <h2>Click on the card to view the detailed information.</h2>
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
