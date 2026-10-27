<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use Filament\View\PanelsRenderHook;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\Cargos\CargoResource;
use App\Filament\Resources\Departamentos\DepartamentoResource;
use App\Filament\Resources\IngresoVehiculos\IngresoVehiculoResource;
use App\Filament\Resources\MedioPagos\MedioPagoResource;
use App\Filament\Resources\TipoVehiculos\TipoVehiculoResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Pages\FormularioRequerimientoCompra;
use App\Filament\Pages\CajaChica;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Resources\DocumentoCarpetas\DocumentoCarpetaResource;
use App\Filament\Resources\Documentos\DocumentoResource;
use Filament\Support\Assets\Css;
use App\Filament\Pages\ExploradorDocumentos;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->loginRouteSlug('login')
            ->passwordReset(
                RequestPasswordReset::class
            )
            ->brandName('Intranet PRZ')
            ->brandLogo(asset('images/logo-prz.png'))
            ->brandLogoHeight('3rem')
            ->topNavigation()
            ->profile(EditProfile::class, isSimple: false)
            ->assets([
                Css::make(
                    'documentos-estilos',
                    asset('css/documentos.css')
                ),
            ])
            ->colors([
                'primary' => Color::hex('#176B45'),
            ])

            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn(): string => '
                <style>
                    .fi-sidebar-nav-groups {
                        gap: 0.5rem !important;
                    }

                    /* Botón Crear Departamento */
                    .fi-page.fi-resource-departamentos
                    .fi-form-actions .fi-btn.fi-color-primary {
                        background-color: #176b45 !important;
                        color: #ffffff !important;
                    }

                    /* Interruptor Activo */
                    .fi-page.fi-resource-departamentos
                    .fi-toggle[aria-checked="true"] {
                        background-color: #176b45 !important;
                    }
                </style>'
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')

            ->pages([
                Dashboard::class,
            ])

            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {

                return $builder->groups([


                    // PRIMERO: COMPRAS
                    NavigationGroup::make()
                        ->label('Compras')
                        ->icon('heroicon-o-shopping-cart')
                        ->items([

                            // Formulario de Requerimiento de Compra
                            NavigationItem::make('Formulario de Requerimiento de Compra')
                                ->url(FormularioRequerimientoCompra::getUrl())
                                ->isActiveWhen(
                                    fn(): bool => request()->routeIs(
                                        'filament.admin.pages.formulario-requerimiento-compra'
                                    )
                                ),

                            // Caja Chica
                            NavigationItem::make('Caja Chica')
                                ->url(CajaChica::getUrl())
                                ->isActiveWhen(
                                    fn(): bool => request()->routeIs(
                                        'filament.admin.pages.caja-chica'
                                    )
                                ),

                        ]),

                    // SEGUNDO: ARCHIVOS
                    NavigationGroup::make()
                        ->label('Archivos')
                        ->icon('heroicon-o-folder')
                        ->items([

                            NavigationItem::make('Explorador de archivos')
                                ->url(fn(): string => ExploradorDocumentos::getUrl())
                                ->isActiveWhen(
                                    fn(): bool => request()->routeIs(
                                        'filament.admin.pages.archivos'
                                    )
                                ),

                        ]),

                    // SEGUNDO: MANTENEDORES
                    NavigationGroup::make()
                        ->label('Mantenedores')
                        ->icon('heroicon-o-briefcase')
                        ->items([
                            ...CargoResource::getNavigationItems(),
                            ...DepartamentoResource::getNavigationItems(),
                            ...IngresoVehiculoResource::getNavigationItems(),
                            ...MedioPagoResource::getNavigationItems(),
                            ...TipoVehiculoResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make()
                        ->label('Drive PRZ'),

                    // TERCERO: USUARIOS
                    ...(
                        auth()->user()?->rol === 'ADMIN'
                        ? [
                            NavigationGroup::make()
                                ->items([
                                    ...UserResource::getNavigationItems(),
                                ]),
                        ]
                        : []
                    ),


                ]);
            })

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')

            ->widgets([
                Widgets\AccountWidget::class,
            ])

            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn(): string => '
                <style>
                    .fi-sidebar-nav-groups {
                        gap: 0.5rem !important;
                    }

                    .btn-verde-pdf {
                        background-color: #176b45 !important;
                        border-color: #176b45 !important;
                        color: white !important;
                        border-radius: 12px !important;
                    }

                    .btn-verde-pdf:hover {
                        background-color: #125636 !important;
                        border-color: #125636 !important;
                        color: white !important;
                    }
                    
                    /* Ampliar el ancho de los submenús */
                        .fi-topbar .fi-dropdown-panel {
                        min-width: 320px !important;
                        width: max-content !important;
                        max-width: 400px !important;
                    }

                    /* Evitar que los nombres se corten */
                        .fi-topbar .fi-dropdown-list-item-label {
                        white-space: nowrap !important;
                        overflow: visible !important;
                        text-overflow: clip !important;
                    }

                    /* Confirmación de activar usuario */
                        .fi-modal .fi-btn.btn-activar-usuario {
                        background-color: #176B45 !important;
                        border-color: #176B45 !important;
                        color: #ffffff !important;
                    }

                    /* Hover activar usuario */
                        .fi-modal .fi-btn.btn-activar-usuario:hover {
                        background-color: #125636 !important;
                        border-color: #125636 !important;
                        color: #ffffff !important;
                    }

                    /* Confirmación de desactivar usuario */
                        .fi-modal .fi-btn.btn-desactivar-usuario {
                        background-color: #dc2626 !important;
                        border-color: #dc2626 !important;
                        color: #ffffff !important;
                    }
                </style>'
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
