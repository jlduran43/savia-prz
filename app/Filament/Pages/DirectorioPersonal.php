<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class DirectorioPersonal extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string | UnitEnum | null $navigationGroup = 'RRHH';

    protected static ?string $navigationLabel = 'Directorio Personal';

    protected static ?string $title = 'Directorio Personal';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.directorio-personal';

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
