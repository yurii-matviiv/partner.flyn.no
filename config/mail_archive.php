<?php

/**
 * Копія надісланих системою листів у папку «Надіслані» реальної поштової скриньки.
 *
 * Навіщо: система шле через SMTP (info@flyn.no), а SMTP лише ВІДПРАВЛЯЄ — копії в
 * папці Sent не з'являється, і в веб-пошті історія листування з клієнтом обривається.
 * Після відправки ми додаємо той самий лист у Sent через IMAP APPEND, і в скриньці
 * він виглядає так, ніби його написали вручну.
 *
 * enabled = false за замовчуванням: поки в .env не задані IMAP-доступи, нічого не
 * відбувається і поведінка відправки не змінюється взагалі.
 */
return [
    'enabled' => (bool) env('MAIL_IMAP_ARCHIVE', false),

    'host' => env('MAIL_IMAP_HOST', env('MAIL_HOST')),
    'port' => (int) env('MAIL_IMAP_PORT', 993),
    // ssl — імпліцитний TLS на 993 (звичайний варіант для cPanel-хостингу).
    'encryption' => env('MAIL_IMAP_ENCRYPTION', 'ssl'),

    'username' => env('MAIL_IMAP_USERNAME', env('MAIL_USERNAME')),
    'password' => env('MAIL_IMAP_PASSWORD', env('MAIL_PASSWORD')),

    /**
     * Назва папки «Надіслані» різна в різних поштових серверах: cPanel/Dovecot
     * зазвичай має INBOX.Sent, інші — Sent. Пробуємо по черзі, перша успішна
     * запам'ятовується в логах; можна зафіксувати одну через MAIL_IMAP_SENT_FOLDER.
     */
    'sent_folders' => array_values(array_filter([
        env('MAIL_IMAP_SENT_FOLDER'),
        'INBOX.Sent',
        'Sent',
        'Sent Items',
        'Sent Messages',
    ])),

    'timeout' => (int) env('MAIL_IMAP_TIMEOUT', 10),
];
