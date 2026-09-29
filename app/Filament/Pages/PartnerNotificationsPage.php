<?php

namespace App\Filament\Pages;

use App\Models\PartnerUser;
use Filament\Pages\Page;

class PartnerNotificationsPage extends Page
{
    protected string $view = 'filament.pages.partner-notifications';

    protected static ?string $slug = 'varsler';

    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return app()->getLocale() === 'en' ? 'Notifications' : 'Varsler';
    }

    protected function getViewData(): array
    {
        /** @var PartnerUser $user */
        $user = auth('partner')->user();

        return [
            'isEnglish' => app()->getLocale() === 'en',
            'notifications' => $user->notifications()
                ->where('data->format', 'filament')
                ->latest()
                ->get(),
        ];
    }
}
