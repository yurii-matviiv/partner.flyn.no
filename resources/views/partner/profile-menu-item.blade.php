{{-- First item after the authenticated user's name in the avatar dropdown. --}}
<div class="fi-dropdown-list">
    <a href="{{ \App\Filament\Pages\PartnerProfilePage::getUrl() }}" class="fi-dropdown-list-item fi-dropdown-list-item-color-gray">
        <x-filament::icon icon="heroicon-o-user-circle" class="fi-dropdown-list-item-icon" />
        <span class="fi-dropdown-list-item-label">
            {{ app()->getLocale() === 'en' ? 'My profile' : 'Min profil' }}
        </span>
    </a>
</div>
