<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;

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
                ViewAction::make()
                    ->label('Ver')
                    ->color('gray'),

                EditAction::make()
                    ->label('Editar')
                    ->color('success'),

                Action::make('cambiarEstado')
                    ->label(
                        fn($record) => $record->activo
                            ? 'Eliminar'
                            : 'Activar'
                    )
                    ->icon(
                        fn($record) => $record->activo
                            ? 'heroicon-o-no-symbol'
                            : 'heroicon-o-check-circle'
                    )
                    ->color(
                        fn($record) => $record->activo
                            ? 'danger'
                            : 'success'
                    )
                    ->requiresConfirmation()
                    ->modalHeading(
                        fn($record) => $record->activo
                            ? 'Desactivar usuario'
                            : 'Activar usuario'
                    )
                    ->modalDescription(
                        fn($record) => $record->activo
                            ? '¿Estás seguro de que deseas desactivar este usuario?'
                            : '¿Estás seguro de que deseas activar este usuario?'
                    )
                    ->modalSubmitActionLabel(
                        fn($record) => $record->activo
                            ? 'Sí, desactivar'
                            : 'Sí, activar'
                    )
                    ->modalSubmitAction(
                        fn($action, $record) => $action
                            ->color(
                                $record->activo
                                    ? 'danger'
                                    : 'primary'
                            )
                            ->extraAttributes([
                                'class' => $record->activo
                                    ? 'btn-desactivar-usuario'
                                    : 'btn-activar-usuario',
                            ])
                    )
                    ->modalCancelActionLabel('Volver')
                    ->action(function ($record) {
                        $nuevoEstado = ! (bool) $record->activo;

                        $record->update([
                            'activo' => $nuevoEstado,
                        ]);
                    })
                    ->successNotificationTitle('Estado del usuario actualizado correctamente'),
            ]);
    }
}
