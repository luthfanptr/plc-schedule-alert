<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Control\Pages\Dashboard;
use App\Filament\Control\Widgets\PlcPercentageChart;
use App\Filament\Control\Widgets\SpkChart;
use App\Filament\Control\Widgets\SpkSummaryWidget;
use App\Filament\Control\Widgets\TopPlcWidget;
use App\Filament\Widgets\GreetingHeader;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color as FilamentColor;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Openplain\FilamentShadcnTheme\Color;
use ThalysJuvenal\Aurum\AurumTheme;
use ThalysJuvenal\Aurum\Presets\Sapphire;

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
            ->topbar(true)
            ->favicon(asset('images/mi.jpg'))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth(Width::Full)
            ->breadcrumbs(false)
            ->databaseTransactions()
            ->defaultThemeMode(ThemeMode::Light)
            ->plugin(AurumTheme::make()->preset(Sapphire::class))
            // ->colors([
            //     'primary' => Color::adaptive(
            //         lightColor: FilamentColor::Blue,
            //         darkColor: FilamentColor::Sky
            //     ),
            // ])
            ->discoverResources(in: app_path('Filament/Control/Resources'), for: 'App\Filament\Control\Resources')
            ->discoverPages(in: app_path('Filament/Control/Pages'), for: 'App\Filament\Control\Pages')
            ->pages([
            ])
            ->discoverWidgets(in: app_path('Filament/Control/Widgets'), for: 'App\Filament\Control\Widgets')
            ->widgets([
                //AccountWidget::class,
                GreetingHeader::class,
                PlcPercentageChart::class,
                SpkChart::class,
                SpkSummaryWidget::class,
                TopPlcWidget::class,
                //FilamentInfoWidget::class,
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
            ->plugins([
                AuthDesignerPlugin::make()
                    ->login(fn ($config) => $config
                        ->media(asset('images/comp-asset.webp'))
                        ->mediaPosition(MediaPosition::Cover)
                        ->mediaSize('70%')
                        //->blur(1)
                    )
                    ->themeToggle('90%', '50%'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->resources([
                \App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource::class,
                \App\Filament\Admin\Resources\PlcData\PlcDataResource::class,
                \App\Filament\Admin\Resources\PersonalAccessTokens\PersonalAccessTokenResource::class,
            ]);
    }
}
