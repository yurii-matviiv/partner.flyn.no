<?php

namespace App\Filament\Pages;

class PaidInvoicesPage extends PartnerPortalSectionPage
{
    protected static ?string $slug = 'betalte-fakturaer';
    protected static ?int $navigationSort = 6;

    protected function sectionKey(): string { return 'paid'; }
    protected static function norwegianNavigationLabel(): string { return 'Betalte fakturaer'; }
    protected static function englishNavigationLabel(): string { return 'Paid invoices'; }
}
