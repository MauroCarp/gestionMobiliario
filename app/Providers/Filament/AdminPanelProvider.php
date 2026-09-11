<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Impresora;
use App\Filament\Widgets\AnalisisGlobalWidget;
// use App\Filament\Widgets\OrdenesCompraWidget;
// use App\Filament\Widgets\ProyectosActivosWidget;
use App\Filament\Widgets\InsumosUrgentesWidget;
use App\Filament\Widgets\LotesEnProcesoWidget;
use App\Filament\Widgets\PresupuestosPendientesEntregaWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile()
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            // ->brandName('Orlando Pierantoni S.R.L - Gestión Mobiliario')
            ->brandLogo(fn () => new HtmlString(view('filament.brand-logo')->render()))
            ->brandLogoHeight('3.5rem')
            ->maxContentWidth(MaxWidth::Full)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                AnalisisGlobalWidget::class,
                PresupuestosPendientesEntregaWidget::class,
                InsumosUrgentesWidget::class,
                // OrdenesCompraWidget::class,
                // ProyectosActivosWidget::class,
                LotesEnProcesoWidget::class,
            ])
            ->navigationGroups([
                'Operaciones',
                'Mobiliario',
                'Tercerizados',
                'Análisis',
                'Configuración',
                'Administración',
            ])
            ->navigationItems([
                NavigationItem::make(fn (): string => Impresora::getNavigationLabel())
                    ->icon('heroicon-o-printer')
                    ->group('Administración')
                    ->sort(0)
                    ->visible(fn (): bool => Impresora::canAccess())
                    ->url('#impresora')
                    ->isActiveWhen(fn (): bool => false),
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
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render("@vite(['resources/js/app.js', 'resources/css/app.css'])"),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Impresora::canAccess()
                    ? view('filament.hooks.impresora-modal')->render()
                    : '',
            );
    }
}
