<?php

namespace App\Filament\Resources\TipoVehiculos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TipoVehiculoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Tipo de vehículo')
                    ->required()
                    ->maxLength(150)
                    ->unique(
                        table: 'tipos_vehiculo',
                        column: 'nombre',
                        ignoreRecord: true
                    ),

                TextInput::make('precio_base')
                    ->label('Precio base')
                    ->prefix('$')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}