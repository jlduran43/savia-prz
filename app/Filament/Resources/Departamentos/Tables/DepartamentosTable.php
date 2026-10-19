<?php

namespace App\Filament\Resources\Departamentos\Tables;

use App\Models\Departamento;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartamentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('activo')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state ? 'Activo' : 'Inactivo'
                    )
                    ->color(
                        fn($state) => $state ? 'success' : 'danger'
                    ),
            ])

            ->filters([
                //
            ])

            ->recordActions([

                ViewAction::make(),

                EditAction::make(),

                Action::make('cambiarEstado')
                    ->label(
                        fn(Departamento $record) =>
                        $record->activo ? 'Eliminar' : 'Activar'
                    )
                    ->icon(
                        fn(Departamento $record) =>
                        $record->activo
                            ? 'heroicon-o-trash'
                            : 'heroicon-o-check-circle'
                    )
                    ->color(
                        fn(Departamento $record) =>
                        $record->activo ? 'danger' : 'success'
                    )
                    ->requiresConfirmation()

                    ->modalHeading(
                        fn(Departamento $record) =>
                        $record->activo
                            ? '¿Desactivar departamento?'
                            : '¿Activar departamento?'
                    )

                    ->modalDescription(
                        fn(Departamento $record) =>
                        $record->activo
                            ? "¿Estás seguro de que deseas desactivar el departamento {$record->nombre}? El registro no será eliminado."
                            : "¿Estás seguro de que deseas activar el departamento {$record->nombre}?"
                    )

                    ->modalSubmitActionLabel(
                        fn(Departamento $record) =>
                        $record->activo
                            ? 'Sí, desactivar'
                            : 'Sí, activar'
                    )

                    ->modalSubmitAction(
                        fn(Action $action, Departamento $record) => $action
                            ->color($record->activo ? 'danger' : 'success')
                            ->extraAttributes([
                                'style' => $record->activo
                                    ? 'background-color: #dc2626 !important; color: white !important;'
                                    : 'background-color: #176B4A !important; color: white !important;',
                            ])
                    )

                    ->modalCancelActionLabel('Cancelar')

                    ->action(function (Departamento $record): void {

                        $nuevoEstado = ! $record->activo;

                        $record->update([
                            'activo' => $nuevoEstado,
                        ]);

                        Notification::make()
                            ->title(
                                $nuevoEstado
                                    ? 'Departamento activado correctamente'
                                    : 'Departamento desactivado correctamente'
                            )
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
