
<?php

namespace App\Support;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;

class DocumentoAcceso
{
    public static function verificar(Documento $documento): void
    {
        abort_unless(auth()->check(), 403);

        $usuarioId = auth()->id();

        abort_unless(
            (int) $documento->subido_por === (int) $usuarioId,
            403
        );

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