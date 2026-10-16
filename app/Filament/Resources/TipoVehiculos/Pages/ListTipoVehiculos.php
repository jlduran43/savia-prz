<?php

namespace App\Filament\Resources\TipoVehiculos\Pages;

use App\Filament\Resources\TipoVehiculos\TipoVehiculoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoVehiculos extends ListRecords
{
    protected static string $resource = TipoVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
