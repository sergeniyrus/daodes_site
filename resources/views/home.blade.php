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
                font-size: 0.875rem;
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
                <!-- <h1>Decentralized Ecosystems Based on DAO</h1> -->
                {{-- <strong>Decentralized Ecosystems Based on DAO</strong> --}}
                <img src="/img/home/1.png">
                <p class="spacer">
                    DAODES presents an innovative decentralized ecosystem built on the principles of DAO (Decentralized
                    Autonomous Organization). Our mission is to create a universal platform for Web3 that integrates
                    blockchain technology, decentralized applications, and unique tools for management and interaction.
                </p>

                <!-- Key Advantages of DAODES -->
                <div align="center"><strong>Key Advantages of DAODES:</strong></div>
                <hr>

                <!-- Якорное меню -->
                <ul style="color:aqua; margin-left:2rem">
                    <li><a href="#custom-blockchain">Custom Blockchain Based on Cosmos SDK</a></li>
                    <li><a href="#decentralized-messenger">Unique Decentralized Messenger</a></li>
                    <li><a href="#native-coin">Native Coin and Stablecoins</a></li>
                    <li><a href="#web3-platform">Web3 Application Platform</a></li>
                    <li><a href="#decision-making">Unique Decision-Making System</a></li>
                    <li><a href="#task-exchange">Task Exchange Platform</a></li>
                    <li><a href="#reward-system">Reward System</a></li>
                    <li><a href="#proof-of-time">Proof of Time (PoT) Consensus</a></li>
                    <li><a href="#activity-tracking">Activity Tracking System</a></li>
                    <li><a href="#market-opportunities">Market and Opportunities</a></li>
                    <li><a href="#financial-model">Financial Model</a></li>
                    <li><a href="#roadmap">Roadmap</a></li>
                    <li><a href="#team">Team</a></li>
                    <li><a href="#conclusion">Conclusion</a></li>
                </ul>
                <hr><br>

                <!-- Описания с якорями -->
                <p class="spacer" id="custom-blockchain">
                    {{-- <strong>Custom Blockchain Based on Cosmos SDK</strong> --}}
                    <img src="/img/home/2.png">
                    We have developed a high-performance blockchain using the open-source Cosmos SDK. It offers scalability,
                    security, and compatibility with other blockchains in the Cosmos ecosystem. Additionally, it supports
                    smart contracts and cross-chain interoperability.
                </p>

                <p class="spacer" id="decentralized-messenger">
                    {{-- <strong>Unique Decentralized Messenger</strong> --}}
                    <img src="/img/home/3.png">
                    Our decentralized messenger ensures complete data privacy and security. It integrates with the DAODES
                    ecosystem for community management and voting, making it applicable in business, education, and social
                    projects.
                </p>

                <p class="spacer" id="native-coin">
                    {{-- <strong>Native Coin and Stablecoins</strong> --}}
                    <img src="/img/home/4.png">
                    DAODES native token (DDS) is used for transaction fees, voting, and staking. We also offer stablecoins
                    pegged to fiat currencies and crypto assets to ensure stability and convenience in transactions.
                </p>

                <p class="spacer" id="web3-platform">
                    {{-- <strong>Web3 Application Platform</strong> --}}
                    <img src="/img/home/5.png">
                    Our platform provides a universal environment for developing and launching decentralized applications
                    (dApps). It supports various sectors, including finance, education, logistics, gaming, and social
                    projects.
                </p>

                <p class="spacer" id="decision-making">
                    {{-- <strong>Unique Decision-Making System</strong> --}}
                    <img src="/img/home/6.png">
                    DAO decisions are recorded in IPFS (InterPlanetary File System) to ensure transparency and immutability.
                    Every community member can submit proposals and participate in voting.
                </p>

                <p class="spacer" id="task-exchange">
                    {{-- <strong>Task Exchange Platform</strong> --}}
                    <img src="/img/home/7.png">
                    Our task exchange platform allows users to find contractors and complete tasks. Payments can be made in
                    DAODES tokens or stablecoins, and a rating system ensures service quality.
                </p>

                <p class="spacer" id="reward-system">
                    {{-- <strong>Reward System</strong> --}}
                    <img src="/img/home/8.png">
                    Application developers receive up to 30% of transactions in their programs, incentivizing the creation
                    of
                    high-quality and in-demand dApps.
                </p>

                <p class="spacer" id="proof-of-time">
                    {{-- <strong>Proof of Time (PoT) Consensus</strong> --}}
                    <img src="/img/home/9.png">
                    Our innovative consensus algorithm, Proof of Time (PoT), considers the time spent in the network,
                    ensuring
                    fair reward distribution and preventing centralization.
                </p>

                <p class="spacer" id="activity-tracking">
                    {{-- <strong>Activity Tracking System</strong> --}}
                    <img src="/img/home/10.png">
                    We track each participant's contribution to the ecosystem's development and limit a single participant's
                    influence share to no more than 8%, ensuring decentralization.
                </p>

                <!-- Market and Opportunities -->
                <p class="spacer" id="market-opportunities">
                    {{-- <strong>Market and Opportunities</strong> --}}
                    <img src="/img/home/11.png">
                    The Web3 and DAO market is growing rapidly, and DAODES occupies a unique niche by combining technologies
                    and tools for creating decentralized ecosystems. Our platform is suitable for businesses, government
                    organizations, startups, and communities.
                </p>

                <!-- Financial Model -->
                <p class="spacer" id="financial-model">
                    {{-- <strong>Financial Model</strong> --}}
                    <img src="/img/home/12.png">
                    Primary revenue sources include transaction fees, platform usage fees, staking, and token issuance.
                    Investments will be directed toward blockchain development, marketing, and team expansion.
                </p>

                <!-- Roadmap -->
                <p class="spacer" id="roadmap">
                    {{-- <strong>Roadmap</strong> --}}
                    <img src="/img/home/13.png">
                    <strong>2025–2026:</strong> Launch of the main blockchain network, DDS token issuance, release of the
                    messenger, and task exchange.
                    <br>
                    <strong>2026:</strong> Integration with major blockchain ecosystems, launch of the dApp platform.
                    <br>
                    <strong>2027:</strong> Expansion of DAO functionality, entry into the international market.
                </p>

                <!-- команда -->
                <div class="spacer" id="team">
                    <img src="/img/home/14.png">
                    <h1>Our Team — Our Strength</h1>
                    <p><strong>Sergey</strong> — Technical Lead. <strong>Expert in programming with extensive
                            experience.</strong> Passionate about innovation and new technologies.
                    </p>
                    <p><strong>Denis</strong> — Marketing Guru. <strong>Enthusiast of blockchain and its potential.</strong>
                        Sees the societal benefits of technology.
                    </p>
                    <p><strong>Valery</strong> — Project Author and Team Visionary<strong> Unites the team and guides the project.</strong></p>
                    <br>
                    <h1>
                        Aims to benefit humanity through the project's technologies.
                    </h1>
                </div>



                <!-- Conclusion -->

                <p class="spacer" id="conclusion">
                    <img src="/img/home/15.png">
                    DAODES is not just a platform but a new paradigm for interaction in Web3. We offer investors a unique
                    opportunity to be part of the revolution in decentralized technologies.
                </p>
                <div align="center">
                    <p class="spacer" style="text-align: center">Thank you for your attention! Let's build the future
                        together!</p>
                </div>
            </div>

            <div class="" align="center">
                <a href="/register" class="blue_btn">
                    <strong>Become part of the team</strong>
                </a>
            </div>
        </div>

        <div class="pubble-app" data-app-id="128664" data-app-identifier="128664"></div>
        <script type="text/javascript" src="https://cdn.chatify.com/javascript/loader.js" defer></script>


    </main>
@endsection
