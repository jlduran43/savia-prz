<?php

namespace App\Filament\Resources\IngresoVehiculos\Pages;

use App\Filament\Resources\IngresoVehiculos\IngresoVehiculoResource;
use App\Services\OneDriveService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateIngresoVehiculos extends CreateRecord
{
    protected static string $resource = IngresoVehiculoResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $resultado = app(OneDriveService::class)
            ->subirRegistroIngresos();

        if ($resultado['ok']) {

            Notification::make()
                ->title('Registro guardado')
                ->body('Excel actualizado correctamente en OneDrive.')
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Registro guardado, pero OneDrive no se actualizó')
            ->body(
                $resultado['message']
                    ?? 'No fue posible actualizar el Excel.'
            )
            ->warning()
            ->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tipo_movimiento'] = 'Ingreso';
        $data['centro_ingreso'] = 'Arqueo Acceso';
        $data['hora_inicio'] = now()->format('H:i:s');

        if (($data['condicion_pago'] ?? null) === 'LIBERADO') {
            $data['medio_pago'] = null;
            $data['monto'] = null;
        }

        return $data;
    }

    public function getTitle(): string
    {
        return 'Registrar Vehículo';
    }
}
