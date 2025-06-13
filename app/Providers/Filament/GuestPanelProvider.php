<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GuestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guest')
            ->path('/')
            ->topNavigation()
            ->brandLogo(asset('img/Sipanda.png'))
            ->darkModeBrandLogo(asset('img/Sipandablack.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Guest/Resources'), for: 'App\\Filament\\Guest\\Resources')
            ->discoverPages(in: app_path('Filament/Guest/Pages'), for: 'App\\Filament\\Guest\\Pages')
            ->pages([
//                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Guest/Widgets'), for: 'App\\Filament\\Guest\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
            ])
            ->renderHook('panels::topbar.end', fn (): string => Blade::render('<x-theme-switcher-dropdown />'))

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
//            ->authMiddleware([
//                Authenticate::class,
//            ]);
    }
}
