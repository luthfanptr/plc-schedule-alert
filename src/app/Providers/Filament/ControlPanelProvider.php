<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Control\Pages\Dashboard;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
//use Filament\Support\Colors\Color;
use Filament\Support\Colors\Color as FilamentColor;
//use Filament\Support\Facades\FilamentColor;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Openplain\FilamentShadcnTheme\Color;

class ControlPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('control')
            ->path('control')
            ->spa()
            ->spaUrlExceptions([
                Dashboard::class,
            ])
            ->login()
            ->topbar(false)
            ->favicon(asset('images/mi.jpg'))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth(Width::Full)
            ->databaseTransactions()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::adaptive(
                    lightColor: FilamentColor::Blue,
                    darkColor: FilamentColor::Sky
                ),
            ])
            ->discoverResources(in: app_path('Filament/Control/Resources'), for: 'App\Filament\Control\Resources')
            ->discoverPages(in: app_path('Filament/Control/Pages'), for: 'App\Filament\Control\Pages')
            ->pages([
            ])
            ->discoverWidgets(in: app_path('Filament/Control/Widgets'), for: 'App\Filament\Control\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->resources([
                \App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource::class,
                \App\Filament\Admin\Resources\PlcData\PlcDataResource::class,
                \App\Filament\Admin\Resources\PersonalAccessTokens\PersonalAccessTokenResource::class,
            ]);
    }
}
