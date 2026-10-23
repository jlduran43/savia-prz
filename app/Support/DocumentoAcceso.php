<?php

namespace App\Support;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;

class DocumentoAcceso
{
    public static function verificar(Documento $documento): void
    {
        abort_unless(auth()->check(), 403);

        $usuarioId = (int) auth()->id();

        // Los documentos compartidos se pueden consultar
        // por cualquier usuario autenticado.
        if ($documento->es_publico) {
            return;
        }

        // Los documentos privados solo pertenecen
        // al usuario que los subió.
        abort_unless(
            (int) $documento->subido_por === $usuarioId,
            403,
            'No tienes permiso para acceder al documento.'
        );

        // Validar también la propiedad de las carpetas
        $carpetaId = $documento->carpeta_id;
        $visitadas = [];

        while ($carpetaId !== null) {

            abort_if(
                in_array($carpetaId, $visitadas, true),
                403
            );

            $visitadas[] = $carpetaId;

            $carpeta = DocumentoCarpeta::query()
                ->whereKey($carpetaId)
                ->where('propietario_id', $usuarioId)
                ->first();

            abort_unless($carpeta, 403);

            $carpetaId = $carpeta->carpeta_padre_id;
        }
    }
}
