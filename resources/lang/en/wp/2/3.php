<?php

return [
    'paragraph1' => 'Our ecosystem is based on a combined consensus (75% collateral (similar to DAI) + 25% algorithmic support with our own DES coin), with the following characteristic advantages:',
    'advantages_list' => [
        '75% of the collateral: Formed from at least 15 of the most decentralized cryptocurrencies in the world with a large community and multidirectional volatility (for a detailed description, see the decentralized stablecoins section of the project).',
        '25% of the collateral: Implemented algorithmically, using the project\'s own DES coin.',
        'Stablecoin issuance: 70-75 DESStable stablecoins can be issued for the equivalent of $100.',
        'User-created stablecoins: In the future, users will be able to create their own stablecoins in their wallet, as well as previously undeveloped fiat currencies or other goods that work in accordance with the general principles of issuance and security embedded in the ecosystem algorithm.',
    ],
    'description_title' => 'Description:',
    'description_paragraph1' => 'One of the products of our blockchain project is stablecoins of various currencies of the world. If desired, each user will be able to create their own stable currency in the amount they need.',
    'description_paragraph2' => 'The minimum security for our stable currencies will be fractional. 125%-150% (depending on the volatility coefficient of each specific security cryptocurrency) of the security value (in the range from 70 to 80%) is confirmed by means blocked by the user, in the form of one of the cryptocurrencies on the list available for blocking.',
    'description_paragraph3' => 'The list contains about 16 top DeFi cryptocurrencies by market capitalization that have no claims on issues of decentralization and security based on data from CoinMarketCap, CoinGekco and TradingWiev.',
    'description_paragraph4' => 'Another 25% of the value of the additional collateral will be blocked in DES coins from a special pool by a smart contract. Total: each stable coin will be secured with collateral for at least 150%, depending on the level of volatility of each specific security crypto currency.',
    'description_paragraph5' => 'It should be understood that the threshold of 150% is the minimum threshold for collateral. And if the value of the asset falls below this threshold, the collateral will be liquidated. Therefore, if the user plans to return the collateral after the targeted use of stable coins, he will need to be safe and deposit collateral in excess in order to avoid liquidation of his collateral.',
    'collateral_mechanism_title' => 'Collateral Mechanism:',
    'collateral_mechanism_paragraph' => 'The part of the security that is implemented based on the deposit of an excessive amount of cryptocurrencies will work by analogy with the security mechanism in the DAI project from MakerDAO with the following differences:',
    'collateral_mechanism_list' => [
        'Only coins from the "DES Crypto Asset Decentralization Rating" are accepted for deposit into the security vault at the first stages, which ensures that there is no dependence on security problems in centralized assets such as DAI\'s USDC.',
        'The user deposits a collateral asset for only 75% of the target volume of 125-150% (depending on the volatility rating of each security cryptocurrency).',
    ],
    'algorithmic_collateral_title' => 'Algorithmic Collateral with DES Tokens:',
    'algorithmic_collateral_paragraph' => 'The part of the collateral that is implemented through DES tokens will be filled from a pre-formed security fund (in the form of a smart contract) in the amount of 200 million DES, which will be constantly replenished in the amount of 10% of the cost of commissions for all actions with the stablecoins of the DES project.',
    'algorithmic_collateral_paragraph2' => 'With a drop in the volume of collateral for cryptocurrencies, in the range from 125% to 120%, collateral regulation occurs by replenishing the volume of DES tokens transferred from the project\'s security pool of stablecoins to the collateral contract. If the value of collateral continues to fall, cost regulation mechanisms based on arbitration and liquidation of collateral are activated (by analogy with the DAI project).',
];