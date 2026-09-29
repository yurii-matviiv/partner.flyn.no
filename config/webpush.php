<?php

/**
 * Web Push / VAPID конфігурація.
 *
 * Згенерувати ключі: php artisan webpush:vapid
 * Потім вставити в .env:
 *   VAPID_PUBLIC_KEY=...
 *   VAPID_PRIVATE_KEY=...
 *
 * VAPID_PUBLIC_KEY також потрібен у JS (виводиться в blade через config()).
 */
return [
    'vapid_public_key'  => env('VAPID_PUBLIC_KEY', ''),
    'vapid_private_key' => env('VAPID_PRIVATE_KEY', ''),
];
