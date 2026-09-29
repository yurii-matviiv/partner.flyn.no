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

    'bring' => [
        'uid' => env('BRING_API_UID'),
        'key' => env('BRING_API_KEY'),
    ],

    'fiken' => [
        'token'        => env('FIKEN_API_TOKEN'),
        'company_slug' => env('FIKEN_COMPANY_SLUG'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    // Відправка від імені менеджерів Google Workspace (@flyn.no) через Gmail API.
    // Ключ службового акаунта лежить у проєкті (storage/ не в git); шлях — відносно кореня проєкту.
    'google_mail' => [
        'service_account_json' => env('GOOGLE_MAIL_SERVICE_ACCOUNT_JSON', 'storage/app/private/google/google-mail-service-account.json'),
    ],

    // Автовхід менеджера у вебпошту mail.flyn.no (Roundcube) без пароля:
    // UAPI Session::create_webmail_session_for_mail_user. Токен — cPanel → Security → Manage API Tokens.
    'cpanel' => [
        'host' => env('CPANEL_HOST', 'bifrost.domene.no'),
        'user' => env('CPANEL_USER'),
        'api_token' => env('CPANEL_API_TOKEN'),
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

    // Basic Auth для CSV-фіда конверсій Google Ads
    // (/api/google-ads/conversions.csv). Google Ads забирає фід за розкладом
    // з цими credentials — при ротації пароля оновити і в Google Ads.
    'google_ads_csv' => [
        'username' => env('GOOGLE_ADS_CSV_USERNAME'),
        'password' => env('GOOGLE_ADS_CSV_PASSWORD'),
    ],

    // Окремий, write-only канал для secondary-конверсії regular_client_flyn.
    // Він навмисно не пов'язаний із CSV-фідом lead_flyn / sale_flyn.
    'google_ads_data_manager' => [
        'enabled' => env('GOOGLE_ADS_DATA_MANAGER_ENABLED', false),
        // Existing FLYN Ads service-account key (flyn-ads-conversions@flyn-ads-api…).
        'service_account_json' => env('GOOGLE_ADS_DATA_MANAGER_SERVICE_ACCOUNT_JSON', 'storage/app/private/google/flyn-ads-api.json'),
        'customer_id' => env('GOOGLE_ADS_CUSTOMER_ID', '4494671076'),
        'conversion_action_id' => env('GOOGLE_ADS_REGULAR_CLIENT_CONVERSION_ACTION_ID', '7793092500'),
        // A separate, CRM-controlled Customer Match audience. Keep this ID
        // distinct from the two historical lists in Google Ads.
        'customer_match_user_list_id' => env('GOOGLE_ADS_CUSTOMER_MATCH_USER_LIST_ID', '9477630182'),
        // This must be set deliberately by the account owner after accepting
        // Google's Customer Match terms. It prevents accidental PII transfer.
        'customer_match_terms_accepted' => env('GOOGLE_ADS_CUSTOMER_MATCH_TERMS_ACCEPTED', false),
        'click_window_days' => env('GOOGLE_ADS_REGULAR_CLIENT_CLICK_WINDOW_DAYS', 90),
        'value_months' => env('GOOGLE_ADS_REGULAR_CLIENT_VALUE_MONTHS', 3),
        'value_multiplier' => env('GOOGLE_ADS_REGULAR_CLIENT_VALUE_MULTIPLIER', 0.70),
    ],

    // Лише читання агрегованої статистики conversion actions для адмінського
    // екрана контролю. Це інший service account, він не може змінювати Ads.
    'google_ads_reporting' => [
        'service_account_json' => env('GOOGLE_ADS_REPORTING_SERVICE_ACCOUNT_JSON', 'storage/app/private/google/google-ads-service-account.json'),
        'customer_id' => env('GOOGLE_ADS_CUSTOMER_ID', '4494671076'),
        'api_version' => env('GOOGLE_ADS_REPORTING_API_VERSION', 'v24'),
    ],

    'internal_chat_realtime' => [
        'worker_url' => env('CHAT_REALTIME_WORKER_URL', 'https://flyn-chat-websocket.matviiv-yurii-no.workers.dev'),
        'token_secret' => env('CHAT_REALTIME_TOKEN_SECRET'),
        'event_secret' => env('CHAT_REALTIME_EVENT_SECRET'),
    ],

    'crm_realtime' => [
        'worker_url'   => env('CRM_REALTIME_WORKER_URL'),
        'token_secret' => env('CRM_REALTIME_TOKEN_SECRET'),
        'event_secret' => env('CRM_REALTIME_EVENT_SECRET'),
    ],

    'deploy_cache_token' => env('DEPLOY_CACHE_TOKEN'),

    // Shared secret for the flyn.no backend to create a public offer for its own lead.
    'website_offer' => [
        'token' => env('WEBSITE_OFFER_API_TOKEN'),
        'system_user_id' => env('WEBSITE_OFFER_SYSTEM_USER_ID'),
    ],

];
