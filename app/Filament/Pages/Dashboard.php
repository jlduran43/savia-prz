<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Inicio';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
    protected static bool $shouldRegisterNavigation = false;

}
