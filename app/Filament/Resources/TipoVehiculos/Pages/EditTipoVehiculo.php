<?php

namespace App\Filament\Resources\TipoVehiculos\Pages;

use App\Filament\Resources\TipoVehiculos\TipoVehiculoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoVehiculo extends EditRecord
{
    protected static string $resource = TipoVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
