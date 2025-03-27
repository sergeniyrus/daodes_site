<?php

/**
 * Copyright (c) 2017 - present
 * LaravelGoogleRecaptcha - recaptcha.php
 * автор: Roberto Belotti - roby.belotti@gmail.com
 * веб: robertobelotti.com, github.com/biscolab
 * Первая версия создана: 12/9/2018
 * Лицензия MIT: https://github.com/biscolab/laravel-recaptcha/blob/master/LICENSE
 */

/**
 * Для правильной настройки посетите https://developers.google.com/recaptcha/docs/start
 */
return [

    /**
     *
     * Ключ сайта
     * Получите ключ сайта @ www.google.com/recaptcha/admin
     *
     */
    'api_site_key'                 => env('RECAPTCHA_SITE_KEY', ''),

    /**
     *
     * Секретный ключ
     * Получите секретный ключ @ www.google.com/recaptcha/admin
     *
     */
    'api_secret_key'               => env('RECAPTCHA_SECRET_KEY', ''),

    /**
     *
     * Версия reCAPTCHA
     * Поддерживаемые версии: "v2", "invisible", "v3",
     *
     * Подробнее @ https://developers.google.com/recaptcha/docs/versions
     *
     */
    'version'                      => 'v2',

    /**
     *
     * Таймаут curl в секундах для проверки токена reCAPTCHA
     * @since v3.5.0
     *
     */
    'curl_timeout'                 => 10,

    /**
     *
     * IP-адреса, для которых проверка будет пропущена
     * IP/CIDR маска сети, например, 127.0.0.0/24, также принимается 127.0.0.1 и предполагается /32
     *
     */
    'skip_ip' => env('RECAPTCHA_SKIP_IP', [
    '127.0.0.1',       // Локальный хост (localhost)
    '95.188.118.100',
    //'10.0.0.0/8',      // Частная сеть класса A (10.0.0.0 - 10.255.255.255)
    //'172.16.0.0/12',   // Частная сеть класса B (172.16.0.0 - 172.31.255.255)
    '192.168.0.0/16',  // Частная сеть класса C (192.168.0.0 - 192.168.255.255)
    '::1',             // IPv6 localhost
    //'fc00::/7',        // IPv6 уникальные локальные адреса (ULA)
    //'fe80::/10',       // IPv6 link-local адреса
]),

    /**
     *
     * Маршрут по умолчанию для проверки токена Google reCAPTCHA
     * @since v3.2.0
     *
     */
    'default_validation_route'     => 'biscolab-recaptcha/validate',

    /**
     *
     * Имя параметра, используемого для отправки токена Google reCAPTCHA на маршрут проверки
     * @since v3.2.0
     *
     */
    'default_token_parameter_name' => 'token',

    /**
     *
     * Код языка Google reCAPTCHA по умолчанию
     * Не влияет на версию v3
     * @see   https://developers.google.com/recaptcha/docs/language
     * @since v3.6.0
     *
     */
    'default_language'             => 'en',

    /**
     *
     * ID формы по умолчанию. Только для "invisible" reCAPTCHA
     * @since v4.0.0
     *
     */
    'default_form_id'              => 'biscolab-recaptcha-invisible-form',

    /**
     *
     * Отложенный рендеринг может быть достигнут путем указания вашей функции обратного вызова onload и добавления параметров к ресурсу JavaScript.
     * Не влияет на версии v3 и invisible
     * @see   https://developers.google.com/recaptcha/docs/display#explicit_render
     * @since v4.0.0
     * Поддерживаемые значения: true, false
     *
     */
    'explicit'                     => true,

    /**
     *
     * Установите домен API. Вы можете использовать "www.recaptcha.net", если "www.google.com" недоступен.
     * (проверка введенного значения не будет выполняться)
     * @see   https://developers.google.com/recaptcha/docs/faq#can-i-use-recaptcha-globally
     * @since v4.3.0
     * По умолчанию 'www.google.com' (ReCaptchaBuilder::DEFAULT_RECAPTCHA_API_DOMAIN)
     *
     */
    'api_domain'                   => 'www.google.com',

    /**
     *
     * Установите `true`, если сообщение об ошибке должно быть пустым
     * @since v5.1.0
     * По умолчанию false
     *
     */
    'empty_message' => false,

    /**
     *
     * Установите либо сообщение об ошибке, либо ключ перевода сообщения об ошибке
     * @since v5.1.0
     * По умолчанию 'validation.recaptcha'
     *
     */
    'error_message_key' => 'validation.recaptcha',

    /**
     *
     * Атрибуты тега g-recaptcha и параметры grecaptcha.render (только для v2)
     * @see   https://developers.google.com/recaptcha/docs/display#render_param
     * @since v4.0.0
     */
    'tag_attributes'               => [

        /**
         * Цветовая тема виджета.
         * Поддерживаемые значения: "light", "dark"
         */
        'theme'            => 'dark',

        /**
         * Размер виджета.
         * Поддерживаемые значения: "normal", "compact"
         */
        'size'             => 'normal',

        /**
         * Индекс табуляции виджета и вызова.
         * Если другие элементы на вашей странице используют индекс табуляции, он должен быть установлен для облегчения навигации пользователя.
         */
        'tabindex'         => 0,

        /**
         * Имя вашей функции обратного вызова, выполняемой при успешной отправке ответа пользователем.
         * Токен g-recaptcha-response передается в вашу функцию обратного вызова.
         * НЕ УСТАНАВЛИВАЙТЕ "biscolabOnloadCallback"
         */
        'callback'         => null,

        /**
         * Имя вашей функции обратного вызова, выполняемой при истечении срока действия ответа reCAPTCHA и необходимости повторной проверки пользователем.
         * НЕ УСТАНАВЛИВАЙТЕ "biscolabOnloadCallback"
         */
        'expired-callback' => null,

        /**
         * Имя вашей функции обратного вызова, выполняемой при возникновении ошибки reCAPTCHA (обычно из-за проблем с сетевым подключением) и невозможности продолжить до восстановления подключения.
         * Если вы укажете функцию здесь, вы должны уведомить пользователя о необходимости повторить попытку.
         * НЕ УСТАНАВЛИВАЙТЕ "biscolabOnloadCallback"
         */
        'error-callback'   => null,
    ]
];