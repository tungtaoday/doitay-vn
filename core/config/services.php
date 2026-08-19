<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'google' => [
        'client_id' => 'REDACTED_GOOGLE_CLIENT_ID',
        'client_secret' => 'REDACTED_GOOGLE_CLIENT_SECRET',
        'redirect' => env('APP_URL') . '/social-login/callback/google',
    ],
    'google' => [
        'client_id' => 'REDACTED_GOOGLE_CLIENT_ID',
        'client_secret' => 'REDACTED_GOOGLE_CLIENT_SECRET',
        'redirect' => env('APP_URL') . '/social-login/callback/google',
    ],

    // Zalo Mini App "Hồ Sơ Thợ" — dùng dựng deeplink gửi thợ nhận hồ sơ CTV làm hộ:
    // https://zalo.me/s/<miniapp_id>/?tho=<id>&claim=<token>
    'zalo' => [
        'miniapp_id' => env('ZALO_MINIAPP_ID'),
    ],

    // Báo tức thời cho người vận hành (đăng ký mới…) — TelegramNotifier.
    'telegram' => [
        'token'      => env('TELEGRAM_BOT_TOKEN'),
        'admin_chat' => env('TELEGRAM_ADMIN_CHAT'),
    ],

];
