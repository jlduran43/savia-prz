<?php

namespace App\Filament\Resources\DocumentoCarpetas\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;

class DocumentoCarpetasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nombre')
                    ->label('Carpeta')
                    ->icon('heroicon-o-folder')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('carpetaPadre.nombre')
                    ->label('Ubicación')
                    ->placeholder('Principal'),

                TextColumn::make('creador.name')
                    ->label('Creada por')
                    ->placeholder('Sin usuario'),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
            ]);
    }
}
