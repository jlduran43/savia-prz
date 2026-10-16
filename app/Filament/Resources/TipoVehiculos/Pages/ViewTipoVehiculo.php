<?php

namespace App\Filament\Resources\TipoVehiculos\Pages;

use App\Filament\Resources\TipoVehiculos\TipoVehiculoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTipoVehiculo extends ViewRecord
{
    protected static string $resource = TipoVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
