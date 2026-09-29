<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;
use Illuminate\Contracts\Support\Htmlable;

class PartnerDashboard extends Dashboard
{
    public function getTitle(): string|Htmlable
    {
        return app()->getLocale() === 'en' ? 'Overview' : 'Oversikt';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'en' ? 'Overview' : 'Oversikt';
    }
}
