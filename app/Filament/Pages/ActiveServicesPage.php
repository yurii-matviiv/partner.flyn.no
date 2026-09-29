<?php

namespace App\Filament\Pages;

class ActiveServicesPage extends PartnerPortalSectionPage
{
    protected static ?string $slug = 'aktive-tjenester';
    protected static ?int $navigationSort = 3;

    protected function sectionKey(): string { return 'services'; }
    protected static function norwegianNavigationLabel(): string { return 'Aktive tjenester'; }
    protected static function englishNavigationLabel(): string { return 'Active services'; }
}
