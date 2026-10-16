<?php

namespace App\Filament\Resources\IngresoVehiculos\Pages;

use App\Filament\Resources\IngresoVehiculos\IngresoVehiculoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIngresoVehiculos extends ListRecords
{
    protected static string $resource = IngresoVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Ingreso'),
        ];
    }
}