<?php

namespace App\Filament\Pages;

use App\Models\PartnerContact;
use App\Models\PartnerCustomerCompany;
use App\Models\PartnerProfile;
use App\Models\PartnerUser;
use Filament\Pages\Page;

class PartnerProfilePage extends Page
{
    protected string $view = 'filament.pages.partner-profile';

    protected static ?string $slug = 'profile';

    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return app()->getLocale() === 'en' ? 'My profile' : 'Min profil';
    }

    protected function getViewData(): array
    {
        /** @var PartnerUser $user */
        $user = auth('partner')->user();
        $profile = PartnerProfile::query()
            ->where('partner_user_id', $user->id)
            ->first();

        return [
            'user' => $user,
            'profile' => $profile,
            'company' => $profile?->customer_company_id
                ? PartnerCustomerCompany::query()->find($profile->customer_company_id)
                : null,
            'contact' => $profile?->contact_client_id
                ? PartnerContact::query()->find($profile->contact_client_id)
                : null,
            'locale' => app()->getLocale(),
        ];
    }
}
