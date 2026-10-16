<?php

namespace App\Filament\Resources\Cargos\Pages;

use App\Filament\Resources\Cargos\CargoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;

class ViewCargo extends ViewRecord
{
    protected static string $resource = CargoResource::class;

    // Eliminar botón Editar de la parte superior
    protected function getHeaderActions(): array
    {
        return [];
    }

    // Agregar botón Volver debajo del formulario
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...parent::content($schema)->getComponents(),

                Actions::make([
                    Action::make('volver')
                        ->label('Volver')
                        ->color('gray')
                        ->url(static::getResource()::getUrl('index')),
                ]),
            ]);
    }
}