<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class PricesAndServicesPage extends Page
{
    protected string $view = 'filament.pages.prices-and-services';

    protected static ?string $slug = 'priser-og-tjenester';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?int $navigationSort = 2;

    public function getTitle(): string
    {
        return app()->getLocale() === 'en' ? 'Prices and services' : 'Priser og tjenester';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'en' ? 'Prices and services' : 'Priser og tjenester';
    }
}
