<?php

namespace App\Notifications;

use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Notification;

class PartnerProfileIncompleteNotification extends Notification
{
    public const KEY = 'partner_profile_incomplete';

    public function __construct(private readonly string $language)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isEnglish = $this->language === 'en';

        return [
            ...FilamentNotification::make()
                ->warning()
                ->title($isEnglish ? 'Complete your company profile' : 'Fullfør bedriftsprofilen når du er klar')
                ->body($isEnglish
                    ? 'Company and contact details are still missing. Add them when you are ready.'
                    : 'Bedrifts- og kontaktinformasjon mangler fortsatt. Legg den til når du er klar.')
                ->actions([
                    Action::make('complete_partner_profile')
                        ->label($isEnglish ? 'Complete profile' : 'Fullfør profil')
                        ->url(\App\Filament\Pages\PartnerProfilePage::getUrl()),
                ])
                ->getDatabaseMessage(),
            'partner_key' => self::KEY,
        ];
    }
}
