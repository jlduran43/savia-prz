<?php

namespace App\Filament\Resources\MedioPagos\Pages;

use App\Filament\Resources\MedioPagos\MedioPagoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedioPago extends EditRecord
{
    protected static string $resource = MedioPagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
