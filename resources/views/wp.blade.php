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

            .big {
                font-style: bold;
                font-size: 3rem;
            }

            .chapter-container {
                margin: 15px 0;
                border: 1px solid #154472;
                border-radius: 8px;
                overflow: hidden;
                background: #0b0c18ce;
                height: auto;
                transition: all 0.3s ease;
            }

            .chapter-header {
                padding: 15px 20px;
                background: #1a1a2e;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s ease;
                position: relative;
            }

            .chapter-header:hover {
                background: #2c3e50;
            }

            .chapter-header.active {
                background: #34495e;
            }

            .chapter-header::after {
                content: "▼";
                font-size: 0.8em;
                color: #d7fc09;
                position: absolute;
                right: 25px;
                transition: transform 0.3s ease;
            }

            .chapter-header.active::after {
                content: "▲";
                transform: rotate(270deg);
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
                overflow: hidden;
                transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);


            }

            .subchapter-item {
                padding: 15px 25px;
                border-top: 1px solid rgba(44, 62, 80, 0.5);
                background: #0b0c18ce;
                color: #ffffff;
                transition: background 0.2s ease;
                cursor: pointer;
                position: relative;
                transition: all 0.2s ease;
                z-index: 2;

            }

            .subchapter-item:hover {
                background: #1a1a2e;
            }

            .subchapter-item.active {
                background: #1a1a2e;
            }

            .subchapter-item::before {
                content: "";
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                width: 8px;
                height: 8px;
                background: #d7fc09;
                border-radius: 50%;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .subchapter-item:hover::before {
                opacity: 1;
            }

            .subchapter-number {
                color: #d7fc09;
                margin-right: 10px;
            }

            .subchapter-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 0 20px;
                margin-top: 10px 0;
                background: #0b0c18ce;
                position: relative;
                z-index: 3;

            }

            .subchapter-content.open {
                margin-top: 10px;
                max-height: 1000vh;
                padding: 15px 20px;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .active-chapter {
                border-color: #00ccff;
                box-shadow: 0 0 15px rgba(0, 204, 255, 0.2);
            }


            p {
                margin-bottom: 20px;
            }

            .pros-cons {
                margin-bottom: 30px;
            }

            .pros-cons h3,h4, h2 {
                color: #4CAF50;
            }

            .pros-cons ul {
                list-style-type: disc;
                margin-left: 20px;
            }

            .pros-cons ul li {
                margin-bottom: 10px;
            }

            ul {
                list-style-type: disc;
                margin-left: 20px;
                margin-bottom: 20px;
            }

            ul li {
                margin-bottom: 10px;
            }

            ol {
                list-style-type: upper-alpha;
                margin-left: 20px;
                margin-bottom: 20px;
            }

            ol li {
                margin-bottom: 10px;
            }

            ol ul {
                list-style-type: disc;
                margin-left: 20px;
                margin-top: 10px;
            }

            ol ul li {
                margin-bottom: 5px;
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
                {{-- <div class="chapter-container">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <h3 class="chapter-title">Team story</h3>

                    </div>
                    <div class="subchapters">
                        <div class="subchapter-item" onclick="toggleSubchapter(this)">
                            <span class="subchapter-number">Team story</span>
                            <div class="subchapter-content">
                                @include('wp.2')
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </main>
    <script>
        // Глобальная переменная для отслеживания открытой главы
        let openChapter = null;

        function toggleChapter(header) {
            const container = header.parentElement;
            const subchapters = container.querySelector('.subchapters');
            const isOpening = !container.classList.contains('active-chapter');

            // Закрываем все другие главы
            document.querySelectorAll('.chapter-container').forEach(chapter => {
                if (chapter !== container) {
                    chapter.classList.remove('active-chapter');
                    chapter.querySelector('.subchapters').style.maxHeight = null;
                    chapter.querySelector('.chapter-header').classList.remove('active');
                }
            });

            // Обрабатываем текущую главу
            container.classList.toggle('active-chapter', isOpening);
            header.classList.toggle('active', isOpening);

            if (isOpening) {
                subchapters.style.maxHeight = subchapters.scrollHeight + "px";
                openChapter = container;
            } else {
                subchapters.style.maxHeight = null;
                openChapter = null;
            }
        }

        function toggleSubchapter(item) {
            const content = item.querySelector('.subchapter-content');
            const isOpening = !content.classList.contains('open');

            // Закрываем все подразделы в текущей главе
            item.parentElement.querySelectorAll('.subchapter-content').forEach(c => {
                if (c !== content) {
                    c.classList.remove('open');
                    c.style.maxHeight = null;
                    c.parentElement.classList.remove('active');
                }
            });

            // Обновляем текущий подраздел
            item.classList.toggle('active', isOpening);
            content.classList.toggle('open', isOpening);
            content.style.maxHeight = isOpening ? content.scrollHeight + "px" : null;

            // Обновляем высоту родительской главы
            if (openChapter) {
                const subchapters = openChapter.querySelector('.subchapters');
                subchapters.style.maxHeight = subchapters.scrollHeight + (isOpening ? content.scrollHeight : -content
                    .scrollHeight) + "px";
            }
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.subchapters').forEach(sub => {
                sub.style.maxHeight = null;
            });
        });
    </script>
@endsection
