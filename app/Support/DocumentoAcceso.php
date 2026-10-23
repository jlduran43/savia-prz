<?php

namespace App\Support;

use App\Models\Documento;
use App\Models\DocumentoCarpeta;

class DocumentoAcceso
{
    public static function verificar(Documento $documento): void
    {
        abort_unless(auth()->check(), 403);

        $usuario = auth()->user();
        $usuarioId = (int) $usuario->id;

        abort_unless(
            (bool) $usuario->activo,
            403,
            'Tu cuenta no está habilitada.'
        );

        $esPropietario =
            (int) $documento->subido_por === $usuarioId;

        // Si es propietario, validar también las carpetas.
        if ($esPropietario) {
            self::verificarCarpetasPropias(
                $documento->carpeta_id,
                $usuarioId
            );

            return;
        }

        // Documento compartido con toda la intranet.
        if ($documento->es_publico) {
            return;
        }

        // Comprobar acceso individual.
        $tienePermiso = $documento
            ->usuariosCompartidos()
            ->whereKey($usuarioId)
            ->exists();

        abort_unless(
            $tienePermiso,
            403,
            'No tienes permiso para acceder al documento.'
        );
    }

    private static function verificarCarpetasPropias(
        ?int $carpetaId,
        int $usuarioId
    ): void {
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

            $carpetaId = $carpeta->carpeta_padre_id === null
                ? null
                : (int) $carpeta->carpeta_padre_id;
        }
    }
}
