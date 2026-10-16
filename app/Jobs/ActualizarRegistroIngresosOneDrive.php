<?php

namespace App\Jobs;

use App\Services\OneDriveService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ActualizarRegistroIngresosOneDrive implements ShouldQueue
{
    use Queueable;

    /*
    |--------------------------------------------------------------------------
    | Cantidad máxima de intentos
    |--------------------------------------------------------------------------
    */

    public int $tries = 5;


    /*
    |--------------------------------------------------------------------------
    | Tiempo máximo de ejecución
    |--------------------------------------------------------------------------
    */

    public int $timeout = 120;


    /*
    |--------------------------------------------------------------------------
    | Ejecutar Job
    |--------------------------------------------------------------------------
    */

    public function handle(
        OneDriveService $oneDriveService
    ): void
    {
        $resultado =
            $oneDriveService->subirRegistroIngresos();

        /*
        |--------------------------------------------------------------------------
        | Todo correcto
        |--------------------------------------------------------------------------
        */

        if ($resultado['ok']) {

            Log::info(
                'Excel de ingresos actualizado en OneDrive.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Archivo bloqueado
        |--------------------------------------------------------------------------
        */

        if (
            ($resultado['code'] ?? null) === 'locked'
        ) {

            Log::warning(
                'Excel bloqueado en OneDrive. Se reintentará.',
                $resultado
            );

            /*
             * Devolver el Job a la cola.
             * Se intentará nuevamente en 15 segundos.
             */
            $this->release(15);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Otro error
        |--------------------------------------------------------------------------
        */

        Log::error(
            'No fue posible actualizar el Excel en OneDrive.',
            $resultado
        );

        throw new \RuntimeException(
            $resultado['message']
                ?? 'Error actualizando OneDrive.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Error definitivo
    |--------------------------------------------------------------------------
    */

    public function failed(
        ?\Throwable $exception
    ): void
    {
        Log::error(
            'El Job de actualización de OneDrive falló definitivamente.',
            [
                'error' =>
                    $exception?->getMessage(),
            ]
        );
    }
}