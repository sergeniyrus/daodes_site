<?php

return [
    'conditions_title' => 'Conditions for Obtaining Validator Status',
    'conditions_description' => 'To become a validator, a participant must:',
    'conditions_list' => [
        'deposit' => 'Deposit the blockchain\'s main coin and a stablecoin available on the platform. The stablecoin deposit must be at least 50% of the value of the blockchain coin deposit.',
        'balance' => 'Maintain the token/stablecoin balance as the value of the blockchain coin increases.',
    ],
    'conditions_footer' => 'The maximum number of validators at the project\'s start is tied to network activity, beginning with 4 validators. During the testnet phase, participants can earn test coins for active participation and completing tasks. These test coins will be proportionally converted to real DES tokens upon the mainnet launch. Active validators may also benefit from a reduced initial stake requirement (30% of the minimum stake, with the project covering the remainder).',

    'collateral_title' => 'Validator Collateral and Penalties',
    'collateral_list' => [
        'unlocking' => 'If a validator loses their position, their collateral is unlocked over 60 days. Stablecoins are unlocked in two stages (50% after 15 days and 50% after 30 days), while blockchain coins are unlocked in three stages (1/3 every 10 days).',
        'penalties' => 'Validators attempting to compromise the network or engage in malicious activities will face fines deducted from their collateral. Repeated offenses may result in the loss of all collateral and permanent exclusion from the validator set.',
    ],

    'voting_title' => 'Voting Power and Voice Tokens',
    'voting_list' => [
        'power' => 'A validator\'s voting power depends on the amount of collateral they have locked and the funds delegated to them by users (via P1). Additionally, 75% of their voting power is determined by the number of illiquid voting tokens they hold, which are distributed proportionally based on their voting activity and Uptime.',
        'voice_tokens' => 'These tokens are non-transferable and cannot be speculated upon. Validators who leave the project after a period of active participation may receive a share of the voice token pool as a severance package.',
    ],

    'dao_title' => 'DAO Governance',
    'dao_description' => 'Only the DAO can submit proposals for voting after an internal preliminary vote. Validators are not required to be DAO members, but their voting activity and Uptime significantly influence their voting power and rewards.',
];