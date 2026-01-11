<?php

return [
    'no_username' => "⚠️ You do not have a public username set in Telegram.\n\n"
        . "Please set your username in Telegram settings and send /start again.",

    'profile_not_found' => "🔍 Your username \"@:username\" was not found in the DAODES system.\n\n"
        . "Please ensure that:\n"
        . "1️⃣ You are registered on the website.\n"
        . "2️⃣ You have entered this exact username in the “Nickname” field of your profile.\n\n"
        . "After that, send /start again.",

    'bound_success' => "🎉 Successfully connected, @:username!\n\n"
        . "You will now receive notifications about new private messages and mentions.\n\n"
        . "💡 *Tip*: Press `/start` again if you change your Telegram username.",

    'new_message' => [
        'personal' => "🔐 You have a new secret message from **:sender_login**.\n"
            . "👉 Go to chat:",

        'group' => "💬 New messages in the secret chat **\":chat_name\"**.\n"
            . "👉 Go to chat:",
    ],

    'open_on_site' => '🌐 Open on Website',
    'open_in_mini_app' => '📱 Open in Mini App',
];