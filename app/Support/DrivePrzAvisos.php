<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class DrivePrzAvisos
{
    public static function documentoCompartido(
        int $destinatarioId,
        int $remitenteId,
        string $tipo,
        int $elementoId,
        string $nombre
    ): void {
        // No notificar al mismo usuario.
        if ($destinatarioId === $remitenteId) {
            return;
        }

        if (!in_array($tipo, ['files', 'folders'], true)) {
            throw new \InvalidArgumentException(
                'Tipo de elemento inválido.'
            );
        }

        $nombre = mb_substr($nombre, 0, 255);

        DB::table('drive_prz_notificaciones')->insert([
            'usuario_id' => $destinatarioId,
            'remitente_id' => $remitenteId,
            'tipo' => 'DOCUMENTO_COMPARTIDO',
            'elemento_tipo' => $tipo,
            'elemento_id' => $elementoId,
            'elemento_nombre' => $nombre,
            'mensaje' => $tipo === 'folders'
                ? "Compartió contigo la carpeta {$nombre}"
                : "Compartió contigo el archivo {$nombre}",
            'leida_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}