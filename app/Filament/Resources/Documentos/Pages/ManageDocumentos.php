<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;

class ManageDocumentos extends ManageRecords
{
    protected static string $resource = DocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Subir documento')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Subir nuevo documento')
                ->modalSubmitActionLabel('Guardar documento')
                ->modalCancelActionLabel('Volver')
                ->color(Color::hex('#176B45')),
        ];
    }
}
