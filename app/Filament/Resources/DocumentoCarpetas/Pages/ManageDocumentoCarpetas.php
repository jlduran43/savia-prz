<?php

namespace App\Filament\Resources\DocumentoCarpetas\Pages;

use App\Filament\Resources\DocumentoCarpetas\DocumentoCarpetaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;


class ManageDocumentoCarpetas extends ManageRecords
{
    protected static string $resource = DocumentoCarpetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Nueva carpeta')
                ->modalHeading('Crear nueva carpeta')
                ->modalSubmitActionLabel('Guardar')
                ->modalCancelActionLabel('Volver')
                ->color(Color::hex('#176B45')),
        ];
    }
}
