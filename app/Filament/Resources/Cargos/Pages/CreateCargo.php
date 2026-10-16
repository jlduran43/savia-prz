<?php

namespace App\Filament\Resources\Cargos\Pages;

use App\Filament\Resources\Cargos\CargoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCargo extends CreateRecord
{
    protected static string $resource = CargoResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Crear')
            ->color('success')
            ->extraAttributes([
                'style' => 'background-color: #15803d !important; color: white !important;',
            ]);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->hidden();
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Volver');
    }

    // Después de crear, regresar al listado de cargos
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}