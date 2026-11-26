<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make(
                'login-prz',
                resource_path('css/filament/login-prz.css')
            ),
        ]);

        Gate::define('rrhh-documentacion', function (User $user) {
            $rol = $user->rol instanceof \BackedEnum
                ? $user->rol->value
                : $user->rol;

            return (bool) $user->activo
                && $rol === 'ADMIN';
        });
    }
}
