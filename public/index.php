<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Оверлей швидкодії (2026-08-01, аудит: SQL — лише 2-6% серверного часу на
// хостингу, причина деінде в PHP-шарі) — контрольна точка одразу після
// автозавантажувача, ДО побудови Laravel-контейнера. Разом з FLYN_APP_BOOTED
// нижче й контрольною точкою в TrackPagePerformance middleware дає розбивку
// серверного часу на фази (автозавантаження / побудова контейнера /
// boot провайдерів+роутинг / власне рендер сторінки), а не тільки загальну
// цифру — щоб бачити, ЯКА фаза реально гальмує на хостингу.

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';


// Точна розбивка колишнього спільного показника "boot+routing". Ці callback-и
// прив'язані до реальних bootstrapper-ів Laravel, а не до приблизних місць у
// прикладному коді: конфігурація → реєстрація провайдерів → boot провайдерів.
// Самі мітки — лише microtime(), без SQL та без зміни логіки запиту.

$app->handleRequest(Request::capture());
