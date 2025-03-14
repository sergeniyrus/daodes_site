<?php

return [
    'title' => 'Modified DPoS Consensus Mechanism',
    'paragraph1' => 'For the most part, consensus will run on the Tendermint BFT engine. However, it will be modified to implement a non-standard, upgraded DPoS (Delegated Proof of Stake) consensus mechanism.',
    'differences_title' => 'Key Differences from Standard DPoS',
    'differences_list' => [
        'Delegator Pool (P1): In standard DPoS, delegators delegate their tokens directly to a chosen validator. In our modified DPoS, delegators delegate their funds to a general Pool of Delegators (P1). The total amount in this pool is considered as 100% for the block of delegators.',
        'Validator Pool (P2): Validators form their own pool (P2), which determines their voting power. Validators are selected based on their contributions and performance within the network.',
        'Voting Power Limitation: The project enforces a condition at both the blockchain and DAO levels that no participant can hold more than 8% of the total voting power.',
    ],
    'dao_title' => 'DAO-Based Project Management',
    'dao_paragraph1' => 'All project management is based on the principles of a DAO (Decentralized Autonomous Organization). The DAO is managed by active project participants who contribute to the project through various actions, such as:',
    'dao_list' => [
        'Programming and development',
        'Participating in hackathons',
        'Copywriting and content creation',
        'Translations and localization',
        'Public relations (PR) and marketing',
        'Proposing ideas for project improvements',
    ],
    'dao_paragraph2' => 'These participants are rewarded based on their contributions, ensuring that the project benefits from a diverse range of skills and expertise.',
];