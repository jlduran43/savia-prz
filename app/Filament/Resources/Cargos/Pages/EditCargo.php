<?php

namespace App\Filament\Resources\Cargos\Pages;

use App\Filament\Resources\Cargos\CargoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCargo extends EditRecord
{
    protected static string $resource = CargoResource::class;

    // Eliminar botones Ver y Borrar
    protected function getHeaderActions(): array
    {
        return [];
    }

    // Botón Guardar cambios verde oscuro
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar cambios')
            ->color('success')
            ->extraAttributes([
                'style' => 'background-color: #157347 !important; color: white !important;',
            ]);
    }

    // Cambiar Cancelar por Volver
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Volver')
            ->url(CargoResource::getUrl('index'));
    }
}