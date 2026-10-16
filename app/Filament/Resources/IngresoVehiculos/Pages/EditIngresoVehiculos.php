<?php

namespace App\Filament\Resources\RegistroIngresos\Pages;

use App\Filament\Resources\RegistroIngresos\IngresoVehiculoResource;
use App\Services\OneDriveService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRegistroIngreso extends EditRecord
{
    protected static string $resource =
        IngresoVehiculoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),

            DeleteAction::make()
                ->after(function () {

                    $resultado =
                        app(OneDriveService::class)
                        ->subirRegistroIngresos();

                    if (!$resultado['ok']) {
                        Notification::make()
                            ->title('Registro eliminado')
                            ->body(
                                'No se pudo actualizar OneDrive: '
                                . ($resultado['message'] ?? '')
                            )
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }

    protected function afterSave(): void
    {
        $resultado =
            app(OneDriveService::class)
            ->subirRegistroIngresos();

        if ($resultado['ok']) {

            Notification::make()
                ->title('Registro actualizado')
                ->body('Excel actualizado correctamente en OneDrive.')
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Registro actualizado, pero OneDrive falló')
            ->body(
                $resultado['message']
                ?? 'No fue posible actualizar el Excel.'
            )
            ->warning()
            ->send();
    }
}