<?php

namespace App\Filament\Pages;

class UnpaidInvoicesPage extends PartnerPortalSectionPage
{
    protected static ?string $slug = 'ubetalte-fakturaer';
    protected static ?int $navigationSort = 5;

    protected function sectionKey(): string { return 'unpaid'; }
    protected static function norwegianNavigationLabel(): string { return 'Ubetalte fakturaer'; }
    protected static function englishNavigationLabel(): string { return 'Unpaid invoices'; }
}
