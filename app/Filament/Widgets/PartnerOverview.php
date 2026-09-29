<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ActiveServicesPage;
use App\Filament\Pages\PaidInvoicesPage;
use App\Filament\Pages\UnpaidInvoicesPage;
use App\Filament\Pages\UpcomingVisitsPage;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PartnerOverview extends StatsOverviewWidget
{
    protected function getHeading(): ?string
    {
        return app()->getLocale() === 'en' ? 'Overview' : 'Oversikt';
    }

    protected function getDescription(): ?string
    {
        return app()->getLocale() === 'en'
            ? 'Services, visits and invoices will be collected here when they become available.'
            : 'Avtaler, besøk og fakturaer samles her når de blir tilgjengelige.';
    }

    protected function getStats(): array
    {
        $isEnglish = app()->getLocale() === 'en';

        return [
            Stat::make($isEnglish ? 'Active services' : 'Aktive tjenester', '0')
                ->description($isEnglish ? 'No active services' : 'Ingen aktive tjenester')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info')
                ->url(ActiveServicesPage::getUrl()),
            Stat::make($isEnglish ? 'Next visit' : 'Neste besøk', '—')
                ->description($isEnglish ? 'No visit planned' : 'Ingen planlagte besøk')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('gray')
                ->url(UpcomingVisitsPage::getUrl()),
            Stat::make($isEnglish ? 'Unpaid invoices' : 'Ubetalte fakturaer', '0 kr')
                ->description($isEnglish ? 'Nothing to pay' : 'Ingenting å betale')
                ->descriptionIcon('heroicon-m-document-currency-dollar')
                ->color('success')
                ->url(UnpaidInvoicesPage::getUrl()),
            Stat::make($isEnglish ? 'Paid this year' : 'Betalt i år', '0 kr')
                ->description($isEnglish ? 'No payments registered' : 'Ingen betalinger registrert')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('gray')
                ->url(PaidInvoicesPage::getUrl()),
        ];
    }
}
