<?php

namespace App\Filament\Resources\RegistroIngresos\Pages;

use App\Filament\Resources\RegistroIngresos\IngresoVehiculoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRegistroIngreso extends ViewRecord
{
    protected static string $resource = IngresoVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
