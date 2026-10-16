<?php

namespace App\Filament\Resources\TipoVehiculos\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TipoVehiculosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Tipo de vehículo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('precio_base')
                    ->label('Precio base')
                    ->money('CLP')
                    ->sortable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}