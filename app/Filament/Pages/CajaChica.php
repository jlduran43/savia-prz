<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CajaChica extends Page
{
    // Vista Blade
    protected string $view = 'filament.pages.caja-chica';

    // Nombre del menú
    protected static ?string $navigationLabel = 'Caja Chica';

    // Grupo de navegación
    protected static string|\UnitEnum|null $navigationGroup = 'Compras';

    // Orden dentro del menú
    protected static ?int $navigationSort = 2;

    // Título de la página
    protected static ?string $title = 'Caja Chica';
}