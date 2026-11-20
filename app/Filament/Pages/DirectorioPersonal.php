<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DirectorioPersonal extends Page
{
    protected static ?string $slug = 'directorio-personal';

    protected static ?string $title = 'Directorio personal';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.directorio-personal';
}