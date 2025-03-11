<?php

return [
    'accounting_title' => 'Accounting for Activity in the DES Project',
    'accounting_description' => 'The DES project is managed by Active Members of the Community (referred to as the Asset), who prioritize the social, functional, and decentralized aspects of the network over its economic component.',

    'principles_title' => 'Key Principles of Activity Accounting',
    'principles_list' => [
        'initial_system' => 'At the first stage of the project, a system is introduced (see Table 1) to identify and involve the most active community members in project management based on their contributions to the project\'s development.',
        'contribution_assessment' => 'Participants\' contributions are assessed using Table 1. The more utility points a participant earns, the higher their status and the greater their share of management tokens during the initial issuance.',
        'consensus_adjustments' => 'During the development and modernization phase, adjustments to the consensus-building process are allowed, subject to approval by the Community Asset through voting using utility points from Table 1.',
        'smart_contract' => 'After launch, consensus is achieved using management tokens, which allow participation in voting on proposals but have no economic value.',
        'post_launch_changes' => 'Changes to the consensus after the launch of the smart contract/blockchain require tokenized voting by the project Asset.',
        'security_priority' => 'When considering proposals, the security and decentralization of the project must take precedence over economic considerations.',
    ],

    'distribution_title' => 'Management Token Distribution',
    'distribution_list' => [
        'initial_issuance' => [
            'title' => 'Initial Issuance',
            'description' => 'Management tokens are distributed based on:',
            'criteria' => [
                'involvement' => 'The level of involvement in the project (quality and quantity of actions contributing to the project\'s development, as per Table 1).',
                'reputation' => 'The participant\'s reputation (their participation in network management should not raise doubts among other participants).',
            ],
        ],
        'community_pool' => [
            'title' => 'Community Pool',
            'description' => 'A percentage (to be determined by voting) of all transactions in the Liquidity Pool is transferred to the Community Pool, which funds network modernization (e.g., payments to developers, marketing, and motivation of active community members). Allocation of funds from the Community Pool requires tokenized voting by the Asset.',
        ],
        'pension_fund' => [
            'title' => 'Pension Fund',
            'description' => 'The capitalization of the Pension Fund is equivalent to 100% of the management tokens. Participants can return their management tokens to the Pension Fund at any time and receive a proportional share of the funds. If a participant leaves the activist pool, they receive DES tokens equivalent to their share of management tokens from the Pension Fund.',
        ],
    ],

    'revocation_title' => 'Management Token Revocation',
    'revocation_list' => [
        'revocation_process' => 'An Asset member can initiate a vote to revoke management tokens from another member. If the vote passes, the tokens are returned to the Pension Fund, and the owner receives compensation proportional to the number of votes against the revocation.',
        'delegators_validators' => 'Delegators and Validators can also initiate the revocation of management tokens without providing compensation.',
    ],

    'motivators_title' => 'Economic Motivators and Inactivity',
    'motivators_list' => [
        'economic_motivators' => 'Each participant has an economic incentive to contribute to the network, as they can withdraw their share of the Pension Fund and retire from network management at any time.',
        'inactivity_penalties' => 'Participants who are inactive (failing to meet DAO activity criteria) lose the opportunity to receive management tokens after 5 consecutive instances. Continued inactivity results in exclusion from the Asset and transfer to a monitoring channel.',
    ],

    'weekly_analysis_title' => 'Weekly Activity Analysis',
    'weekly_analysis_description' => 'Based on Table 1, a weekly activity analysis is conducted to redistribute utility points and management tokens from inactive participants back to active mining.',
];