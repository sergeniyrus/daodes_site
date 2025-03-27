<?php

return [
    'title' => 'Encouragement of Validators and Delegators',
    'description' => 'Validators and delegators are incentivized to ensure business continuity and liquidity through a modified DPoS consensus mechanism. The system is designed to reward both validators and delegators based on their contributions to the network.',

    'pools' => [
        'title' => 'Validator and Delegator Pools',
        'delegator_pool' => [
            'title' => 'Delegator Pool (P1)',
            'description' => 'Delegators contribute their tokens to a general pool (P1). The voting power of this pool is distributed among validators based on their Uptime (the number of blocks signed consecutively without omissions, fines, or malicious actions) over a period of 10,000 blocks. Validators are ranked, and their voting power is updated periodically based on their performance.',
        ],
        'validator_pool' => [
            'title' => 'Validator Pool (P2)',
            'description' => 'Transaction fees collected during the 10,000-block period are placed in a separate pool (P2). This pool is split 50/50 between validators and delegators.',
        ],
    ],

    'earnings' => [
        'title' => 'Earnings Distribution',
        'validators' => [
            'title' => 'Validators',
            'description' => 'Validators earn rewards based on the number of blocks they sign. For every signed block, validators receive 0.01% of the total rewards pool (P2).',
        ],
        'delegators' => [
            'title' => 'Delegators',
            'description' => 'Delegators receive 50% of the rewards pool (P2), distributed proportionally based on their share of the total delegator pool (P1). The system adjusts in real-time to account for changes in the delegator pool during the 10,000-block period.',
        ],
    ],

    'scheme' => 'The distribution scheme of remuneration can be represented as follows:',
];