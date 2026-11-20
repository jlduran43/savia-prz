<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RrhhDirectorioController extends Controller
{
    public function screen(): Response
    {
        $path = resource_path('rrhh/index.html');

        abort_unless(is_file($path), 404);

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
