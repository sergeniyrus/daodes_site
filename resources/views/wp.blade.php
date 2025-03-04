@extends('template')
@section('title_page')
    White paper
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

            /* .spacer {
                                margin-bottom: 50px;
                            }

                            .pubble-app {
                                margin: 0px auto;
                            } */

            .big {
                font-style: bold;
                font-size: 3rem;
            }

            .chapter-container {
                margin: 15px 0;
                border: 1px solid #2c3e50;
                border-radius: 8px;
                overflow: hidden;
                background: #0b0c18ce;
                height: auto;
            }

            .chapter-header {
                padding: 15px 20px;
                background: #1a1a2e;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s ease;
            }

            .chapter-header:hover {
                background: #2c3e50;
            }

            .chapter-header.active {
                background: #34495e;
            }

            .chapter-title {
                font-size: 1.2em;
                color: #00ccff;
                margin: 0;
            }

            .toggle-icon {
                font-size: 1.2em;
                color: #d7fc09;
                transition: transform 0.3s ease;
            }

            .toggle-icon.rotated {
                transform: rotate(90deg);
            }

            .subchapters {
                max-height: 0;
                /* overflow: hidden; */
                transition: max-height 0.3s ease-out;
                overflow: visible;
                /* Изменено с hidden на visible */
                position: relative;
                /* Добавлено для корректной работы z-index */
                z-index: 1;
                /* Добавлено, чтобы содержимое было поверх других элементов */
                overflow: visible;
                /* Разрешаем содержимому выходить за пределы */
            }

            .subchapter-item {
                padding: 12px 25px;
                border-top: 1px solid #2c3e50;
                background: #0b0c18ce;
                color: #ffffff;
                transition: background 0.2s ease;
                cursor: pointer;
                position: relative;
                /* Добавлено для корректной работы z-index */
                z-index: 2;
                /* Добавлено, чтобы содержимое было поверх других элементов */
            }

            .subchapter-item:hover {
                background: #1a1a2e;
            }

            .subchapter-item.active {
                background: #1a1a2e;
            }

            .subchapter-number {
                color: #d7fc09;
                margin-right: 10px;
            }

            .subchapter-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out;
                padding: 0 10px;
                margin-top: 10px 0;
                background: #0b0c18ce;
                /* Добавлено, чтобы фон был виден */
                position: relative;
                /* Добавлено для корректной работы z-index */
                z-index: 3;
                /* Добавлено, чтобы содержимое было поверх других элементов */
            }

            .subchapter-content.open {
                margin-top: 10px;
                max-height: 1000vh;
            }

            p {
                margin-bottom: 20px;
            }

            .pros-cons {
                margin-bottom: 30px;
            }

            .pros-cons h3 {
                color: #4CAF50;
            }

            .pros-cons ul {
                list-style-type: disc;
                margin-left: 20px;
            }

            .pros-cons ul li {
                margin-bottom: 10px;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .subchapter-item {
                animation: slideDown 0.3s ease forwards;
            }
        </style>
        <div class="container">
            <h1>Decentralized EcoSystems</h1>
            <h1 class="big">White paper</h1>
            <div class="content">
                <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Introduction</h3>
                        <span class="toggle-icon">▶</span>
                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">Welcome to the DES Project </span>
                            <div class="subchapter-content">
                                @include('wp.1')
                            </div>
                        </div>
                    </div>
                </div>


                <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Description of the market</h3>
                        <span class="toggle-icon">▶</span>
                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">1.1</span>Blockchain ecosystems
                            <div class="subchapter-content">
                                @include('wp.1.1')
                            </div>
                        </div>

                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">1.2</span>Decentralized messengers
                            <div class="subchapter-content">
                                @include('wp.1.2')
                            </div>
                        </div>

                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">1.3</span>Decentralized stablecoins
                            <div class="subchapter-content">
                                @include('wp.1.3')
                            </div>
                        </div>

                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">1.4</span>Decentralized Exchanges (DEX)
                            <div class="subchapter-content">
                                @include('wp.1.4')
                            </div>
                        </div>

                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">1.5</span>Decentralized Shell and Operating System
                            <div class="subchapter-content">
                                @include('wp.1.5')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Product description and proposed solution method</h3>
                        <span class="toggle-icon">▶</span>
                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">2.1</span>Third-generation Decentralized Blockchain
                            <div class="subchapter-content">
                                @include('wp.2.1')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">2.2</span>The uniqueness of the messenger
                            <div class="subchapter-content">
                                @include('wp.2.2')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">2.3</span>The Ecosystem of Decentralized Stablecoins
                            <div class="subchapter-content">
                                @include('wp.2.3')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">2.4</span>A decentralized exchange
                            <div class="subchapter-content">
                                @include('wp.2.4')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">2.5</span>A Decentralized Operating System
                            <div class="subchapter-content">
                                @include('wp.2.5')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Tokenomics</h3>
                        <span class="toggle-icon">▶</span>
                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.1</span>Key characteristics
                            <div class="subchapter-content">
                                @include('wp.3.1')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.2</span>Description of token implementation and project
                            economy
                            <div class="subchapter-content">
                                @include('wp.3.2')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.3</span>Ways of earning for project participants and activists
                            <div class="subchapter-content">
                                @include('wp.3.3')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.4</span>DES stablecoin emission collateral pool 20% of total
                            emission
                            <div class="subchapter-content">
                                @include('wp.3.4')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.5</span>Distribution of the remaining 200,000,000 DES or 20%
                            of emission
                            <div class="subchapter-content">
                                @include('wp.3.5')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.6</span>Distribution of transaction fees
                            <div class="subchapter-content">
                                @include('wp.3.6')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.7</span>DES liquidity pool collateral
                            <div class="subchapter-content">
                                @include('wp.3.7')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.8</span>Investment direction in DES ecosystem
                            <div class="subchapter-content">
                                @include('wp.3.8')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">3.9</span>Reward for application creators
                            <div class="subchapter-content">
                                @include('wp.3.9')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Project management</h3>
                        <span class="toggle-icon">▶</span>
                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">4.1</span>Network consensus
                            <div class="subchapter-content">
                                @include('wp.4.1')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">4.2</span>Encouraging validators and deligators
                            <div class="subchapter-content">
                                @include('wp.4.2')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">4.3</span>Conditions for obtaining Validator status
                            <div class="subchapter-content">
                                @include('wp.4.3')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">4.4</span>Coin mining for creating value in the project
                            <div class="subchapter-content">
                                @include('wp.4.4')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">4.5</span>Activity monitoring in DES project
                            <div class="subchapter-content">
                                @include('wp.4.5')
                            </div>
                        </div>
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">4.6</span>DES Arena
                            <div class="subchapter-content">
                                @include('wp.4.6')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Team story</h3>
                        <span class="toggle-icon">▶</span>
                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">Team story</span>
                            <div class="subchapter-content">
                                @include('wp.2')
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <script>
        // Функция для раскрытия/скрытия глав
        function toggleChapter(header) {
            const container = header.parentElement;
            const subchapters = container.querySelector('.subchapters');
            const icon = header.querySelector('.toggle-icon');

            // Переключаем классы для анимации и стилей
            header.classList.toggle('active');
            icon.classList.toggle('rotated');

            // Раскрываем или скрываем подразделы
            if (subchapters.style.maxHeight) {
                subchapters.style.maxHeight = null; // Скрываем
            } else {
                subchapters.style.maxHeight = subchapters.scrollHeight + "px"; // Раскрываем
            }
        }

        // Функция для раскрытия/скрытия подразделов
        function toggleSubchapter(item) {
            const content = item.querySelector('.subchapter-content');
            content.classList.toggle('open');

            // Обновляем высоту родительского контейнера (.subchapters)
            const subchapters = item.closest('.subchapters');
            if (content.classList.contains('open')) {
                // Увеличиваем высоту, чтобы вместить раскрытый контент
                subchapters.style.maxHeight = subchapters.scrollHeight + content.scrollHeight + "px";
            } else {
                // Уменьшаем высоту, так как контент скрыт
                subchapters.style.maxHeight = subchapters.scrollHeight - content.scrollHeight + "px";
            }
        }

        // Инициализация по умолчанию: скрываем все подразделы
        document.querySelectorAll('.subchapters').forEach(sub => {
            sub.style.maxHeight = null;
        });
    </script>
@endsection
