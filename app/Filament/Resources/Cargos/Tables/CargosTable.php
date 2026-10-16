<?php

namespace App\Filament\Resources\Cargos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\DeleteAction;
use App\Models\Cargo;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class CargosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Cargo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('departamento.nombre')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('activo')
                    ->label('Estado')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([

                ViewAction::make()
                    ->label('Ver'),

                EditAction::make()
                    ->label('Editar')
                    ->color('success'),

                Action::make('cambiarEstado')
                    ->label(
                        fn(Cargo $record): string =>
                        $record->activo ? 'Borrar' : 'Activar'
                    )
                    ->icon(
                        fn(Cargo $record): string =>
                        $record->activo
                            ? 'heroicon-o-trash'
                            : 'heroicon-o-check-circle'
                    )
                    ->color(
                        fn(Cargo $record): string =>
                        $record->activo ? 'danger' : 'success'
                    )
                    ->requiresConfirmation()

                    ->modalIcon('heroicon-o-exclamation-triangle')

                    ->modalIconColor('danger')

                    ->modalHeading(
                        fn(Cargo $record): string =>
                        $record->activo
                            ? '¿Desactivar cargo?'
                            : '¿Activar cargo?'
                    )

                    ->modalDescription(
                        fn(Cargo $record): string =>
                        $record->activo
                            ? "¿Estás seguro de que deseas desactivar el cargo {$record->nombre}? El registro no será eliminado."
                            : "¿Estás seguro de que deseas activar el cargo {$record->nombre}?"
                    )

                    ->modalSubmitActionLabel(
                        fn(Cargo $record): string =>
                        $record->activo ? 'Sí, desactivar' : 'Sí, activar'
                    )

                    ->modalSubmitAction(
                        fn(Action $action, Cargo $record): Action =>
                        $action->color($record->activo ? 'danger' : 'success')
                    )

                    ->modalCancelActionLabel('Cancelar')

                    ->action(function (Cargo $record): void {

                        $nuevoEstado = ! $record->activo;

                        $record->update([
                            'activo' => $nuevoEstado,
                        ]);

                        Notification::make()
                            ->title(
                                $nuevoEstado
                                    ? 'Cargo activado correctamente'
                                    : 'Cargo desactivado correctamente'
                            )
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
