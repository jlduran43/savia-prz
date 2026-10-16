<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('apellido')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rut')
                    ->label('RUT')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),

                TextColumn::make('departamento.nombre')
                    ->label('Departamento')
                    ->sortable(),

                TextColumn::make('cargo.nombre')
                    ->label('Cargo')
                    ->sortable(),

                TextColumn::make('jefe.name')
                    ->label('Jefatura')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->jefe
                            ? $record->jefe->name . ' ' . $record->jefe->apellido
                            : 'Sin jefatura'
                    ),

                IconColumn::make('activo')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
