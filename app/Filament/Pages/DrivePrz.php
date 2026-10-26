<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class DrivePrz extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;
    protected static ?string $navigationLabel = 'Drive PRZ';
    protected static ?string $title = 'Drive PRZ';
    protected string $view = 'filament.pages.drive-prz';
}
