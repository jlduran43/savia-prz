<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Exports\RegistroIngresosExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MicrosoftToken;
use App\Services\MicrosoftAuthService;

class MicrosoftController extends Controller
{
    public function redirect()
    {
        $state = Str::random(40);

        session([
            'microsoft_oauth_state' => $state,
        ]);

        $params = http_build_query([
            'client_id' =>
            config('services.microsoft.client_id'),

            'response_type' =>
            'code',

            'redirect_uri' =>
            config('services.microsoft.redirect_uri'),

            'response_mode' =>
            'query',

            'scope' => implode(' ', [
                'User.Read',
                'Files.ReadWrite',
                'offline_access',
            ]),

            'state' =>
            $state,

            'prompt' =>
            'select_account',
        ]);

        return redirect(
            'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?'
                . $params
        );
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return response()->json([
                'error' =>
                $request->get('error'),

                'description' =>
                $request->get('error_description'),

            ], 400);
        }


        /*
    |--------------------------------------------------------------------------
    | Validar state OAuth
    |--------------------------------------------------------------------------
    */

        if (
            !$request->state ||
            $request->state !== session('microsoft_oauth_state')
        ) {
            return response()->json([
                'mensaje' =>
                'Estado OAuth inválido.',

            ], 403);
        }


        if (!$request->code) {
            abort(
                400,
                'Microsoft no devolvió el código de autorización.'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | Intercambiar código por tokens
    |--------------------------------------------------------------------------
    */

        $response = Http::asForm()->post(
            'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            [
                'client_id' =>
                config('services.microsoft.client_id'),

                'client_secret' =>
                config('services.microsoft.client_secret'),

                'code' =>
                $request->code,

                'redirect_uri' =>
                config('services.microsoft.redirect_uri'),

                'grant_type' =>
                'authorization_code',

                'scope' => implode(' ', [
                    'User.Read',
                    'Files.ReadWrite',
                    'offline_access',
                ]),
            ]
        );


        if ($response->failed()) {
            return response()->json([
                'message' =>
                'No fue posible obtener el token.',

                'microsoft' =>
                $response->json(),

            ], $response->status());
        }


        $tokens = $response->json();


        /*
    |--------------------------------------------------------------------------
    | Guardar tokens persistentemente
    |--------------------------------------------------------------------------
    */

        MicrosoftToken::updateOrCreate(
            [
                'provider' => 'microsoft',
            ],
            [
                'access_token' =>
                $tokens['access_token'],

                'refresh_token' =>
                $tokens['refresh_token'] ?? null,

                'expires_at' =>
                now()->addSeconds(
                    $tokens['expires_in'] ?? 3600
                ),
            ]
        );


        /*
    |--------------------------------------------------------------------------
    | El state sí sigue siendo de sesión
    |--------------------------------------------------------------------------
    */

        session()->forget(
            'microsoft_oauth_state'
        );


        return redirect()
            ->route('microsoft.test');
    }

    public function test(
        MicrosoftAuthService $microsoftAuth
    ) {
        $accessToken =
            $microsoftAuth->getValidAccessToken();

        if (!$accessToken) {
            return redirect()
                ->route('microsoft.login');
        }

        $response = Http::withToken(
            $accessToken
        )
            ->get(
                'https://graph.microsoft.com/v1.0/me/drive'
            );


        /*
    |--------------------------------------------------------------------------
    | Si Microsoft devuelve 401, forzamos renovación
    |--------------------------------------------------------------------------
    */

        if ($response->status() === 401) {

            $accessToken =
                $microsoftAuth->refreshAccessToken();

            if (!$accessToken) {
                return redirect()
                    ->route('microsoft.login');
            }

            $response = Http::withToken(
                $accessToken
            )
                ->get(
                    'https://graph.microsoft.com/v1.0/me/drive'
                );
        }


        if ($response->failed()) {
            return response()->json([
                'message' =>
                'No se pudo acceder a OneDrive.',

                'microsoft' =>
                $response->json(),

            ], $response->status());
        }


        return response()->json([
            'message' =>
            'Conexión con OneDrive correcta.',

            'drive' =>
            $response->json(),
        ]);
    }

    public function subirExcel(
        MicrosoftAuthService $microsoftAuth
    ) {
        try {

            /*
        |--------------------------------------------------------------------------
        | 1. Obtener token válido
        |--------------------------------------------------------------------------
        */

            $accessToken =
                $microsoftAuth->getValidAccessToken();

            if (!$accessToken) {
                return redirect()
                    ->route('microsoft.login');
            }

            /*
        |--------------------------------------------------------------------------
        | 2. Nombre del archivo
        |--------------------------------------------------------------------------
        */

            $nombreArchivo =
                'registro_ingresos.xlsx';

            /*
        |--------------------------------------------------------------------------
        | 3. Generar nuevamente el Excel con todos los registros
        |--------------------------------------------------------------------------
        */

            Excel::store(
                new RegistroIngresosExport(),
                $nombreArchivo,
                'local'
            );

            /*
        |--------------------------------------------------------------------------
        | 4. Ruta local del archivo
        |--------------------------------------------------------------------------
        */

            $rutaArchivo =
                storage_path(
                    'app/private/' . $nombreArchivo
                );

            if (!file_exists($rutaArchivo)) {
                return response()->json([
                    'message' =>
                    'No se encontró el Excel generado.',

                    'ruta' =>
                    $rutaArchivo,
                ], 500);
            }

            /*
        |--------------------------------------------------------------------------
        | 5. Leer contenido
        |--------------------------------------------------------------------------
        */

            $contenido =
                file_get_contents($rutaArchivo);

            /*
        |--------------------------------------------------------------------------
        | 6. URL real del archivo en OneDrive
        |--------------------------------------------------------------------------
        */

            $url =
                'https://graph.microsoft.com/v1.0/me/drive/root:/PowerBI/'
                . $nombreArchivo
                . ':/content';

            /*
        |--------------------------------------------------------------------------
        | 7. Intentar subir archivo
        |--------------------------------------------------------------------------
        */

            $maxIntentos = 5;

            $response = null;

            for (
                $intento = 1;
                $intento <= $maxIntentos;
                $intento++
            ) {

                $response =
                    Http::withToken(
                        $accessToken
                    )
                    ->withBody(
                        $contenido,
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    )
                    ->put($url);

                /*
            |--------------------------------------------------------------------------
            | Si fue exitoso terminamos
            |--------------------------------------------------------------------------
            */

                if ($response->successful()) {
                    break;
                }

                /*
            |--------------------------------------------------------------------------
            | Token expirado
            |--------------------------------------------------------------------------
            */

                if ($response->status() === 401) {

                    $accessToken =
                        $microsoftAuth->refreshAccessToken();

                    if (!$accessToken) {
                        return redirect()
                            ->route('microsoft.login');
                    }

                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | Archivo bloqueado
            |--------------------------------------------------------------------------
            */

                if ($response->status() === 423) {

                    /*
                 * Esperar 3 segundos
                 * antes del siguiente intento
                 */

                    sleep(3);

                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | Cualquier otro error no tiene sentido reintentarlo
            |--------------------------------------------------------------------------
            */

                break;
            }

            /*
        |--------------------------------------------------------------------------
        | 8. Comprobar resultado final
        |--------------------------------------------------------------------------
        */

            if (
                !$response ||
                $response->failed()
            ) {

                return response()->json(
                    [
                        'message' =>
                        'No se pudo actualizar el Excel en OneDrive.',

                        'status' =>
                        $response
                            ? $response->status()
                            : null,

                        'microsoft' =>
                        $response
                            ? $response->json()
                            : null,

                    ],
                    $response
                        ? $response->status()
                        : 500
                );
            }

            /*
        |--------------------------------------------------------------------------
        | 9. Todo correcto
        |--------------------------------------------------------------------------
        */

            return response()->json([
                'message' =>
                'Excel actualizado correctamente en OneDrive.',

                'archivo' =>
                $response->json(),
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'message' =>
                'Ocurrió un error al actualizar el Excel.',

                'error' =>
                $e->getMessage(),

                'archivo' =>
                $e->getFile(),

                'linea' =>
                $e->getLine(),

            ], 500);
        }
    }
}
