<?php

return [
    'title' => 'Типы стейблкоинов на современном рынке',
    'centralized_title' => '1. Централизованные стейблкоины (USDT, USDC, BUSD)',
    'centralized_description' => 'Обеспечены фиатными деньгами и эквивалентом в акциях, облигациях и т.д.',
    'centralized_pros_title' => 'Плюсы:',
    'centralized_pros_list' => [
        'Первые из стейблкоинов на рынке.',
        'Большая капитализация.',
        'Признание и частое присутствие на большом количестве бирж.',
    ],
    'centralized_cons_title' => 'Минусы:',
    'centralized_cons_list' => [
        'Централизация.',
        'Возможность блокировки токенов на уровне смарт-контракта.',
        'Существуют большие сомнения в достаточной обеспеченности полной эмиссии.',
    ],
    'crypto_backed_title' => '2. Стейблкоины, обеспеченные криптовалютой (DAI)',
    'crypto_backed_description' => 'Сложно найти другие популярные стейблкоины такого типа на рынке.',
    'crypto_backed_pros_title' => 'Плюсы:',
    'crypto_backed_pros_list' => [
        'Высокая децентрализация и безопасность (в случае с DAI. Хотя он зависит от централизованного USDC, который обеспечивает около 32% от общей стоимости эмиссии DAI. Стоит отметить, что Circle (эмитент USDC) допустил возможность блокировки залога, содержащегося в смарт-контракте DAI).',
        'В случае с DAI существует повышенное обеспечение, что негативно влияет на доходность приложения и меньшую ликвидность проекта для самого эмитента.',
    ],
    'crypto_backed_cons_title' => 'Минусы:',
    'crypto_backed_cons_list' => [
        'Участвуя в эмиссии DAI, пользователи должны вносить избыточное обеспечение для получения необходимого количества токенов. Так, например, чтобы выпустить DAI на эквивалент $100, необходимо внести залог, например, в монете Eth на сумму не менее $150, чтобы позиции не были ликвидированы.',
        'Высокие комиссии сети Ethereum.',
    ],
    'algorithmic_title' => '3. Алгоритмические стейблкоины (USDD, USDN, UST)',
    'algorithmic_description' => 'Последние два радикально потеряли привязку к $.',
    'algorithmic_pros_title' => 'Плюсы:',
    'algorithmic_pros_list' => [
        'Высокая ликвидность обеспечивающей монеты и ее рост с ростом капитализации предоставляемого стейблкоина.',
    ],
    'algorithmic_cons_title' => 'Минусы:',
    'algorithmic_cons_list' => [
        'Возможность потери привязки к целевой фиатной валюте в случае резких колебаний на рынке криптовалют и недостаточной проработки алгоритмов безопасности.',
    ],
    'paragraph1' => 'На данный момент рынок не испытывает недостатка в представителях экосистемы стейблкоинов. Их действительно много. Даже всех не упомнишь.',
    'paragraph2' => 'Однако, как упоминалось выше, почти все они имеют некоторые недостатки или даже критические уязвимости, которые мошенники или конкуренты рано или поздно используют.',
];