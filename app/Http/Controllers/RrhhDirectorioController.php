<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RrhhDirectorioController extends Controller
{
    public function screen(): Response
    {
        // La ruta exige que el usuario haya iniciado sesión.
        // Temporalmente no restringimos por rol.

        $path = resource_path('rrhh/index.html');

        abort_unless(
            is_file($path),
            404,
            'No se encontró el archivo HTML del Directorio Personal.'
        );

        return response(
            file_get_contents($path),
            200,
            [
                'Content-Type' => 'text/html; charset=UTF-8',
                'Cache-Control' => 'no-store, private',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
