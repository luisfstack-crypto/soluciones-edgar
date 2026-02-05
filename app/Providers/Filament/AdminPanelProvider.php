<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Soluciones Edgar')
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->brandLogoHeight('3rem') 
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => Color::Red,  
            ])
            ->font('Poppins')
            ->topNavigation() 
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class    
            ])
            ->renderHook(
                'panels::head.end',
                fn (): string => "
                    <style>
                        .fi-topbar {
                            height: 4.5rem !important;
                            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
                        }
                        .fi-topbar-content {
                            height: 4.5rem !important;
                            padding-top: 0.5rem;
                            padding-bottom: 0.5rem;
                        }
                        .fi-logo {
                            height: 3rem !important;
                            width: auto !important;
                        }
                        .fi-topbar-nav-list, .fi-user-menu {
                            align-self: center !important;
                            margin-top: 0.5rem;
                            margin-bottom: 0.5rem;
                        }
                        .fi-topbar {
                            border-bottom-color: rgb(229, 231, 235) !important;
                        }
                    </style>
                ",
            )
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
            ]);
    }
}