<?php
// config\locales.php
return [
    // список підтримуваних локалей сайту
    'supported' => ['nb', 'en', 'uk', 'pl'],

    // основна (дефолтна) локаль
    'default' => env('APP_LOCALE', 'nb'),

    // fallback-локаль (для перекладів)
    'fallback' => env('APP_FALLBACK_LOCALE', 'nb'),

    // faker-локаль (для генерації тестових даних)
    'faker' => env('APP_FAKER_LOCALE', 'nb_NO'),
];
