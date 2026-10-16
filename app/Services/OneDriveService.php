<?php

namespace App\Services;

use App\Exports\RegistroIngresosExport;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class OneDriveService
{
    public function __construct(
        private MicrosoftAuthService $microsoftAuth
    ) {}


    public function subirRegistroIngresos(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Obtener token válido automáticamente
        |--------------------------------------------------------------------------
        */

        $accessToken =
            $this->microsoftAuth
            ->getValidAccessToken();


        if (!$accessToken) {
            return [
                'ok' => false,

                'message' =>
                'No fue posible obtener un token válido de Microsoft.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Nombre del archivo
        |--------------------------------------------------------------------------
        */

        $nombreArchivo =
            'registro_ingresos.xlsx';


        /*
        |--------------------------------------------------------------------------
        | Generar Excel
        |--------------------------------------------------------------------------
        */

        Excel::store(
            new RegistroIngresosExport(),
            $nombreArchivo,
            'local'
        );


        /*
        |--------------------------------------------------------------------------
        | Ruta local
        |--------------------------------------------------------------------------
        */

        $rutaArchivo = storage_path(
            'app/private/' . $nombreArchivo
        );


        if (!file_exists($rutaArchivo)) {
            return [
                'ok' => false,

                'message' =>
                'No se encontró el archivo Excel generado.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Contenido binario
        |--------------------------------------------------------------------------
        */

        $contenido =
            file_get_contents($rutaArchivo);


        /*
        |--------------------------------------------------------------------------
        | Subir a OneDrive
        |--------------------------------------------------------------------------
        */

        $response =
            $this->subirArchivo(
                $accessToken,
                $nombreArchivo,
                $contenido
            );


        /*
        |--------------------------------------------------------------------------
        | Token rechazado
        |--------------------------------------------------------------------------
        |
        | Aunque según expires_at debería ser válido,
        | Microsoft podría devolver 401.
        |
        | En ese caso renovamos y reintentamos una vez.
        |
        */

        if ($response->status() === 401) {

            $accessToken =
                $this->microsoftAuth
                ->refreshAccessToken();


            if (!$accessToken) {
                return [
                    'ok' => false,

                    'message' =>
                    'Microsoft rechazó el token y no fue posible renovarlo.',
                ];
            }


            $response =
                $this->subirArchivo(
                    $accessToken,
                    $nombreArchivo,
                    $contenido
                );
        }

        $codigo = data_get(
            $response->json(),
            'error.code'
        );

        $mensaje = data_get(
            $response->json(),
            'error.message'
        );

        if (
            $codigo === 'notAllowed'
            && str_contains(
                strtolower($mensaje ?? ''),
                'locked'
            )
        ) {
            return [
                'ok' => false,

                'code' => 'locked',

                'message' =>
                'El archivo está bloqueado temporalmente en OneDrive.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Resultado
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {
            return [
                'ok' => false,

                'message' =>
                'Microsoft Graph rechazó la subida.',

                'microsoft' =>
                $response->json(),
            ];
        }


        return [
            'ok' => true,

            'message' =>
            'Excel actualizado correctamente en OneDrive.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Método privado para subir archivo
    |--------------------------------------------------------------------------
    */

    private function subirArchivo(
        string $accessToken,
        string $nombreArchivo,
        string $contenido
    ) {
        $maxIntentos = 4;

        for ($intento = 1; $intento <= $maxIntentos; $intento++) {

            $response = Http::withToken($accessToken)
                ->withBody(
                    $contenido,
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                )
                ->put(
                    'https://graph.microsoft.com/v1.0/me/drive/root:/PowerBI/'
                        . $nombreArchivo
                        . ':/content'
                );

            /*
        |--------------------------------------------------------------------------
        | Si funcionó, terminamos inmediatamente
        |--------------------------------------------------------------------------
        */

            if ($response->successful()) {
                return $response;
            }

            /*
        |--------------------------------------------------------------------------
        | Revisamos si Microsoft dice que el archivo está bloqueado
        |--------------------------------------------------------------------------
        */

            $codigo = data_get(
                $response->json(),
                'error.code'
            );

            $mensaje = data_get(
                $response->json(),
                'error.message'
            );

            $archivoBloqueado =
                $codigo === 'notAllowed'
                && str_contains(
                    strtolower($mensaje ?? ''),
                    'locked'
                );

            /*
        |--------------------------------------------------------------------------
        | Si NO es bloqueo, devolvemos el error inmediatamente
        |--------------------------------------------------------------------------
        */

            if (!$archivoBloqueado) {
                return $response;
            }

            /*
        |--------------------------------------------------------------------------
        | Si ya fue el último intento, terminamos
        |--------------------------------------------------------------------------
        */

            if ($intento === $maxIntentos) {
                return $response;
            }

            /*
        |--------------------------------------------------------------------------
        | Esperar antes del siguiente intento
        |--------------------------------------------------------------------------
        */

            sleep(3);
        }

        return $response;
    }
}
