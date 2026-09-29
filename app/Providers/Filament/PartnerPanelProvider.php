<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetPartnerLocale;
use App\Http\Middleware\EnsurePartnerProfileComplete;
use App\Filament\Pages\PartnerProfilePage;
use App\Filament\Pages\PricesAndServicesPage;
use App\Filament\Pages\ActiveServicesPage;
use App\Filament\Pages\UpcomingVisitsPage;
use App\Filament\Pages\UnpaidInvoicesPage;
use App\Filament\Pages\PaidInvoicesPage;
use App\Filament\Pages\PartnerDashboard;
use App\Filament\Pages\PartnerNotificationsPage;
use App\Filament\Widgets\PartnerOverview;
use App\Filament\Widgets\PartnerLatestNotification;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Actions\Action as FilamentAction;
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
            ->pages([
                PartnerDashboard::class,
                PartnerProfilePage::class,
                PricesAndServicesPage::class,
                ActiveServicesPage::class,
                UpcomingVisitsPage::class,
                UnpaidInvoicesPage::class,
                PaidInvoicesPage::class,
                PartnerNotificationsPage::class,
            ])
            ->widgets([
                PartnerOverview::class,
                PartnerLatestNotification::class,
            ])
            // Follow the ERM user-menu structure: the authenticated person's
            // name is the menu header and the first action opens their profile.
            // The profile is a full page, not a modal.
            ->userMenuItems([
                // Keep Filament's own label: it resolves the current Partner
                // user correctly and falls back to their verified email.
                'profile' => fn (FilamentAction $action): FilamentAction => $action
                    ->url(null)
                    ->sort(-2),
            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_START,
                fn (): string => view('filament.partials.sidebar-brand')->render(),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="' . asset('css/partner-sidebar-brand.css') . '"><link rel="stylesheet" href="' . asset('css/partner-auth.css') . '"><link rel="stylesheet" href="' . asset('css/partner-prices.css') . '">',
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
                fn (): string => view('partner.profile-menu-item')->render(),
            )
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
