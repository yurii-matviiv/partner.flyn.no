<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetPartnerLocale;
use App\Http\Middleware\EnsurePartnerProfileComplete;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Actions\Action as FilamentAction;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PartnerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('partner-panel')
            ->path('partner-panel')
            ->authGuard('partner')
            ->login()
            ->brandLogo(asset('images/branding/flyn-service.svg'))
            ->brandLogoHeight('2rem')
            ->brandName('FLYN Partner')
            ->favicon(asset('favicon.ico'))
            ->colors(['primary' => Color::Sky])
            // Як у flyn-erm: логотип у sidebar — єдина кнопка згортання меню на десктопі.
            ->sidebarCollapsibleOnDesktop()
            // Uses the same native Filament bell and right-side slide-over as ERM.
            ->databaseNotifications()
            ->databaseNotificationsPolling(null)
            ->pages([Dashboard::class])
            // Follow the ERM user-menu structure: the authenticated person's
            // name is the menu header and the first action opens their profile.
            // The profile is a full page, not a modal.
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn (): string => filament()->auth()->user()?->name ?? '')
                    ->sort(-2),
                FilamentAction::make('partner-profile')
                    ->label(fn (): string => app()->getLocale() === 'en' ? 'My profile' : 'Min profil')
                    ->icon('heroicon-o-user-circle')
                    ->sort(-1)
                    ->url(fn (): string => route('partner.profile.edit')),
            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_START,
                fn (): string => view('filament.partials.sidebar-brand')->render(),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="' . asset('css/partner-sidebar-brand.css') . '"><link rel="stylesheet" href="' . asset('css/partner-auth.css') . '">',
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => view('partner.login-intro')->render(),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => view('partner.login-access-code')->render(),
            )
            // In the authenticated panel, language choice belongs inside the
            // avatar dropdown. The standalone access/profile pages keep their
            // visible switcher because an avatar menu is not available there.
            ->renderHook(
                PanelsRenderHook::USER_MENU_PROFILE_AFTER,
                fn (): string => view('partner.language-menu-items')->render(),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetPartnerLocale::class,
                AuthenticateSession::class,
                EnsurePartnerProfileComplete::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
