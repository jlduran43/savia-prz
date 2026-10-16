<?php

namespace App\Filament\Resources\IngresoVehiculos\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class IngresoVehiculoTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('responsable')
                    ->label('Responsable')
                    ->searchable(),

                TextColumn::make('tipo_vehiculo')
                    ->label('Tipo de vehículo'),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d-m-Y')
                    ->sortable(),

                TextColumn::make('hora_inicio')
                    ->label('Hora inicio'),

                TextColumn::make('hora_termino')
                    ->label('Hora término'),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}