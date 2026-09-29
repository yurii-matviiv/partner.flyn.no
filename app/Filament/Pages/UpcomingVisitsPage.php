<?php

namespace App\Filament\Pages;

class UpcomingVisitsPage extends PartnerPortalSectionPage
{
    protected static ?string $slug = 'kommende-besok';
    protected static ?int $navigationSort = 4;

    protected function sectionKey(): string { return 'visits'; }
    protected static function norwegianNavigationLabel(): string { return 'Kommende besøk'; }
    protected static function englishNavigationLabel(): string { return 'Upcoming visits'; }
}
