<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FormularioRequerimientoCompra extends Page
{
    protected static ?string $navigationLabel = 'Formulario de requerimiento de compra';

    protected static ?string $title = 'Formulario de requerimiento de compra';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.formulario-requerimiento-compra';

    protected static bool $shouldRegisterNavigation = false;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public function getTitle(): string
    {
        return 'Formulario de Requerimiento de Compra';
    }
}
