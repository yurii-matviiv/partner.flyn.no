<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

abstract class PartnerPortalSectionPage extends Page
{
    protected string $view = 'filament.pages.partner-portal-section';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    abstract protected function sectionKey(): string;

    protected function getViewData(): array
    {
        $isEnglish = app()->getLocale() === 'en';

        return [
            'section' => $this->sectionCopy($this->sectionKey(), $isEnglish),
        ];
    }

    public function getTitle(): string
    {
        return $this->sectionCopy($this->sectionKey(), app()->getLocale() === 'en')['title'];
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'en' ? static::englishNavigationLabel() : static::norwegianNavigationLabel();
    }

    abstract protected static function norwegianNavigationLabel(): string;

    abstract protected static function englishNavigationLabel(): string;

    /** @return array{title: string, description: string, icon: string, detail: string} */
    private function sectionCopy(string $key, bool $isEnglish): array
    {
        $norwegian = [
            'services' => ['title' => 'Aktive tjenester', 'description' => 'Her får dere oversikt over aktive avtaler og tjenester.', 'icon' => 'heroicon-o-briefcase', 'detail' => 'Dere har ingen aktive tjenester ennå. Når en avtale er bekreftet, vises den her.'],
            'visits' => ['title' => 'Kommende besøk', 'description' => 'Her vises planlagte besøk, tidspunkt og lokaler.', 'icon' => 'heroicon-o-calendar-days', 'detail' => 'Det er ingen planlagte besøk akkurat nå. Neste besøk vises her når det er avtalt.'],
            'unpaid' => ['title' => 'Ubetalte fakturaer', 'description' => 'Her finner dere fakturaer som venter på betaling.', 'icon' => 'heroicon-o-document-currency-dollar', 'detail' => 'Dere har ingen ubetalte fakturaer. Nye fakturaer vises her når de er sendt.'],
            'paid' => ['title' => 'Betalte fakturaer', 'description' => 'Her ser dere fakturaer og betalinger som er registrert.', 'icon' => 'heroicon-o-check-circle', 'detail' => 'Det er ingen registrerte betalinger ennå. Betalte fakturaer vises her etter at betalingen er mottatt.'],
        ];
        $english = [
            'services' => ['title' => 'Active services', 'description' => 'This is where you will find your active agreements and services.', 'icon' => 'heroicon-o-briefcase', 'detail' => 'You do not have any active services yet. A confirmed agreement will appear here.'],
            'visits' => ['title' => 'Upcoming visits', 'description' => 'This is where planned visits, times and premises are shown.', 'icon' => 'heroicon-o-calendar-days', 'detail' => 'There are no planned visits right now. Your next visit will appear here once it is agreed.'],
            'unpaid' => ['title' => 'Unpaid invoices', 'description' => 'This is where you will find invoices waiting for payment.', 'icon' => 'heroicon-o-document-currency-dollar', 'detail' => 'You have no unpaid invoices. New invoices will appear here after they are sent.'],
            'paid' => ['title' => 'Paid invoices', 'description' => 'This is where registered invoices and payments are shown.', 'icon' => 'heroicon-o-check-circle', 'detail' => 'There are no registered payments yet. Paid invoices will appear here once payment has been received.'],
        ];

        return ($isEnglish ? $english : $norwegian)[$key];
    }
}
