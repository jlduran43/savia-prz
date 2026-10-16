<?php

namespace App\Filament\Resources\MedioPagos\Pages;

use App\Filament\Resources\MedioPagos\MedioPagoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedioPagos extends ListRecords
{
    protected static string $resource = MedioPagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
