<?php

namespace App\Filament\Resources\MedioPagos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediosPagoTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Medio de pago')
                    ->searchable()
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