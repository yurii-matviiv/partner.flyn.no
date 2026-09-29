<?php

use App\Http\Controllers\PartnerAccessController;
use App\Http\Controllers\PartnerProfileController;
use App\Http\Middleware\SetPartnerLocale;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/partner-panel/login');

Route::post('/partner/locale/{locale}', [PartnerAccessController::class, 'setLocale'])
    ->whereIn('locale', ['nb', 'en'])
    ->name('partner.locale');

Route::middleware(SetPartnerLocale::class)->group(function (): void {
    Route::post('/partner-panel/access/request-code', [PartnerAccessController::class, 'requestCode'])
        ->middleware('throttle:6,10')
        ->name('partner.access.request-code');

    Route::get('/partner-panel/access/verify', [PartnerAccessController::class, 'showVerifyForm'])
        ->name('partner.access.verify');

    Route::post('/partner-panel/access/verify', [PartnerAccessController::class, 'verifyCode'])
        ->middleware('throttle:10,10')
        ->name('partner.access.verify.submit');

    Route::middleware('auth:partner')->group(function (): void {
    Route::get('/partner-panel/profile', [PartnerProfileController::class, 'edit'])
        ->name('partner.profile.edit');
    Route::post('/partner-panel/profile', [PartnerProfileController::class, 'store'])
        ->name('partner.profile.store');
    Route::post('/partner-panel/profile/skip', [PartnerProfileController::class, 'skip'])
        ->name('partner.profile.skip');
    });
});
