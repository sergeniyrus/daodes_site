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
                margin-bottom: 20px;
                /* Adds space between paragraphs */
            }
            .pubble-app {
                margin: 0px auto;
            }
        </style>
        <div class="container">
            <div class="">
                <strong>
                    <h1>Decentralized Ecosystems Based on DAO</h1>
                </strong>

                <p class="spacer">
                    DAODES presents an innovative decentralized ecosystem built on the principles of DAO (Decentralized
                    Autonomous Organization). Our mission is to create a universal platform for Web3 that integrates
                    blockchain technology, decentralized applications, and unique tools for management and interaction.
                </p>

                <strong>Key Advantages of DAODES:</strong>
                <hr>

                <ol>
                    <li>
                        <strong>
                            <h2>Custom Blockchain Based on Cosmos SDK</h2>
                        </strong>
                        <ul>
                            <li>We have developed a high-performance blockchain using the open-source Cosmos SDK.</li>
                            <li>Scalability, security, and compatibility with other blockchains in the Cosmos ecosystem.
                            </li>
                            <li>Support for smart contracts and cross-chain interoperability.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Unique Decentralized Messenger</h2>
                        </strong>
                        <ul>
                            <li>Complete data privacy and security.</li>
                            <li>Integration with the DAODES ecosystem for community management and voting.</li>
                            <li>Applicable in business, education, and social projects.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Native Coin and Stablecoins</h2>
                        </strong>
                        <ul>
                            <li>DAODES native token (DDS) for transaction fees, voting, and staking.</li>
                            <li>Stablecoins pegged to fiat currencies and crypto assets for stability and convenience in
                                transactions.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Web3 Application Platform</h2>
                        </strong>
                        <ul>
                            <li>A universal environment for developing and launching decentralized applications (dApps).
                            </li>
                            <li>Support for various sectors: finance, education, logistics, gaming, social projects, and
                                more.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Unique Decision-Making System</h2>
                        </strong>
                        <ul>
                            <li>DAO decisions are recorded in IPFS (InterPlanetary File System) to ensure transparency and
                                immutability.</li>
                            <li>Every community member can submit proposals and participate in voting.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Task Exchange Platform</h2>
                        </strong>
                        <ul>
                            <li>A platform for finding contractors and completing tasks.</li>
                            <li>Payment in DAODES tokens or stablecoins.</li>
                            <li>Rating system to ensure service quality.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Reward System</h2>
                        </strong>
                        <ul>
                            <li>Application developers receive up to 30% of transactions in their programs.</li>
                            <li>Incentivizing the creation of high-quality and in-demand dApps.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Proof of Time (PoT) Consensus</h2>
                        </strong>
                        <ul>
                            <li>An innovative consensus algorithm that considers time spent in the network.</li>
                            <li>Fair reward distribution and prevention of centralization.</li>
                        </ul>
                    </li>
                    <li class="spacer">
                        <strong>
                            <h2>Activity Tracking System</h2>
                        </strong>
                        <ul>
                            <li>Tracking each participant's contribution to the ecosystem's development.</li>
                            <li>Limiting a single participant's influence share (no more than 8%) to ensure
                                decentralization.</li>
                        </ul>
                    </li>
                </ol>

                <strong>
                    <h2>Market and Opportunities</h2>
                </strong>
                <p class="spacer">
                    The Web3 and DAO market is growing rapidly, and DAODES occupies a unique niche by combining technologies
                    and tools for creating decentralized ecosystems. Our platform is suitable for businesses, government
                    organizations, startups, and communities.
                </p>

                <strong>
                    <h2>Financial Model</h2>
                </strong>
                <ul class="spacer">
                    <li>Primary revenue sources: transaction fees, platform usage fees, staking, and token issuance.</li>
                    <li>Investments will be directed toward blockchain development, marketing, and team expansion.</li>
                </ul>

                <strong>
                    <h2>Roadmap</h2>
                </strong>
                <ol class="spacer">
                    <li><strong>2025–2026:</strong> Launch of the main blockchain network, DDS token issuance, release of
                        the messenger, and task exchange.</li>
                    <li><strong>2026:</strong> Integration with major blockchain ecosystems, launch of the dApp platform.
                    </li>
                    <li><strong>2027:</strong> Expansion of DAO functionality, entry into the international market.</li>
                </ol>

                <p class="spacer">
                    DAODES is not just a platform but a new paradigm for interaction in Web3. We offer investors a unique
                    opportunity to be part of the revolution in decentralized technologies.
                </p>

                <p class="spacer">Thank you for your attention! Let's build the future together!</p>

                <div class=""  align="center">
                    <a href="/register" class="blue_btn">
                        <h1>Become part of the team</h1>
                    </a>
                </div>
            </div>
        </div>

        <div class="pubble-app" data-app-id="128664" data-app-identifier="128664"></div>
        <script type="text/javascript" src="https://cdn.chatify.com/javascript/loader.js" defer></script>


    </main>
@endsection
