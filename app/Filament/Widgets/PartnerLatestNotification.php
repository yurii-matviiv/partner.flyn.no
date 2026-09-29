<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\PartnerNotificationsPage;
use App\Models\PartnerUser;
use Filament\Widgets\Widget;

class PartnerLatestNotification extends Widget
{
    protected string $view = 'filament.widgets.partner-latest-notification';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var PartnerUser|null $user */
        $user = auth('partner')->user();

        return $user?->notifications()
            ->where('data->format', 'filament')
            ->exists() ?? false;
    }

    protected function getViewData(): array
    {
        /** @var PartnerUser $user */
        $user = auth('partner')->user();
        $notification = $user->notifications()
            ->where('data->format', 'filament')
            ->latest()
            ->first();

        return [
            'notification' => $notification,
            'isEnglish' => app()->getLocale() === 'en',
            'notificationsUrl' => PartnerNotificationsPage::getUrl(),
        ];
    }
}
