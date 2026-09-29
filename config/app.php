<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

'timezone' => 'Europe/Oslo',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

'locale' => env('APP_LOCALE', 'nb'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'nb'),
'faker_locale' => env('APP_FAKER_LOCALE', 'nb_NO'),



    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
    
'company_name' => env('COMPANY_NAME'),
'company_org_number' => env('COMPANY_ORG_NUMBER'),
'company_address' => env('COMPANY_ADDRESS'),
'company_email' => env('COMPANY_EMAIL'),
'company_website' => env('COMPANY_WEBSITE'),
'company_phone' => env('COMPANY_PHONE'),

'company_bank_account' => env('COMPANY_BANK_ACCOUNT'),
'company_iban' => env('COMPANY_IBAN'),
'company_swift' => env('COMPANY_SWIFT'),

/*
|--------------------------------------------------------------------------
| Local dev tools (npm run build button in user menu)
|--------------------------------------------------------------------------
|
| НЕ прив'язано до APP_ENV — тут .env тримає APP_ENV=production і локально
| теж, тому app()->environment('local') не відрізняє хостинг від локальної
| машини (перевірено на практиці 2026-07-26, кнопка спрацювала на хостингу).
| Замість цього — окремий прапорець: локальний .env ставить його true,
| .env на хостингу цього ключа просто не має → false → кнопка не рендериться
| і route її відмовляє. Ніколи не додавати FLYN_LOCAL_DEV_TOOLS=true в .env
| на хостингу.
|
*/

'local_dev_tools' => (bool) env('FLYN_LOCAL_DEV_TOOLS', false),

/*
|--------------------------------------------------------------------------
| External actions safety
|--------------------------------------------------------------------------
|
| APP_ENV не є джерелом правди для локально/хостинг у цьому проєкті.
| За замовчуванням зовнішні дії заблоковані: локальна копія може читати
| Fiken, але не може створювати/відправляти/видаляти в Fiken, а клієнтські
| email перенаправляються тільки на тестову адресу. На хостингу потрібно
| явно встановити FLYN_EXTERNAL_ACTIONS_MODE=production.
|
*/

'external_actions_mode' => env('FLYN_EXTERNAL_ACTIONS_MODE', 'local_safe'),
'local_test_email' => env('FLYN_LOCAL_TEST_EMAIL', 'matviiv.yurii.no@gmail.com'),
// Disabled by default. When explicitly enabled locally, only reversible
// invoice-draft operations are allowed and only for this exact Fiken company.
'local_test_fiken_drafts_enabled' => env('FLYN_LOCAL_TEST_FIKEN_DRAFTS', false),
'local_test_fiken_company_slug' => env('FLYN_LOCAL_TEST_FIKEN_COMPANY_SLUG', ''),

];
